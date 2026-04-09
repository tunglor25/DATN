@extends('layouts.app_client')

@section('title', 'Sổ Địa Chỉ - TLO Fashion')

@section('content')
<div class="tlo-full-width">
    <!-- Hero -->
    <section class="tlo-page-hero">
        <div class="tlo-page-hero-inner">
            <div class="tlo-hero-badge"><i class="fas fa-map-marker-alt"></i> Tài khoản</div>
            <h1 class="tlo-hero-title">Sổ Địa Chỉ</h1>
            <p class="tlo-hero-desc">Quản lý địa chỉ giao hàng của bạn</p>
        </div>
    </section>

    <div class="tlo-container" style="padding-top: 32px; padding-bottom: 60px;">
        <div class="user-page-layout">
            <!-- Sidebar -->
            @include('client.partials.user-sidebar')

            <!-- Main Content -->
            <div class="user-page-main">
                <div class="user-page-card">
                    <div class="addr-header">
                        <h2 class="addr-title"><i class="fas fa-location-dot"></i> Địa chỉ của tôi</h2>
                        <a href="{{ route('addresses.create') }}" class="addr-add-btn">
                            <i class="fas fa-plus"></i> Thêm địa chỉ mới
                        </a>
                    </div>

                    @if($addresses->count() > 0)
                        <div class="addr-list">
                            @foreach($addresses as $address)
                                <div class="addr-card {{ $address->is_default ? 'is-default' : '' }}">
                                    <div class="addr-card-body">
                                        <div class="addr-info">
                                            <div class="addr-name-row">
                                                <span class="addr-name">{{ $address->receiver_name }}</span>
                                                @if($address->is_default)
                                                    <span class="addr-default-badge"><i class="fas fa-check-circle"></i> Mặc định</span>
                                                @endif
                                            </div>
                                            <div class="addr-detail">
                                                <span><i class="fas fa-phone"></i> {{ $address->receiver_phone }}</span>
                                            </div>
                                            <div class="addr-detail">
                                                <span><i class="fas fa-map-pin"></i> {{ $address->street_address }}, {{ $address->ward_name }}, {{ $address->province_name }}</span>
                                            </div>
                                        </div>
                                        <div class="addr-actions">
                                            @if(!$address->is_default)
                                                <form action="{{ route('addresses.set-default', $address) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="addr-action-btn default" title="Đặt làm mặc định">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('addresses.edit', $address) }}" class="addr-action-btn edit" title="Chỉnh sửa">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            @if(!$address->isUsedInOrders())
                                                <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="addr-action-btn delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">
                                                        <i class="fas fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="addr-empty">
                            <i class="fas fa-map-marker-alt"></i>
                            <h3>Chưa có địa chỉ nào</h3>
                            <p>Thêm địa chỉ giao hàng để thuận tiện khi đặt hàng</p>
                            <a href="{{ route('addresses.create') }}" class="addr-add-btn">
                                <i class="fas fa-plus"></i> Thêm địa chỉ đầu tiên
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Header */
    .addr-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .addr-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--tlo-text-primary);
        margin: 0;
    }
    .addr-title i {
        color: var(--tlo-accent);
        margin-right: 8px;
    }
    .addr-add-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        color: #fff;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(255, 107, 107, 0.2);
        border: none;
        cursor: pointer;
    }
    .addr-add-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.35);
        color: #fff;
    }

    /* Address List */
    .addr-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* Address Card */
    .addr-card {
        background: var(--tlo-surface, #fff);
        border: 1px solid var(--tlo-border, #e2e8f0);
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .addr-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        transform: translateY(-2px);
    }
    .addr-card.is-default {
        border-color: rgba(255, 107, 107, 0.3);
        background: linear-gradient(135deg, rgba(255, 107, 107, 0.03), rgba(238, 90, 36, 0.02));
    }

    .addr-card-body {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 20px 24px;
        gap: 16px;
    }

    .addr-info {
        flex: 1;
        min-width: 0;
    }
    .addr-name-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }
    .addr-name {
        font-weight: 700;
        font-size: 1rem;
        color: var(--tlo-text-primary, #1e293b);
    }
    .addr-default-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.72rem;
        font-weight: 600;
        color: #10b981;
        background: rgba(16, 185, 129, 0.1);
        padding: 3px 10px;
        border-radius: 20px;
    }
    .addr-detail {
        font-size: 0.88rem;
        color: var(--tlo-text-secondary, #64748b);
        margin-bottom: 6px;
        line-height: 1.5;
    }
    .addr-detail i {
        width: 18px;
        color: var(--tlo-text-muted, #94a3b8);
        margin-right: 6px;
        font-size: 0.8rem;
    }

    /* Actions */
    .addr-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }
    .addr-action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid var(--tlo-border, #e2e8f0);
        background: var(--tlo-surface, #fff);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.8rem;
        text-decoration: none;
    }
    .addr-action-btn.default {
        color: #f59e0b;
    }
    .addr-action-btn.default:hover {
        background: #fffbeb;
        border-color: #f59e0b;
    }
    .addr-action-btn.edit {
        color: #3b82f6;
    }
    .addr-action-btn.edit:hover {
        background: #eff6ff;
        border-color: #3b82f6;
    }
    .addr-action-btn.delete {
        color: #ef4444;
    }
    .addr-action-btn.delete:hover {
        background: #fef2f2;
        border-color: #ef4444;
    }

    /* Empty */
    .addr-empty {
        text-align: center;
        padding: 60px 20px;
    }
    .addr-empty i {
        font-size: 3rem;
        color: var(--tlo-border, #e2e8f0);
        margin-bottom: 16px;
        display: block;
    }
    .addr-empty h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.2rem;
        color: var(--tlo-text-primary, #1e293b);
        margin-bottom: 8px;
    }
    .addr-empty p {
        color: var(--tlo-text-secondary, #94a3b8);
        margin-bottom: 20px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .addr-card-body {
            flex-direction: column;
            padding: 16px;
        }
        .addr-actions {
            width: 100%;
            justify-content: flex-end;
            padding-top: 12px;
            border-top: 1px solid var(--tlo-border, #e2e8f0);
        }
        .addr-header {
            flex-direction: column;
            align-items: flex-start;
        }
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
