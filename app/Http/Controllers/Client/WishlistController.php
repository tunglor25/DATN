<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    /**
     * GET /wishlist  –  Hiển thị danh sách yêu thích
     */
    public function index()
    {
        // eager-load product + brand để tránh N+1 query
        $wishlists = Wishlist::with(['product.brand','product.reviews'])
            ->where('user_id', Auth::id())
            ->get();

        return view('client.wishlist.index', compact('wishlists'));
    }

    /**
     * POST /wishlist  –  Thêm sản phẩm vào yêu thích
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::firstOrCreate([
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return back()->with('success', 'Đã thêm vào danh sách yêu thích');
    }

    /**
     * DELETE /wishlist/remove/{id}  –  Xóa 1 mục yêu thích
     */
    public function destroy($id)
    {
        // Chỉ xóa mục của chính user hiện tại, 404 nếu không tồn tại
        $wishlist = Wishlist::where('user_id', Auth::id())
                            ->where('id', $id)
                            ->firstOrFail();

        $wishlist->delete();

        return back()->with('success', 'Đã xóa khỏi danh sách yêu thích');
    }
}
