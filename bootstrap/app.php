<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ClearBuyAgainSession;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\CleanupCheckoutSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/web.php',     // Admin
            __DIR__ . '/../routes/client.php',  // Client
            __DIR__ . '/../routes/auth.php',    // Auth
        ],
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'email/verify/*'
        ]);
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        ]);
        
        // Thêm middleware global để xóa session buy_again_items
        $middleware->append(ClearBuyAgainSession::class);
        
        // Thêm middleware global để set locale tiếng Việt
        $middleware->append(SetLocale::class);
        
        // Thêm middleware global để dọn dẹp session checkout
        $middleware->append(CleanupCheckoutSession::class);
    })
    ->withProviders([
        \App\Providers\EmailVerificationServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
