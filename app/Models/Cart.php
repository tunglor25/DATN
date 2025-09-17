<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id'];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Helper methods
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getTotalAmountAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function isEmpty()
    {
        return $this->items->count() === 0;
    }

    public function clear()
    {
        return $this->items()->delete();
    }
}
