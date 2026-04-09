<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

// Đăng ký, đăng nhập
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::get('register', function () { return redirect('/'); });
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Xác thực email
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Liên kết xác thực mới đã được gửi!');
    })->middleware('throttle:6,1')->name('verification.send');
});

Route::get('/email/verify/{id}/{hash}', function ($id, $hash, Request $request) {
    // Kiểm tra signature trước
    if (!$request->hasValidSignature()) {
        abort(403, 'Liên kết không hợp lệ hoặc đã hết hạn');
    }

    $user = User::findOrFail($id);

    if (!hash_equals($hash, sha1($user->email))) {
        abort(403, 'Xác thực không hợp lệ');
    }

    if ($user->hasVerifiedEmail()) {
        return redirect('/')->with('message', 'Email đã được xác thực');
    }

    $user->forceFill([
        'email_verified_at' => now(),
        'status' => 'active'
    ])->save();

    // Đăng nhập tự động nếu cần
    Auth::login($user);

    return redirect('/')->with('verified', true);
})->name('verification.verify')->middleware('signed');

Route::get('/email-not-received', function () {
    return view('auth.email-not-received');
})->name('verification.email-not-received');

Route::post('/resend-verification-unauthed', function (Request $request) {
    $email = session('email');

    if (!$email) {
        return redirect()->back()->with('error', 'Không tìm thấy email cần xác thực. Vui lòng đăng ký lại.');
    }

    $user = User::where('email', $email)->first();

    if (!$user) {
        return redirect()->back()->with('error', 'Email không tồn tại trong hệ thống.');
    }

    if ($user->hasVerifiedEmail()) {
        return redirect('/login')->with('message', 'Email đã được xác thực, bạn có thể đăng nhập.');
    }

    event(new Registered($user)); // gửi lại mail xác thực
    return back()->with('resent', true);
})->name('verification.send');

// Password reset routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
    ->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'reset'])
    ->name('password.update');