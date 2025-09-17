<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('values')->get();
        return view('admin.attributes.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:attributes,name',
            'type' => 'required|string|max:50',
            'values.*' => 'nullable|string|max:100'
        ]);

        $attribute = Attribute::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        $values = collect($request->input('values'))->filter();
        foreach ($values as $value) {
            $attribute->values()->create(['value' => $value]);
        }

        return redirect()->route('admin.attributes.index')->with('success', 'Đã thêm loại và giá trị biến thể.');
    }

    public function edit($id)
    {
        $attribute = Attribute::with('values')->findOrFail($id);
        $attributes = Attribute::with('values')->get();
        return view('admin.attributes.index', compact('attribute', 'attributes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'values' => 'array',
            'values.*' => 'string|nullable|max:100',
        ]);

        $attribute = Attribute::findOrFail($id);
        $attribute->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        // Xoá giá trị cũ
        $attribute->values()->delete();

        // Thêm lại giá trị mới
        foreach ($request->values as $value) {
            if (trim($value) !== '') {
                $attribute->values()->create(['value' => $value]);
            }
        }

        return redirect()->route('admin.attributes.index')->with('success', 'Cập nhật loại biến thể thành công!');
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return back()->with('success', 'Đã xoá loại biến thể.');
    }
}
