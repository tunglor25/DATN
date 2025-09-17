@extends('layouts.app_client')

@section('styles')
<style>
/* Root & theme variables */
:root {
    --primary-color: #ff6b6b;
    --primary-hover: #ff5252;
    --dark-color: #111;
    --light-color: #f8f9fa;
    --border-radius: 14px;
            --box-shadow-soft: 0 4px 24px 0 rgba(17, 17, 17, 0.08), 0 1.5px 6px 0 rgba(255, 107, 107, 0.04);
            --box-shadow-deep: 0 16px 48px 0 rgba(17, 17, 17, 0.22), 0 2px 10px 0 rgba(255, 107, 107, 0.10), 0 1px 3px rgba(0, 0, 0, 0.12);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --text-main: #23272f;
    --text-soft: #555a68;
    --icon-bg: #fbeaea;
    --success-bg: #e8faf0;
    --success-color: #2fa97c;
}

/* Body: align center, soft bg */
body {
    background: linear-gradient(135deg, var(--light-color) 70%, #ffe7e7 100%);
    min-height: 100vh;
    color: var(--text-main);
}



.card {
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow-deep);
    border: none;
    background: #fff;
    transition: var(--transition);
    min-width: 320px;
    padding: 0;
    overflow: hidden;
    position: relative;
}

.card-header {
    background: var(--dark-color) !important;
    color: #fff !important;
    border-top-left-radius: var(--border-radius);
    border-top-right-radius: var(--border-radius);
    padding: 1.25rem 2rem 1.25rem 2rem;
    font-size: 1.35rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    border-bottom: none;
    display: flex;
    align-items: center;
    gap: 1rem;
            box-shadow: 0 2px 14px 0 rgba(17, 17, 17, 0.04);
}

.card-header .icon-header {
    background: var(--icon-bg);
    color: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    font-size: 1.7rem;
    box-shadow: 0 2px 8px rgba(255, 107, 107, 0.08);
}

.card-body {
    padding: 2.2rem 2.2rem 2rem 2.2rem;
}

.alert-success {
    background: var(--success-bg);
    color: var(--success-color);
    border: none;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    gap: 0.8rem;
    box-shadow: 0 2px 6px rgba(47, 169, 124, 0.08);
    font-size: 1.04rem;
    margin-bottom: 1.5rem;
    padding: 1rem 1.2rem;
}

.alert-success i {
    font-size: 1.3rem;
    vertical-align: middle;
}

.notice-box {
    display: flex;
    align-items: flex-start;
    gap: 0.8rem;
    background: #f6fafe;
    border-left: 4px solid var(--primary-color);
    border-radius: calc(var(--border-radius) - 4px);
    padding: 1rem 1.2rem;
    margin-bottom: 2rem;
    font-size: 1.08rem;
    color: var(--text-soft);
}

.notice-box i {
    color: #3082f7;
    font-size: 1.3rem;
    margin-top: 2.5px;
}

.btn-link {
    font-weight: 600;
    color: var(--primary-color) !important;
    text-decoration: none !important;
    padding: 0;
    font-size: 1.02rem;
    margin-left: 2px;
    margin-right: 2px;
    box-shadow: none;
    transition: color 0.18s;
}

        .btn-link:hover,
        .btn-link:focus {
    color: var(--primary-hover) !important;
    text-decoration: underline !important;
}

.btn-dark {
    background: var(--dark-color) !important;
    border: none;
    color: #fff;
    border-radius: 0.7rem;
    font-weight: 600;
    font-size: 1.04rem;
    min-width: 140px;
    min-height: 43px;
    margin-top: 0.2rem;
            box-shadow: 0 2px 10px rgba(17, 17, 17, 0.08);
    transition: background 0.18s, transform 0.14s;
    display: inline-flex;
    align-items: center;
    gap: 0.6em;
}

        .btn-dark:hover,
        .btn-dark:focus {
    background: #23272f !important;
    color: #fff !important;
    transform: translateY(-1.5px) scale(1.02);
}

