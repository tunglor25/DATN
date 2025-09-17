<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand'])
            ->withCount('variants')
            ->orderBy('created_at', 'desc');

        if ($keyword = $request->input('q')) {
            $query->where(function ($q) use ($keyword) {
                $q->where('products.name', 'like', '%' . $keyword . '%')
                    ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%$keyword%"))
                    ->orWhereHas('brand', fn($q) => $q->where('name', 'like', "%$keyword%"));
            });
        }

        $products = $query->paginate(10);

        if ($request->ajax()) {
            return view('admin.products._table', compact('products'))->render();
        }

        return view('admin.products.index', compact('products'));
    }




    public function create()
    {
        $attributes = Attribute::with('values')->get();
        $categories = Category::all();
        $brands = Brand::all();

        $categoryOptions = $this->buildCategoryOptions($categories);

        return view('admin.products.create', compact('attributes', 'categories', 'brands', 'categoryOptions'));
    }


    private function buildCategoryOptions($categories, $parentId = null, $prefix = '', $selectedId = null)
    {
        $html = '';
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $selected = $selectedId == $category->id ? ' selected' : '';
                $html .= '<option value="' . $category->id . '"' . $selected . '>' . $prefix . $category->name . '</option>';
                $html .= $this->buildCategoryOptions($categories, $category->id, $prefix . '-- ', $selectedId);
            }
        }
        return $html;
    }



    private function generateVariantSku(Product $product, array $attributeValueIds)
    {
        sort($attributeValueIds);

        $name = Str::ascii($product->name);
        $words = preg_split('/\s+/', $name);
        $shortCode = strtoupper(implode('', array_map(fn($word) => mb_substr($word, 0, 1), $words)));

        $suffix = implode('-', $attributeValueIds);

        return $shortCode . '-' . $product->id . '-' . $suffix;
    }

    private function checkDuplicateVariants(Product $product, array $variants, $excludeVariantId = null)
    {
        $usedAttributeCombinations = [];
        
        foreach ($variants as $index => $variantData) {
            if (!empty($variantData['attribute_values'])) {
                $attributeValues = $variantData['attribute_values'];
                sort($attributeValues);
                $combination = implode(',', $attributeValues);
                
                // Kiểm tra trong request hiện tại
                if (in_array($combination, $usedAttributeCombinations)) {
                    throw new \Exception("Tổ hợp thuộc tính trùng lặp ở biến thể #" . ($index + 1));
                }
                
                // Kiểm tra trong database
                $query = $product->variants()->whereHas('attributeValues', function($q) use ($variantData) {
                    $q->whereIn('attribute_value_id', $variantData['attribute_values']);
                }, '=', count($variantData['attribute_values']));
                
                if ($excludeVariantId) {
                    $query->where('id', '!=', $excludeVariantId);
                }
                
                if ($query->exists()) {
                    throw new \Exception("Tổ hợp thuộc tính đã tồn tại trong hệ thống");
                }
                
                $usedAttributeCombinations[] = $combination;
            }
        }
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads/descriptions', 'public');
            $url = asset('storage/' . $path);

            return response()->json(['url' => $url]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }


    public function show(Product $product)
    {
        $product->load('variants.attributeValues.attribute'); // Eager load quan hệ
        return view('admin.products.show', compact('product'));
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'name.string' => 'Tên sản phẩm phải là một chuỗi ký tự.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'slug.string' => 'Slug phải là một chuỗi ký tự.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug đã tồn tại.',
            'price.required' => 'Giá sản phẩm không được để trống.',
            'price.numeric' => 'Giá sản phẩm phải là một số.',
            'price.min' => 'Giá sản phẩm phải lớn hơn 0.',
            'product_image.required' => 'Ảnh bìa sản phẩm không được để trống.',
            'product_image.image' => 'Tệp phải là một hình ảnh.',
            'product_image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'category_id.exists' => 'Danh mục được chọn không hợp lệ.',
            'brand_id.exists' => 'Thương hiệu được chọn không hợp lệ.',
            'stock.required_if' => 'Tồn kho không được để trống khi sản phẩm không có biến thể.',
            'stock.integer' => 'Tồn kho phải là một số nguyên.',
            'stock.min' => 'Tồn kho phải lớn hơn hoặc bằng 0.',
            'has_variants.boolean' => 'Trường sản phẩm có biến thể không hợp lệ.',
            'variants.required_if' => 'Sản phẩm có biến thể phải có ít nhất một biến thể.',
            'variants.*.price.required' => 'Giá của biến thể không được để trống.',
            'variants.*.price.numeric' => 'Giá của biến thể phải là một số.',
            'variants.*.price.min' => 'Giá của biến thể phải lớn hơn 0.',
            'variants.*.stock.required' => 'Số lượng tồn kho của biến thể không được để trống.',
            'variants.*.stock.integer' => 'Số lượng tồn kho của biến thể phải là một số nguyên.',
            'variants.*.stock.min' => 'Số lượng tồn kho của biến thể phải lớn hơn hoặc bằng 0.',
            'variants.*.sku.string' => 'SKU của biến thể phải là một chuỗi ký tự.',
            'variants.*.sku.max' => 'SKU của biến thể không được vượt quá 50 ký tự.',
            'variants.*.attribute_values.required' => 'Mỗi biến thể phải có ít nhất một giá trị thuộc tính.',
            'variants.*.attribute_values.min' => 'Mỗi biến thể phải có ít nhất một giá trị thuộc tính.',
            'variants.*.image.image' => 'Tệp ảnh của biến thể phải là một hình ảnh.',
            'variants.*.image.max' => 'Kích thước ảnh của biến thể không được vượt quá 2MB.',
        ];

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required_if:has_variants,0|integer|min:0',
            'product_image' => 'required|image|max:2048',
            'is_active' => 'sometimes|boolean',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'has_variants' => 'sometimes|boolean',

            'variants' => 'required_if:has_variants,1|array',
            'variants.*.price' => 'required_if:has_variants,1|numeric|min:0',
            'variants.*.stock' => 'required_if:has_variants,1|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:50',
            'variants.*.attribute_values' => 'required_if:has_variants,1|array|min:1',
            'variants.*.image' => 'nullable|image|max:2048',
        ], $messages);

        // Sử dụng database transaction để đảm bảo tính nhất quán
        return DB::transaction(function () use ($request, $data) {
            $slug = $data['slug'] ?? Str::slug($data['name']);

            // Kiểm tra slug unique
            $slugCount = Product::where('slug', $slug)->count();
            if ($slugCount > 0) {
                $slug = $slug . '-' . ($slugCount + 1);
            }

            // Xử lý ảnh sản phẩm với kiểm tra lỗi
            $productImage = null;
            if ($request->hasFile('product_image')) {
                $imageFile = $request->file('product_image');
                if ($imageFile->isValid()) {
                    $productImage = $imageFile->store('products', 'public');
                    if (!$productImage) {
                        throw new \Exception('Không thể upload ảnh sản phẩm.');
                    }
                } else {
                    throw new \Exception('File ảnh không hợp lệ.');
                }
            }

            // Xác định tồn kho ban đầu
            $hasVariants = $data['has_variants'] ?? false;
            $initialStock = !$hasVariants ? $data['stock'] : 0;

            $product = Product::create([
                'name' => $data['name'],
                'slug' => $slug,
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'stock' => $initialStock,
                'product_image' => $productImage,
                'is_active' => $data['is_active'] ?? true,
                'category_id' => $data['category_id'] ?? null,
                'brand_id' => $data['brand_id'] ?? null,
            ]);

            // Nếu có biến thể, tạo các biến thể
            if ($hasVariants && isset($data['variants'])) {
                // Kiểm tra duplicate variants
                $this->checkDuplicateVariants($product, $data['variants']);
                
                $totalStock = 0;
                $usedSkus = []; // Theo dõi SKU đã sử dụng

                foreach ($data['variants'] as $index => $variantData) {
                    $attributeValues = $variantData['attribute_values'];

                    // Tạo SKU và kiểm tra trùng lặp
                    $sku = $variantData['sku'] ?? $this->generateVariantSku($product, $attributeValues);
                    $originalSku = $sku;
                    $counter = 1;

                    while (in_array($sku, $usedSkus)) {
                        $sku = $originalSku . '-' . $counter;
                        $counter++;
                    }
                    $usedSkus[] = $sku;

                    $stock = $variantData['stock'];
                    $price = $variantData['price'];

                    $variant = $product->variants()->create([
                        'price' => $price,
                        'stock' => $stock,
                        'sku' => $sku,
                    ]);

                    $variant->attributeValues()->attach($attributeValues);

                    // Xử lý ảnh biến thể với kiểm tra lỗi
                    if ($request->hasFile("variants.$index.image")) {
                        $imageFile = $request->file("variants.$index.image");
                        if ($imageFile->isValid()) {
                            $path = $imageFile->store('variants', 'public');
                            if ($path) {
                                $variant->update(['image' => $path]);
                            }
                        }
                    }

                    $totalStock += $stock;
                }

                // Cập nhật tồn kho sản phẩm theo tổng tồn kho biến thể
                $product->update(['stock' => $totalStock]);
            }

            $message = $hasVariants ? 'Sản phẩm và biến thể đã được thêm.' : 'Sản phẩm đã được thêm thành công.';
            return redirect()->route('admin.products.index')->with('success', $message);
        });
    }



    public function edit(Product $product)
    {
        $product->load('variants.attributeValues.attribute'); // Eager load quan hệ

        $attributes = Attribute::with('values')->get();
        $categories = Category::all();
        $brands = Brand::all();
        $categoryOptions = $this->buildCategoryOptions($categories, null, '', old('category_id', $product->category_id));

        return view('admin.products.edit', compact('product', 'attributes', 'categories', 'brands', 'categoryOptions'));
    }

    public function update(Request $request, Product $product)
    {
        $messages = [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'name.string' => 'Tên sản phẩm phải là một chuỗi ký tự.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'slug.string' => 'Slug phải là một chuỗi ký tự.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug đã tồn tại.',
            'price.required' => 'Giá sản phẩm không được để trống.',
            'price.numeric' => 'Giá sản phẩm phải là một số.',
            'product_image.image' => 'Tệp phải là một hình ảnh.',
            'product_image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'category_id.exists' => 'Danh mục được chọn không hợp lệ.',
            'brand_id.exists' => 'Thương hiệu được chọn không hợp lệ.',
            'stock.required_if' => 'Tồn kho không được để trống khi sản phẩm không có biến thể.',
            'stock.integer' => 'Tồn kho phải là một số nguyên.',
            'stock.min' => 'Tồn kho phải lớn hơn hoặc bằng 0.',
            'has_variants.boolean' => 'Trường sản phẩm có biến thể không hợp lệ.',
            'variants.required_if' => 'Sản phẩm có biến thể phải có ít nhất một biến thể.',
            'variants.*.id.exists' => 'ID biến thể không hợp lệ.',
            'variants.*.price.required' => 'Giá của biến thể không được để trống.',
            'variants.*.price.numeric' => 'Giá của biến thể phải là một số.',
            'variants.*.stock.required' => 'Số lượng tồn kho của biến thể không được để trống.',
            'variants.*.stock.integer' => 'Số lượng tồn kho của biến thể phải là một số nguyên.',
            'variants.*.sku.string' => 'SKU của biến thể phải là một chuỗi ký tự.',
            'variants.*.sku.max' => 'SKU của biến thể không được vượt quá 50 ký tự.',
            'variants.*.attribute_values.required' => 'Mỗi biến thể phải có ít nhất một giá trị thuộc tính.',
            'variants.*.attribute_values.min' => 'Mỗi biến thể phải có ít nhất một giá trị thuộc tính.',
            'variants.*.image.image' => 'Tệp ảnh của biến thể phải là một hình ảnh.',
            'variants.*.image.max' => 'Kích thước ảnh của biến thể không được vượt quá 2MB.',
        ];

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required_if:has_variants,0|integer|min:0',
            'product_image' => 'nullable|image|max:2048',
            'is_active' => 'sometimes|boolean',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'has_variants' => 'sometimes|boolean',

            'variants' => 'required_if:has_variants,1|array',
            'variants.*.id' => 'nullable|integer|exists:product_variants,id',
            'variants.*.price' => 'required_if:has_variants,1|numeric',
            'variants.*.stock' => 'required_if:has_variants,1|integer',
            'variants.*.sku' => 'nullable|string|max:50',
            'variants.*.attribute_values' => 'required_if:has_variants,1|array|min:1',
            'variants.*.image' => 'nullable|image|max:2048',
        ], $messages);

        $slug = $data['slug'] ?? Str::slug($data['name']);

        // Cập nhật ảnh sản phẩm nếu có
        if ($request->hasFile('product_image')) {
            if ($product->product_image) {
                Storage::disk('public')->delete($product->product_image);
            }
            $productImage = $request->file('product_image')->store('products', 'public');
            $product->product_image = $productImage;
        }

        // Xác định loại sản phẩm và tồn kho
        $hasVariants = $data['has_variants'] ?? false;
        $initialStock = !$hasVariants ? $data['stock'] : 0;

        $product->update([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $initialStock,
            'is_active' => $data['is_active'] ?? true,
            'category_id' => $data['category_id'] ?? null,
            'brand_id' => $data['brand_id'] ?? null,
            'product_image' => $product->product_image, // nếu không đổi ảnh, vẫn giữ lại
        ]);

        // Xóa tất cả biến thể cũ nếu chuyển từ có biến thể sang không có biến thể
        if (!$hasVariants) {
            $product->variants()->get()->each(function ($variant) {
                if ($variant->image) {
                    Storage::disk('public')->delete($variant->image);
                }
                $variant->attributeValues()->detach();
                $variant->delete();
            });
        } else if (isset($data['variants'])) {
            // Xử lý biến thể nếu có
            $incomingVariantIds = collect($data['variants'])->pluck('id')->filter()->toArray();

            $product->variants()
                ->whereNotIn('id', $incomingVariantIds)
                ->get()
                ->each(function ($variant) {
                    if ($variant->image) {
                        Storage::disk('public')->delete($variant->image);
                    }
                    $variant->attributeValues()->detach();
                    $variant->delete();
                });

            $totalStock = 0;

            foreach ($data['variants'] as $index => $variantData) {
                $attributeValues = $variantData['attribute_values'];
                $sku = $variantData['sku'] ?? $this->generateVariantSku($product, $attributeValues);
                $stock = $variantData['stock'];
                $price = $variantData['price'];

                $imageFile = $request->file("variants.$index.image");

                if (!empty($variantData['id'])) {
                    // Cập nhật biến thể cũ - kiểm tra duplicate loại trừ biến thể hiện tại
                    $this->checkDuplicateVariants($product, [$variantData], $variantData['id']);
                    
                    $variant = $product->variants()->findOrFail($variantData['id']);
                    $oldImage = $variant->image;

                    $variant->update([
                        'price' => $price,
                        'stock' => $stock,
                        'sku' => $sku,
                    ]);

                    if ($imageFile && $imageFile->isValid()) {
                        $newImagePath = $imageFile->store('variants', 'public');
                        if ($oldImage) {
                            Storage::disk('public')->delete($oldImage);
                        }
                        $variant->update(['image' => $newImagePath]);
                    }

                    $variant->attributeValues()->sync($attributeValues);
                } else {
                    // Tạo biến thể mới - kiểm tra duplicate
                    $this->checkDuplicateVariants($product, [$variantData]);
                    
                    $newImagePath = null;
                    if ($imageFile && $imageFile->isValid()) {
                        $newImagePath = $imageFile->store('variants', 'public');
                    }

                    $variant = $product->variants()->create([
                        'price' => $price,
                        'stock' => $stock,
                        'sku' => $sku,
                        'image' => $newImagePath,
                    ]);

                    $variant->attributeValues()->attach($attributeValues);
                }

                $totalStock += $stock;
            }

            // Cập nhật tồn kho sản phẩm theo tổng tồn kho biến thể
            $product->update(['stock' => $totalStock]);
        }

        $message = $hasVariants ? 'Cập nhật sản phẩm và biến thể thành công.' : 'Cập nhật sản phẩm thành công.';
        return redirect()->route('admin.products.index')->with('success', $message);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được đưa vào thùng rác.');
    }

    public function trash()
    {
        $trashedProducts = Product::onlyTrashed()->with(['category', 'brand'])->paginate(10);
        return view('admin.products.trash', compact('trashedProducts'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->route('admin.products.trash')->with('success', 'Khôi phục sản phẩm thành công.');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        // Nếu có ảnh, xóa file vật lý
        if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
            Storage::disk('public')->delete($product->product_image);
        }

        $product->forceDelete();
        return redirect()->route('admin.products.trash')->with('success', 'Xóa vĩnh viễn sản phẩm thành công.');
    }
}
