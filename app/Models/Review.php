<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'comment',
        'images',
        'is_verified',
        'is_hidden'
    ];

    protected $casts = [
        'images' => 'array',
        'is_verified' => 'boolean',
        'is_hidden' => 'boolean',
        'rating' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getImagesArrayAttribute()
    {
        return $this->images ?? [];
    }

    public function hasImages()
    {
        return !empty($this->images);
    }

    // Scope để lọc đánh giá đang hiện
    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }

    // Scope để lọc đánh giá bị ẩn
    public function scopeHidden($query)
    {
        return $query->where('is_hidden', true);
    }

    // Scope để lọc đánh giá đã xác thực
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    // Scope để lọc đánh giá chưa xác thực
    public function scopeUnverified($query)
    {
        return $query->where('is_verified', false);
    }
}
