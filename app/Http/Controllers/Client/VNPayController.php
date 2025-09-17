<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\VNPayService;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    protected $vnpayService;

    public function __construct(VNPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Kiểm tra quyền truy cập
        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Không có quyền truy cập đơn hàng này'], 403);
        }

        // Kiểm tra trạng thái đơn hàng
        if ($order->status !== 'pending') {
            return response()->json(['error' => 'Đơn hàng không thể thanh toán'], 400);
        }

        // Kiểm tra xem đã có payment pending cho đơn hàng này chưa
        $existingPayment = Payment::where('order_id', $order->id)
            ->where('payment_gateway', 'VNPAY')
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if ($existingPayment) {
            // Nếu có payment pending và chưa hết hạn, sử dụng lại
            Log::info('Sử dụng lại payment pending cho đơn hàng', [
                'order_id' => $order->id,
                'payment_id' => $existingPayment->id,
                'expires_at' => $existingPayment->expires_at
            ]);

            $paymentUrl = $this->vnpayService->createPaymentUrl(
                $order->order_number,
                $order->total_amount,
                "Thanh toan don hang " . $order->order_number
            );

            return response()->json([
                'success' => true,
                'payment_url' => $paymentUrl,
                'payment_id' => $existingPayment->id,
                'message' => 'Sử dụng lại phiên thanh toán hiện có'
            ]);
        }

        // Kiểm tra kết nối VNPay trước khi tạo payment (chỉ log warning, không dừng)
        $connectionCheck = $this->vnpayService->checkConnection();
        if (!$connectionCheck['success']) {
            Log::warning('VNPay sandbox may be unstable - continuing anyway', [
                'order_id' => $order->id,
                'http_code' => $connectionCheck['http_code'] ?? 'N/A',
                'error' => $connectionCheck['error'] ?? 'Unknown error',
                'action' => 'Continuing payment creation despite connection issues'
            ]);
        }

        // Tạo payment record mới
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_gateway' => 'VNPAY',
            'amount' => $order->total_amount,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(10), // Thiết lập thời gian hết hạn 10 phút
        ]);

        // Tạo URL thanh toán với timestamp để tránh cache
        $paymentUrl = $this->vnpayService->createPaymentUrl(
            $order->order_number . '_' . time(), // Thêm timestamp để tránh duplicate
            $order->total_amount,
            "Thanh toan don hang " . $order->order_number
        );

        Log::info('Tạo payment mới cho đơn hàng', [
            'order_id' => $order->id,
            'payment_id' => $payment->id,
            'order_number' => $order->order_number . '_' . time(),
            'expires_at' => $payment->expires_at
        ]);

        return response()->json([
            'success' => true,
            'payment_url' => $paymentUrl,
            'payment_id' => $payment->id,
            'connection_status' => $connectionCheck['success'] ? 'OK' : 'WARNING'
        ]);
    }

    /**
     * Xử lý return từ VNPay
     */
    public function return(Request $request)
    {
        Log::info('VNPay Return', $request->all());

        // Xác thực callback
        if (!$this->vnpayService->verifyReturn($request->all())) {
            return redirect()->route('client.checkout.index')
                ->with('error', 'Chữ ký không hợp lệ!');
        }

        $transactionInfo = $this->vnpayService->getTransactionInfo($request->all());
        $orderNumberWithTimestamp = $transactionInfo['order_id'];
        $responseCode = $transactionInfo['response_code'];

        // Xử lý order number có thể chứa timestamp
        $orderNumber = $orderNumberWithTimestamp;
        if (strpos($orderNumberWithTimestamp, '_') !== false) {
            $orderNumber = explode('_', $orderNumberWithTimestamp)[0];
        }

        // Tìm đơn hàng
        $order = Order::with(['items', 'user.cart'])->where('order_number', $orderNumber)->first();
        if (!$order) {
            Log::error('Không tìm thấy đơn hàng trong VNPay return', [
                'order_number_with_timestamp' => $orderNumberWithTimestamp,
                'order_number_cleaned' => $orderNumber,
                'request_data' => $request->all()
            ]);
            return redirect()->route('client.checkout.index')
                ->with('error', 'Không tìm thấy đơn hàng!');
        }

        // Tìm payment record
        $payment = Payment::where('order_id', $order->id)
            ->where('payment_gateway', 'VNPAY')
            ->latest()
            ->first();

        if (!$payment) {
            return redirect()->route('client.checkout.index')
                ->with('error', 'Không tìm thấy thông tin thanh toán!');
        }

        // Cập nhật payment record với logic phân loại response codes
        // Payment record status: success, cancelled, failed
        // Order payment_status: pending, paid, failed (không có cancelled)
        $paymentStatus = 'failed';
        if ($this->vnpayService->isSuccess($responseCode)) {
            $paymentStatus = 'success';
        } elseif ($this->vnpayService->isUserCancelled($responseCode)) {
            $paymentStatus = 'cancelled';
        }
        
        $payment->update([
            'transaction_id' => $transactionInfo['transaction_id'],
            'status' => $paymentStatus,
            'paid_at' => $this->vnpayService->isSuccess($responseCode) ? now() : null,
            'response_code' => $transactionInfo['response_code'],
            'response_message' => $transactionInfo['message'],
            'bank_code' => $transactionInfo['bank_code'],
            'payment_data' => $request->all(),
        ]);

        if ($this->vnpayService->isSuccess($responseCode)) {
            // Thanh toán thành công
            $order->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            // Không cần xóa giỏ hàng nữa vì đã xóa từ trước
            // $this->removePaidItemsFromCart($order);
            
            // Xóa session selected_items nếu có
            if (Auth::check()) {
                session()->forget('selected_items');
            }

            return redirect()->route('orders.index')
                ->with('success', 'Thanh toán thành công! Đơn hàng sẽ được người bán xác nhận.');
                
        } elseif ($this->vnpayService->isUserCancelled($responseCode)) {
            // User chủ động hủy/quay lại - GIỮ NGUYÊN pending để có thể tiếp tục thanh toán
            // Không cần update order->payment_status vì nó đã là pending rồi
            
            Log::info('VNPay payment cancelled by user', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'response_code' => $responseCode,
                'response_message' => $transactionInfo['message']
            ]);
            
            return redirect()->route('orders.index')
                ->with('info', 'Bạn có thể tiếp tục thanh toán đơn hàng này.');
                
        } else {
            // Lỗi hệ thống/thanh toán thất bại
            $order->update(['payment_status' => 'failed']);

            // Kiểm tra xem có phải do hết tồn kho không
            $stockIssues = $this->checkOrderStockAvailability($order);
            
            if (!empty($stockIssues)) {
                // Nếu có vấn đề về tồn kho, khôi phục tồn kho và hiển thị thông báo rõ ràng
                $this->restoreStockForOrder($order);
                
                $stockErrorMessages = array_column($stockIssues, 'message');
                $errorMessage = 'Thanh toán thất bại do sản phẩm hết tồn kho: ' . implode(', ', $stockErrorMessages);
                
                Log::warning('VNPay payment failed due to stock issues', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'stock_issues' => $stockIssues,
                    'vnpay_response_code' => $responseCode
                ]);
                
                return redirect()->route('client.checkout.index')
                    ->with('error', $errorMessage);
            }

            // Nếu không phải do tồn kho, hiển thị thông báo VNPay
            $errorMessage = $this->vnpayService->getResponseMessage($responseCode);
            
            Log::warning('VNPay payment failed due to system error', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'response_code' => $responseCode,
                'response_message' => $transactionInfo['message']
            ]);
            
            return redirect()->route('home')
                ->with('error', 'Thanh toán thất bại: ' . $errorMessage);
        }
    }

    /**
     * Xử lý IPN từ VNPay
     */
    public function ipn(Request $request)
    {
        Log::info('VNPay IPN', $request->all());

        // Xác thực IPN
        if (!$this->vnpayService->verifyIPN($request->all())) {
            return response('Invalid signature', 400);
        }

        $transactionInfo = $this->vnpayService->getTransactionInfo($request->all());
        $orderNumberWithTimestamp = $transactionInfo['order_id'];
        $responseCode = $transactionInfo['response_code'];

        // Xử lý order number có thể chứa timestamp
        $orderNumber = $orderNumberWithTimestamp;
        if (strpos($orderNumberWithTimestamp, '_') !== false) {
            $orderNumber = explode('_', $orderNumberWithTimestamp)[0];
        }

        // Tìm đơn hàng
        $order = Order::with(['items', 'user.cart'])->where('order_number', $orderNumber)->first();
        if (!$order) {
            Log::error('Không tìm thấy đơn hàng trong VNPay IPN', [
                'order_number_with_timestamp' => $orderNumberWithTimestamp,
                'order_number_cleaned' => $orderNumber,
                'request_data' => $request->all()
            ]);
            return response('Order not found', 404);
        }

        // Tìm payment record
        $payment = Payment::where('order_id', $order->id)
            ->where('payment_gateway', 'VNPAY')
            ->latest()
            ->first();

        if (!$payment) {
            return response('Payment not found', 404);
        }

        // Cập nhật payment record với logic phân loại response codes
        // Payment record status: success, cancelled, failed
        // Order payment_status: pending, paid, failed (không có cancelled)
        $paymentStatus = 'failed';
        if ($this->vnpayService->isSuccess($responseCode)) {
            $paymentStatus = 'success';
        } elseif ($this->vnpayService->isUserCancelled($responseCode)) {
            $paymentStatus = 'cancelled';
        }
        
        $payment->update([
            'transaction_id' => $transactionInfo['transaction_id'],
            'status' => $paymentStatus,
            'paid_at' => $this->vnpayService->isSuccess($responseCode) ? now() : null,
            'response_code' => $transactionInfo['response_code'],
            'response_message' => $transactionInfo['message'],
            'bank_code' => $transactionInfo['bank_code'],
            'payment_data' => $request->all(),
        ]);

        if ($this->vnpayService->isSuccess($responseCode)) {
            // Cập nhật trạng thái đơn hàng
            $order->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            // Xóa sản phẩm đã thanh toán khỏi giỏ hàng
            $this->removePaidItemsFromCart($order);
        } elseif ($this->vnpayService->isUserCancelled($responseCode)) {
            // User hủy - giữ nguyên pending trong IPN
            // Không cần update order->payment_status vì nó đã là pending rồi
            
            Log::info('VNPay IPN: Payment cancelled by user', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'response_code' => $responseCode,
                'response_message' => $transactionInfo['message']
            ]);
            
        } else {
            // Lỗi hệ thống/thanh toán thất bại
            $order->update(['payment_status' => 'failed']);

            // Kiểm tra xem có phải do hết tồn kho không
            $stockIssues = $this->checkOrderStockAvailability($order);
            
            if (!empty($stockIssues)) {
                // Log thông tin về stock issues trong IPN
                Log::warning('VNPay IPN: Payment failed due to stock issues', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'stock_issues' => $stockIssues,
                    'vnpay_response_code' => $responseCode
                ]);
                
                // Không khôi phục về giỏ hàng nếu do hết tồn kho
                // Vì user sẽ nhận thông báo rõ ràng khi return
            } else {
                // Khôi phục sản phẩm về giỏ hàng nếu thanh toán thất bại do lý do khác
                $this->restoreItemsToCart($order);
            }
            
            Log::warning('VNPay IPN: Payment failed due to system error', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'response_code' => $responseCode,
                'response_message' => $transactionInfo['message']
            ]);
        }

        return response('OK', 200);
    }

    /**
     * Kiểm tra trạng thái thanh toán
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
        ]);

        $payment = Payment::with('order')->findOrFail($request->payment_id);

        // Kiểm tra quyền truy cập
        if ($payment->order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Không có quyền truy cập payment này'], 403);
        }

        return response()->json([
            'status' => $payment->status,
            'payment_status' => $payment->order->payment_status,
            'is_expired' => $payment->isExpired(),
            'remaining_minutes' => $payment->getRemainingMinutes(),
            'expires_at' => $payment->expires_at,
            'order_number' => $payment->order->order_number,
        ]);
    }

    /**
     * Kiểm tra kết nối VNPay
     */
    public function checkConnection()
    {
        $connectionCheck = $this->vnpayService->checkConnection();
        
        return response()->json([
            'success' => $connectionCheck['success'],
            'http_code' => $connectionCheck['http_code'] ?? null,
            'error' => $connectionCheck['error'] ?? null,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Tiếp tục thanh toán cho đơn hàng VNPay chưa hoàn tất
     */
    public function continuePayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Kiểm tra quyền truy cập
        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Không có quyền truy cập đơn hàng này'], 403);
        }

        // Kiểm tra đơn hàng có thể tiếp tục thanh toán VNPay không
        if (!$order->canContinueVnPayPayment()) {
            return response()->json(['error' => 'Đơn hàng này không thể tiếp tục thanh toán VNPay'], 400);
        }

        // Tìm payment record gần nhất
        $payment = $order->getLatestVnPayPayment();

        // Kiểm tra payment có thể tiếp tục không
        if (!$payment->canBePaid()) {
            // Nếu payment đã hết hạn, tạo payment mới
            if ($payment->isExpired()) {
                // Tạo payment record mới
                $newPayment = Payment::create([
                    'order_id' => $order->id,
                    'payment_gateway' => 'VNPAY',
                    'amount' => $order->total_amount,
                    'status' => 'pending',
                    'expires_at' => now()->addMinutes(10),
                ]);

                $payment = $newPayment;
            } else {
                return response()->json(['error' => 'Thanh toán này không thể tiếp tục'], 400);
            }
        }

        // Tạo URL thanh toán mới
        $paymentUrl = $this->vnpayService->createPaymentUrl(
            $order->order_number,
            $order->total_amount,
            "Thanh toan don hang " . $order->order_number
        );

        return response()->json([
            'success' => true,
            'payment_url' => $paymentUrl,
            'payment_id' => $payment->id,
            'expires_at' => $payment->expires_at,
            'remaining_minutes' => $payment->getRemainingMinutes(),
        ]);
    }

    /**
     * Khôi phục sản phẩm về giỏ hàng khi thanh toán thất bại
     */
    private function restoreItemsToCart($order)
    {
        if (!$order->user || !$order->user->cart) {
            return;
        }

        $cart = $order->user->cart;

        foreach ($order->items as $orderItem) {
            // Tìm cart item tương ứng
            $cartItem = $cart->items()
                ->where('product_id', $orderItem->product_id)
                ->where('product_variant_id', $orderItem->product_variant_id)
                ->first();
            
            if ($cartItem) {
                // Tăng số lượng trong giỏ hàng
                $cartItem->increment('quantity', $orderItem->quantity);
            } else {
                // Tạo cart item mới nếu chưa có
                $cart->items()->create([
                    'product_id' => $orderItem->product_id,
                    'product_variant_id' => $orderItem->product_variant_id,
                    'quantity' => $orderItem->quantity,
                    'price' => $orderItem->price,
                ]);
            }
        }
    }

    /**
     * Xóa sản phẩm đã thanh toán khỏi giỏ hàng
     */
    private function removePaidItemsFromCart($order)
    {
        if (!$order->user || !$order->user->cart) {
            return;
        }

        $cart = $order->user->cart;

        foreach ($order->items as $orderItem) {
            // Tìm cart item tương ứng
            $cartItem = $cart->items()
                ->where('product_id', $orderItem->product_id)
                ->where('product_variant_id', $orderItem->product_variant_id)
                ->first();
            
            if ($cartItem) {
                // Nếu số lượng trong giỏ hàng >= số lượng đã mua, giảm số lượng
                if ($cartItem->quantity >= $orderItem->quantity) {
                    $cartItem->decrement('quantity', $orderItem->quantity);
                    
                    // Nếu số lượng = 0, xóa item
                    if ($cartItem->quantity <= 0) {
                        $cartItem->delete();
                    }
                } else {
                    // Nếu số lượng trong giỏ hàng < số lượng đã mua, xóa item
                    $cartItem->delete();
                }
            }
        }

        // Xóa session hidden items sau khi thanh toán thành công
        session()->forget('vnpay_hidden_items_' . $order->id);
    }

    /**
     * Kiểm tra tồn kho cho đơn hàng (tính cả số lượng đã được đặt)
     * 
     * @param \App\Models\Order $order
     * @return array Danh sách các vấn đề về tồn kho
     */
    private function checkOrderStockAvailability($order)
    {
        $stockIssues = [];
        
        foreach ($order->items as $orderItem) {
            $quantity = $orderItem->quantity;
            
            if ($orderItem->product_variant_id) {
                // Kiểm tra tồn kho variant
                $variant = \App\Models\ProductVariant::find($orderItem->product_variant_id);
                if (!$variant) {
                    $stockIssues[] = [
                        'type' => 'variant_not_found',
                        'product_name' => $orderItem->product_name,
                        'message' => "Biến thể sản phẩm '{$orderItem->product_name}' không tồn tại"
                    ];
                    continue;
                }
                
                // Tính tổng số lượng đã được đặt cho variant này (trừ đơn hàng hiện tại)
                $totalOrdered = \App\Models\OrderItem::where('product_variant_id', $orderItem->product_variant_id)
                    ->whereHas('order', function($query) use ($order) {
                        $query->where('id', '!=', $order->id)
                              ->whereIn('payment_status', ['pending', 'paid'])
                              ->whereIn('status', ['pending', 'confirmed', 'shipped', 'delivered']);
                    })
                    ->sum('quantity');
                
                // Tồn kho thực tế = tồn kho hiện tại + số lượng đã đặt
                $actualStock = $variant->stock + $totalOrdered;
                
                if ($actualStock < $quantity) {
                    $stockIssues[] = [
                        'type' => 'insufficient_stock',
                        'product_name' => $orderItem->product_name,
                        'requested' => $quantity,
                        'available' => $actualStock,
                        'current_stock' => $variant->stock,
                        'ordered_quantity' => $totalOrdered,
                        'message' => "Sản phẩm '{$orderItem->product_name}' chỉ còn {$actualStock} trong kho (yêu cầu: {$quantity})"
                    ];
                }
            } else {
                // Kiểm tra tồn kho sản phẩm gốc
                $product = \App\Models\Product::find($orderItem->product_id);
                if (!$product) {
                    $stockIssues[] = [
                        'type' => 'product_not_found',
                        'product_name' => $orderItem->product_name,
                        'message' => "Sản phẩm '{$orderItem->product_name}' không tồn tại"
                    ];
                    continue;
                }
                
                // Tính tổng số lượng đã được đặt cho sản phẩm này (trừ đơn hàng hiện tại)
                $totalOrdered = \App\Models\OrderItem::where('product_id', $orderItem->product_id)
                    ->whereNull('product_variant_id')
                    ->whereHas('order', function($query) use ($order) {
                        $query->where('id', '!=', $order->id)
                              ->whereIn('payment_status', ['pending', 'paid'])
                              ->whereIn('status', ['pending', 'confirmed', 'shipped', 'delivered']);
                    })
                    ->sum('quantity');
                
                // Tồn kho thực tế = tồn kho hiện tại + số lượng đã đặt
                $actualStock = $product->stock + $totalOrdered;
                
                if ($actualStock < $quantity) {
                    $stockIssues[] = [
                        'type' => 'insufficient_stock',
                        'product_name' => $orderItem->product_name,
                        'requested' => $quantity,
                        'available' => $actualStock,
                        'current_stock' => $product->stock,
                        'ordered_quantity' => $totalOrdered,
                        'message' => "Sản phẩm '{$orderItem->product_name}' chỉ còn {$actualStock} trong kho (yêu cầu: {$quantity})"
                    ];
                }
            }
        }
        
        return $stockIssues;
    }

    /**
     * Khôi phục tồn kho cho đơn hàng khi thanh toán thất bại
     * 
     * @param \App\Models\Order $order
     */
    private function restoreStockForOrder($order)
    {
        try {
            foreach ($order->items as $orderItem) {
                $quantity = $orderItem->quantity;
                
                if ($orderItem->product_variant_id) {
                    $variant = \App\Models\ProductVariant::find($orderItem->product_variant_id);
                    if ($variant) {
                        $oldStock = $variant->stock;
                        $variant->increment('stock', $quantity);
                        $newStock = $variant->fresh()->stock;
                        
                        Log::info("Đã khôi phục tồn kho variant cho VNPay failed", [
                            'order_id' => $order->id,
                            'variant_id' => $variant->id,
                            'variant_sku' => $variant->sku,
                            'quantity_restored' => $quantity,
                            'old_stock' => $oldStock,
                            'new_stock' => $newStock
                        ]);
                    }
                } else {
                    $product = \App\Models\Product::find($orderItem->product_id);
                    if ($product) {
                        $oldStock = $product->stock;
                        $product->increment('stock', $quantity);
                        $newStock = $product->fresh()->stock;
                        
                        Log::info("Đã khôi phục tồn kho sản phẩm cho VNPay failed", [
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'quantity_restored' => $quantity,
                            'old_stock' => $oldStock,
                            'new_stock' => $newStock
                        ]);
                    }
                }
            }
            
            Log::info("Hoàn thành khôi phục tồn kho cho đơn hàng VNPay failed", [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total_items' => $order->items->count()
            ]);
        } catch (\Exception $e) {
            Log::error("Lỗi khi khôi phục tồn kho cho VNPay failed", [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
