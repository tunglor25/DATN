<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'product_variant_id', 'quantity', 'price'];

    protected $casts = [
        'cart_id' => 'integer',
        'product_id' => 'integer',
        'product_variant_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productThroughVariant()
    {
        return $this->hasOneThrough(Product::class, ProductVariant::class, 'id', 'id', 'product_variant_id', 'product_id');
    }

    // Helper methods
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function updateQuantity($quantity)
    {
        if ($quantity <= 0) {
            return $this->delete();
        }

        return $this->update(['quantity' => $quantity]);
    }

    // Helper methods for product/variant handling
    public function getActualProductAttribute()
    {
        // Nếu có product_id trực tiếp, trả về product đó
        if ($this->product_id) {
            return $this->product;
        }
        // Nếu không có product_id nhưng có variant, trả về product thông qua variant
        if ($this->product_variant_id) {
            return $this->variant->product;
        }
        return null;
    }

    public function getActualVariantAttribute()
    {
        return $this->product_variant_id ? $this->variant : null;
    }

    public function getProductNameAttribute()
    {
        $product = $this->actual_product;
        return $product ? $product->name : 'Sản phẩm không xác định';
    }

    public function getProductImageAttribute()
    {
        // Ưu tiên hình ảnh của variant
        if ($this->product_variant_id && $this->variant && !empty($this->variant->image)) {
            return asset('storage/' . $this->variant->image);
        }

        // Nếu không có variant hoặc variant không có hình, dùng hình của product
        if ($this->product && !empty($this->product->product_image)) {
            return asset('storage/' . $this->product->product_image);
        }
        
        // Trả về placeholder nếu không có ảnh
        return 'https://via.placeholder.com/80x80/f0f0f0/999999?text=No+Image';
    }

    public function getSkuAttribute()
    {
        // Ưu tiên SKU của variant
        if ($this->product_variant_id && $this->variant) {
            return $this->variant->sku;
        }

        // Nếu không có variant, dùng SKU của product
        $product = $this->actual_product;
        return $product ? $product->sku : null;
    }

    public function getMaxStockAttribute()
    {
        // Ưu tiên stock của variant
        if ($this->product_variant_id && $this->variant) {
            return $this->variant->stock;
        }

        // Nếu không có variant, dùng stock của product
        $product = $this->actual_product;
        return $product ? $product->stock : 0;
    }

    public function getVariantAttributesAttribute()
    {
        if ($this->product_variant_id && $this->variant) {
            return $this->variant->attributeValues;
        }
        return collect(); // Trả về collection rỗng cho sản phẩm gốc
    }
}
