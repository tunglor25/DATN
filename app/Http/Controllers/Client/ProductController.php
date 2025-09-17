<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'variants.attributeValues.attribute'])
            ->where('is_active', true);

        //LỌC GIÁ 
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        //LỌC DANH MỤC
        if ($request->filled('category_id')) {
            $categoryIds = (array) $request->category_id;
            $allCategoryIds = collect($categoryIds);
            foreach ($categoryIds as $id) {
                $children = Category::where('parent_id', $id)->pluck('id');
                $allCategoryIds = $allCategoryIds->merge($children);
            }
            $query->whereIn('category_id', $allCategoryIds->unique());
        }

        // LỌC THƯƠNG HIỆU 
        if ($request->filled('brand_id')) {
            $query->whereIn('brand_id', (array) $request->brand_id);
        }

        //LỌC THEO THUỘC TÍNH ĐỘNG 
        if ($request->has('attributes')) {
            foreach ($request->input('attributes') as $attributeId => $valueIds) {
                $query->whereHas('variants.attributeValues', function ($q) use ($attributeId, $valueIds) {
                    $q->whereIn('attribute_value_id', $valueIds)
                        ->whereHas('attribute', function ($a) use ($attributeId) {
                            $a->where('id', $attributeId);
                        });
                });
            }
        }

        //SẮP XẾP
        switch ($request->input('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        //DỮ LIỆU PHỤ
        $categories = Category::all();
        $brands = Brand::all();
        $attributes = Attribute::with('values')->get();

        return view('client.products.index', compact(
            'products',
            'categories',
            'brands',
            'attributes'
        ));
    }
}