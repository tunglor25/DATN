<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::latest()->paginate(10);

        $editBrand = null;
        if ($request->has('edit')) {
            $editBrand = Brand::find($request->get('edit'));
        }

        return view('admin.brands.index', compact('brands', 'editBrand'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'unique:brands,name|required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập tên thương hiệu.',
            'name.unique' => 'Tên thương hiệu đã tồn tại.',
            'logo.mimes' => 'Ảnh phải thuộc định dạng: jpeg, png, jpg, gif, svg.',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($validated);
        return redirect()->route('admin.brands.index')->with('success', 'Đã thêm brand!');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'unique:brands,name|required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập tên thương hiệu.',
            'name.unique' => 'Tên thương hiệu đã tồn tại.',
            'logo.mimes' => 'Ảnh phải thuộc định dạng: jpeg, png, jpg, gif, svg.',
        ]);

        $brand = Brand::findOrFail($id);

        if ($request->hasFile('logo')) {
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }

            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($validated);
        return redirect()->route('admin.brands.index')->with('success', 'Đã cập nhật brand!');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Đã xóa mềm brand!');
    }


    public function trash()
    {
        $brands = Brand::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);
        return view('admin.brands.trash', compact('brands'));
    }


    public function restore($id)
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);
        $brand->restore();
        return redirect()->route('admin.brands.trash')->with('success', 'Khôi phục brand thành công!');
    }

    public function forceDelete($id)
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);

        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->forceDelete();
        return redirect()->route('admin.brands.trash')->with('success', 'Đã xóa vĩnh viễn brand!');
    }
}
