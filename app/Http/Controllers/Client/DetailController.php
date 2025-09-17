<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailController extends Controller
{
    public function detail(Product $product)
    {
        $product->load([
            'variants.attributeValues.attribute',
            'category',
            'brand'
        ]);

        // Tạo mảng attributes với cấu trúc rõ ràng hơn
        $attributes = [];
        $attributeGroups = [];

        // Nhóm các giá trị theo attribute
        foreach ($product->variants as $variant) {
            foreach ($variant->attributeValues as $value) {
                $attrName = $value->attribute->name;
                $attrId = $value->attribute->id;

                if (!isset($attributes[$attrName])) {
                    $attributes[$attrName] = [];
                    $attributeGroups[$attrName] = $attrId;
                }

                // Chỉ thêm giá trị nếu chưa có
                if (!isset($attributes[$attrName][$value->id])) {
                    $attributes[$attrName][$value->id] = $value->value;
                }
            }
        }

        // Lấy 8 sản phẩm có cùng danh mục với sản phẩm đang xem
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'brand', 'reviews'])
            ->limit(8)
            ->get();

        // Chuẩn bị dữ liệu variants cho JavaScript
        $variantsData = [];
        foreach ($product->variants as $variant) {
            $variantValues = $variant->attributeValues->pluck('id')->toArray();
            sort($variantValues);

            $variantsData[] = [
                'id' => $variant->id,
                'values' => $variantValues,
                'price' => $variant->price,
                'stock' => $variant->stock,
                'sku' => $variant->sku,
                'image' => $variant->image ? asset('storage/' . $variant->image) : null
            ];
        }
        // Review
        $user = Auth::user();
        $hasReviewed = $user ? $product->reviews()->where('is_hidden', false)->where('user_id', $user->id)->exists() : false;

        // Chỉ tính đánh giá đang hiện
        $visibleReviews = $product->reviews()->where('is_hidden', false)->get();
        $averageRating = round($visibleReviews->avg('rating'), 1);
        $ratingsCount = [
            5 => $visibleReviews->where('rating', 5)->count(),
            4 => $visibleReviews->where('rating', 4)->count(),
            3 => $visibleReviews->where('rating', 3)->count(),
            2 => $visibleReviews->where('rating', 2)->count(),
            1 => $visibleReviews->where('rating', 1)->count(),
        ];

        $reviewsWithImages = $visibleReviews->filter(fn($r) => is_array($r->images) && count($r->images) > 0)->count();
        $reviewsWithComment = $visibleReviews->filter(fn($r) => !empty($r->comment))->count();

        // Chỉ lấy đánh giá đang hiện cho khách hàng
        $reviewQuery = $product->reviews()->where('is_hidden', false)->with('user')->latest();
        if (request('rating') && is_numeric(request('rating'))) {
            $reviewQuery->where('rating', request('rating'));
        }
        if (request('has_images')) {
            $reviewQuery->whereNotNull('images')->whereRaw('JSON_LENGTH(images) > 0');
        }
        if (request('has_comment')) {
            $reviewQuery->whereNotNull('comment')->where('comment', '!=', '');
        }

        $reviews = $reviewQuery->paginate(3)->withQueryString();


        return view('client.products.detail', compact(
            'product',
            'attributes',
            'attributeGroups',
            'relatedProducts',
            'variantsData',
            'hasReviewed',
            'averageRating',
            'ratingsCount',
            'reviewsWithImages',
            'reviewsWithComment',
            'reviews'

        ));
    }
}
