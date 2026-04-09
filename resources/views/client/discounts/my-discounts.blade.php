@extends('layouts.app_client')

@section('title', 'Mã Giảm Giá Của Tôi - TLO Fashion')

@section('content')
<div class="tlo-full-width">
    <!-- Hero -->
    <section class="tlo-page-hero">
        <div class="tlo-page-hero-inner">
            <div class="tlo-hero-badge"><i class="fas fa-wallet"></i> Ví voucher</div>
            <h1 class="tlo-hero-title">Mã Giảm Giá Của Tôi</h1>
            <p class="tlo-hero-desc">Quản lý và sử dụng mã giảm giá đã lưu</p>
        </div>
    </section>

    <div class="tlo-container" style="padding-top: 32px; padding-bottom: 60px;">
        <div class="user-page-layout">
            <!-- Sidebar -->
            @include('client.partials.user-sidebar')

            <!-- Main Content -->
            <div class="user-page-main">
                <div class="user-page-card">
                    <div class="my-disc-header">
                        <h2 class="my-disc-title"><i class="fas fa-ticket-alt"></i> Mã giảm giá của tôi</h2>
                        <a href="{{ route('discounts.index') }}" class="my-disc-get-more">
                            <i class="fas fa-plus-circle"></i> Nhận thêm mã
                        </a>
                    </div>

                    <!-- Filter tabs -->
                    <div class="my-disc-tabs">
                        <ul class="nav nav-tabs" id="discountTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                                    <i class="fas fa-layer-group"></i> Tất cả
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
                                    <i class="fas fa-check-circle"></i> Có thể dùng
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="used-tab" data-bs-toggle="tab" data-bs-target="#used" type="button" role="tab">
                                    <i class="fas fa-receipt"></i> Đã sử dụng
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="expired-tab" data-bs-toggle="tab" data-bs-target="#expired" type="button" role="tab">
                                    <i class="fas fa-clock"></i> Hết hạn
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab content -->
                    <div class="tab-content" id="discountTabsContent">
                        <!-- All discounts -->
                        <div class="tab-pane fade show active" id="all" role="tabpanel">
                            @if($userDiscounts->count() > 0)
                                <div class="my-disc-list">
                                    @foreach($userDiscounts as $userDiscount)
                                        @php
                                            $isExpired = $userDiscount->discount->expires_at && $userDiscount->discount->expires_at->isPast();
                                            $isActive = $userDiscount->status === 'active' && !$isExpired;
                                            $isUsed = $userDiscount->status === 'used';
                                        @endphp
                                        <div class="my-disc-card {{ $isUsed ? 'is-used' : ($isExpired ? 'is-expired' : '') }}">
                                            <div class="my-disc-left {{ $isUsed ? 'used' : ($isExpired ? 'expired' : '') }}">
                                                <div class="my-disc-brand">T</div>
                                                <div class="my-disc-brand-name">TLO</div>
                                            </div>
                                            <div class="my-disc-body">
                                                <div class="my-disc-info">
                                                    <div class="my-disc-value">
                                                        {{ $userDiscount->discount->type === 'fixed' ? 'Giảm ' . number_format($userDiscount->discount->value) . 'đ' : 'Giảm ' . $userDiscount->discount->value . '%' }}
                                                        @if($userDiscount->discount->type === 'percent' && $userDiscount->discount->min_order_value > 0)
                                                            <span class="my-disc-max">tối đa {{ number_format($userDiscount->discount->min_order_value * $userDiscount->discount->value / 100) }}đ</span>
                                                        @endif
                                                    </div>
                                                    @if($userDiscount->discount->min_order_value > 0)
                                                        <div class="my-disc-condition">Đơn tối thiểu {{ number_format($userDiscount->discount->min_order_value) }}đ</div>
                                                    @else
                                                        <div class="my-disc-condition">Áp dụng mọi đơn hàng</div>
                                                    @endif
                                                </div>
                                                <div class="my-disc-meta">
                                                    <span><i class="far fa-calendar-check"></i> Nhận: {{ $userDiscount->claimed_at->format('d/m/Y') }}</span>
                                                    @if($userDiscount->used_at)
                                                        <span><i class="fas fa-check"></i> Dùng: {{ $userDiscount->used_at->format('d/m/Y') }}</span>
                                                    @endif
                                                    @if($userDiscount->discount->expires_at)
                                                        <span><i class="far fa-clock"></i> HSD: {{ $userDiscount->discount->expires_at->format('d/m/Y') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="my-disc-action">
                                                @if($isActive)
                                                    <a href="{{ route('checkout.index') }}" class="my-disc-btn active">Sử dụng</a>
                                                @elseif($isUsed)
                                                    <span class="my-disc-btn used">Đã dùng</span>
                                                @else
                                                    <span class="my-disc-btn expired">Hết hạn</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="my-disc-empty">
                                    <i class="fas fa-wallet"></i>
                                    <p>Bạn chưa có mã giảm giá nào</p>
                                    <a href="{{ route('discounts.index') }}" class="my-disc-btn active">
                                        <i class="fas fa-gift"></i> Nhận mã giảm giá
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
                                <div class="my-disc-list">
                                    @foreach($activeDiscounts as $userDiscount)
                                        <div class="my-disc-card">
                                            <div class="my-disc-left">
                                                <div class="my-disc-brand">T</div>
                                                <div class="my-disc-brand-name">TLO</div>
                                            </div>
                                            <div class="my-disc-body">
                                                <div class="my-disc-info">
                                                    <div class="my-disc-value">
                                                        {{ $userDiscount->discount->type === 'fixed' ? 'Giảm ' . number_format($userDiscount->discount->value) . 'đ' : 'Giảm ' . $userDiscount->discount->value . '%' }}
                                                    </div>
                                                    @if($userDiscount->discount->min_order_value > 0)
                                                        <div class="my-disc-condition">Đơn tối thiểu {{ number_format($userDiscount->discount->min_order_value) }}đ</div>
                                                    @endif
                                                </div>
                                                <div class="my-disc-meta">
                                                    <span><i class="far fa-calendar-check"></i> Nhận: {{ $userDiscount->claimed_at->format('d/m/Y') }}</span>
                                                    @if($userDiscount->discount->expires_at)
                                                        <span><i class="far fa-clock"></i> HSD: {{ $userDiscount->discount->expires_at->format('d/m/Y') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="my-disc-action">
                                                <a href="{{ route('checkout.index') }}" class="my-disc-btn active">Sử dụng</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="my-disc-empty">
                                    <i class="fas fa-check-circle"></i>
                                    <p>Không có mã giảm giá nào có thể sử dụng</p>
                                </div>
                            @endif
                        </div>

                        <!-- Used discounts -->
                        <div class="tab-pane fade" id="used" role="tabpanel">
                            @php $usedDiscounts = $userDiscounts->where('status', 'used'); @endphp
                            @if($usedDiscounts->count() > 0)
                                <div class="my-disc-list">
                                    @foreach($usedDiscounts as $userDiscount)
                                        <div class="my-disc-card is-used">
                                            <div class="my-disc-left used">
                                                <div class="my-disc-brand">T</div>
                                                <div class="my-disc-brand-name">TLO</div>
                                            </div>
                                            <div class="my-disc-body">
                                                <div class="my-disc-info">
                                                    <div class="my-disc-value">
                                                        {{ $userDiscount->discount->type === 'fixed' ? 'Giảm ' . number_format($userDiscount->discount->value) . 'đ' : 'Giảm ' . $userDiscount->discount->value . '%' }}
                                                    </div>
                                                    @if($userDiscount->discount->min_order_value > 0)
                                                        <div class="my-disc-condition">Đơn tối thiểu {{ number_format($userDiscount->discount->min_order_value) }}đ</div>
                                                    @endif
                                                </div>
                                                <div class="my-disc-meta">
                                                    <span><i class="far fa-calendar-check"></i> Nhận: {{ $userDiscount->claimed_at->format('d/m/Y') }}</span>
                                                    <span><i class="fas fa-check"></i> Dùng: {{ $userDiscount->used_at->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                            <div class="my-disc-action">
                                                <span class="my-disc-btn used">Đã dùng</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="my-disc-empty">
                                    <i class="fas fa-receipt"></i>
                                    <p>Chưa có mã giảm giá nào được sử dụng</p>
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
                                <div class="my-disc-list">
                                    @foreach($expiredDiscounts as $userDiscount)
                                        <div class="my-disc-card is-expired">
                                            <div class="my-disc-left expired">
                                                <div class="my-disc-brand">T</div>
                                                <div class="my-disc-brand-name">TLO</div>
                                            </div>
                                            <div class="my-disc-body">
                                                <div class="my-disc-info">
                                                    <div class="my-disc-value">
                                                        {{ $userDiscount->discount->type === 'fixed' ? 'Giảm ' . number_format($userDiscount->discount->value) . 'đ' : 'Giảm ' . $userDiscount->discount->value . '%' }}
                                                    </div>
                                                    @if($userDiscount->discount->min_order_value > 0)
                                                        <div class="my-disc-condition">Đơn tối thiểu {{ number_format($userDiscount->discount->min_order_value) }}đ</div>
                                                    @endif
                                                </div>
                                                <div class="my-disc-meta">
                                                    <span><i class="far fa-calendar-check"></i> Nhận: {{ $userDiscount->claimed_at->format('d/m/Y') }}</span>
                                                    @if($userDiscount->discount->expires_at)
                                                        <span><i class="far fa-clock"></i> HSD: {{ $userDiscount->discount->expires_at->format('d/m/Y') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="my-disc-action">
                                                @if($userDiscount->status === 'used')
                                                    <span class="my-disc-btn used">Đã dùng</span>
                                                @else
                                                    <span class="my-disc-btn expired">Hết hạn</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="my-disc-empty">
                                    <i class="fas fa-clock"></i>
                                    <p>Không có mã giảm giá nào hết hạn</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Header */
    .my-disc-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .my-disc-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--tlo-text-primary);
        margin: 0;
    }
    .my-disc-title i {
        color: var(--tlo-accent);
        margin-right: 8px;
    }
    .my-disc-get-more {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        color: #fff;
        padding: 8px 18px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(255, 107, 107, 0.2);
    }
    .my-disc-get-more:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.35);
        color: #fff;
    }

    /* Tabs */
    .my-disc-tabs {
        margin-bottom: 24px;
    }
    .my-disc-tabs .nav-tabs {
        border-bottom: 2px solid var(--tlo-border, #e2e8f0);
        gap: 0;
    }
    .my-disc-tabs .nav-link {
        color: var(--tlo-text-secondary, #94a3b8);
        border: none;
        border-bottom: 2px solid transparent;
        padding: 10px 18px;
        font-size: 0.85rem;
        font-weight: 500;
        border-radius: 0;
        margin-bottom: -2px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        background: none;
    }
    .my-disc-tabs .nav-link.active {
        color: var(--tlo-accent, #ff6b6b);
        border-bottom-color: var(--tlo-accent, #ff6b6b);
        background: none;
        font-weight: 600;
    }
    .my-disc-tabs .nav-link:hover {
        color: var(--tlo-accent, #ff6b6b);
        border-color: transparent;
        border-bottom-color: rgba(255, 107, 107, 0.3);
    }

    /* Coupon List */
    .my-disc-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* Coupon Card */
    .my-disc-card {
        display: flex;
        align-items: stretch;
        background: var(--tlo-surface, #fff);
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid var(--tlo-border, #e2e8f0);
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
    }
    .my-disc-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .my-disc-card.is-used {
        opacity: 0.6;
    }
    .my-disc-card.is-expired {
        opacity: 0.45;
    }

    /* Left Brand Section */
    .my-disc-left {
        width: 100px;
        min-height: 100px;
        background: linear-gradient(135deg, #1a1a2e, #0f0f0f);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        position: relative;
        flex-shrink: 0;
    }
    .my-disc-left.used {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    .my-disc-left.expired {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    .my-disc-left::after {
        content: '';
        position: absolute;
        right: -8px;
        top: 0;
        bottom: 0;
        width: 16px;
        background: radial-gradient(circle at left, transparent 8px, var(--tlo-surface, #fff) 8px);
        background-size: 16px 18px;
        background-repeat: repeat-y;
    }
    .my-disc-brand {
        width: 36px;
        height: 36px;
        border: 2px solid rgba(255,255,255,0.8);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 800;
        color: #fff;
    }
    .my-disc-brand-name {
        font-size: 10px;
        font-weight: 700;
        color: rgba(255,255,255,0.8);
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    /* Body */
    .my-disc-body {
        flex: 1;
        padding: 16px 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 8px;
        min-width: 0;
    }
    .my-disc-value {
        font-size: 1rem;
        font-weight: 700;
        color: var(--tlo-text-primary, #1e293b);
    }
    .my-disc-max {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--tlo-text-secondary, #94a3b8);
        margin-left: 4px;
    }
    .my-disc-condition {
        font-size: 0.8rem;
        color: var(--tlo-text-secondary, #94a3b8);
    }
    .my-disc-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 0.75rem;
        color: var(--tlo-text-muted, #cbd5e1);
    }
    .my-disc-meta i {
        margin-right: 3px;
    }

    /* Action */
    .my-disc-action {
        display: flex;
        align-items: center;
        padding: 0 20px;
        flex-shrink: 0;
    }
    .my-disc-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 20px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        white-space: nowrap;
        border: none;
        cursor: default;
    }
    .my-disc-btn.active {
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        color: #fff;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(255, 107, 107, 0.2);
    }
    .my-disc-btn.active:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.35);
        color: #fff;
    }
    .my-disc-btn.used {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
    }
    .my-disc-btn.expired {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    /* Empty State */
    .my-disc-empty {
        text-align: center;
        padding: 60px 20px;
    }
    .my-disc-empty i {
        font-size: 3rem;
        color: var(--tlo-border, #e2e8f0);
        margin-bottom: 16px;
        display: block;
    }
    .my-disc-empty p {
        color: var(--tlo-text-secondary, #94a3b8);
        margin-bottom: 20px;
        font-size: 0.95rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .my-disc-card {
            flex-direction: column;
        }
        .my-disc-left {
            width: 100%;
            min-height: 50px;
            flex-direction: row;
            gap: 10px;
            padding: 10px 0;
        }
        .my-disc-left::after {
            display: none;
        }
        .my-disc-action {
            padding: 0 16px 16px;
        }
        .my-disc-btn {
            width: 100%;
        }
        .my-disc-tabs .nav-link {
            padding: 8px 12px;
            font-size: 0.78rem;
        }
        .my-disc-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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