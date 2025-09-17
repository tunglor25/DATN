<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // sử dụng auth để check đã đăng nhập hay chưa
        if (!Auth::check()) {
            return redirect()->back()->with('needLogin', true);
        }

        // check phân quyền của admin
        if (Auth::user()->role !== 'admin') {
            // trả về trang 403
            return response()->view('errors.403', [], 403);
        }
        return $next($request);
    }
}
