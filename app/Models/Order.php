<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'tax_amount',
        'shipping_fee',
        'discount_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'shipping_address',
        'shipping_phone',
        'shipping_name',
        'notes',
        'paid_at',
        'shipped_at',
        'delivered_at'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Định nghĩa luồng trạng thái đơn hàng
    const STATUS_FLOW = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['processing', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped' => ['delivered', 'cancelled'],
        'delivered' => [], // Không thể thay đổi từ delivered
        'cancelled' => [], // Không thể thay đổi từ cancelled
    ];

    // Định nghĩa thứ tự ưu tiên trạng thái
    const STATUS_PRIORITY = [
        'pending' => 1,
        'confirmed' => 2,
        'processing' => 3,
        'shipped' => 4,
        'delivered' => 5,
        'cancelled' => 0, // Đặc biệt, có thể áp dụng ở bất kỳ giai đoạn nào
    ];

    // Boot method để tự động tạo order_number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Y') . '-' . str_pad(static::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function discounts()
    {
        return $this->hasMany(OrderDiscount::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Helper methods
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed', 'processing', 'shipped']);
    }

    public function canBeShipped()
    {
        return in_array($this->status, ['confirmed', 'processing']);
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function isCompleted()
    {
        return $this->status === 'delivered';
    }

    public function calculateTotal()
    {
        $this->total_amount = $this->subtotal + $this->tax_amount + $this->shipping_fee - $this->discount_amount;
        return $this->total_amount;
    }

    /**
     * Kiểm tra xem có thể cập nhật sang trạng thái mới không
     */
    public function canUpdateToStatus($newStatus)
    {
        // Kiểm tra trạng thái hiện tại có tồn tại trong luồng không
        if (!isset(self::STATUS_FLOW[$this->status])) {
            return false;
        }

        // Kiểm tra trạng thái mới có trong danh sách cho phép không
        return in_array($newStatus, self::STATUS_FLOW[$this->status]);
    }

    /**
     * Lấy danh sách trạng thái có thể cập nhật từ trạng thái hiện tại
     */
    public function getAvailableStatuses()
    {
        return self::STATUS_FLOW[$this->status] ?? [];
    }

    /**
     * Kiểm tra xem đơn hàng có phải thanh toán qua VNPay không
     */
    public function isVnPayPayment()
    {
        return $this->payment_method === 'vnpay';
    }

    /**
     * Kiểm tra xem có thể thay đổi trạng thái thanh toán không
     */
    public function canUpdatePaymentStatus()
    {
        // Không cho phép thay đổi trạng thái thanh toán của đơn hàng VNPay
        if ($this->isVnPayPayment()) {
            return false;
        }

        // Đối với COD: chỉ cho phép thay đổi từ "pending" sang "paid" khi đơn hàng đã giao
        if ($this->payment_method === 'cod') {
            return $this->payment_status === 'pending' && $this->status === 'delivered';
        }

        // Cho phép thay đổi với các phương thức thanh toán khác
        return true;
    }

    /**
     * Lấy danh sách trạng thái thanh toán có thể cập nhật
     */
    public function getAvailablePaymentStatuses()
    {
        // Đối với VNPay: không cho phép thay đổi
        if ($this->isVnPayPayment()) {
            return [];
        }

        // Đối với COD: chỉ cho phép 2 trạng thái khi đơn hàng đã giao
        if ($this->payment_method === 'cod') {
            if ($this->payment_status === 'pending' && $this->status === 'delivered') {
                return ['pending', 'paid'];
            } else {
                return [$this->payment_status]; // Chỉ hiển thị trạng thái hiện tại
            }
        }

        // Đối với các phương thức khác: cho phép tất cả
        return ['pending', 'paid', 'failed', 'refund_pending', 'refunded'];
    }

    /**
     * Lấy text hiển thị cho trạng thái
     */
    public function getStatusText()
    {
        $statusTexts = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đã gửi hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy'
        ];

        return $statusTexts[$this->status] ?? $this->status;
    }

    /**
     * Lấy text hiển thị cho trạng thái thanh toán
     */
    public function getPaymentStatusText()
    {
        $paymentStatusTexts = [
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'failed' => 'Thanh toán thất bại',
            'refund_pending' => 'Chờ hoàn tiền', // Thêm mới
            'refunded' => 'Đã hoàn tiền'
        ];
        return $paymentStatusTexts[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Lấy class CSS cho badge trạng thái
     */
    public function getStatusBadgeClass()
    {
        $badgeClasses = [
            'pending' => 'bg-warning text-dark',
            'confirmed' => 'bg-info text-white',
            'processing' => 'bg-primary text-white',
            'shipped' => 'bg-info text-white',
            'delivered' => 'bg-success text-white',
            'cancelled' => 'bg-danger text-white'
        ];

        return $badgeClasses[$this->status] ?? 'bg-secondary text-white';
    }

    /**
     * Lấy class CSS cho badge trạng thái thanh toán
     */
    public function getPaymentStatusBadgeClass()
    {
        $badgeClasses = [
            'pending' => 'bg-warning text-dark',
            'paid' => 'bg-success text-white',
            'failed' => 'bg-danger text-white',
            'refund_pending' => 'bg-info text-dark', // Thêm mới
            'refunded' => 'bg-info text-white'
        ];
        return $badgeClasses[$this->payment_status] ?? 'bg-secondary text-white';
    }

    /**
     * Kiểm tra có thể cập nhật trạng thái đơn hàng khi đang chờ hoàn tiền
     */
    public function canUpdateOrderStatusWhenRefundPending()
    {
        return $this->payment_status === 'refund_pending';
    }

    /**
     * Lấy text hiển thị cho phương thức thanh toán
     */
    public function getPaymentMethodText()
    {
        $methods = [
            'cod' => 'COD',
            'bank_transfer' => 'Chuyển khoản',
            'credit_card' => 'Thẻ tín dụng',
            'momo' => 'MoMo',
            'vnpay' => 'VNPay'
        ];

        return $methods[$this->payment_method] ?? 'Chưa chọn';
    }

    /**
     * Kiểm tra xem đơn hàng có thể tiếp tục thanh toán VNPay không
     */
    public function canContinueVnPayPayment()
    {
        // Kiểm tra đơn hàng có phải VNPay không
        if ($this->payment_method !== 'vnpay') {
            return false;
        }

        // Kiểm tra trạng thái thanh toán phải là pending
        if ($this->payment_status !== 'pending') {
            return false;
        }

        // Kiểm tra trạng thái đơn hàng phải là pending
        if ($this->status !== 'pending') {
            return false;
        }

        // Tìm payment VNPay gần nhất
        $payment = $this->getLatestVnPayPayment();
        if (!$payment) {
            return false;
        }

        // Kiểm tra payment có thể thanh toán không
        return $payment->canBePaid();
    }

    /**
     * Lấy payment VNPay gần nhất
     */
    public function getLatestVnPayPayment()
    {
        return $this->payments()
            ->where('payment_gateway', 'VNPAY')
            ->latest()
            ->first();
    }

    /**
     * Lấy thời gian còn lại của payment VNPay (phút)
     */
    public function getVnPayPaymentRemainingMinutes()
    {
        $payment = $this->getLatestVnPayPayment();
        return $payment ? $payment->getRemainingMinutes() : 0;
    }

    /**
     * Kiểm tra xem đơn hàng có payment VNPay đã hết hạn không
     */
    public function hasExpiredVnPayPayment()
    {
        $payment = $this->getLatestVnPayPayment();
        return $payment && $payment->isExpired();
    }

    /**
     * Kiểm tra xem đơn hàng có thể đánh giá sản phẩm không
     */
    public function canReviewProducts()
    {
        return $this->status === 'delivered' && $this->payment_status === 'paid';
    }

    /**
     * Kiểm tra xem sản phẩm cụ thể có thể đánh giá không
     */
    public function canReviewProduct($productId)
    {
        if (!$this->canReviewProducts()) {
            return false;
        }

        // Kiểm tra xem đơn hàng có chứa sản phẩm này không
        return $this->items()->where('product_id', $productId)->exists();
    }
}
