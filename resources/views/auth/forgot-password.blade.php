@extends('layouts.app_client')

@section('content')
<div class="main-content">
    <div class="wrapper fade-in" style="max-width: 600px; margin: 0 auto;">
        <div style="background: white; border-radius: var(--border-radius); box-shadow: var(--box-shadow); padding: 2rem;">
            <h2 style="color: var(--dark-color); margin-bottom: 1.5rem; text-align: center;">{{ __('Quên mật khẩu') }}</h2>

            @if (session('status'))
                <div class="notification" style="margin-bottom: 1.5rem;">
                    <div class="notification-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-message">{{ session('status') }}</div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div style="margin-bottom: 1.5rem;">
                    <label for="email" style="display: block; margin-bottom: 0.5rem; color: var(--dark-color);">{{ __('Địa chỉ Email') }}</label>
                    <input id="email" type="email" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: var(--border-radius); transition: var(--transition);" 
                           class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span style="display: block; margin-top: 0.5rem; color: var(--primary-color); font-size: 0.875rem;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div style="text-align: center;">
                    <button type="submit" style="background: var(--primary-color); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: var(--border-radius); cursor: pointer; transition: var(--transition);">
                        {{ __('Gửi liên kết đặt lại mật khẩu') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection