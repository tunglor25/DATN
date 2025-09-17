<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\Slide;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy 3 slide đang hoạt động
        $slides = Slide::where('is_active', true)
            ->orderBy('position')
            ->take(3)
            ->get();

        // kiểm tra nếu có id ng dùng thì mới lấy danh sách
        if (Auth::check()) {
            $userId = Auth::user()->id;
            $wishlistProductIds = Wishlist::where('user_id', $userId)->pluck('product_id')->toArray();
        } else {
            $wishlistProductIds = [];
        }
        
        // lấy category nam, nu
        $categoryMan = Category::where('slug', 'like', "%nam%")->first();
        $categoryWoman = Category::where('slug', 'like', "%nu%")->first();

        // Gọi hàm lọc sản phẩm nam
        $productsMan = $this->getProductsByGenderSlug(['nam', 'man', 'men'], 6);

        // Gọi hàm lọc sản phẩm nu
        $productsWoman = $this->getProductsByGenderSlug(['nu', 'woman', 'women'], 6);

        // Lấy ra brand mới nhất
        $brands = Brand::orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Lấy ra 9 bài viết mới nhất và trạng thái công khai
        $news = Post::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->limit(9)
            ->get();

        return view('client.home', compact(
            'slides',
            'productsMan',
            'productsWoman',
            'brands',
            'news',
            'categoryMan',
            'categoryWoman',
            'wishlistProductIds'
        ));
    }

    // Lọc sản phẩm theo gender
    private function getProductsByGenderSlug(array $genderSlugs, int $limit = 6)
    {
        return Product::whereHas('category', function ($query) use ($genderSlugs) {
            $query->where(function ($q) use ($genderSlugs) {
                foreach ($genderSlugs as $slug) {
                    $q->orWhere('slug', 'like', "%{$slug}%");
                }
            });
        })
            ->where('is_active', true)
            ->take($limit)
            ->get();
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        // Kiểm tra nếu query rỗng
        if (empty($query)) {
            return response()->json([]);
        }
        
        $products = Product::where('name', 'like', "%$query%")
            ->select('id', 'name', 'slug', 'product_image as image', 'price') // đổi product_image thành image
            ->limit(5)
            ->get();

        return response()->json($products);
    }
}
