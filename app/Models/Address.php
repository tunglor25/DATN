<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'receiver_name',
        'receiver_phone',
        'province_code',
        'province_name',
        'ward_code',
        'ward_name',
        'street_address',
        'is_default',
        'is_active'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Methods
    public function setAsDefault(): void
    {
        // Bỏ default của tất cả địa chỉ khác của user này
        $this->user->addresses()->where('id', '!=', $this->id)->update(['is_default' => false]);
        
        // Đặt địa chỉ này làm default
        $this->update(['is_default' => true]);
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->street_address}, {$this->ward_name}, {$this->province_name}";
    }

    public function getShortAddressAttribute(): string
    {
        return "{$this->ward_name}, {$this->province_name}";
    }

    public function isUsedInOrders(): bool
    {
        return $this->user->orders()->where('address_id', $this->id)->exists();
    }
}
