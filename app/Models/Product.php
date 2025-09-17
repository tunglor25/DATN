<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes; // Kích hoạt xóa mềm

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'product_image',
        'is_active',
        'category_id',
        'brand_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'float',
        'stock' => 'integer',
    ];

    public $timestamps = true;

    // Lấy sản phẩm theo slug thay vì id
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Quan hệ với biến thể
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Quan hệ với giỏ hàng (thông qua variants)
    public function cartItems()
    {
        return $this->hasManyThrough(CartItem::class, ProductVariant::class);
    }

    // Quan hệ với danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Quan hệ với thương hiệu
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Quan hệ sản phẩm yêu thích
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Tính rating trung bình (chỉ tính đánh giá đang hiện)
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('is_hidden', false)->avg('rating') ?? 0;
    }

    // Lấy số lượng review (chỉ tính đánh giá đang hiện)
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->where('is_hidden', false)->count();
    }

    // Lấy rating trung bình làm tròn đến 0.5 (chỉ tính đánh giá đang hiện)
    public function getRoundedRatingAttribute()
    {
        $rating = $this->getAverageRatingAttribute();
        return round($rating * 2) / 2; // Làm tròn đến 0.5
    }

    // Lấy tất cả đánh giá (bao gồm cả ẩn) - cho admin
    public function getAllReviewsAttribute()
    {
        return $this->reviews()->orderBy('created_at', 'desc')->get();
    }

    // Lấy đánh giá đang hiện - cho khách hàng
    public function getVisibleReviewsAttribute()
    {
        return $this->reviews()->where('is_hidden', false)->orderBy('created_at', 'desc')->get();
    }
}