.mt-4 {
    margin-top: 2.1rem !important;
}

/* Animate card */
@media (hover: hover) {

            .card:hover,
            .card:focus-within {
    box-shadow: 0 28px 72px 0 rgba(17, 17, 17, 0.26), 
                0 4px 16px 0 rgba(255, 107, 107, 0.13),
                    0 1px 3px rgba(0, 0, 0, 0.13);
    transform: translateY(-4px) scale(1.012);
}
}

/* Responsive */
@media (max-width: 768px) {
    .main-content {
        padding: 1.1rem 0.2rem;
        max-width: 100vw;
    }

            .card-body,
            .card-header {
        padding-left: 1.1rem;
        padding-right: 1.1rem;
    }

    .card {
        min-width: 0;
    }
}

/* Extra: focus styles for accessibility */
        .btn-link:focus,
        .btn-dark:focus {
    outline: 2px dashed var(--primary-color);
    outline-offset: 2px;
}
</style>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <span class="icon-header">
                        <i class="fas fa-envelope-open-text"></i>
                    </span>
                    Xác thực Email của bạn
                </div>
                <div class="card-body">

                    @if (session('resent'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            Email xác thực đã gửi đến: 
                            <strong>{{ auth()->user()->email ?? session('email') }}</strong>
                        </div>
                    @endif

                    <div class="notice-box">
                        <i class="fas fa-info-circle"></i>
                        <span>
                            Vui lòng kiểm tra email 
                            <strong>{{ auth()->user()->email ?? session('email') }}</strong> để hoàn tất quá trình xác thực
                            tài khoản.
                            Nếu bạn không nhận được email, hãy nhấn vào nút bên dưới để gửi lại.
                        </span>
                    </div>

                    <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                        @csrf
                        <button type="submit" id="resendVerificationBtn" class="btn btn-link" onclick="disableButton()">
                            <i class="fas fa-paper-plane me-1"></i>
                            <span id="buttonText">Gửi lại email xác thực</span>
                        </button>
                    </form>
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-dark">
                            <i class="fas fa-arrow-left"></i>
                            <span>Quay về trang chủ</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
        document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra nếu có thời gian đếm ngược trong localStorage
    let countdown = localStorage.getItem('verificationCountdown');
    let countdownInterval;

    if (countdown) {
        startCountdown(countdown);
    }

            // Thêm event listener cho form submit
            const form = document.querySelector('form[action="{{ route('verification.send') }}"]');
            form.addEventListener('submit', function(e) {
                const button = document.getElementById('resendVerificationBtn');
                if (button.disabled) {
                    e.preventDefault();
                    return false;
                }

                disableButton();
                return true;
            });

    function disableButton() {
        const button = document.getElementById('resendVerificationBtn');
        const buttonText = document.getElementById('buttonText');

        // Vô hiệu hóa nút
        button.disabled = true;

                // Đặt thời gian chờ (5 phút = 300 giây)
        let seconds = 300;

        // Lưu thời gian kết thúc vào localStorage
        const endTime = new Date().getTime() + seconds * 1000;
        localStorage.setItem('verificationCountdown', endTime);

        // Bắt đầu đếm ngược
        startCountdown(endTime);
    }

    function startCountdown(endTime) {
        const button = document.getElementById('resendVerificationBtn');
        const buttonText = document.getElementById('buttonText');

        button.disabled = true;

        countdownInterval = setInterval(function() {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance <= 0) {
                clearInterval(countdownInterval);
                localStorage.removeItem('verificationCountdown');
                button.disabled = false;
                buttonText.textContent = 'Gửi lại email xác thực';
                return;
            }

            // Tính phút và giây còn lại
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Hiển thị thời gian còn lại
                    buttonText.textContent =
                    `Thử lại sau ${minutes}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }
        });
</script>
@endsection
