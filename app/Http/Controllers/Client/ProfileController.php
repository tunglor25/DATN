<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('client.profile.index', compact('user'));
    }

        public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $messages = [
            'name.required' => 'Họ và tên không được để trống.',
            'name.string'   => 'Họ và tên phải là chuỗi ký tự.',
            'name.max'      => 'Họ và tên không được vượt quá 100 ký tự.',

            'phone.required' => 'Số điện thoại không được để trống.',
            'phone.string'   => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max'      => 'Số điện thoại không được vượt quá 11 ký tự.',

            'gender.in'     => 'Giới tính không hợp lệ.',
            'gender.required' => 'Giới tính không được để trống.',
        ];

        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:11',
            'gender'  => 'in:M,F,O',
        ], $messages);

        $user->update($validated);

        return redirect()->back()->with('success', 'Thông tin đã được cập nhật thành công!');
    }

}
