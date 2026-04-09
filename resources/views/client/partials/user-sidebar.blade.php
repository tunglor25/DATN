{{-- Shared User Account Sidebar - used on Profile, Orders, Wishlist, etc. --}}
<div class="user-sidebar">
    <!-- User Card -->
    <div class="user-sidebar-card">
        <div class="d-flex align-items-center">
            <div class="user-sidebar-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="ms-3">
                <h6 class="mb-0 fw-bold" style="font-size: 0.95rem;">{{ Auth::user()->name }}</h6>
                <small style="color: var(--tlo-text-light); font-size: 0.8rem;">{{ Auth::user()->phone ?? 'Chưa cập nhật' }}</small>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="user-sidebar-nav">
        <a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> Thông tin cá nhân
        </a>
        <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="fas fa-box"></i> Đơn hàng của tôi
        </a>
        <a href="{{ route('wishlist.index') }}" class="{{ request()->routeIs('wishlist.*') ? 'active' : '' }}">
            <i class="fas fa-heart"></i> Sản phẩm yêu thích
        </a>
        <a href="{{ route('addresses.index') }}" class="{{ request()->routeIs('addresses.*') ? 'active' : '' }}">
            <i class="fas fa-map-marker-alt"></i> Sổ địa chỉ
        </a>
        <a href="{{ route('discounts.my-discounts') }}" class="{{ request()->routeIs('discounts.my-discounts') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i> Mã của tôi
        </a>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="text-danger" style="background: none; border: none; width: 100%; text-align: left; padding: 12px 20px; font-size: 0.88rem; cursor: pointer; display: flex; align-items: center; gap: 10px; font-family: inherit; color: #ef4444;">
                <i class="fas fa-sign-out-alt" style="width: 20px;"></i> Đăng xuất
            </button>
        </form>
    </div>
</div>
