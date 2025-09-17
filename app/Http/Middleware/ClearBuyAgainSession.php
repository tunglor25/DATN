<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClearBuyAgainSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Xóa session buy_again_items khi truy cập các trang khác ngoài checkout
        if (!$request->is('checkout*') && !$request->is('vnpay*')) {
            session()->forget(['buy_again_items', 'buy_again_order_id']);
        }

        return $next($request);
    }
} 