<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\UserDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    public function index()
    {
        $availableDiscounts = Discount::claimable()->get();

        $claimedDiscounts = collect();
        if (Auth::check()) {
            $claimedDiscounts = Auth::user()->userDiscounts()
                ->with('discount')
                ->orderBy('claimed_at', 'desc')
                ->get();
        }

        return view('client.discounts.index', compact('availableDiscounts', 'claimedDiscounts'));
    }

    public function claim(Request $request, $discountId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để nhận mã giảm giá!');
        }

        try {
            DB::beginTransaction();

            $discount = Discount::findOrFail($discountId);
            $userId = Auth::id();

            // Kiểm tra điều kiện
            if (!$discount->isClaimable()) {
                return redirect()->back()->with('error', 'Mã giảm giá không khả dụng!');
            }

            // Kiểm tra user đã nhận mã này chưa
            $existingClaim = UserDiscount::where('user_id', $userId)
                ->where('discount_id', $discountId)
                ->first();

            if ($existingClaim) {
                return redirect()->back()->with('error', 'Bạn đã nhận mã giảm giá này rồi!');
            }

            // Kiểm tra lại claim limit (double-check)
            if ($discount->claim_limit && $discount->claimed_count >= $discount->claim_limit) {
                return redirect()->back()->with('error', 'Mã giảm giá đã hết lượt nhận!');
            }

            // Tạo user discount
            $userDiscount = UserDiscount::create([
                'user_id' => $userId,
                'discount_id' => $discountId,
                'status' => 'active',
                'claimed_at' => now(),
            ]);

            // Tăng số người đã nhận
            $discount->incrementClaimedCount();

            DB::commit();

            return redirect()->back()->with('success', 'Đã nhận mã giảm giá "' . $discount->code . '" thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Kiểm tra xem có phải lỗi duplicate key không
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return redirect()->back()->with('error', 'Bạn đã nhận mã giảm giá này rồi!');
            }

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi nhận mã giảm giá. Vui lòng thử lại!');
        }
    }

    public function myDiscounts()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userDiscounts = Auth::user()->userDiscounts()
            ->with('discount')
            ->orderBy('claimed_at', 'desc')
            ->get();

        return view('client.discounts.my-discounts', compact('userDiscounts'));
    }
}
