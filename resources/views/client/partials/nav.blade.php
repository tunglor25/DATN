<style>
    /* Main Navbar Styles */
    .main-navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 72px;
        z-index: 1000;
        background-color: white;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        padding: 0 2rem;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .navbar-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Logo Styles */
    .navbar-brand {
        font-weight: 700;
        color: #111 !important;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        min-height: 40px;
    }

    .navbar-brand img {
        display: block;
        max-width: 100%;
        height: auto;
        object-fit: contain;
    }

    .navbar-brand i {
        color: #ff6b6b;
    }

    /* Navigation Links */
    .nav-links {
        display: flex;
        gap: 1.5rem;
        margin: 0 2rem;
    }

    .nav-link {
        color: #333;
        font-weight: 500;
        text-decoration: none;
        position: relative;
        padding: 0.5rem 0;
        transition: color 0.2s;
    }

    .nav-link:hover {
        color: #000;
    }

    .nav-link.active {
        color: #000;
        font-weight: 600;
    }

    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(135deg, #ff4444, #ff8c00);
        border-radius: 1px;
    }

    /* Search Box */
    .search-container {
        display: flex;
        align-items: center;
        flex-grow: 1;
        max-width: 400px;
    }

    .search-input {
        border: 1px solid #e0e0e0;
        border-radius: 24px;
        padding: 0.5rem 1.25rem;
        width: 100%;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .search-input:focus {
        outline: none;
        border-color: #ff6b6b;
        box-shadow: 0 0 0 2px rgba(255, 107, 107, 0.2);
    }

    #search-results {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        z-index: 999;
        display: flex;
        justify-content: center;
    }

    .search-dropdown-menu {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        min-width: 550px;
        max-width: 700px;
        width: max-content;
        background: white;
        border-radius: 14px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.14);
        margin-top: 12px;
        list-style: none;
        opacity: 1 !important;
        visibility: visible !important;
        padding: 0;
        z-index: 999;
    }

    .search-container {
        position: relative;
        /* ...các thuộc tính khác... */
    }

    .search-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.2);
        z-index: 998;
        display: none;
    }

    .search-suggest-item a {
        color: #222;
        transition: background 0.2s, color 0.2s;
        border-radius: 8px;
    }

    .search-suggest-item a:hover {
        background: #f0f0f0;
        color: #ff6b6b;
        font-weight: 600;
        text-decoration: none;
    }

    .suggest-name {
        font-weight: bold;
        font-size: 1rem;
        color: #222;
    }

    .suggest-price {
        color: #ff6b6b;
        font-size: 0.95rem;
        margin-top: 2px;
        font-weight: 500;
        display: block;
    }

    .search-suggest-title {
        background: #f3f3f3;
        border-radius: 8px 8px 0 0;
        border-top: 4px solid white;
        border-bottom: 4px solid white;
        border-left: 12px solid white;
        border-right: 12px solid white;
        padding: 4px 8px;
        font-weight: bold;
        color: #222;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        text-align: left;
    }

    .search-suggest-item img {
        width: 48px;
        height: 48px;
        object-fit: cover;
        background: #fafafa;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .cart-btn {
        position: relative;
        background: none;
        border: none;
        cursor: pointer;
        color: #333;
        font-size: 1.25rem;
        padding: 0.5rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        transition: color 0.2s;
    }

    .cart-btn:hover {
        color: #ff6b6b;
    }

    .cart-count {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #ff6b6b;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        font-weight: bold;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }

    /* User Dropdown */
    .user-dropdown {
        position: relative;
    }

    .user-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: 24px;
        transition: background 0.2s;
    }

    .user-btn:hover {
        background: #f5f5f5;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        background: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #555;
    }

    .user-name {
        font-weight: 500;
        font-size: 0.95rem;
    }

    .dropdown-menu {
        position: absolute;
        right: 0;
        top: 100%;
        background: white;
        border-radius: 8px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        min-width: 200px;
        padding: 0.5rem 0;
        margin-top: 0.5rem;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.2s;
        z-index: 100;
    }

    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0.65rem 1.25rem;
        color: #333;
        text-decoration: none;
        transition: all 0.2s;
    }

    .dropdown-item:hover {
        background: #f8f8f8;
        color: #000;
    }

    .dropdown-item i {
        width: 20px;
        text-align: center;
        color: #777;
    }

    .dropdown-divider {
        height: 1px;
        background: #eee;
        margin: 0.25rem 0;
    }

    /* Login Button */
    .login-btn {
        background: #ff6b6b;
        color: white;
        border: none;
        border-radius: 24px;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .login-btn:hover {
        background: #ff5252;
        transform: translateY(-1px);
    }

    /* Responsive Styles */
    @media (max-width: 992px) {
        .nav-links {
            display: none;
        }

        .search-container {
            margin: 0 1rem;
        }
    }

    @media (max-width: 768px) {
        .main-navbar {
            padding: 0 1rem;
            height: 60px;
        }

        .search-container {
            display: none;
        }

        .user-name {
            display: none;
        }
    }
</style>

<?php
if (Auth::check()) {
    $user = Auth::user();
    $avatar = $user->avatar ?? null;
    $cart = $user->cart;
    $cartCount = $cart ? $cart->total_items : 0;
} else {
    $avatar = null;
    $cartCount = 0;
}
?>

<nav class="main-navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('storage/img/logo-v2.png') }}?v={{ time() }}" alt="Logo" style="width: 160px; height: 40px; display: block; max-width: 100%; height: auto;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'; console.log('Logo failed to load');">
            <span style="display: none; font-weight: 700; color: #111; font-size: 1.5rem;">TLO Fashion</span>
        </a>

        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="{{ route('home') }}" class="nav-link" data-route="home">Trang chủ</a>
            <a href="{{ route('client.products.index') }}" class="nav-link" data-route="products">Sản phẩm</a>
            <a href="{{ route('about') }}" class="nav-link" data-route="about">Giới thiệu</a>
            <a href="{{ route('posts.news') }}" class="nav-link" data-route="news">Tin tức</a>
        </div>

        <!-- Search Box -->
        <div class="search-container" style="position:relative;">
            <input type="text" class="search-input" placeholder="Tìm kiếm ...">
            <div id="search-results"></div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <!-- Cart Button -->
            <a href="{{ route('cart.index') }}" class="cart-btn">
                <i class="fas fa-shopping-bag"></i>
                <span class="cart-count" style="display: {{ $cartCount > 0 ? 'flex' : 'none' }};">{{ $cartCount }}</span>
            </a>

            <!-- User Dropdown or Login Button -->
            @auth
                <div class="user-dropdown" id="userDropdown">
                    <button class="user-btn" id="userBtn">
                        @if($avatar)
                            <img src="{{ asset('storage/' . $avatar) }}" class="user-avatar" alt="User Avatar">
                        @else
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="user-name">{{ auth()->user()->name }}</span>
                    </button>

                    <div class="dropdown-menu" id="dropdownMenu">
                        <a href="{{ route('profile.index') }}" class="dropdown-item">
                            <i class="fas fa-user"></i> Hồ sơ
                        </a>
                        <a href="{{ route('orders.index') }}" class="dropdown-item">
                            <i class="fas fa-shopping-cart"></i> Đơn hàng
                        </a>
                        <a href="{{ route('wishlist.index')}}" class="dropdown-item">
                            <i class="fas fa-heart"></i> Yêu thích
                        </a>
                        <a href="{{ route('discounts.index') }}" class="dropdown-item">
                            <i class="fas fa-tags"></i> Mã giảm giá
                        </a>
                        <a href="{{ route('discounts.my-discounts') }}" class="dropdown-item">
                            <i class="fas fa-wallet"></i> Mã của tôi
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-cog"></i> Cài đặt
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item"
                                style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <button class="login-btn" data-bs-toggle="modal" data-bs-target="#loginModal">
                    Đăng nhập
                </button>
            @endauth
        </div>
    </div>
</nav>
<div class="search-overlay" id="search-overlay" style="display:none;"></div>

@include('auth.auth_modals')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Navigation Active State Management
    function setActiveNavLink() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            
            const route = link.getAttribute('data-route');
            const href = link.getAttribute('href');
            
            // Check if current path matches the link
            if (route === 'home' && (currentPath === '/' || currentPath === '')) {
                link.classList.add('active');
            } else if (route === 'products' && currentPath.includes('/products')) {
                link.classList.add('active');
            } else if (route === 'about' && currentPath.includes('/gioi-thieu')) {
                link.classList.add('active');
            } else if (route === 'news' && currentPath.includes('/tin-tuc')) {
                link.classList.add('active');
            }
        });
    }

    // Set active state on page load
    document.addEventListener('DOMContentLoaded', function () {
        setActiveNavLink();
        
        @if (!empty($showLoginModal))
            setTimeout(function () {
                var loginModalEl = document.getElementById('loginModal');
                if (loginModalEl) {
                    var loginModal = new bootstrap.Modal(loginModalEl);
                    loginModal.show();
                }
            }, 300);
        @endif

        @if (session('needLogin'))
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        @endif

        @if (session('messageLogin'))
            alert('{{ session('messageLogin') }}');
        @endif

        // Set active navigation link
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const linkPath = link.getAttribute('href');
            
            if (linkPath === currentPath || 
                (currentPath === '/' && linkPath === '{{ route("home") }}') ||
                (currentPath.startsWith('/products') && linkPath === '{{ route("client.products.index") }}') ||
                (currentPath.startsWith('/posts') && linkPath === '{{ route("posts.news") }}')) {
                link.classList.add('active');
            }
        });

        // Dropdown menu toggle
        const userBtn = document.getElementById('userBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if (userBtn && dropdownMenu) {
            userBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!dropdownMenu.contains(e.target) && !userBtn.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                }
            });
        }
    });
