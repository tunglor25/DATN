<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'product_name',
        'product_image',
        'variant_sku',
        'price',
        'quantity',
        'subtotal',
        'variant_attributes'
    ];

    protected $casts = [
        'order_id' => 'integer',
        'product_variant_id' => 'integer',
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
        'variant_attributes' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // Helper method
    public function calculateSubtotal()
    {
        $this->subtotal = $this->price * $this->quantity;
        return $this->subtotal;
    }

    /**
     * Lấy hình ảnh sản phẩm với logic ưu tiên:
     * 1. Dữ liệu snapshot (nếu có)
     * 2. Ảnh của biến thể (nếu có)
     * 3. Ảnh gốc của sản phẩm (nếu biến thể không có ảnh)
     * 4. Ảnh placeholder (nếu cả ba đều không có)
     */
    public function getProductImage()
    {
        return $this->getSnapshotImageUrl();
    }

    /**
     * Lấy đường dẫn tương đối của ảnh sản phẩm (cho checkout)
     */
    public function getProductImagePath()
    {
        // Ưu tiên ảnh của biến thể
        if ($this->variant && !empty($this->variant->image)) {
            return $this->variant->image;
        }
        
        // Lấy ảnh gốc của sản phẩm
        if ($this->product && !empty($this->product->product_image)) {
            return $this->product->product_image;
        }
        
        // Trả về chuỗi rỗng nếu không có ảnh
        return '';
    }

    /**
     * Lấy product_id từ mối quan hệ với sản phẩm
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Lấy hình ảnh sản phẩm từ snapshot data (không phụ thuộc vào relationship)
     * Sử dụng khi sản phẩm đã bị xóa nhưng vẫn cần hiển thị trong đơn hàng
     */
    public function getSnapshotImageUrl()
    {
        // Ưu tiên sử dụng dữ liệu snapshot trước
        if (!empty($this->product_image)) {
            $imagePath = $this->product_image;
        }
        // Thử lấy ảnh từ relationship (nếu sản phẩm vẫn tồn tại)
        elseif ($this->variant && !empty($this->variant->image)) {
            $imagePath = $this->variant->image;
        } elseif ($this->product && !empty($this->product->product_image)) {
            $imagePath = $this->product->product_image;
        } else {
            // Nếu không có dữ liệu nào, trả về placeholder
            return 'https://via.placeholder.com/80x80/f0f0f0/999999?text=No+Image';
        }

        // Xác định URL ảnh
        if ($imagePath) {
            // Nếu là URL tuyệt đối (bắt đầu bằng http/https)
            if (strpos($imagePath, 'http') === 0) {
                return $imagePath;
            }
            
            // Kiểm tra các thư mục có thể chứa ảnh
            if (file_exists(public_path('storage/products/' . $imagePath))) {
                return asset('storage/products/' . $imagePath);
            } elseif (file_exists(public_path('storage/' . $imagePath))) {
                return asset('storage/' . $imagePath);
            } elseif (file_exists(public_path('storage/img/' . $imagePath))) {
                return asset('storage/img/' . $imagePath);
            } elseif (file_exists(public_path('storage/uploads/' . $imagePath))) {
                return asset('storage/uploads/' . $imagePath);
            } elseif (file_exists(public_path('storage/thumbnail/' . $imagePath))) {
                return asset('storage/thumbnail/' . $imagePath);
            } else {
                // Thử trả về URL mặc định nếu file không tồn tại
                return asset('storage/products/' . $imagePath);
            }
        }

        return 'https://via.placeholder.com/80x80/f0f0f0/999999?text=No+Image';
    }

    /**
     * Kiểm tra xem sản phẩm có tồn tại không
     */
    public function isProductExists()
    {
        return $this->product !== null;
    }

    /**
     * Kiểm tra xem variant có tồn tại không
     */
    public function isVariantExists()
    {
        return $this->variant !== null;
    }

    /**
     * Lấy danh sách thuộc tính sản phẩm từ snapshot hoặc relationship
     */
    public function getDisplayAttributes()
    {
        // Ưu tiên sử dụng dữ liệu snapshot trước
        if ($this->variant_attributes && is_array($this->variant_attributes)) {
            return $this->variant_attributes;
        }
        
        // Nếu không có snapshot, thử lấy từ relationship
        if ($this->variant && $this->variant->attributeValues->isNotEmpty()) {
            $attributes = [];
            foreach ($this->variant->attributeValues as $attrValue) {
                $attributes[$attrValue->attribute->name] = $attrValue->value;
            }
            return $attributes;
        }
        
        return [];
    }

    /**
     * Method tương thích ngược để lấy variant attributes
     * Sử dụng trong views để thay thế logic cũ
     */
    public function getVariantAttributesForDisplay()
    {
        $attributes = $this->getDisplayAttributes();
        if (empty($attributes)) {
            return collect();
        }
        
        // Chuyển đổi thành format tương thích với logic cũ
        $attributeValues = collect();
        foreach ($attributes as $name => $value) {
            $attributeValues->push((object) [
                'attribute' => (object) ['name' => $name],
                'value' => $value
            ]);
        }
        
        return $attributeValues;
    }

    /**
     * Lấy hình ảnh sản phẩm với fallback cho snapshot
     */
    public function getDisplayImageUrl()
    {
        return $this->getSnapshotImageUrl();
    }


}
