<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\CustomResetPassword;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegisterForm(Request $request)
    {
        // Redirect về trang chủ và truyền session để mở modal đăng ký
        return redirect('/')->with('showRegisterModal', true);
    }

    public function register(Request $request)
    {
        $isAjax = $request->expectsJson() || $request->ajax();

        $validator = Validator::make($request->all(), [
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:6', 'confirmed'],
            'phone'     => ['required', 'regex:/^0[0-9]{9,10}$/', 'unique:users'],
            'gender'    => ['nullable', Rule::in(['M', 'F', 'O'])],
            'agreeTerms' => ['accepted'],
        ], [
            'agreeTerms.accepted' => 'Bạn phải đồng ý với điều khoản sử dụng',
            'email.unique' => 'Email này đã được đăng ký',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'phone.unique' => 'Số điện thoại này đã được đăng ký'
        ]);

        if ($validator->fails()) {
            if ($isAjax) {
                return response()->json([
                    'message' => 'Registration failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect('/')->with('error', 'Đăng ký không thành công!');
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'phone'     => $request->phone,
            'gender'    => $request->gender,
            'role'      => 'user',
            'status'    => 'inactive', // Change to inactive until email is verified
        ]);

        event(new Registered($user));

        if ($isAjax) {
            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.'
            ]);
        }

        return redirect('/')->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.');
    }

    public function showLoginForm(Request $request)
    {
        // Redirect về trang chủ và truyền session để mở modal đăng nhập
        return redirect('/')->with('showLoginModal', true);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->filled('remember');

        // First find the user to check their status
        $user = User::where('email', $credentials['email'])->first();

        // Check if user exists and is banned
        if ($user && $user->status === 'banned') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'
                ], 403);
            }
            return redirect('/')->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.');
        }

        // Check if email is verified
        if ($user && !$user->hasVerifiedEmail()) {
            // Store email in session for resend verification
            session(['email' => $user->email]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Vui lòng xác thực email trước khi đăng nhập.',
                    'redirect' => route('verification.email-not-received')
                ], 403);
            }
            return redirect()->route('verification.email-not-received')->with('error', 'Vui lòng xác thực email để đăng nhập');
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Kiểm tra role admin
            if ($user && $user->role === 'admin') {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => true, 'redirect' => route('admin.dashboard')]);
                }
                return redirect()->route('admin.dashboard');
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'redirect' => url('/')]);
            }
            return redirect()->intended('/');
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không đúng.'
            ], 401);
        }

        return redirect('/')->with('error', 'Email hoặc mật khẩu không đúng.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Add this method to check account status
    public function checkAccountStatus(Request $request)
    {
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Not authenticated'], 401);
            }
            return redirect('/login');
        }

        $user = Auth::user();

        if ($user->status === 'banned') {
            Auth::logout();
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'banned' => true,
                    'message' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'
                ], 403);
            }
            return redirect('/')->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.');
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'banned' => false,
                'status' => $user->status
            ]);
        }

        return null;
    }

        public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Send password reset link
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                $user->notify(new CustomResetPassword($token));
            }
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // Show reset password form
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // Handle password reset
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('home')->with('success', 'Thay đổi mật khẩu thành công!')
            : back()->withErrors(['email' => [__($status)]]);
    }

    // Google Socialite Login
    public function redirectToGoogle()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
                
                if ($user->status === 'banned') {
                    return redirect('/')->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.');
                }
                
                Auth::login($user);
            } else {
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => null,
                    'role' => 'user',
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);
                Auth::login($newUser);
            }

            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Lỗi đăng nhập bằng Google: ' . $e->getMessage());
        }
    }
}
