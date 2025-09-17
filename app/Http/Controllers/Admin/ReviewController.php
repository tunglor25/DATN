<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách người dùng đã đánh giá với số lượng đánh giá
        $query = User::whereHas('reviews')
            ->withCount('reviews')
            ->with(['reviews' => function($q) {
                $q->with(['product' => function($productQuery) {
                    $productQuery->withTrashed(); // Load cả sản phẩm đã bị xóa
                }])
                ->latest()
                ->take(1); // Lấy đánh giá mới nhất để hiển thị thông tin
            }])
            ->orderBy('reviews_count', 'desc');

        // Tìm kiếm theo tên người dùng
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Lọc theo số lượng đánh giá
        if ($request->filled('rating')) {
            $query->having('reviews_count', '>=', $request->rating);
        }

        // Lọc theo trạng thái xác thực (người dùng có ít nhất 1 đánh giá đã xác thực)
        if ($request->filled('verified')) {
            if ($request->verified == '1') {
                $query->whereHas('reviews', function($q) {
                    $q->where('is_verified', true);
                });
            } else {
                $query->whereHas('reviews', function($q) {
                    $q->where('is_verified', false);
                });
            }
        }

        $users = $query->paginate(15);

        return view('admin.reviews.index', compact('users'));
    }

    public function show(User $user)
    {
        // Lấy tất cả đánh giá của người dùng với thông tin sản phẩm và đơn hàng
        $reviews = $user->reviews()
            ->with(['product' => function($query) {
                $query->withTrashed(); // Load cả sản phẩm đã bị xóa
            }, 'order'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.reviews.show', compact('user', 'reviews'));
    }

    public function hideComment(Review $review)
    {
        $review->update(['comment' => null]);
        
        return redirect()->back()->with('success', 'Đã ẩn bình luận của đánh giá thành công!');
    }

    public function toggleHidden(Review $review)
    {
        $review->update(['is_hidden' => !$review->is_hidden]);
        
        $status = $review->is_hidden ? 'ẩn' : 'hiện';
        return redirect()->back()->with('success', "Đã {$status} đánh giá thành công!");
    }

    public function toggleVerified(Review $review)
    {
        $review->update(['is_verified' => !$review->is_verified]);
        
        $status = $review->is_verified ? 'xác thực' : 'bỏ xác thực';
        return redirect()->back()->with('success', "Đã {$status} đánh giá thành công!");
    }

    public function destroy(Review $review)
    {
        $review->delete();
        
        return redirect()->back()->with('success', 'Đã xóa đánh giá thành công!');
    }
}
