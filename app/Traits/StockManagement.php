<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait StockManagement
{
    /**
     * Kiểm tra và giảm tồn kho với transaction và row lock
     * 
     * @param array $items Danh sách items cần kiểm tra
     * @return array Thông tin items đã được reserve
     * @throws \Exception Khi không đủ tồn kho
     */
    protected function reserveStock($items)
    {
        return DB::transaction(function () use ($items) {
            $reservedItems = [];
            
            // Sắp xếp items theo ID để tránh deadlock
            usort($items, function($a, $b) {
                $aId = $a['variant_id'] ?? $a['product_id'];
                $bId = $b['variant_id'] ?? $b['product_id'];
                return $aId <=> $bId;
            });
            
            foreach ($items as $item) {
                $productId = $item['product_id'];
                $variantId = $item['variant_id'] ?? null;
                $quantity = $item['quantity'];
                
                if ($variantId) {
                    // Lock variant row để tránh race condition
                    $variant = ProductVariant::lockForUpdate()->find($variantId);
                    
                    if (!$variant) {
                        throw new \Exception("Biến thể sản phẩm không tồn tại (ID: {$variantId})");
                    }
                    
                    // Kiểm tra tồn kho với thông báo chi tiết hơn
                    if ($variant->stock < $quantity) {
                        $productName = $variant->product ? $variant->product->name : 'Sản phẩm';
                        $availableStock = $variant->stock;
                        
                        Log::warning("Không đủ tồn kho variant", [
                            'variant_id' => $variant->id,
                            'variant_sku' => $variant->sku,
                            'product_name' => $productName,
                            'quantity_requested' => $quantity,
                            'available_stock' => $availableStock,
                            'user_id' => auth()->id(),
                            'timestamp' => now()
                        ]);
                        
                        throw new \Exception(
                            "Sản phẩm '{$productName}' chỉ còn {$availableStock} trong kho " .
                            "(yêu cầu: {$quantity}). Vui lòng giảm số lượng hoặc chọn sản phẩm khác."
                        );
                    }
                    
                    // Giảm tồn kho
                    $oldStock = $variant->stock;
                    $variant->decrement('stock', $quantity);
                    $newStock = $variant->fresh()->stock;
                    
                    // Log thông tin
                    Log::info("Đã giảm tồn kho variant", [
                        'variant_id' => $variant->id,
                        'variant_sku' => $variant->sku,
                        'product_name' => $variant->product ? $variant->product->name : 'N/A',
                        'quantity_requested' => $quantity,
                        'old_stock' => $oldStock,
                        'new_stock' => $newStock,
                        'user_id' => auth()->id(),
                        'timestamp' => now()
                    ]);
                    
                    $reservedItems[] = [
                        'type' => 'variant',
                        'id' => $variant->id,
                        'quantity' => $quantity,
                        'old_stock' => $oldStock,
                        'new_stock' => $newStock
                    ];
                    
                } else {
                    // Lock product row để tránh race condition
                    $product = Product::lockForUpdate()->find($productId);
                    
                    if (!$product) {
                        throw new \Exception("Sản phẩm không tồn tại (ID: {$productId})");
                    }
                    
                    // Kiểm tra tồn kho với thông báo chi tiết hơn
                    if ($product->stock < $quantity) {
                        $availableStock = $product->stock;
                        
                        Log::warning("Không đủ tồn kho sản phẩm", [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'quantity_requested' => $quantity,
                            'available_stock' => $availableStock,
                            'user_id' => auth()->id(),
                            'timestamp' => now()
                        ]);
                        
                        throw new \Exception(
                            "Sản phẩm '{$product->name}' chỉ còn {$availableStock} trong kho " .
                            "(yêu cầu: {$quantity}). Vui lòng giảm số lượng hoặc chọn sản phẩm khác."
                        );
                    }
                    
                    // Giảm tồn kho
                    $oldStock = $product->stock;
                    $product->decrement('stock', $quantity);
                    $newStock = $product->fresh()->stock;
                    
                    // Log thông tin
                    Log::info("Đã giảm tồn kho sản phẩm", [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity_requested' => $quantity,
                        'old_stock' => $oldStock,
                        'new_stock' => $newStock,
                        'user_id' => auth()->id(),
                        'timestamp' => now()
                    ]);
                    
                    $reservedItems[] = [
                        'type' => 'product',
                        'id' => $product->id,
                        'quantity' => $quantity,
                        'old_stock' => $oldStock,
                        'new_stock' => $newStock
                    ];
                }
            }
            
            return $reservedItems;
        }, 5); // Retry 5 lần nếu có deadlock
    }
    
    /**
     * Khôi phục tồn kho khi có lỗi
     * 
     * @param array $reservedItems Danh sách items đã được reserve
     */
    protected function restoreStock($reservedItems)
    {
        foreach ($reservedItems as $item) {
            try {
                if ($item['type'] === 'variant') {
                    $variant = ProductVariant::find($item['id']);
                    if ($variant) {
                        $oldStock = $variant->stock;
                        $variant->increment('stock', $item['quantity']);
                        $newStock = $variant->fresh()->stock;
                        
                        Log::info("Đã khôi phục tồn kho variant", [
                            'variant_id' => $variant->id,
                            'variant_sku' => $variant->sku,
                            'quantity_restored' => $item['quantity'],
                            'old_stock' => $oldStock,
                            'new_stock' => $newStock
                        ]);
                    }
                } else {
                    $product = Product::find($item['id']);
                    if ($product) {
                        $oldStock = $product->stock;
                        $product->increment('stock', $item['quantity']);
                        $newStock = $product->fresh()->stock;
                        
                        Log::info("Đã khôi phục tồn kho sản phẩm", [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'quantity_restored' => $item['quantity'],
                            'old_stock' => $oldStock,
                            'new_stock' => $newStock
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error("Lỗi khi khôi phục tồn kho", [
                    'item' => $item,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    
    /**
     * Kiểm tra tồn kho mà không giảm (chỉ đọc)
     * 
     * @param array $items Danh sách items cần kiểm tra
     * @return array Kết quả kiểm tra
     */
    protected function checkStockAvailability($items)
    {
        $results = [];
        
        foreach ($items as $item) {
            $productId = $item['product_id'];
            $variantId = $item['variant_id'] ?? null;
            $quantity = $item['quantity'];
            
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if (!$variant) {
                    $results[] = [
                        'available' => false,
                        'message' => "Biến thể sản phẩm không tồn tại",
                        'item' => $item
                    ];
                    continue;
                }
                
                if ($variant->stock < $quantity) {
                    $productName = $variant->product ? $variant->product->name : 'Sản phẩm';
                    $results[] = [
                        'available' => false,
                        'message' => "Sản phẩm '{$productName}' chỉ còn {$variant->stock} trong kho",
                        'item' => $item,
                        'available_stock' => $variant->stock
                    ];
                    continue;
                }
                
                $results[] = [
                    'available' => true,
                    'item' => $item,
                    'available_stock' => $variant->stock
                ];
                
            } else {
                $product = Product::find($productId);
                if (!$product) {
                    $results[] = [
                        'available' => false,
                        'message' => "Sản phẩm không tồn tại",
                        'item' => $item
                    ];
                    continue;
                }
                
                if ($product->stock < $quantity) {
                    $results[] = [
                        'available' => false,
                        'message' => "Sản phẩm '{$product->name}' chỉ còn {$product->stock} trong kho",
                        'item' => $item,
                        'available_stock' => $product->stock
                    ];
                    continue;
                }
                
                $results[] = [
                    'available' => true,
                    'item' => $item,
                    'available_stock' => $product->stock
                ];
            }
        }
        
        return $results;
    }
    
    /**
     * Cộng lại tồn kho khi hủy đơn hàng
     * 
     * @param \App\Models\Order $order
     */
    protected function restoreStockOnCancellation($order)
    {
        try {
            foreach ($order->items as $orderItem) {
                $quantity = $orderItem->quantity;
                
                if ($orderItem->product_variant_id) {
                    // Cộng lại tồn kho cho variant
                    $variant = ProductVariant::find($orderItem->product_variant_id);
                    if ($variant) {
                        $oldStock = $variant->stock;
                        $variant->increment('stock', $quantity);
                        $newStock = $variant->fresh()->stock;
                        
                        Log::info("Đã cộng lại tồn kho variant", [
                            'order_id' => $order->id,
                            'variant_id' => $variant->id,
                            'variant_sku' => $variant->sku,
                            'quantity_restored' => $quantity,
                            'old_stock' => $oldStock,
                            'new_stock' => $newStock
                        ]);
                    }
                } else {
                    // Cộng lại tồn kho cho sản phẩm gốc
                    $product = Product::find($orderItem->product_id);
                    if ($product) {
                        $oldStock = $product->stock;
                        $product->increment('stock', $quantity);
                        $newStock = $product->fresh()->stock;
                        
                        Log::info("Đã cộng lại tồn kho sản phẩm", [
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'quantity_restored' => $quantity,
                            'old_stock' => $oldStock,
                            'new_stock' => $newStock
                        ]);
                    }
                }
            }
            
            Log::info("Hoàn thành cộng lại tồn kho cho đơn hàng", [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total_items' => $order->items->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error("Lỗi khi cộng lại tồn kho", [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
} 