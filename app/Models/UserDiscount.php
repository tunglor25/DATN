<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDiscount extends Model
{
    protected $fillable = [
        'user_id', 'discount_id', 'status', 'claimed_at', 'used_at'
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->discount->isValid();
    }

    public function markAsUsed()
    {
        $this->update([
            'status' => 'used',
            'used_at' => now()
        ]);
    }

    public function markAsExpired()
    {
        $this->update([
            'status' => 'expired'
        ]);
    }
}
