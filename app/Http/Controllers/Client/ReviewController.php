<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create($productId, $orderId)
    {
        $user = Auth::user();

        // Lấy thông tin sản phẩm với các quan hệ cần thiết
        $product = Product::with([
            'brand',
            'category',
            'variants.attributeValues.attribute'
        ])->findOrFail($productId);

        // Lấy đơn hàng và kiểm tra quyền sở hữu
        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->with(['items' => function ($query) use ($productId) {
                $query->where('product_id', $productId);
            }])
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại hoặc bạn không có quyền truy cập.');
        }

        // Kiểm tra xem đơn hàng có thể đánh giá sản phẩm này không
        if (!$order->canReviewProduct($productId)) {
            return redirect()->back()->with('error', 'Bạn chỉ có thể đánh giá khi đã mua và nhận hàng.');
        }

        // Kiểm tra xem đã đánh giá sản phẩm này trong đơn hàng này chưa
        $hasReviewed = Review::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->exists();

        if ($hasReviewed) {
            return redirect()->route('detail', ['product' => $product->slug])
                ->with('success', 'Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi.');
        }

        return view('client.reviews.review', compact('product', 'order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        // Kiểm tra quyền sở hữu đơn hàng
        $order = Order::where('id', $request->order_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại hoặc bạn không có quyền truy cập.');
        }

        // Kiểm tra xem đã đánh giá sản phẩm này trong đơn hàng này chưa
        $hasReviewed = Review::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->where('order_id', $request->order_id)
            ->exists();

        if ($hasReviewed) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi.');
        }

        $images = [];

        // Lưu các ảnh nếu có
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('reviews', 'public');
            }
        }

        // Tạo đánh giá
        Review::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'images' => $images,
            'is_verified' => true,
        ]);

        // Redirect về trang chi tiết sản phẩm theo slug
        $product = Product::findOrFail($request->product_id);

        return redirect()->route('detail', ['product' => $product->slug])
            ->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}
