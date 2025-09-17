<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load([
            'items.variant.product',
            'items.variant.attributeValues.attribute',
            'items.product'
        ]);

        return view('client.cart.index', compact('cart'));
    }

    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required_without:variant_id',
                'variant_id' => 'required_without:product_id',
                'quantity' => 'required|integer|min:1',
            ]);

            // Xóa session buy_again_items khi thêm sản phẩm mới vào giỏ hàng
            session()->forget(['buy_again_items', 'buy_again_order_id']);

            $cart = $this->getOrCreateCart();
            $product = null;
            $variant = null;
            $price = 0;
            $stock = 0;

            // Xử lý thêm sản phẩm có biến thể
            if ($request->has('variant_id')) {
                $variant = ProductVariant::find($request->variant_id);
                if (!$variant) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Biến thể sản phẩm không tồn tại'
                    ], 404);
                }
                $product = $variant->product;
                $price = $variant->price;
                $stock = $variant->stock;
            }
            // Xử lý thêm sản phẩm gốc (không có biến thể)
            else if ($request->has('product_id')) {
                $product = \App\Models\Product::find($request->product_id);
                if (!$product) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sản phẩm không tồn tại'
                    ], 404);
                }
                $price = $product->price;
                $stock = $product->stock;
            }

            // Kiểm tra stock
            if ($stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng vượt quá tồn kho'
                ], 400);
            }

            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            $existingItem = null;
            if ($variant) {
                // Nếu có variant, kiểm tra theo variant
                $existingItem = $cart->items()->where('product_variant_id', $variant->id)->first();
            } else {
                // Nếu không có variant, kiểm tra theo product
                $existingItem = $cart->items()->where('product_id', $product->id)
                    ->whereNull('product_variant_id')->first();
            }

            if ($existingItem) {
                $newQuantity = $existingItem->quantity + $request->quantity;
                if ($newQuantity > $stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tổng số lượng vượt quá tồn kho'
                    ], 400);
                }
                $existingItem->update(['quantity' => $newQuantity]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'product_variant_id' => $variant ? $variant->id : null,
                    'quantity' => $request->quantity,
                    'price' => $price,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào giỏ hàng',
                'cart_count' => $cart->fresh()->total_items
            ]);
        } catch (\Exception $e) {
            \Log::error('Add to cart error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $cart = $this->getOrCreateCart();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại!',
                'cart_count' => $cart->fresh()->total_items
            ], 500);
        }
    }

    public function updateQuantity(Request $request, $itemId)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $cart = $this->getOrCreateCart();
            $item = $cart->items()->findOrFail($itemId);

            // Kiểm tra stock
            $maxStock = $item->max_stock;
            if ($request->quantity > $maxStock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng vượt quá tồn kho',
                    'cart_count' => $cart->fresh()->total_items
                ], 400);
            }

            $item->update(['quantity' => $request->quantity]);

            // Refresh the item to get updated subtotal
            $item->refresh();

            return response()->json([
                'success' => true,
                'subtotal' => $item->subtotal,
                'total' => $cart->fresh()->total_amount,
                'cart_count' => $cart->fresh()->total_items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật số lượng, vui lòng thử lại!',
                'cart_count' => 0
            ], 500);
        }
    }

    public function removeItem($itemId)
    {
        try {
            $cart = $this->getOrCreateCart();
            $item = $cart->items()->findOrFail($itemId);
            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
                'total' => $cart->fresh()->total_amount,
                'cart_count' => $cart->fresh()->total_items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa sản phẩm, vui lòng thử lại!',
                'cart_count' => 0
            ], 500);
        }
    }

    public function clear()
    {
        try {
            $cart = $this->getOrCreateCart();
            $cart->clear();

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa tất cả sản phẩm',
                'cart_count' => 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa giỏ hàng, vui lòng thử lại!',
                'cart_count' => 0
            ], 500);
        }
    }

    private function getOrCreateCart()
    {
        $user = Auth::user();
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }
}