</script>

<script>
    $(document).ready(function () {
        $('.search-input').on('keyup', function () {
            let query = $(this).val();
            if (query.length > 0) {
                $.ajax({
                    url: '/search-products',
                    type: 'GET',
                    data: { query: query },
                    success: function (data) {
                        let html = `<ul class="search-dropdown-menu">
                        <li class="search-suggest-title">Sản phẩm gợi ý</li>`;
                                            data.forEach(function (product) {
                                                html += `<li class="search-suggest-item">
                            <a href="/products/${product.slug}" style="display:flex;align-items:center;gap:12px;padding:10px 16px;text-decoration:none;">
                                <img src="/storage/${product.image}" width="48" height="48" style="object-fit:cover;">
                                <div style="display:flex;flex-direction:column;">
                                    <span class="suggest-name">${product.name}</span>
                                    <span class="suggest-price">${product.price.toLocaleString('vi-VN')}₫</span>
                                </div>
                            </a>
                        </li>`;
                        });
                        html += '</ul>';
                        $('#search-results').html(html);

                        // Hiện overlay khi có kết quả
                        if (data.length > 0) {
                            $('#search-overlay').show();
                        } else {
                            $('#search-overlay').hide();
                        }
                    }
                });
            } else {
                $('#search-results').html('');
                $('#search-overlay').hide();
            }
        });

        // Ẩn kết quả và overlay khi click ra ngoài
        $(document).click(function (e) {
            if (!$(e.target).closest('.search-container').length) {
                $('#search-results').html('');
                $('#search-overlay').hide();
            }
        });
    });
