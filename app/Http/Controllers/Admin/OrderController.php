<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\StockManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use StockManagement;
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product', 'items.variant'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('shipping_name', 'like', "%{$search}%")
                  ->orWhere('shipping_phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(10);

        // Get statistics
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.variant.attributeValues.attribute', 'discounts', 'payments']);
        
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Kiểm soát cập nhật trạng thái đơn hàng VNPay: chỉ áp dụng sau khi đã hủy
        if ($order->isVnPayPayment() && $oldStatus === 'cancelled' && $newStatus !== 'cancelled' && !$order->canUpdateOrderStatusWhenRefundPending()) {
            return redirect()->back()->with('error',
                'Chỉ có thể cập nhật trạng thái đơn hàng VNPay khi trạng thái thanh toán là "Chờ hoàn tiền".'
            );
        }

        // Kiểm tra xem có thể cập nhật sang trạng thái mới không
        if (!$order->canUpdateToStatus($newStatus)) {
            $availableStatuses = $order->getAvailableStatuses();
            $availableStatusTexts = [];
            
            foreach ($availableStatuses as $status) {
                $tempOrder = clone $order;
                $tempOrder->status = $status;
                $availableStatusTexts[] = $tempOrder->getStatusText();
            }
            
            $tempOrder = clone $order;
            $tempOrder->status = $newStatus;
            
            return redirect()->back()->with('error', 
                "Không thể cập nhật trạng thái từ '{$order->getStatusText()}' sang '{$tempOrder->getStatusText()}'. " .
                "Các trạng thái có thể cập nhật: " . implode(', ', $availableStatusTexts)
            );
        }

        // Update order status
        $order->status = $newStatus;
        
        // THÊM LOGIC MỚI: Xử lý logic đặc biệt khi hủy đơn hàng
        if ($newStatus === 'cancelled') {
            $this->handleOrderCancellation($order);
        }
        
        // Update timestamps based on status
        switch ($newStatus) {
            case 'shipped':
                $order->shipped_at = now();
                break;
            case 'delivered':
                $order->delivered_at = now();
                break;
        }

        // Add notes if provided
        if ($request->filled('notes')) {
            $order->notes = $request->notes;
        }

        $order->save();

        // Log the status change
        // You can add activity logging here if needed

        $tempOrder = clone $order;
        $tempOrder->status = $oldStatus;
        $oldStatusText = $tempOrder->getStatusText();
        $newStatusText = $order->getStatusText();
        
        return redirect()->back()->with('success', 
            "Đơn hàng đã được cập nhật trạng thái thành công"
        );
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refund_pending,refunded'
        ]);

        // Kiểm tra xem có thể thay đổi trạng thái thanh toán không
        if (!$order->canUpdatePaymentStatus()) {
            if ($order->isVnPayPayment()) {
                return redirect()->back()->with('error', 
                    "Không thể thay đổi trạng thái thanh toán của đơn hàng VNPay. " .
                    "Trạng thái thanh toán VNPay được quản lý tự động bởi hệ thống."
                );
            } elseif ($order->payment_method === 'cod' && $order->payment_status === 'paid') {
                return redirect()->back()->with('error', 
                    "Đơn hàng COD đã thanh toán không thể thay đổi trạng thái để đảm bảo tính minh bạch."
                );
            } elseif ($order->payment_method === 'cod' && $order->status !== 'delivered') {
                return redirect()->back()->with('error', 
                    "Đơn hàng COD chỉ có thể cập nhật trạng thái thanh toán khi đã giao hàng. " .
                    "Hiện tại đơn hàng đang ở trạng thái: " . $order->getStatusText()
                );
            }
        }

        // Kiểm tra validation cho COD
        if ($order->payment_method === 'cod') {
            $availableStatuses = $order->getAvailablePaymentStatuses();
            if (!in_array($request->payment_status, $availableStatuses)) {
                return redirect()->back()->with('error', 
                    "Trạng thái thanh toán không hợp lệ cho đơn hàng COD. " .
                    "Chỉ có thể cập nhật từ 'Chờ thanh toán' sang 'Đã thanh toán'."
                );
            }
        }

        $oldPaymentStatus = $order->payment_status;
        $newPaymentStatus = $request->payment_status;

        $order->payment_status = $newPaymentStatus;
        
        if ($newPaymentStatus === 'paid') {
            $order->paid_at = now();
        }

        $order->save();

        $tempOrder = clone $order;
        $tempOrder->payment_status = $oldPaymentStatus;
        $oldPaymentStatusText = $tempOrder->getPaymentStatusText();
        $newPaymentStatusText = $order->getPaymentStatusText();
        
        return redirect()->back()->with('success', 
            "Trạng thái thanh toán của đơn hàng đã được cập nhật từ '{$oldPaymentStatusText}' sang '{$newPaymentStatusText}'"
        );
    }

    public function export(Request $request)
    {
        $query = Order::with(['user', 'items.product', 'items.variant'])
            ->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('shipping_name', 'like', "%{$search}%")
                  ->orWhere('shipping_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->get();

        // Generate CSV content
        $filename = 'orders_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Mã đơn hàng',
                'Khách hàng',
                'Email',
                'Số điện thoại',
                'Địa chỉ',
                'Trạng thái',
                'Trạng thái thanh toán',
                'Phương thức thanh toán',
                'Tổng tiền',
                'Ngày tạo',
                'Ghi chú'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->shipping_name,
                    $order->user->email ?? 'N/A',
                    $order->shipping_phone,
                    $order->shipping_address,
                    $this->getStatusText($order->status),
                    $this->getPaymentStatusText($order->payment_status),
                    $this->getPaymentMethodText($order->payment_method),
                    number_format($order->total_amount, 0, ',', '.') . ' VNĐ',
                    $order->created_at->format('d/m/Y H:i'),
                    $order->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getStatusText($status)
    {
        $statuses = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đã gửi hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy'
        ];

        return $statuses[$status] ?? $status;
    }

    private function getPaymentStatusText($status)
    {
        $statuses = [
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'failed' => 'Thanh toán thất bại',
            'refund_pending' => 'Chờ hoàn tiền',
            'refunded' => 'Đã hoàn tiền'
        ];

        return $statuses[$status] ?? $status;
    }

    private function getPaymentMethodText($method)
    {
        $methods = [
            'cod' => 'Tiền mặt',
            'bank_transfer' => 'Chuyển khoản',
            'credit_card' => 'Thẻ tín dụng',
            'momo' => 'MoMo',
            'vnpay' => 'VNPay'
        ];

        return $methods[$method] ?? 'Chưa chọn';
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

        // THÊM LOGIC MỚI: Cộng lại tồn kho khi hủy đơn hàng
        try {
            $this->restoreStockOnCancellation($order);
            $order->notes = ($order->notes ? $order->notes . "\n" : "") . 
                "Đã cộng lại tồn kho cho tất cả sản phẩm. " . now()->format('d/m/Y H:i');
        } catch (\Exception $e) {
            Log::error("Lỗi khi cộng lại tồn kho cho đơn hàng", [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            $order->notes = ($order->notes ? $order->notes . "\n" : "") . 
                "LỖI: Không thể cộng lại tồn kho - " . $e->getMessage() . ". " . now()->format('d/m/Y H:i');
        }
    }

    /**
     * Xác nhận hoàn tiền cho đơn hàng
     */
    public function refund(Request $request, Order $order)
    {
        // Kiểm tra trạng thái thanh toán hiện tại
        if ($order->payment_status !== 'refund_pending') {
            return redirect()->back()->with('error', 
                'Chỉ có thể xác nhận hoàn tiền cho đơn hàng đang trong trạng thái "Chờ hoàn tiền".'
            );
        }

        // Kiểm tra xem có phải đơn hàng VNPay không
        if (!$order->isVnPayPayment()) {
            return redirect()->back()->with('error', 
                'Chỉ có thể xác nhận hoàn tiền cho đơn hàng VNPay.'
            );
        }

        // Cập nhật trạng thái thanh toán thành đã hoàn tiền
        $order->payment_status = 'refunded';
        $order->notes = ($order->notes ? $order->notes . "\n" : "") . 
            "Đã hoàn tiền. " . now()->format('d/m/Y H:i');
        $order->save();

        return redirect()->back()->with('success', 
            'Đã xác nhận hoàn tiền thành công cho đơn hàng.'
        );
    }

} 