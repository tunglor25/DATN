@extends('layouts.app_client')

@section('title', 'Mã Giảm Giá - TLO Fashion')

@section('content')
<div class="tlo-full-width">
    <!-- Hero -->
    <section class="tlo-page-hero">
        <div class="tlo-page-hero-inner">
            <div class="tlo-hero-badge"><i class="fas fa-tags"></i> Ưu đãi</div>
            <h1 class="tlo-hero-title">Mã Giảm Giá</h1>
            <p class="tlo-hero-desc">Nhận mã giảm giá để tiết kiệm hơn khi mua sắm</p>
        </div>
    </section>

<div class="tlo-container" style="padding-top: 32px; padding-bottom: 60px;">

    <div class="row">
        <!-- Cột trái: Danh sách mã giảm giá -->
        <div class="col-lg-8 col-md-7">
            <!-- Mã giảm giá có thể nhận -->
            <div class="available-discounts mb-5">

                
                @if($availableDiscounts->count() > 0)
                    <div class="coupon-list">
                        @foreach($availableDiscounts as $discount)
                        <div class="coupon {{ $claimedDiscounts->where('discount_id', $discount->id)->count() > 0 ? 'saved' : '' }}" id="coupon-{{ $discount->id }}">
                            <div class="coupon-left">
                                <div class="shop-icon">T</div>
                                <div class="shop-name">TLO FASHION</div>
                            </div>
                            <div class="coupon-content">
                                <div>
                                    <div class="discount-title">
                                        {{ $discount->type === 'fixed' ? 'Giảm ' . number_format($discount->value) . 'đ' : 'Giảm ' . $discount->value . '%' }}
                                        @if($discount->type === 'percent' && $discount->min_order_value > 0)
                                            Giảm tối đa đ{{ number_format($discount->min_order_value * $discount->value / 100) }}
                                        @endif
                                    </div>
                                    @if($discount->min_order_value > 0)
                                        <div class="discount-condition">Đơn Tối Thiểu đ{{ number_format($discount->min_order_value) }}</div>
                                    @else
                                        <div class="discount-condition">Áp dụng cho mọi đơn hàng</div>
                                    @endif
                                </div>
                                <div class="coupon-validity">
                                    @if($discount->starts_at)
                                        <p>Có hiệu lực từ {{ $discount->starts_at->format('d/m/Y') }}</p>
                                    @endif
                                    @if($discount->expires_at)
                                        <p>Hết hạn: {{ $discount->expires_at->format('d/m/Y') }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            @auth
                                @if($claimedDiscounts->where('discount_id', $discount->id)->count() > 0)
                                    <button class="save-button saved" disabled>
                                        <span class="checkmark">đã lưu</span>
                                    </button>
                                @else
                                    <form action="{{ route('discounts.claim', $discount->id) }}" method="POST" class="claim-form" data-discount-id="{{ $discount->id }}">
                                        @csrf
                                        <button type="submit" class="save-button claim-btn">
                                            Lưu
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="save-button" style="text-decoration: none;">
                                    Đăng nhập
                                </a>
                            @endauth
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Hiện tại không có mã giảm giá nào đang phát hành</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Cột phải: Block quảng cáo -->
        <div class="col-lg-4 col-md-5">
            <div class="advertisement-sidebar">
                <!-- Block quảng cáo chính -->
                <div class="ad-block main-ad mb-4">
                    <div class="ad-container">
                        <img src="/storage/img/poster1.jpg" alt="Quảng cáo chính" class="ad-image">
                        <div class="ad-overlay">
                            <div class="ad-text">
                                <h4 class="ad-title">Bộ Sưu Tập Mới</h4>
                                <p class="ad-description">Khám phá ngay những xu hướng thời trang mới nhất</p>
                                <button class="ad-button">Xem Ngay</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Block quảng cáo phụ -->
                <div class="ad-block secondary-ad mb-4">
                    <div class="ad-container">
                        <img src="/storage/img/poster2.jpg" alt="Quảng cáo phụ" class="ad-image">
                        <div class="ad-overlay">
                            <div class="ad-text">
                                <h5 class="ad-title">Ưu Đãi Đặc Biệt</h5>
                                <p class="ad-description">Giảm giá lên đến 50%</p>
                                <button class="ad-button">Mua Ngay</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Block thông tin bổ sung -->
                <div class="info-block mb-4">
                    <div class="info-card">
                        <div class="info-header">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            <h6 class="mb-0">Hướng dẫn sử dụng</h6>
                        </div>
                        <div class="info-content">
                            <ul class="info-list">
                                <li>Nhấn "Lưu" để nhận mã giảm giá</li>
                                <li>Mỗi mã chỉ được nhận 1 lần</li>
                                <li>Sử dụng mã khi thanh toán</li>
                                <li>Mã có thời hạn sử dụng</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<style>
    .coupon-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-width: 100%;
    }

    .coupon {
        width: 100%;
        height: 100px;
        background: var(--tlo-surface);
        border-radius: var(--tlo-radius-md);
        display: flex;
        overflow: hidden;
        box-shadow: var(--tlo-shadow-sm);
        border: 1px solid var(--tlo-border);
        position: relative;
        transition: var(--tlo-transition);
    }

    .coupon.saved {
        opacity: 0.5;
    }

    .coupon.expired {
        opacity: 0.3;
    }

    .coupon-left {
        width: 120px;
        background: linear-gradient(135deg, #1a1a2e, #0f0f0f);
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
        color: var(--tlo-text-primary);
        margin-bottom: 4px;
    }

    .discount-condition {
        font-size: 12px;
        color: var(--tlo-text-secondary);
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
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 50px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(255, 107, 107, 0.2);
    }

    .save-button:hover {
        transform: translateY(-50%) translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.35);
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

    .save-button.loading {
        background: #6c757d;
        cursor: not-allowed;
    }

    .checkmark {
        animation: checkmarkPop 0.3s ease;
    }

    @keyframes checkmarkPop {
        0% {
            transform: scale(0.8);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }

    /* Advertisement Sidebar Styles */
    .advertisement-sidebar {
        position: sticky;
        top: 20px;
    }

    .ad-block {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    /* Advertisement Container Styles */
    .ad-container {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .ad-container:hover {
        transform: translateY(-5px);
    }

    .ad-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .ad-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            to bottom,
            rgba(0, 0, 0, 0.1) 0%,
            rgba(0, 0, 0, 0.3) 50%,
            rgba(0, 0, 0, 0.7) 100%
        );
        display: flex;
        align-items: flex-end;
        padding: 20px;
        transition: all 0.3s ease;
    }

    .ad-container:hover .ad-overlay {
        background: linear-gradient(
            to bottom,
            rgba(0, 0, 0, 0.2) 0%,
            rgba(0, 0, 0, 0.4) 50%,
            rgba(0, 0, 0, 0.8) 100%
        );
    }

    .ad-text {
        color: white;
        text-align: left;
        width: 100%;
    }

    .ad-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 8px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .ad-description {
        font-size: 0.9rem;
        margin-bottom: 15px;
        opacity: 0.9;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    .ad-button {
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: bold;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
    }

    .ad-button:hover {
        background: linear-gradient(135deg, #ee5a24, #ff6b6b);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
    }

    .main-ad .ad-container {
        height: 600px;
    }

    .secondary-ad .ad-container {
        height: 250px;
    }

    .secondary-ad .ad-title {
        font-size: 1.2rem;
    }

    .secondary-ad .ad-description {
        font-size: 0.8rem;
    }

    .secondary-ad .ad-button {
        padding: 6px 16px;
        font-size: 0.75rem;
    }

    /* Info Block Styles */
    .info-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .info-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        padding: 15px 20px;
        display: flex;
        align-items: center;
    }

    .info-content {
        padding: 20px;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-list li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        position: relative;
        padding-left: 20px;
    }

    .info-list li:before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #28a745;
        font-weight: bold;
    }

    .info-list li:last-child {
        border-bottom: none;
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

        .advertisement-sidebar {
            position: static;
            margin-top: 30px;
        }

        .main-ad .ad-container {
            height: 300px;
        }

        .secondary-ad .ad-container {
            height: 200px;
        }

        .ad-title {
            font-size: 1.2rem;
        }

        .ad-description {
            font-size: 0.8rem;
        }

        .ad-button {
            padding: 6px 16px;
            font-size: 0.75rem;
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
            }, 5000);
        }
        const errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(errorAlert);
                bsAlert.close();
            }, 5000);
        }

        // Advertisement interactions
        const adButtons = document.querySelectorAll('.ad-button');
        adButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Add click animation
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);

                // You can add navigation logic here
                // For example: window.location.href = '/products';
                console.log('Ad button clicked:', this.textContent);
            });
        });

        // Add hover effects for ad containers
        const adContainers = document.querySelectorAll('.ad-container');
        adContainers.forEach(container => {
            container.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });
            
            container.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
</script>
@endsection 