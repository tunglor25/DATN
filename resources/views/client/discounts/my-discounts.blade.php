@extends('layouts.app_client')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item active">Mã giảm giá của tôi</li>
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Mã giảm giá của tôi</h5>
                    <a href="{{ route('discounts.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-gift me-1"></i>Nhận thêm mã
                    </a>
                </div>

                <!-- Filter tabs -->
                <div class="mb-4">
                    <ul class="nav nav-tabs" id="discountTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                                <i class="fas fa-list me-1"></i>Tất cả
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
                                <i class="fas fa-check-circle me-1"></i>Có thể sử dụng
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="used-tab" data-bs-toggle="tab" data-bs-target="#used" type="button" role="tab">
                                <i class="fas fa-check me-1"></i>Đã sử dụng
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="expired-tab" data-bs-toggle="tab" data-bs-target="#expired" type="button" role="tab">
                                <i class="fas fa-times-circle me-1"></i>Hết hạn
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab content -->
                <div class="tab-content" id="discountTabsContent">
                    <!-- All discounts -->
                    <div class="tab-pane fade show active" id="all" role="tabpanel">
                        @if($userDiscounts->count() > 0)
                            <div class="coupon-list">
                                @foreach($userDiscounts as $userDiscount)
                                    @php
                                        $isExpired = $userDiscount->discount->expires_at && $userDiscount->discount->expires_at->isPast();
                                        $isActive = $userDiscount->status === 'active' && !$isExpired;
                                        $isUsed = $userDiscount->status === 'used';
                                    @endphp
                                    <div class="coupon {{ $isUsed ? 'saved' : ($isExpired ? 'expired' : '') }}">
                                        <div class="coupon-left {{ $isUsed ? 'used' : ($isExpired ? 'expired' : '') }}">
                                            <div class="shop-icon">T</div>
                                            <div class="shop-name">TLO FASHION</div>
                                        </div>
                                        <div class="coupon-content">
                                            <div>
                                                <div class="discount-title">
                                                    {{ $userDiscount->discount->type === 'fixed' ? 'Giảm ' . number_format($userDiscount->discount->value) . 'đ' : 'Giảm ' . $userDiscount->discount->value . '%' }}
                                                    @if($userDiscount->discount->type === 'percent' && $userDiscount->discount->min_order_value > 0)
                                                        Giảm tối đa đ{{ number_format($userDiscount->discount->min_order_value * $userDiscount->discount->value / 100) }}
                                                    @endif
                                                </div>
                                                @if($userDiscount->discount->min_order_value > 0)
                                                    <div class="discount-condition">Đơn Tối Thiểu đ{{ number_format($userDiscount->discount->min_order_value) }}</div>
                                                @else
                                                    <div class="discount-condition">Áp dụng cho mọi đơn hàng</div>
                                                @endif
                                            </div>
                                            <div class="coupon-validity">
                                                <p>Nhận lúc: {{ $userDiscount->claimed_at->format('d/m/Y H:i') }}</p>
                                                @if($userDiscount->used_at)
                                                    <p>Đã sử dụng: {{ $userDiscount->used_at->format('d/m/Y H:i') }}</p>
                                                @endif
                                                @if($userDiscount->discount->expires_at)
                                                    <p>Hết hạn: {{ $userDiscount->discount->expires_at->format('d/m/Y') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($isActive)
                                            <a href="{{ route('checkout.index') }}" class="save-button use-button">
                                                Sử dụng
                                            </a>
                                        @elseif($isUsed)
                                            <button class="save-button used-button" disabled>
                                                Đã sử dụng
                                            </button>
                                        @else
                                            <button class="save-button expired-button" disabled>
                                                Hết hạn
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-wallet fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Bạn chưa có mã giảm giá nào</p>
                                <a href="{{ route('discounts.index') }}" class="btn btn-primary">
                                    <i class="fas fa-gift me-1"></i>Nhận mã giảm giá
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Active discounts -->
                    <div class="tab-pane fade" id="active" role="tabpanel">
                        @php
                            $activeDiscounts = $userDiscounts->where('status', 'active')->filter(function($ud) {
                                return !($ud->discount->expires_at && $ud->discount->expires_at->isPast());
                            });
                        @endphp
                        
                        @if($activeDiscounts->count() > 0)
                            <div class="coupon-list">
                                @foreach($activeDiscounts as $userDiscount)
                                    <div class="coupon">
                                        <div class="coupon-left">
                                            <div class="shop-icon">T</div>
                                            <div class="shop-name">TLO FASHION</div>
                                        </div>
                                        <div class="coupon-content">
                                            <div>
                                                <div class="discount-title">
                                                    {{ $userDiscount->discount->type === 'fixed' ? 'Giảm ' . number_format($userDiscount->discount->value) . 'đ' : 'Giảm ' . $userDiscount->discount->value . '%' }}
                                                    @if($userDiscount->discount->type === 'percent' && $userDiscount->discount->min_order_value > 0)
                                                        Giảm tối đa đ{{ number_format($userDiscount->discount->min_order_value * $userDiscount->discount->value / 100) }}
                                                    @endif
                                                </div>
                                                @if($userDiscount->discount->min_order_value > 0)
                                                    <div class="discount-condition">Đơn Tối Thiểu đ{{ number_format($userDiscount->discount->min_order_value) }}</div>
                                                @else
                                                    <div class="discount-condition">Áp dụng cho mọi đơn hàng</div>
                                                @endif
                                            </div>
                                            <div class="coupon-validity">
                                                <p>Nhận lúc: {{ $userDiscount->claimed_at->format('d/m/Y H:i') }}</p>
                                                @if($userDiscount->discount->expires_at)
                                                    <p>Hết hạn: {{ $userDiscount->discount->expires_at->format('d/m/Y') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <a href="{{ route('checkout.index') }}" class="save-button use-button">
                                            Sử dụng
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Không có mã giảm giá nào có thể sử dụng</p>
                            </div>
                        @endif
                    </div>

                    <!-- Used discounts -->
                    <div class="tab-pane fade" id="used" role="tabpanel">
                        @php
                            $usedDiscounts = $userDiscounts->where('status', 'used');
                        @endphp
                        
                        @if($usedDiscounts->count() > 0)
                            <div class="coupon-list">
                                @foreach($usedDiscounts as $userDiscount)
                                    <div class="coupon saved">
                                        <div class="coupon-left used">
                                            <div class="shop-icon">T</div>
                                            <div class="shop-name">TLO FASHION</div>
                                        </div>
                                        <div class="coupon-content">
                                            <div>
                                                <div class="discount-title">
                                                    {{ $userDiscount->discount->type === 'fixed' ? 'Giảm ' . number_format($userDiscount->discount->value) . 'đ' : 'Giảm ' . $userDiscount->discount->value . '%' }}
                                                    @if($userDiscount->discount->type === 'percent' && $userDiscount->discount->min_order_value > 0)
                                                        Giảm tối đa đ{{ number_format($userDiscount->discount->min_order_value * $userDiscount->discount->value / 100) }}
                                                    @endif
                                                </div>
                                                @if($userDiscount->discount->min_order_value > 0)
                                                    <div class="discount-condition">Đơn Tối Thiểu đ{{ number_format($userDiscount->discount->min_order_value) }}</div>
                                                @else
                                                    <div class="discount-condition">Áp dụng cho mọi đơn hàng</div>
                                                @endif
                                            </div>
                                            <div class="coupon-validity">
                                                <p>Nhận lúc: {{ $userDiscount->claimed_at->format('d/m/Y H:i') }}</p>
                                                <p>Đã sử dụng: {{ $userDiscount->used_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                        
                                        <button class="save-button used-button" disabled>
                                            Đã sử dụng
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-check fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có mã giảm giá nào được sử dụng</p>
                            </div>
                        @endif
                    </div>

                    <!-- Expired discounts -->
                    <div class="tab-pane fade" id="expired" role="tabpanel">
                        @php
                            $expiredDiscounts = $userDiscounts->filter(function($ud) {
                                return $ud->status === 'expired' || 
                                       ($ud->discount->expires_at && $ud->discount->expires_at->isPast());
                            });
                        @endphp
                        
                        @if($expiredDiscounts->count() > 0)
                            <div class="coupon-list">
                                @foreach($expiredDiscounts as $userDiscount)
                                    @php
                                        $isExpired = $userDiscount->discount->expires_at && $userDiscount->discount->expires_at->isPast();
                                        $isActive = $userDiscount->status === 'active' && !$isExpired;
                                    @endphp
                                    <div class="coupon expired">
                                        <div class="coupon-left expired">
                                            <div class="shop-icon">T</div>
                                            <div class="shop-name">TLO FASHION</div>
                                        </div>
                                        <div class="coupon-content">
                                            <div>
                                                <div class="discount-title">
                                                    {{ $userDiscount->discount->type === 'fixed' ? 'Giảm ' . number_format($userDiscount->discount->value) . 'đ' : 'Giảm ' . $userDiscount->discount->value . '%' }}
                                                    @if($userDiscount->discount->type === 'percent' && $userDiscount->discount->min_order_value > 0)
                                                        Giảm tối đa đ{{ number_format($userDiscount->discount->min_order_value * $userDiscount->discount->value / 100) }}
                                                    @endif
                                                </div>
                                                @if($userDiscount->discount->min_order_value > 0)
                                                    <div class="discount-condition">Đơn Tối Thiểu đ{{ number_format($userDiscount->discount->min_order_value) }}</div>
                                                @else
                                                    <div class="discount-condition">Áp dụng cho mọi đơn hàng</div>
                                                @endif
                                            </div>
                                            <div class="coupon-validity">
                                                <p>Nhận lúc: {{ $userDiscount->claimed_at->format('d/m/Y H:i') }}</p>
                                                @if($userDiscount->discount->expires_at)
                                                    <p>Hết hạn: {{ $userDiscount->discount->expires_at->format('d/m/Y') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($userDiscount->status === 'used')
                                            <button class="save-button used-button" disabled>
                                                Đã sử dụng
                                            </button>
                                        @else
                                            <button class="save-button expired-button" disabled>
                                                Hết hạn
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-times-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Không có mã giảm giá nào hết hạn</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .content {
        border-radius: 10px;
        padding: 10px 0px;
    }

    .sidebar {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .user-profile-card {
        background-color: #fff5f5;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        background-color: #ff6b35;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .promo-card {
        background-color: #fff5f5;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #ffe6e6;
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
        transition: all 0.3s ease;
    }

    .nav-menu a:hover,
    .nav-menu a.active {
        background-color: #f8f9fa;
        border-left: 3px solid #ff6b35;
    }

    .nav-menu i {
        margin-right: 12px;
        width: 20px;
        color: #666;
    }

    .main-content {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-promo {
        background-color: #dc3545;
        border-color: #dc3545;
        font-size: 12px;
        padding: 6px 12px;
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

    /* Coupon Styles */
    .coupon-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-width: 100%;
    }

    .coupon {
        width: 100%;
        height: 100px;
        background: white;
        border-radius: 8px;
        display: flex;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        position: relative;
        transition: opacity 0.3s ease;
    }

    .coupon.saved {
        opacity: 0.5;
    }

    .coupon.expired {
        opacity: 0.3;
    }

    .coupon-left {
        width: 120px;
        background: linear-gradient(135deg, #000000, #000000);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        position: relative;
    }

    .coupon-left.used {
        background: linear-gradient(135deg, #4caf50, #45a049);
    }

    .coupon-left.expired {
        background: linear-gradient(135deg, #f44336, #d32f2f);
    }

    .coupon-left::after {
        content: '';
        position: absolute;
        right: -8px;
        top: 0;
        bottom: 0;
        width: 16px;
        background: radial-gradient(circle at left, transparent 8px, white 8px);
        background-size: 16px 16px;
        background-repeat: repeat-y;
    }

    .shop-icon {
        width: 40px;
        height: 32px;
        border: 2px solid white;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .shop-name {
        font-size: 12px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .coupon-content {
        flex: 1;
        padding: 15px 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .discount-title {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        margin-bottom: 4px;
    }

    .discount-condition {
        font-size: 12px;
        color: #666;
        margin-bottom: 8px;
    }

    .coupon-validity {
        font-size: 11px;
        color: #999;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .coupon-validity p {
        margin: 0;
    }

    .save-button {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: #000000;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 50px;
        text-decoration: none;
    }

    .save-button:hover {
        background: #00000093;
        color: white;
        text-decoration: none;
    }

    .save-button.saved {
        background: #4caf50;
        cursor: default;
    }

    .save-button.saved:hover {
        background: #4caf50;
    }

    .save-button.used-button {
        background: #2196f3;
        cursor: default;
    }

    .save-button.used-button:hover {
        background: #2196f3;
    }

    .save-button.expired-button {
        background: #f44336;
        cursor: default;
    }

    .save-button.expired-button:hover {
        background: #f44336;
    }

    .save-button.use-button {
        background: #ff9800;
    }

    .save-button.use-button:hover {
        background: #f57c00;
        color: white;
    }

    /* Tab Styles */
    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
    }

    .nav-tabs .nav-link.active {
        color: #ff6b35;
        background-color: transparent;
        border-bottom: 2px solid #ff6b35;
        font-weight: 600;
    }

    .nav-tabs .nav-link:hover {
        color: #ff6b35;
        border-color: transparent;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .coupon {
            height: auto;
            min-height: 100px;
        }
        
        .coupon-content {
            padding: 10px 15px;
        }
        
        .discount-title {
            font-size: 14px;
        }
        
        .discount-condition {
            font-size: 11px;
        }
        
        .coupon-validity {
            font-size: 10px;
        }
        
        .save-button {
            padding: 6px 12px;
            font-size: 11px;
            min-width: 40px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Auto close alerts
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