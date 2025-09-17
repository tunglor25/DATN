@extends('layouts.app_client')

@section('content')
<div class="main-content">
    <div class="wrapper fade-in" style="max-width: 600px; margin: 0 auto;">
        <div style="background: white; border-radius: var(--border-radius); box-shadow: var(--box-shadow); padding: 2rem;">
            <h2 style="color: var(--dark-color); margin-bottom: 1.5rem; text-align: center;">{{ __('Đặt lại mật khẩu') }}</h2>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div style="margin-bottom: 1.5rem;">
                    <label for="email" style="display: block; margin-bottom: 0.5rem; color: var(--dark-color);">{{ __('Địa chỉ Email') }}</label>
                    <input id="email" type="email" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: var(--border-radius); transition: var(--transition);" 
                           class="@error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span style="display: block; margin-top: 0.5rem; color: var(--primary-color); font-size: 0.875rem;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="password" style="display: block; margin-bottom: 0.5rem; color: var(--dark-color);">{{ __('Mật khẩu mới') }}</label>
                    <input id="password" type="password" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: var(--border-radius); transition: var(--transition);" 
                           class="@error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                        <span style="display: block; margin-top: 0.5rem; color: var(--primary-color); font-size: 0.875rem;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="password-confirm" style="display: block; margin-bottom: 0.5rem; color: var(--dark-color);">{{ __('Xác nhận mật khẩu') }}</label>
                    <input id="password-confirm" type="password" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: var(--border-radius); transition: var(--transition);" 
                           name="password_confirmation" required autocomplete="new-password">
                </div>

                <div style="text-align: center;">
                    <button type="submit" style="background: var(--primary-color); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--border-radius); cursor: pointer; transition: var(--transition);">
                        {{ __('Đặt lại mật khẩu') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection