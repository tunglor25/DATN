<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'stock',
        'image',
    ];

    public $timestamps = true;

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'variant_attribute_values', 'variant_id', 'attribute_value_id')
            ->withTimestamps()
            ->with('attribute');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_variant_id');
    }
        public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_variant_id');
    }
}
