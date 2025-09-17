@extends('layouts.app_client')

@section('title', 'Sổ địa chỉ')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
        <li class="breadcrumb-item active">Sổ địa chỉ</li>
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
                <li><a href="{{ route('addresses.index') }}" class="active"><i class="fas fa-map-marker-alt"></i> Sổ địa chỉ</a></li>
                <li><a href="{{ route('discounts.my-discounts') }}"><i class="fas fa-wallet"></i> Mã của tôi</a></li>
                <li><a href="{{ route('logout') }}" class="text-danger"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
            </ul>
        </div>
    </div>

    <div class="col-lg-9 col-md-8">
        <div class="main-content p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0" style="color: #3d3d3d;">Địa chỉ của tôi</h4>
                <a href="{{ route('addresses.create') }}" class="btn_success">
                    <i class="fas fa-plus me-2"></i>Thêm địa chỉ mới
                </a>
            </div>
            @if($addresses->count() > 0)
                <div class="address-list">
                    @foreach($addresses as $address)
                        <div class="address-item mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <h6 class="fw-bold mb-0 me-3 text-color">{{ $address->receiver_name }}</h6>
                                                @if($address->is_default)
                                                    <span class="badge-bg-success">Mặc định</span>
                                                @endif
                                            </div>
                                            
                                            <div class="address-details">
                                                <p class="mb-1">
                                                    <i class="fas fa-phone me-2 text-muted"></i>
                                                    {{ $address->receiver_phone }}
                                                </p>
                                                <p class="mb-0">
                                                    <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                                    {{ $address->street_address }}, {{ $address->ward_name }}, {{ $address->province_name }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="address-actions">
                                            <div class="btn-group" role="group">
                                                @if(!$address->is_default)
                                                    <form action="{{ route('addresses.set-default', $address) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn_info btn" title="Đặt làm mặc định">
                                                            <i class="fas fa-star"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <a href="{{ route('addresses.edit', $address) }}" class="btn_warning btn" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                @if(!$address->isUsedInOrders())
                                                    <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline delete-address-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn_danger btn" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted mb-2">Chưa có địa chỉ nào</h6>
                        <p class="text-muted mb-4">Thêm địa chỉ giao hàng để thuận tiện khi đặt hàng</p>
                        <a href="{{ route('addresses.create') }}" class="btn btn-danger">
                            <i class="fas fa-plus me-2"></i>Thêm địa chỉ đầu tiên
                        </a>
                    </div>
                </div>
            @endif
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
    .user-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        width: 50px;
        height: 50px;
        background-color: #ff6b35;
        font-size: 20px;
    }
    .btn-edit, .btn-promo {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .btn-promo {
        font-size: 12px;
        padding: 6px 12px;
    }
    .btn_success {
        background: linear-gradient(135deg, #00d4aa 0%, #00b894 100%);
        border: none;
        color: #ffffff;
        font-weight: 600;
        border-radius: 0;
        padding: 8px 12px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 212, 170, 0.3);
        text-decoration: none;
    }
    .btn_success:hover {
        background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px #00d4aa;
    }

    .btn_info .btn_warning .btn_danger{
        border: none;
        color: #ffffff;
        font-weight: 600;
        border-radius: 0;
        padding: 4px 8px;
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
    .nav-menu a:hover, .nav-menu a.active {
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
    
    .address-item .card {
        transition: all 0.3s ease;
    }
    .address-item .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
    }
    .address-details p {
        font-size: 14px;
        color: #666;
    }
    .address-actions .btn-group .btn {
        border-radius: 0;
        margin-right: 5px;
        padding: 3px 6px;
    }
    .empty-state {
        color: #6c757d;
    }
    .badge-bg-success{
        font-size: 11px;
        color: #009476;
        border: 1px solid #009476;
        padding: 2px 8px;
    }

    .text-color{
        color: #e33505;
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
@endsection
