<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Traits\StockManagement;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    use StockManagement;

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem đơn hàng!');
        }

        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['items' => function ($query) {
                $query->with(['product', 'variant.attributeValues.attribute']);
            }, 'discounts', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('status');

        // Lấy danh sách review đã đánh giá của user hiện tại
        $userReviews = \App\Models\Review::where('user_id', $user->id)
            ->get(['product_id', 'order_id']);

        return response()->view('client.orders.index', compact('orders', 'userReviews'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * AJAX method để load đơn hàng theo trạng thái
     */
    public function getOrdersByStatus($status)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $user = Auth::user();
            
            // Validate status parameter - thêm return_requested và returned
            $validStatuses = ['all', 'pending', 'confirmed', 'processing', 'shipped', 'delivered', 'return_requested', 'returned', 'cancelled'];
            if (!in_array($status, $validStatuses)) {
                return response()->json(['error' => 'Invalid status'], 400);
            }

            $query = Order::where('user_id', $user->id)
                ->with(['items' => function ($query) {
                    $query->with(['product', 'variant.attributeValues.attribute']);
                }])
                ->orderBy('created_at', 'desc');

            // Lấy danh sách review đã đánh giá của user hiện tại
            $userReviews = \App\Models\Review::where('user_id', $user->id)
                ->get(['product_id', 'order_id']);

            if ($status === 'all') {
                $orders = $query->get()->groupBy('status');
            } else {
                $orders = $query->where('status', $status)->get();
            }

            $html = view('client.orders._orders_list', compact('orders', 'userReviews', 'status'))->render();
            
            return response()->json([
                'html' => $html,
                'count' => $status === 'all' ? $orders->flatten()->count() : $orders->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error loading orders by status: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Hủy đơn hàng - cho phép ở pending và confirmed
     */
    public function cancel(Request $request, $orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để hủy đơn hàng!');
        }
        
        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại!');
        }
        
        // Cho phép hủy đơn hàng ở trạng thái pending và confirmed
        if (!$order->canBeCancelled()) {
            return redirect()->back()->with('error', 'Chỉ có thể hủy đơn hàng ở trạng thái chờ xác nhận hoặc đã xác nhận!');
        }
        
        // Xử lý logic hủy đơn hàng và cập nhật payment_status
        $this->handleOrderCancellation($order);
        
        // Cập nhật trạng thái đơn hàng thành cancelled
        $order->status = 'cancelled';
        $order->save();
        
        return redirect()->back()->with('success', 'Đơn hàng đã được hủy thành công!');
    }

    /**
     * Xác nhận đã nhận hàng - khách hàng xác nhận shipped → delivered
     */
    public function confirmReceived(Request $request, $orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập!');
        }

        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại!');
        }

        if (!$order->canConfirmReceived()) {
            return redirect()->back()->with('error', 'Đơn hàng không ở trạng thái đang giao!');
        }

        // Cập nhật trạng thái thành delivered
        $order->status = 'delivered';
        $order->delivered_at = now();

        // Tự động cập nhật thanh toán cho COD khi nhận hàng
        if ($order->payment_method === 'cod' && $order->payment_status === 'pending') {
            $order->payment_status = 'paid';
            $order->paid_at = now();
            $order->notes = ($order->notes ? $order->notes . "\n" : "") .
                "Khách hàng xác nhận đã nhận hàng và thanh toán COD. " . now()->format('d/m/Y H:i');
        } else {
            $order->notes = ($order->notes ? $order->notes . "\n" : "") .
                "Khách hàng xác nhận đã nhận hàng. " . now()->format('d/m/Y H:i');
        }

        $order->save();

        return redirect()->back()->with('success', 'Đã xác nhận nhận hàng thành công! Cảm ơn bạn đã mua sắm.');
    }

    /**
     * Yêu cầu trả hàng/hoàn tiền
     */
    public function requestReturn(Request $request, $orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập!');
        }

        $request->validate([
            'return_reason' => 'required|string|min:10|max:1000',
        ], [
            'return_reason.required' => 'Vui lòng nhập lý do trả hàng.',
            'return_reason.min' => 'Lý do trả hàng phải có ít nhất 10 ký tự.',
            'return_reason.max' => 'Lý do trả hàng không được quá 1000 ký tự.',
        ]);

        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại!');
        }

        if (!$order->canRequestReturn()) {
            $daysLimit = Order::RETURN_DAYS_LIMIT;
            return redirect()->back()->with('error', "Không thể yêu cầu trả hàng! Thời hạn trả hàng là {$daysLimit} ngày kể từ khi nhận.");
        }

        // Cập nhật trạng thái
        $order->status = 'return_requested';
        $order->return_reason = $request->return_reason;
        $order->return_requested_at = now();
        $order->notes = ($order->notes ? $order->notes . "\n" : "") .
            "Khách hàng yêu cầu trả hàng. Lý do: " . $request->return_reason . ". " . now()->format('d/m/Y H:i');

        $order->save();

        return redirect()->back()->with('success', 'Đã gửi yêu cầu trả hàng thành công! Chúng tôi sẽ xem xét và phản hồi sớm nhất.');
    }

    /**
     * Xử lý logic khi hủy đơn hàng
     */
    private function handleOrderCancellation(Order $order)
    {
        $currentPaymentStatus = $order->payment_status;
        $paymentMethod = $order->payment_method;

        switch ($currentPaymentStatus) {
            case 'paid':
                // Đơn hàng đã thanh toán -> chuyển sang chờ hoàn tiền
                $order->payment_status = 'refund_pending';
                $order->notes = ($order->notes ? $order->notes . "\n" : "") . 
                    "Hủy đơn - Chờ hoàn tiền. " . now()->format('d/m/Y H:i');
                break;
            case 'pending':
                // Đơn hàng chưa thanh toán
                if (in_array($paymentMethod, ['vnpay', 'bank_transfer', 'credit_card', 'momo'])) {
                    // Có giao dịch thanh toán đang chờ -> thất bại
                    $order->payment_status = 'failed';
                    $order->notes = ($order->notes ? $order->notes . "\n" : "") . 
                        "Hủy đơn - Thanh toán thất bại. " . now()->format('d/m/Y H:i');
                } else {
                    // COD hoặc phương thức khác -> giữ nguyên pending
                    $order->notes = ($order->notes ? $order->notes . "\n" : "") . 
                        "Hủy đơn - Chưa thanh toán. " . now()->format('d/m/Y H:i');
                }
                break;
            case 'failed':
                $order->notes = ($order->notes ? $order->notes . "\n" : "") . 
                    "Hủy đơn - Đã thất bại. " . now()->format('d/m/Y H:i');
                break;
            case 'refund_pending':
                $order->notes = ($order->notes ? $order->notes . "\n" : "") . 
                    "Hủy đơn - Chờ hoàn tiền. " . now()->format('d/m/Y H:i');
                break;
            case 'refunded':
                $order->notes = ($order->notes ? $order->notes . "\n" : "") . 
                    "Hủy đơn - Đã hoàn tiền. " . now()->format('d/m/Y H:i');
                break;
            default:
                $order->notes = ($order->notes ? $order->notes . "\n" : "") . 
                    "Hủy đơn. " . now()->format('d/m/Y H:i');
                break;
        }

        // Cộng lại tồn kho khi hủy đơn hàng
        try {
            $this->restoreStockOnCancellation($order);
            $order->notes = ($order->notes ? $order->notes . "\n" : "") . 
                "Đã cộng lại tồn kho cho tất cả sản phẩm. " . now()->format('d/m/Y H:i');
        } catch (\Exception $e) {
            \Log::error("Lỗi khi cộng lại tồn kho cho đơn hàng", [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            $order->notes = ($order->notes ? $order->notes . "\n" : "") . 
                "LỖI: Không thể cộng lại tồn kho - " . $e->getMessage() . ". " . now()->format('d/m/Y H:i');
        }
    }

    public function buyAgain(Request $request, $orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để mua lại đơn hàng!');
        }

        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại!');
        }

        // Cho phép mua lại đơn hàng đã giao, đã hủy, đã trả hàng
        if (!in_array($order->status, ['delivered', 'cancelled', 'returned'])) {
            return redirect()->back()->with('error', 'Chỉ có thể mua lại đơn hàng đã giao, đã hủy hoặc đã trả hàng!');
        }

        // Chuẩn bị dữ liệu sản phẩm cho session
        $buyAgainItems = [];
        foreach ($order->items as $item) {
            $productId = $item->product_id ?? ($item->variant ? $item->variant->product_id : null);
            $variantId = $item->product_variant_id;
            
            if ($productId) {
                $buyAgainItems[] = [
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'product_name' => $item->product_name,
                    'product_image' => $item->product_image,
                    'variant_sku' => $item->variant_sku,
                    'variant_attributes' => $item->variant_attributes,
                ];
            }
        }

        // Lưu vào session
        session(['buy_again_items' => $buyAgainItems]);
        session(['buy_again_order_id' => $orderId]);

        return redirect()->route('checkout.index')->with('success', 'Đã tải lại đơn hàng! Bạn có thể tiếp tục thanh toán.');
    }

    public function show($orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem chi tiết đơn hàng!');
        }

        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->with(['items' => function ($query) {
                $query->with(['product', 'variant.attributeValues.attribute']);
            }, 'discounts', 'payments'])
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Đơn hàng không tồn tại!');
        }

        // Lấy danh sách review đã đánh giá của user hiện tại
        $userReviews = \App\Models\Review::where('user_id', Auth::id())
            ->get(['product_id', 'order_id']);

        // Tạo timeline cho đơn hàng
        $timeline = $this->generateOrderTimeline($order);

        return view('client.orders.show', compact('order', 'userReviews', 'timeline'));
    }

    /**
     * Tạo timeline cho đơn hàng
     */
    private function generateOrderTimeline(Order $order)
    {
        $timeline = [];

        // Thêm sự kiện tạo đơn hàng (luôn có)
        $timeline[] = [
            'date' => $order->created_at,
            'title' => 'Đơn hàng được tạo',
            'description' => 'Đơn hàng #' . $order->order_number . ' đã được tạo thành công',
            'icon' => 'fas fa-shopping-cart',
            'status' => 'completed',
            'priority' => 1
        ];

        // Thêm sự kiện thanh toán nếu đã thanh toán
        if ($order->paid_at) {
            $timeline[] = [
                'date' => $order->paid_at,
                'title' => 'Thanh toán thành công',
                'description' => 'Đơn hàng đã được thanh toán qua ' . $order->getPaymentMethodText(),
                'icon' => 'fas fa-credit-card',
                'status' => 'completed',
                'priority' => 2
            ];
        }

        // Thêm sự kiện xác nhận (nếu trạng thái >= confirmed)
        if (in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered', 'return_requested', 'returned'])) {
            $timeline[] = [
                'date' => $order->paid_at ? $order->paid_at->addMinutes(5) : $order->created_at->addMinutes(30),
                'title' => 'Đơn hàng được xác nhận',
                'description' => 'Shop đã xác nhận đơn hàng của bạn',
                'icon' => 'fas fa-check-circle',
                'status' => 'completed',
                'priority' => 3
            ];
        }

        // Thêm sự kiện đang xử lý (nếu trạng thái >= processing)
        if (in_array($order->status, ['processing', 'shipped', 'delivered', 'return_requested', 'returned'])) {
            $timeline[] = [
                'date' => $order->paid_at ? $order->paid_at->addMinutes(10) : $order->created_at->addHours(1),
                'title' => 'Đang xử lý đơn hàng',
                'description' => 'Shop đang chuẩn bị hàng cho bạn',
                'icon' => 'fas fa-box',
                'status' => 'completed',
                'priority' => 4
            ];
        }

        // Thêm sự kiện gửi hàng (nếu trạng thái >= shipped)
        if (in_array($order->status, ['shipped', 'delivered', 'return_requested', 'returned'])) {
            $timeline[] = [
                'date' => $order->shipped_at ?? ($order->paid_at ? $order->paid_at->addHours(2) : $order->created_at->addHours(3)),
                'title' => 'Đơn hàng đang được giao',
                'description' => 'Đơn hàng đã được gửi đến đơn vị vận chuyển',
                'icon' => 'fas fa-shipping-fast',
                'status' => 'completed',
                'priority' => 5
            ];
        }

        // Thêm sự kiện giao hàng (nếu trạng thái >= delivered)
        if (in_array($order->status, ['delivered', 'return_requested', 'returned'])) {
            $timeline[] = [
                'date' => $order->delivered_at ?? ($order->paid_at ? $order->paid_at->addDays(1) : $order->created_at->addDays(2)),
                'title' => 'Đã giao hàng',
                'description' => 'Đơn hàng đã được giao thành công',
                'icon' => 'fas fa-home',
                'status' => 'completed',
                'priority' => 6
            ];
        }

        // Thêm sự kiện yêu cầu trả hàng
        if (in_array($order->status, ['return_requested', 'returned'])) {
            $timeline[] = [
                'date' => $order->return_requested_at ?? $order->updated_at,
                'title' => 'Yêu cầu trả hàng',
                'description' => 'Khách hàng yêu cầu trả hàng' . ($order->return_reason ? '. Lý do: ' . $order->return_reason : ''),
                'icon' => 'fas fa-undo',
                'status' => $order->status === 'returned' ? 'completed' : 'pending',
                'priority' => 7
            ];
        }

        // Thêm sự kiện đã trả hàng
        if ($order->status === 'returned') {
            $timeline[] = [
                'date' => $order->returned_at ?? $order->updated_at,
                'title' => 'Đã trả hàng',
                'description' => 'Yêu cầu trả hàng đã được phê duyệt và xử lý',
                'icon' => 'fas fa-check-double',
                'status' => 'completed',
                'priority' => 8
            ];
        }

        // Thêm sự kiện hủy đơn hàng (nếu trạng thái = cancelled)
        if ($order->status === 'cancelled') {
            $timeline[] = [
                'date' => $order->updated_at,
                'title' => 'Đơn hàng đã bị hủy',
                'description' => 'Đơn hàng đã được hủy',
                'icon' => 'fas fa-times-circle',
                'status' => 'cancelled',
                'priority' => 0
            ];
        }

        // Sắp xếp timeline theo priority trước, sau đó theo thời gian
        usort($timeline, function ($a, $b) {
            if ($a['priority'] !== $b['priority']) {
                return $a['priority'] - $b['priority'];
            }
            return $a['date']->timestamp - $b['date']->timestamp;
        });

        return $timeline;
    }
}