<nav class="admin-sidebar" id="adminSidebar">
    <div class="position-sticky pt-3 px-3">
        <ul class="nav flex-column" style="list-style: none; padding-left: 0;">
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'focused' : '' }}" href="{{ route('admin.dashboard') }}" data-item="dashboard">
                    <i class="fas fa-home me-3"></i>
                    <span>Bảng điều khiển</span>
                </a>
            </li>

            <!-- Products with Dropdown -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center justify-content-between dropdown-toggle-custom {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.attributes.*') ? 'focused' : '' }}" href="#"
                    data-item="products" data-dropdown="products-dropdown">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-box-open me-3"></i>
                        <span>Sản phẩm</span>
                    </div>
                    <i class="fas fa-chevron-right chevron-icon {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.attributes.*') ? 'rotated' : '' }}"></i>
                </a>
                <ul class="sidebar-dropdown-menu {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.attributes.*') ? 'show' : '' }}" id="products-dropdown">
                    <li>
                        <a class="dropdown-item-custom {{ request()->routeIs('admin.products.*') ? 'focused' : '' }}" href="{{ route('admin.products.index') }}"
                            data-item="all-products">
                            <i class="fas fa-list me-3"></i>
                            <span>Danh sách</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-custom {{ request()->routeIs('admin.attributes.*') ? 'focused' : '' }}" href="{{ route('admin.attributes.index') }}"
                            data-item="add-product">
                            <i class="fas fa-plus me-3"></i>
                            <span>Biến thể</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.categories.*') ? 'focused' : '' }}" href="{{ route('admin.categories.index') }}"
                    data-item="categories">
                    <i class="fas fa-folder-open me-3"></i>
                    <span>Danh mục</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.slide.*') ? 'focused' : '' }}" href="{{ route('admin.slide.index') }}"
                    data-item="sliders">
                    <i class="fas fa-image me-3"></i>
                    <span>Thanh trượt</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.user.*') ? 'focused' : '' }}" href="{{ route('admin.user.index') }}"
                    data-item="customers">
                    <i class="fas fa-users me-3"></i>
                    <span>Tài khoản</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.orders.*') ? 'focused' : '' }}" href="{{ route('admin.orders.index') }}" data-item="orders">
                    <i class="fas fa-shopping-cart me-3"></i>
                    <span>Đơn hàng</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.reviews.*') ? 'focused' : '' }}" href="{{ route('admin.reviews.index') }}" data-item="comments">
                    <i class="fas fa-comments me-3"></i>
                    <span>Đánh giá</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.post.*') ? 'focused' : '' }}" href="{{ route('admin.post.index') }}" data-item="posts">
                    <i class="fas fa-tags me-3"></i>
                    <span>Bài viết</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.brands.*') ? 'focused' : '' }}" href="{{ route('admin.brands.index') }}" data-item="news">
                    <i class="fas fa-newspaper me-3"></i>
                    <span>Thương hiệu</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.discount.*') ? 'focused' : '' }}" href="{{ route('admin.discount.index') }}" data-item="discount">
                    <i class="fa-solid fa-tags me-3"></i>
                    <span>Giảm giá</span>
                </a>
            </li>

            <!-- Trash Dropdown -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center justify-content-between dropdown-toggle-custom {{ request()->is('admin/*/trash*') ? 'focused' : '' }}" href="#"
                    data-item="trash" data-dropdown="trash-dropdown">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-trash me-3"></i>
                        <span>Thùng rác</span>
                    </div>
                    <i class="fas fa-chevron-right chevron-icon {{ request()->is('admin/*/trash*') ? 'rotated' : '' }}"></i>
                </a>
                <ul class="sidebar-dropdown-menu {{ request()->is('admin/*/trash*') ? 'show' : '' }}" id="trash-dropdown">
                    <li>
                        <a class="dropdown-item-custom {{ request()->is('admin/products/trash*') ? 'focused' : '' }}" href="{{ route('admin.products.trash') }}"
                            data-item="trash-products">
                            <i class="fas fa-box-open me-3"></i>
                            <span>Sản phẩm</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-custom {{ request()->is('admin/categories/trash*') ? 'focused' : '' }}" href="{{ route('admin.categories.trash') }}"
                            data-item="trash-categories">
                            <i class="fas fa-folder-open me-3"></i>
                            <span>Danh mục</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-custom {{ request()->is('admin/slide/trash*') ? 'focused' : '' }}" href="{{ route('admin.slide.trash') }}"
                            data-item="trash-sliders">
                            <i class="fas fa-image me-3"></i>
                            <span>Thanh trượt</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-custom {{ request()->is('admin/post/trash*') ? 'focused' : '' }}" href="{{ route('admin.post.trash') }}" data-item="trash-posts">
                            <i class="fas fa-tags me-3"></i>
                            <span>Bài viết</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-custom {{ request()->is('admin/brands/trash*') ? 'focused' : '' }}" href="{{ route('admin.brands.trash') }}"
                            data-item="trash-brand">
                            <i class="fas fa-newspaper me-3"></i>
                            <span>Thương hiệu</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<style>
    .admin-sidebar {
        position: fixed;
        top: 70px;
        left: 0;
        width: 260px;
        height: calc(100vh - 70px);
        background-color: #111c44; /* Dark blue */
        border-right: none;
        box-shadow: 4px 0 24px rgba(17, 28, 68, 0.1);
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1020;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding-top: 1.5rem;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .admin-sidebar .nav-link {
        padding: 0.85rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 0.35rem;
        color: #a3aed1;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .admin-sidebar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.08);
        color: #ffffff;
    }

    .admin-sidebar .nav-link.focused {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        color: #ffffff !important;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(0, 242, 254, 0.3);
    }

    .admin-sidebar .nav-link i {
        color: #a3aed1;
        width: 22px;
        text-align: center;
        transition: color 0.2s ease;
    }

    .admin-sidebar .nav-link:hover i {
        color: #ffffff;
    }

    .admin-sidebar .nav-link.focused i {
        color: #ffffff !important;
    }

    .sidebar-dropdown-menu {
        display: none;
        margin: 0.25rem 0 0.5rem 0.5rem;
        list-style: none;
        padding: 0;
        border-left: 2px solid rgba(255, 255, 255, 0.1);
        padding-left: 0.5rem;
    }

    .sidebar-dropdown-menu.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .dropdown-item-custom {
        padding: 0.65rem 1.25rem;
        border-radius: 10px;
        margin-bottom: 0.2rem;
        color: #a3aed1;
        font-weight: 500;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .dropdown-item-custom:hover {
        background-color: rgba(255, 255, 255, 0.05);
        color: #ffffff;
    }

    .dropdown-item-custom.focused {
        color: #00f2fe !important;
        font-weight: 600;
        background-color: rgba(255, 255, 255, 0.05);
    }

    .dropdown-item-custom i {
        width: 20px;
        text-align: center;
        color: #a3aed1;
    }

    .dropdown-item-custom:hover i,
    .dropdown-item-custom.focused i {
        color: #00f2fe !important;
    }

    .chevron-icon {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.8rem;
    }

    .chevron-icon.rotated {
        transform: rotate(90deg);
    }

    .admin-sidebar::-webkit-scrollbar {
        width: 4px;
    }

    .admin-sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }
    
    .admin-sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    body.sidebar-collapsed .admin-sidebar {
        width: 0;
        padding: 0;
        overflow: hidden;
    }

    @media (max-width: 991.98px) {
        .admin-sidebar {
            left: -260px;
        }

        .admin-sidebar.show {
            left: 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navLinks = document.querySelectorAll('.admin-sidebar .nav-link');

        // Xử lý click cho dropdown toggle
        navLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                const dropdownId = this.getAttribute('data-dropdown');
                if (dropdownId) {
                    e.preventDefault();
                    const dropdown = document.getElementById(dropdownId);
                    const chevron = this.querySelector('.chevron-icon');
                    dropdown.classList.toggle('show');
                    chevron.classList.toggle('rotated');
                }
            });
        });
    });

    const logoutButton = document.querySelector('form[action*="logout"] button[type="submit"]');
    if (logoutButton) {
        logoutButton.addEventListener('click', function () {
            localStorage.removeItem('sidebar-focused');
        });
    }
</script>