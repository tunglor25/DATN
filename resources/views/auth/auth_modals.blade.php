<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    .modal-content {
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        border: 1px solid #e0e0e0;
        background-color: white;
        /* --color: #E1E1E1;
        background-color: #F3F3F3;
        background-image: linear-gradient(0deg, transparent 24%, var(--color) 25%, var(--color) 26%, transparent 27%, transparent 74%, var(--color) 75%, var(--color) 76%, transparent 77%, transparent),
            linear-gradient(90deg, transparent 24%, var(--color) 25%, var(--color) 26%, transparent 27%, transparent 74%, var(--color) 75%, var(--color) 76%, transparent 77%, transparent);
        background-size: 55px 55px; */
    }

    .modal-header {
        border-bottom: none;
        justify-content: center;
    }

    .modal-title {
        font-weight: 700;
        color: var(--primary-color);
        width: 100%;
        text-align: center;
        letter-spacing: 0.5px;
    }

    .divider {
        text-align: center;
        margin: 1.5rem 0 1rem;
        position: relative;
    }

    .divider span {
        background: #fff;
        padding: 0 1rem;
        color: #888;
        font-size: 0.95rem;
        position: relative;
        z-index: 1;
    }

    .divider:before {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
        height: 1px;
        background: #e0e0e0;
        z-index: 0;
    }

    .social-login {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .btn-social {
        border: none;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.475rem 1.2rem;
        /* color: #fff; */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transition: background 0.2s, transform 0.15s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
    }

    .btn-google {
        background: #ea4335;
    }

    .btn-google:hover,
    .btn-google:focus {
        background: #c6311d;
        transform: translateY(-2px) scale(1.04);
    }

    .btn-facebook {
        background: #1877f3;
    }

    .btn-facebook:hover,
    .btn-facebook:focus {
        background: #0e5ab6;
        transform: translateY(-2px) scale(1.04);
    }

    .btn-login,
    .btn-register {
        width: 100%;
        border-radius: 20px;
        background: var(--primary-color);
        color: #fff;
        font-weight: 600;
        margin-top: 1rem;
        box-shadow: none;
        transition: background var(--transition), box-shadow var(--transition);
        border: none;
    }

    .btn-login:hover,
    .btn-register:hover,
    .btn-login:focus,
    .btn-register:focus {
        background: var(--primary-hover);
        color: #fff;
        box-shadow: 0 4px 22px 0 rgba(171, 75, 56, 0.12);
    }

    .register-link,
    .login-link {
        margin-top: 1.25rem;
        text-align: center;
        font-size: 0.97rem;
        color: #444;
    }

    .inputForm {
        display: flex;
        align-items: center;
        border-radius: 14px;
        border: 1px solid #e0e0e0;
        background: #f8f9fa;
        padding: 0.3rem 0.8rem;
        margin-bottom: 1rem;
        transition: border-color var(--transition), box-shadow var(--transition);
    }

    .inputForm:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 2px 10px 0 rgba(171, 75, 56, 0.08);
    }

    .inputForm svg {
        margin-right: 0.5rem;
        fill: var(--primary-color);
    }

    .inputForm .input {
        border: none;
        background: transparent;
        outline: none;
        color: #333;
        width: 100%;
        font-size: 1rem;
        padding: 0.45rem 0;
    }

    .inputForm .input::placeholder {
        color: #aaa;
    }

    .input-group {
        border-radius: 14px;
        overflow: hidden;
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        margin-bottom: 1rem;
    }

    .input-group:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 2px 8px 0 rgba(171, 75, 56, 0.09);
    }

    .input-group .form-control,
    .input-group-text {
        background: transparent;
        border: none;
        outline: none;
        box-shadow: none;
        font-size: 1rem;
    }

    .input-group .form-control {
        border-radius: 0;
        padding: 0.7rem 1.1rem;
    }

    .input-group-text {
        background: #f1ecec;
        color: var(--primary-color);
        cursor: pointer;
        transition: background var(--transition), color var(--transition);
    }

    .input-group-text:hover {
        background: var(--primary-color);
        color: #fff;
    }

    .password-toggle i {
        color: #888;
        font-size: 1.1em;
        transition: color var(--transition);
    }

    .password-toggle:hover i {
        color: var(--primary-color);
    }

    .form-check-label,
    .form-label {
        color: #555;
        font-weight: 500;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    @media (max-width: 767.98px) {
        .modal-dialog {
            margin: 1rem;
        }
    }

    /* Center modal body content */
    .modal-body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .modal-body form {
        width: 100%;
    }

    /* Newton loader styles (for forgot password) */
    .newtons-cradle {
        --uib-size: 50px;
        --uib-speed: 1.2s;
        --uib-color: #ff5252;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        align-items: center;
        justify-content: center;
        width: var(--uib-size);
        height: var(--uib-size);
        z-index: 10;
    }

    .newtons-cradle__dot {
        position: relative;
        display: flex;
        align-items: center;
        height: 100%;
        width: 25%;
        transform-origin: center top;
    }

    .newtons-cradle__dot::after {
        content: '';
        display: block;
        width: 100%;
        height: 25%;
        border-radius: 50%;
        background-color: var(--uib-color);
    }

    .newtons-cradle__dot:first-child {
        animation: swing var(--uib-speed) linear infinite;
    }

    .newtons-cradle__dot:last-child {
        animation: swing2 var(--uib-speed) linear infinite;
    }

    @keyframes swing {
        0% {
            transform: rotate(0deg);
            animation-timing-function: ease-out;
        }

        25% {
            transform: rotate(70deg);
            animation-timing-function: ease-in;
        }

        50% {
            transform: rotate(0deg);
            animation-timing-function: linear;
        }
    }

    @keyframes swing2 {
        0% {
            transform: rotate(0deg);
            animation-timing-function: linear;
        }

        50% {
            transform: rotate(0deg);
            animation-timing-function: ease-out;
        }

        75% {
            transform: rotate(-70deg);
            animation-timing-function: ease-in;
        }
    }
</style>

<!-- Modal Đăng Nhập -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h5 class="modal-title">Chào mừng trở lại!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-10">
                <div id="loginErrors"></div>
                <form id="loginForm" action="{{ route('login') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ email</label>
                        <div class="input-group">
                            <input type="email" class="form-control" name="email" placeholder="email@example.com"
                                required>
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" placeholder="Nhập mật khẩu"
                                required>
                            <span class="input-group-text password-toggle"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>
                    <div class="form-check d-flex mb-3 justify-content-between align-items-center">
                        <div>
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                        </div>
                        <a href="#" class="text-decoration-none" id="forgotPasswordLink">Quên mật khẩu?</a>
                    </div>
                    <button type="submit" class="btn btn-login text-white">Đăng nhập</button>
                    <div class="divider"><span>hoặc đăng nhập bằng</span></div>
                    <div class="social-login">
                        <button type="button" class="btn btn-social">
                            <svg xml:space="preserve" style="enable-background:new 0 0 512 512;" viewBox="0 0 512 512"
                                y="0px" x="0px" xmlns:xlink="http://www.w3.org/1999/xlink"
                                xmlns="http://www.w3.org/2000/svg" id="Layer_1" width="20" version="1.1">
                                <path d="M113.47,309.408L95.648,375.94l-65.139,1.378C11.042,341.211,0,299.9,0,256
 c0-42.451,10.324-82.483,28.624-117.732h0.014l57.992,10.632l25.404,57.644c-5.317,15.501-8.215,32.141-8.215,49.456
 C103.821,274.792,107.225,292.797,113.47,309.408z" style="fill:#FBBB00;"></path>
                                <path d="M507.527,208.176C510.467,223.662,512,239.655,512,256c0,18.328-1.927,36.206-5.598,53.451
 c-12.462,58.683-45.025,109.925-90.134,146.187l-0.014-0.014l-73.044-3.727l-10.338-64.535
 c29.932-17.554,53.324-45.025,65.646-77.911h-136.89V208.176h138.887L507.527,208.176L507.527,208.176z"
                                    style="fill:#518EF8;"></path>
                                <path d="M416.253,455.624l0.014,0.014C372.396,490.901,316.666,512,256,512
 c-97.491,0-182.252-54.491-225.491-134.681l82.961-67.91c21.619,57.698,77.278,98.771,142.53,98.771
 c28.047,0,54.323-7.582,76.87-20.818L416.253,455.624z" style="fill:#28B446;"></path>
                                <path d="M419.404,58.936l-82.933,67.896c-23.335-14.586-50.919-23.012-80.471-23.012
 c-66.729,0-123.429,42.957-143.965,102.724l-83.397-68.276h-0.014C71.23,56.123,157.06,0,256,0
 C318.115,0,375.068,22.126,419.404,58.936z" style="fill:#F14336;"></path>

                            </svg> Google
                        </button>
                        <button type="button" class="btn btn-social">
                            <i class="fab fa-facebook-f" style="color: #0e5ab6"></i> Facebook
                        </button>
                    </div>
                    <div class="register-link">
                        Chưa có tài khoản? <a href="#" class="text-decoration-none" id="showRegister">Đăng ký
                            ngay</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Đăng Ký -->
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h5 class="modal-title">Tạo tài khoản mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <div id="registerErrors"></div>
                <form id="registerForm" action="{{ route('register') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Họ và tên *</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="name" required>
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <div class="input-group">
                            <input type="email" class="form-control" name="email" required>
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mật khẩu *</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" required>
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Xác nhận mật khẩu *</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password_confirmation" required>
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại *</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" name="phone" required>
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giới tính</label>
                            <div class="input-group">
                                <select class="form-select" name="gender">
                                    <option value="">Chọn giới tính</option>
                                    <option value="M">Nam</option>
                                    <option value="F">Nữ</option>
                                    <option value="O">Khác</option>
                                </select>
                                <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="agreeTerms" name="agreeTerms">
                        <label class="form-check-label" for="agreeTerms">
                            Tôi đồng ý với <a href="#">điều khoản sử dụng</a>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-register text-white">Đăng ký</button>
                    <div class="login-link">
                        Đã có tài khoản? <a href="#" class="text-decoration-none" id="showLogin">Đăng nhập</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Quên mật khẩu -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h5 class="modal-title">Quên mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <div id="forgotPasswordErrors" class="alert alert-danger d-none"></div>
                <div id="forgotPasswordSuccess" class="alert alert-success d-none"></div>

                <form id="forgotPasswordForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ email</label>
                        <div class="input-group">
                            <input type="email" class="form-control" name="email" id="forgotPasswordEmail"
                                placeholder="email@example.com" required>
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                    </div>

                    <div class="position-relative" style="height: 50px;">
                        <button type="submit" class="btn btn-login text-black w-100" id="forgotPasswordSubmit">
                            <span class="submit-text">Gửi liên kết đặt lại</span>
                            <span class="spinner-border spinner-border-sm d-none"></span>
                        </button>

                        <div class="newtons-cradle d-none" id="newtonLoading">
                            <div class="newtons-cradle__dot"></div>
                            <div class="newtons-cradle__dot"></div>
                            <div class="newtons-cradle__dot"></div>
                            <div class="newtons-cradle__dot"></div>
                        </div>
                    </div>

                    <div class="register-link mt-3">
                        <a href="#" class="text-decoration-none" id="showLoginFromForgot">Quay lại đăng
                            nhập</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    // Toggle mật khẩu
    document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.closest('.input-group').querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // Improved modal switching function
    function switchModal(fromId, toId) {
        const fromModal = bootstrap.Modal.getInstance(document.getElementById(fromId));
        const toModalEl = document.getElementById(toId);

        // Hide the current modal
        fromModal.hide();

        // Remove any existing backdrops
        const existingBackdrops = document.querySelectorAll('.modal-backdrop');
        existingBackdrops.forEach(backdrop => backdrop.remove());

        // Remove modal-open class from body if no modals are shown
        document.body.classList.remove('modal-open');

        // Show the new modal after a small delay
        setTimeout(() => {
            // Create new modal instance if needed
            let toModal = bootstrap.Modal.getInstance(toModalEl);
            if (!toModal) {
                toModal = new bootstrap.Modal(toModalEl);
            }

            // Show the new modal
            toModal.show();
        }, 300);
    }

    // Xử lý sự kiện chuyển đổi modal
    document.getElementById('showRegister')?.addEventListener('click', function(e) {
        e.preventDefault();
        switchModal('loginModal', 'registerModal');
    });

    document.getElementById('showLogin')?.addEventListener('click', function(e) {
        e.preventDefault();
        switchModal('registerModal', 'loginModal');
    });

    document.getElementById('forgotPasswordLink')?.addEventListener('click', function(e) {
        e.preventDefault();
        switchModal('loginModal', 'forgotPasswordModal');
    });

    document.getElementById('showLoginFromForgot')?.addEventListener('click', function(e) {
        e.preventDefault();
        switchModal('forgotPasswordModal', 'loginModal');
    });

    // Clean up backdrops when modals are fully hidden
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            // Remove any lingering backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());

            // Remove modal-open class if no modals are shown
            if (document.querySelectorAll('.modal.show').length === 0) {
                document.body.classList.remove('modal-open');
                document.body.style.overflow = ""; // reset overflow
                document.body.style.paddingRight = ""; // reset padding
            }
        });
    });


    // AJAX Đăng ký
    document.getElementById('registerForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const errorContainer = document.getElementById('registerErrors');
        const originalBtnText = submitBtn.innerHTML;

        try {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang đăng ký...';
            submitBtn.disabled = true;
            errorContainer.innerHTML = '';

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();
            if (response.ok) {
                window.location.reload();
            } else {
                let errorHtml = '<div class="alert alert-danger"><ul class="mb-0">';
                if (data.errors) {
                    for (const key in data.errors) {
                        data.errors[key].forEach(error => {
                            errorHtml += `<li>${error}</li>`;
                        });
                    }
                } else if (data.message) {
                    errorHtml += `<li>${data.message}</li>`;
                } else {
                    errorHtml += '<li>Đăng ký thất bại. Vui lòng thử lại.</li>';
                }
                errorHtml += '</ul></div>';
                errorContainer.innerHTML = errorHtml;
            }
        } catch (err) {
            errorContainer.innerHTML =
                '<div class="alert alert-danger">Đã xảy ra lỗi. Vui lòng thử lại.</div>';
        } finally {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    });

    // Xử lý form quên mật khẩu
    window.addEventListener('DOMContentLoaded', () => {
        const submitBtn = document.getElementById('forgotPasswordSubmit');
        const LAST_SEND_KEY = 'lastForgotPasswordTime';
        const WAIT_TIME_MS = 5 * 60 * 1000; // 5 phút

        const now = Date.now();
        const lastSent = parseInt(localStorage.getItem(LAST_SEND_KEY), 10);

        if (!isNaN(lastSent) && now - lastSent < WAIT_TIME_MS) {
            const remainingTime = WAIT_TIME_MS - (now - lastSent);
            disableSubmitBtn(submitBtn, remainingTime);
        }
    });

    document.getElementById('forgotPasswordForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const email = document.getElementById('forgotPasswordEmail').value;
        const errorDiv = document.getElementById('forgotPasswordErrors');
        const successDiv = document.getElementById('forgotPasswordSuccess');
        const submitBtn = document.getElementById('forgotPasswordSubmit');
        const submitText = submitBtn.querySelector('.submit-text');
        const spinner = submitBtn.querySelector('.spinner-border');
        const newtonLoading = document.getElementById('newtonLoading');

        // Kiểm tra delay 5 phút
        const LAST_SEND_KEY = 'lastForgotPasswordTime';
        const WAIT_TIME_MS = 5 * 60 * 1000;
        const now = Date.now();
        const lastSent = parseInt(localStorage.getItem(LAST_SEND_KEY), 10);

        if (!isNaN(lastSent) && (now - lastSent < WAIT_TIME_MS)) {
            const secondsLeft = Math.ceil((WAIT_TIME_MS - (now - lastSent)) / 1000);
            errorDiv.classList.remove('d-none');
            errorDiv.textContent = `Vui lòng đợi ${secondsLeft} giây trước khi gửi lại yêu cầu.`;
            return;
        }

        // Reset trạng thái
        errorDiv.classList.add('d-none');
        successDiv.classList.add('d-none');
        submitBtn.classList.add('d-none');
        newtonLoading.classList.remove('d-none');

        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 45000);

            const response = await fetch('{{ route('password.email') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email
                }),
                signal: controller.signal
            });

            clearTimeout(timeoutId);
            const data = await response.json();

            if (response.ok) {
                successDiv.classList.remove('d-none');
                successDiv.textContent = 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn!';
                form.reset();
                localStorage.setItem(LAST_SEND_KEY, Date.now().toString());
                disableSubmitBtn(submitBtn, WAIT_TIME_MS);
            } else {
                errorDiv.classList.remove('d-none');
                errorDiv.textContent = data.message || data.errors?.email?.[0] ||
                    'Đã xảy ra lỗi khi gửi yêu cầu';
            }
        } catch (error) {
            successDiv.classList.remove('d-none');
            successDiv.textContent = 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn!';
            form.reset();
            localStorage.setItem(LAST_SEND_KEY, Date.now().toString());
            disableSubmitBtn(submitBtn, WAIT_TIME_MS);
        } finally {
            submitBtn.classList.remove('d-none');
            newtonLoading.classList.add('d-none');
        }
    });

    function disableSubmitBtn(button, duration) {
        button.disabled = true;
        const textEl = button.querySelector('.submit-text');
        const originalText = textEl.textContent;
        let remaining = Math.floor(duration / 1000);

        const interval = setInterval(() => {
            if (remaining <= 0) {
                clearInterval(interval);
                button.disabled = false;
                textEl.textContent = originalText;
                return;
            }

            textEl.textContent = `Vui lòng đợi ${remaining--}s...`;
        }, 1000);
    }
</script>
