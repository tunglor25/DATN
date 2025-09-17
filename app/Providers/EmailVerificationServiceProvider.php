<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class EmailVerificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /* 1) Thay đổi thời gian hết hạn link (mặc định 60' → 24 h) */
        Config::set('auth.verification.expire', 60 * 24);

        /* 2) Tùy biến cách Laravel tạo link xác minh (nếu bạn _không_ tự sinh link trong notification) */
        // use Illuminate\Auth\Notifications\VerifyEmail;
        // VerifyEmail::createUrlUsing(function ($notifiable) {
        //     return URL::temporarySignedRoute(
        //         'verification.verify',
        //         now()->addMinutes(Config::get('auth.verification.expire', 60)),
        //         ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        //     );
        // });

        /* 3) Giới hạn resend (3 email / phút / user) */
        RateLimiter::for('resend-verification', function (Request $request) {
            return Limit::perMinute(3)->by(
                optional($request->user())->id ?? $request->ip()
            );
        });
    }
}
