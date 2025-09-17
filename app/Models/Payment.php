<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_gateway',
        'amount',
        'transaction_id',
        'status',
        'paid_at',
        'expires_at',
        'response_code',
        'response_message',
        'bank_code',
        'payment_data'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'payment_data' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Kiểm tra xem payment có thành công không
     */
    public function isSuccess()
    {
        return $this->status === 'success';
    }

    /**
     * Kiểm tra xem payment có đang pending không
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Kiểm tra xem payment có thất bại không
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Kiểm tra xem payment có bị hủy không
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Kiểm tra xem payment có hết hạn không
     */
    public function isExpired()
    {
        if ($this->expires_at) {
            return $this->expires_at->isPast();
        }
        
        // Fallback: kiểm tra theo created_at + 10 phút
        return $this->created_at->diffInMinutes(now()) > 10;
    }

    /**
     * Lấy thời gian còn lại (phút)
     */
    public function getRemainingMinutes()
    {
        if ($this->expires_at) {
            return max(0, now()->diffInMinutes($this->expires_at, false));
        }
        
        // Fallback: tính theo created_at + 10 phút
        return max(0, 10 - $this->created_at->diffInMinutes(now()));
    }

    /**
     * Kiểm tra xem payment có thể thanh toán không
     */
    public function canBePaid()
    {
        // Payment có thể thanh toán nếu đang pending hoặc cancelled (user có thể retry)
        return ($this->status === 'pending' || $this->status === 'cancelled') && !$this->isExpired();
    }
}
