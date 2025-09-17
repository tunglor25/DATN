<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiscountController extends Controller
{
    public function index()
    {
        $query = Discount::query();

        // search
        if ($search = request()->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%");
            });
        }

        $discounts = $query->orderBy('id', 'desc')->paginate(10);

        // Gán trạng thái động cho từng discount
        $now = now();
        foreach ($discounts as $discount) {
            if (!$discount->is_claimable) {
                $discount->status_label = 'Ngừng hoạt động';
                $discount->status_class = 'secondary';
            } elseif ($discount->expires_at && $discount->expires_at < $now) {
                $discount->status_label = 'Hết hạn';
                $discount->status_class = 'danger';
            } elseif ($discount->usage_limit && $discount->used >= $discount->usage_limit) {
                $discount->status_label = 'Hết lượt';
                $discount->status_class = 'warning';
            } else {
                $discount->status_label = 'Đang hoạt động';
                $discount->status_class = 'success';
            }
        }

        return view('admin.discount.index', compact('discounts'));
    }


    public function create()
    {
        return view('admin.discount.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:discounts,code',
            'type' => 'required|in:percent,fixed',
            'value' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    // Không cho % > 100
                    if ($request->type === 'percent' && $value > 100) {
                        $fail('Giá trị giảm theo phần trăm không được lớn hơn 100%.');
                    }
                    // Không cho fixed > min_order_value
                    if ($request->type === 'fixed' && $request->min_order_value && $value > $request->min_order_value) {
                        $fail('Giá trị giảm cố định không được lớn hơn giá trị đơn hàng tối thiểu.');
                    }
                },
            ],
            'min_order_value' => 'required|numeric|min:1',
            // bỏ usage_limit ra khỏi validate
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_claimable' => 'required|boolean',
            'claim_limit' => 'required|integer|min:0',
            'description' => 'nullable|string|max:5000',
        ], [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.unique' => 'Mã giảm giá này đã tồn tại.',
            'type.required' => 'Vui lòng chọn loại giảm giá.',
            'value.required' => 'Vui lòng nhập giá trị giảm giá.',
            'min_order_value.required' => 'Vui lòng nhập giá trị đơn hàng tối thiểu.',
            'expires_at.after_or_equal' => 'Ngày hết hạn phải sau hoặc bằng ngày bắt đầu.',
            'is_claimable.required' => 'Vui lòng chọn Có/Không cho khả năng yêu cầu.',
            'claim_limit.required' => 'Vui lòng nhập giới hạn claim.',
        ]);

        // ép trạng thái luôn = 1
        $validatedData['is_active'] = 1;

        // ép usage_limit = claim_limit trong mọi trường hợp
        $validatedData['usage_limit'] = $validatedData['claim_limit'];

        Discount::create($validatedData);

        return redirect()->route('admin.discount.index')->with('success', 'Thêm Mã giảm giá thành công.');
    }

    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        return view('admin.discount.edit', compact('discount'));
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:discounts,code,' . $discount->id,
            'type' => 'required|in:percent,fixed',
            'value' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === 'percent' && $value > 100) {
                        $fail('Giá trị giảm theo phần trăm không được lớn hơn 100%.');
                    }
                    if ($request->type === 'fixed' && $request->min_order_value && $value > $request->min_order_value) {
                        $fail('Giá trị giảm cố định không được lớn hơn giá trị đơn hàng tối thiểu.');
                    }
                },
            ],
            'min_order_value' => 'required|numeric|min:1',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_claimable' => 'required|boolean',
            'claim_limit' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:5000',
        ], [
            'code.required'           => 'Vui lòng nhập mã giảm giá.',
            'code.unique'             => 'Mã giảm giá này đã tồn tại.',
            'type.required'           => 'Vui lòng chọn loại giảm giá.',
            'value.required'          => 'Vui lòng nhập giá trị giảm giá.',
            'min_order_value.required' => 'Vui lòng nhập giá trị đơn hàng tối thiểu.',
            'usage_limit.min'         => 'Giới hạn sử dụng phải lớn hơn 0.',
            'expires_at.after_or_equal' => 'Ngày hết hạn phải sau hoặc bằng ngày bắt đầu.',
            'is_claimable.required'   => 'Vui lòng chọn Có/Không cho khả năng yêu cầu.',

        ]);
        // ép trạng thái luôn = 1
        $validatedData['is_active'] = 1;
        $validatedData['usage_limit'] = $validatedData['claim_limit'];

        $discount->update($validatedData);

        return redirect()->route('admin.discount.index')->with('success', 'Cập nhật Mã giảm giá thành công.');
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();
        return redirect()->route('admin.discount.index')->with('success', 'Xóa Mã giảm giá thành công.');
    }
}
