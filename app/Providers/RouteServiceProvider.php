<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Đường URL mặc định sau khi đăng nhập
     * (đặt theo nhu cầu của bạn, ví dụ /dashboard).
     */
    public const HOME = '/dashboard';

    /**
     * Đây là phương thức boot chính để định nghĩa tuyến (routes).
     */
    public function boot(): void
    {
        // Gọi phương thức routes() để gom nhóm route theo middleware
        $this->routes(function () {
            /*
             |-----------------------------------------------------------------
             |  WEB routes – session, CSRF, cookies…
             |-----------------------------------------------------------------
             */
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            /*
             |-----------------------------------------------------------------
             |  API routes – stateless, prefix /api
             |-----------------------------------------------------------------
             */
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            /*
             |-----------------------------------------------------------------
             |  (Tùy chọn) Bạn có thể thêm nhóm route riêng
             |  cho admin, seller… nếu muốn:
             |-----------------------------------------------------------------
             |
             | Route::prefix('admin')
             |     ->middleware(['web', 'auth', 'role:admin'])
             |     ->group(base_path('routes/admin.php'));
             |
             */
        });
    }
}
