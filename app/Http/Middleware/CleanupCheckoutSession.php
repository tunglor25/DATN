<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CleanupCheckoutSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Chỉ xử lý cho user đã đăng nhập
        if (Auth::check()) {
            $this->cleanupExpiredSessions();
        }

        return $next($request);
    }

    /**
     * Tự động dọn dẹp session hết hạn
     */
    private function cleanupExpiredSessions()
    {
        // Kiểm tra và xóa pending_checkout_info hết hạn
        $checkoutInfo = session('pending_checkout_info');
        if ($checkoutInfo && isset($checkoutInfo['timestamp'])) {
            if (now()->timestamp - $checkoutInfo['timestamp'] > 1800) { // 30 phút
                session()->forget([
                    'pending_checkout_info',
                    'checkout_issues'
                ]);
            }
        }
        
        // Kiểm tra và xóa buy_again_items hết hạn (nếu không có order_id)
        $buyAgainItems = session('buy_again_items');
        $buyAgainOrderId = session('buy_again_order_id');
        if ($buyAgainItems && !$buyAgainOrderId) {
            // Nếu chỉ có buy_again_items mà không có order_id, có thể là session cũ
            // Xóa sau 1 giờ để tránh xóa nhầm
            $checkoutInfo = session('pending_checkout_info');
            if (!$checkoutInfo || (now()->timestamp - $checkoutInfo['timestamp'] > 3600)) {
                session()->forget([
                    'buy_again_items',
                    'buy_again_order_id'
                ]);
            }
        }
        
        // Xóa selected_items nếu không có pending_checkout_info
        if (session('selected_items') && !session('pending_checkout_info')) {
            session()->forget('selected_items');
        }
    }
}