</script>

<script>
// Đảm bảo cập nhật badge khi thêm sản phẩm vào giỏ hàng bằng JS
function updateCartCount(count) {
    let cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
        cartCountElement.style.display = count > 0 ? 'flex' : 'none';
    } else if (count > 0) {
        // Nếu chưa có badge, tạo mới
        const cartBtn = document.querySelector('.cart-btn');
        if (cartBtn) {
            const badge = document.createElement('span');
            badge.className = 'cart-count';
            badge.textContent = count;
            badge.style.display = 'flex';
            cartBtn.appendChild(badge);
        }
    }
}
</script>

<script>
    window.isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    
    // Debug logo loading
    document.addEventListener('DOMContentLoaded', function() {
        const logoImg = document.querySelector('.navbar-brand img');
        if (logoImg) {
            console.log('Logo element found:', logoImg);
            console.log('Logo src:', logoImg.src);
            
            logoImg.addEventListener('load', function() {
                console.log('Logo loaded successfully');
            });
            
            logoImg.addEventListener('error', function() {
                console.log('Logo failed to load');
                this.style.display = 'none';
                const fallbackText = this.nextElementSibling;
                if (fallbackText) {
                    fallbackText.style.display = 'block';
                }
            });
        } else {
            console.log('Logo element not found');
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.isLoggedIn !== 'undefined' && !window.isLoggedIn) {
            var cartBtn = document.querySelector('.cart-btn');
            if (cartBtn) {
                cartBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    showNotification('Lỗi', 'Vui lòng đăng nhập để truy cập giỏ hàng!', 'error');
                });
            }
        }
    });
</script>