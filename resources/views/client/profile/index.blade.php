
@extends('layouts.app_client')
@section('content')
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item active">Tài khoản</li>
        </ol>
    </nav>

    <div class="row content">
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="user-profile-card">
                <div class="d-flex align-items-center mb-3">
                    <div class="user-avatar me-3"><i class="fas fa-user"></i></div>
                    <div>
                        <h6 class="mb-1 fw-bold">{{ Auth::user()->name }}</h6>
                        <small class="text-muted">{{ Auth::user()->phone }}</small>
                    </div>
                </div>
                <a href="{{ route('profile.index') }}" class="text-primary small text-decoration-none">Xem hồ sơ</a>
            </div>

            <div class="promo-card d-flex">
                <div class="flex-grow-1 me-3">
                    <p class="small fw-bold mb-2">Quý khách là thành viên tại TLO Fashion</p>
                    <p class="small text-muted mb-3">Quan tâm TLO Shop để kích hoạt điểm thưởng</p>
                </div>
                <div class="promo-image"><i class="fas fa-gift"></i></div>
            </div>

            <div class="sidebar">
                <ul class="nav-menu">
                    <li><a href="{{ route('orders.index') }}"><i class="fas fa-box"></i> Đơn hàng của tôi</a></li>
                    <li><a href="{{ route('wishlist.index') }}"><i class="fas fa-heart"></i> Sản phẩm yêu thích</a></li>
                    <li><a href="{{ route('addresses.index') }}"><i class="fas fa-map-marker-alt"></i> Sổ địa chỉ</a></li>
                    <li><a href="{{ route('discounts.my-discounts') }}"><i class="fas fa-wallet"></i> Mã của tôi</a></li>
                    <li><a href="{{ route('logout') }}" class="text-danger"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                </ul>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="main-content p-4">
                <h5 class="fw-bold mb-4">Thông tin cá nhân</h5>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="profile-avatar-large">
                                <div class="profile-avatar-inner"><i class="fas fa-user"></i></div>
                            </div>
                            <h6 class="fw-bold mb-1">{{ Auth::user()->name }}</h6>
                            <p class="text-muted mb-0">{{ Auth::user()->phone }}</p>
                        </div>

                        <div class="col-md-8">
                            <div class="row mb-3">
                                <label for="name" class="col-sm-3 form-label-custom">Họ và tên</label>
                                <div class="col-sm-9">
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        class="form-control"
                                        value="{{ old('name', Auth::user()->name) }}"
                                        readonly
                                    />
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-sm-3 form-label-custom">Số điện thoại</label>
                                <div class="col-sm-9">
                                    <input
                                        type="number"
                                        id="phone"
                                        name="phone"
                                        class="form-control"
                                        value="{{ old('phone', Auth::user()->phone) }}"
                                        readonly
                                    />
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label for="gender" class="col-sm-3 form-label-custom">Giới tính</label>
                                <div class="col-sm-9">
                                    <select
                                        id="gender"
                                        name="gender"
                                        class="form-select"
                                        disabled
                                    >
                                        <option value="">Chọn giới tính</option>
                                        <option value="M" {{ old('gender', Auth::user()->gender) == 'M' ? 'selected' : '' }}>Nam</option>
                                        <option value="F" {{ old('gender', Auth::user()->gender) == 'F' ? 'selected' : '' }}>Nữ</option>
                                        <option value="O" {{ old('gender', Auth::user()->gender) == 'O' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('gender')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                 
                            <div class="row">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="button" class="btn btn-danger btn-edit">Chỉnh sửa thông tin</button>
                                    <button type="submit" class="btn btn-success d-none btn-save">Lưu thông tin</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('scripts')

<style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .content {
            border-radius: 10px;
            padding: 10px 0;
        }
        .sidebar, .main-content {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .user-profile-card, .promo-card {
            background-color: #fff5f5;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .user-avatar, .profile-avatar-inner {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
        }
        .user-avatar {
            width: 50px;
            height: 50px;
            background-color: #ff6b35;
            font-size: 20px;
        }
        .profile-avatar-large {
            width: 120px;
            height: 120px;
            background-color: #ffdbcc;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px;
        }
        .profile-avatar-inner {
            width: 60px;
            height: 60px;
            background-color: #ff6b35;
            font-size: 24px;
        }
        .btn-edit, .btn-promo {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-edit {
            padding: 10px 30px;
            font-weight: 500;
        }
        .btn-promo {
            font-size: 12px;
            padding: 6px 12px;
        }
        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .nav-menu li {
            border-bottom: 1px solid #f0f0f0;
        }
        .nav-menu li:last-child {
            border-bottom: none;
        }
        .nav-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            text-decoration: none;
            color: #333;
            transition: 0.3s;
        }
        .nav-menu a:hover {
            background-color: #f8f9fa;
            border-left: 3px solid #ff6b35;
        }
        .nav-menu i {
            margin-right: 12px;
            width: 20px;
            color: #666;
        }
        .breadcrumb-item a {
            color: #007bff;
            text-decoration: none;
        }
        .form-label-custom {
            color: #666;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .promo-image {
            width: 50px;
            height: 60px;
            background: linear-gradient(45deg, #ff6b35, #ffa500);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
    </style>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(successAlert);
                bsAlert.close();
            }, 3000);
        }
        const errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(errorAlert);
                bsAlert.close();
            }, 3000);
        }
    });
</script>
<script>
    const btnEdit = document.querySelector('.btn-edit');
    const btnSave = document.querySelector('.btn-save');
    const inputs = document.querySelectorAll('form input[readonly], form select[disabled]');

    btnEdit.addEventListener('click', function () {
        inputs.forEach(el => {
            el.removeAttribute('readonly');
            el.removeAttribute('disabled');
        });
        btnEdit.classList.add('d-none');
        btnSave.classList.remove('d-none');
    });

    @if ($errors->any())
        inputs.forEach(el => {
            el.removeAttribute('readonly');
            el.removeAttribute('disabled');
        });
        btnEdit.classList.add('d-none');
        btnSave.classList.remove('d-none');
    @endif

   

</script>
@endsection
