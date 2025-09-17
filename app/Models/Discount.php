<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Discount extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_value',
        'usage_limit',
        'used',
        'starts_at',
        'expires_at',
        'is_claimable',
        'claim_limit',
        'claimed_count',
        'description',
        'image',
        'is_active'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
    ];

    public function orderDiscounts()
    {
        return $this->hasMany(OrderDiscount::class);
    }

    // Kiểm tra mã giảm giá có hợp lệ không
    public function isValid($orderAmount = 0)
    {
        // Kiểm tra trạng thái hoạt động (integer: 1 = active, 0 = inactive)
        if ($this->is_active != 1) {
            return false;
        }

        // Kiểm tra thời gian hiệu lực
        $now = Carbon::now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }
        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        // Kiểm tra giới hạn sử dụng
        if ($this->usage_limit && $this->used >= $this->usage_limit) {
            return false;
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($this->min_order_value > 0 && $orderAmount < $this->min_order_value) {
            return false;
        }

        return true;
    }

    // Tính số tiền giảm giá
    public function calculateDiscount($orderAmount)
    {
        if ($this->type === 'fixed') {
            // Không cho giảm vượt quá tổng đơn
            return min($this->value, $orderAmount);
        } else { // percent
            $discountAmount = $orderAmount * ($this->value / 100);

            // Giới hạn cứng (ví dụ 1,000,000 VNĐ)
            $maxDiscount = 1000000;

            // Không giảm quá mức giới hạn và không quá tổng đơn
            return min($discountAmount, $maxDiscount, $orderAmount);
        }
    }

    // Tăng số lần sử dụng
    public function incrementUsage()
    {
        $this->increment('used');
    }

    // Kiểm tra xem mã có còn sử dụng được không
    public function isAvailable()
    {
        return $this->isValid() && (!$this->usage_limit || $this->used < $this->usage_limit);
    }

    // Kiểm tra có thể nhận không
    public function isClaimable()
    {
        if ($this->is_claimable != 1 || $this->is_active != 1) {
            return false;
        }

        // Kiểm tra thời gian hiệu lực
        $now = Carbon::now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }
        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        // Kiểm tra claim limit
        if ($this->claim_limit && $this->claimed_count >= $this->claim_limit) {
            return false;
        }

        return true;
    }

    // Tăng số người đã nhận
    public function incrementClaimedCount()
    {
        $this->increment('claimed_count');
    }

    // Scope để lấy các mã giảm giá đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', 1)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', Carbon::now());
            })->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', Carbon::now());
            })->where(function ($q) {
                $q->whereNull('usage_limit')
                    ->orWhereRaw('used < usage_limit');
            });
    }

    // Scope để lấy các mã giảm giá có thể nhận
    public function scopeClaimable($query)
    {
        return $query->where('is_claimable', 1)
            ->where('is_active', 1)
            ->where(function ($q) {
                $q->whereNull('claim_limit')
                    ->orWhereRaw('claimed_count < claim_limit');
            })
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', Carbon::now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', Carbon::now());
            });
    }
}
