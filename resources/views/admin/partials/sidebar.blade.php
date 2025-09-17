<nav class="admin-sidebar" id="adminSidebar">
    <div class="position-sticky pt-3 px-3">
        <ul class="nav flex-column" style="list-style: none; padding-left: 0;">
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center" href="{{ route('admin.dashboard') }}" data-item="dashboard">
                    <i class="fas fa-home me-3"></i>
                    <span>Bảng điều khiển</span>
                </a>
            </li>

            <!-- Products with Dropdown -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center justify-content-between dropdown-toggle-custom" href="#"
                    data-item="products" data-dropdown="products-dropdown">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-box-open me-3"></i>
                        <span>Sản phẩm</span>
                    </div>
                    <i class="fas fa-chevron-right chevron-icon"></i>
                </a>
                <ul class="sidebar-dropdown-menu" id="products-dropdown">
                    <li>
                        <a class="dropdown-item-custom" href="{{ route('admin.products.index') }}"
                            data-item="all-products">
                            <i class="fas fa-list me-3"></i>
                            <span>Danh sách</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-custom" href="{{ route('admin.attributes.index') }}"
                            data-item="add-product">
                            <i class="fas fa-plus me-3"></i>
                            <span>Biến thể</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center" href="{{ route('admin.categories.index') }}"
                    data-item="categories">
                    <i class="fas fa-folder-open me-3"></i>
                    <span>Danh mục</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center" href="{{ route('admin.slide.index') }}"
                    data-item="sliders">
                    <i class="fas fa-image me-3"></i>
                    <span>Thanh trượt</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center" href="{{ route('admin.user.index') }}"
                    data-item="customers">
                    <i class="fas fa-users me-3"></i>
                    <span>Tài khoản</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center" href="{{ route('admin.orders.index') }}" data-item="orders">
                    <i class="fas fa-shopping-cart me-3"></i>
                    <span>Đơn hàng</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center" href="{{ route('admin.reviews.index') }}" data-item="comments">
                    <i class="fas fa-comments me-3"></i>
                    <span>Đánh giá</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center" href="{{ route('admin.post.index') }}" data-item="posts">
                    <i class="fas fa-tags me-3"></i>
                    <span>Bài viết</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center" href="{{ route('admin.brands.index') }}" data-item="news">
                    <i class="fas fa-newspaper me-3"></i>
                    <span>Thương hiệu</span>
                </a>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center" href="{{ route('admin.discount.index') }}" data-item="discount">
                    <i class="fa-solid fa-tags me-3"></i>
                    <span>Giảm giá</span>
                </a>
            </li>

            <!-- Trash Dropdown -->
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center justify-content-between dropdown-toggle-custom" href="#"
                    data-item="trash" data-dropdown="trash-dropdown">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-trash me-3"></i>
                        <span>Thùng rác</span>
                    </div>
                    <i class="fas fa-chevron-right chevron-icon"></i>
                </a>
                <ul class="sidebar-dropdown-menu" id="trash-dropdown">
                    <li>
                        <a class="dropdown-item-custom" href="{{ route('admin.products.trash') }}"
                            data-item="trash-products">
                            <i class="fas fa-box-open me-3"></i>
                            <span>Sản phẩm</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-custom" href="{{ route('admin.categories.trash') }}"
                            data-item="trash-categories">
                            <i class="fas fa-folder-open me-3"></i>
                            <span>Danh mục</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-custom" href="{{ route('admin.slide.trash') }}"
                            data-item="trash-sliders">
                            <i class="fas fa-image me-3"></i>
                            <span>Thanh trượt</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-custom" href="{{ route('admin.post.trash') }}" data-item="trash-posts">
                            <i class="fas fa-tags me-3"></i>
                            <span>Bài viết</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-custom" href="{{ route('admin.brands.trash') }}"
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
        top: 60px;
        left: 0;
        width: 250px;
        height: calc(100vh - 60px);
        background-color: white;
        border-right: 1px solid #e0e0e0;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1020;
        transition: all 0.3s ease;
        padding-top: 1rem;
    }

    .admin-sidebar .nav-link {
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 0.2rem;
        color: #555;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .admin-sidebar .nav-link:hover {
        background-color: #f8f9fa;
        color: #333;
    }

    .admin-sidebar .nav-link.focused {
        background-color: #d1ecf1 !important;
        color: #0c5460 !important;
        font-weight: 600;
    }

    .admin-sidebar .nav-link i {
        color: #666;
        width: 20px;
        text-align: center;
    }

    .admin-sidebar .nav-link.focused i {
        color: #0c5460 !important;
    }

    .sidebar-dropdown-menu {
        display: none;
        margin: 0.25rem 0 0.5rem 1.25rem;
        list-style: none;
        padding: 0;
    }

    .sidebar-dropdown-menu.show {
        display: block;
    }

    .dropdown-item-custom {
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 0.2rem;
        color: #555;
        font-weight: 500;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .dropdown-item-custom:hover {
        background-color: #f8f9fa;
        color: #333;
    }

    .dropdown-item-custom.focused {
        background-color: #d1ecf1 !important;
        color: #0c5460 !important;
        font-weight: 600;
    }

    .dropdown-item-custom i {
        width: 20px;
        text-align: center;
        color: #666;
    }

    .dropdown-item-custom.focused i {
        color: #0c5460 !important;
    }

    .chevron-icon {
        transition: transform 0.2s ease;
    }

    .chevron-icon.rotated {
        transform: rotate(90deg);
    }

    .admin-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .admin-sidebar::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 3px;
    }

    body.sidebar-collapsed .admin-sidebar {
        width: 0;
        overflow: hidden;
    }

    @media (max-width: 991.98px) {
        .admin-sidebar {
            left: -250px;
        }

        .admin-sidebar.show {
            left: 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navLinks = document.querySelectorAll('.admin-sidebar .nav-link');
        const dropdownItems = document.querySelectorAll('.dropdown-item-custom');

        // Lưu trạng thái focus
        function saveFocusState(itemId) {
            localStorage.setItem('sidebar-focused', itemId);
        }

        // Lấy trạng thái focus
        function getFocusState() {
            return localStorage.getItem('sidebar-focused');
        }

        // Áp dụng focus state
        function setFocusState(element) {
            navLinks.forEach(l => l.classList.remove('focused'));
            dropdownItems.forEach(item => item.classList.remove('focused'));
            element.classList.add('focused');

            const itemId = element.getAttribute('data-item');
            if (itemId) {
                saveFocusState(itemId);
            }
        }

        // Xử lý click cho nav-link
        navLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                const dropdownId = this.getAttribute('data-dropdown');

                if (dropdownId) {
                    e.preventDefault();

                    // Toggle dropdown
                    const dropdown = document.getElementById(dropdownId);
                    const chevron = this.querySelector('.chevron-icon');

                    dropdown.classList.toggle('show');
                    chevron.classList.toggle('rotated');
                }

                // Set focused state
                setFocusState(this);
            });
        });

        // Xử lý click cho dropdown items
        dropdownItems.forEach(item => {
            item.addEventListener('click', function () {
                setFocusState(this);
            });
        });

        // Khôi phục trạng thái khi load trang
        const currentPath = window.location.pathname;
        const savedFocusState = getFocusState();
        let foundActive = false;

        // Ưu tiên current path trước
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                setFocusState(link);
                foundActive = true;
            }
        });

        dropdownItems.forEach(item => {
            if (item.getAttribute('href') === currentPath) {
                setFocusState(item);
                foundActive = true;

                // Mở parent dropdown
                const parentDropdown = item.closest('.sidebar-dropdown-menu');
                if (parentDropdown) {
                    parentDropdown.classList.add('show');
                    const parentButton = document.querySelector(`[data-dropdown="${parentDropdown.id}"]`);
                    if (parentButton) {
                        parentButton.querySelector('.chevron-icon').classList.add('rotated');
                    }
                }
            }
        });

        // Nếu không tìm thấy current path, dùng saved state
        if (!foundActive && savedFocusState) {
            const savedElement = document.querySelector(`[data-item="${savedFocusState}"]`);
            if (savedElement) {
                savedElement.classList.add('focused');

                // Nếu là dropdown item, mở parent dropdown
                if (savedElement.classList.contains('dropdown-item-custom')) {
                    const parentDropdown = savedElement.closest('.sidebar-dropdown-menu');
                    if (parentDropdown) {
                        parentDropdown.classList.add('show');
                        const parentButton = document.querySelector(`[data-dropdown="${parentDropdown.id}"]`);
                        if (parentButton) {
                            parentButton.querySelector('.chevron-icon').classList.add('rotated');
                        }
                    }
                }
            }
        }
    });
    const logoutButton = document.querySelector('form[action*="logout"] button[type="submit"]');

    if (logoutButton) {
        logoutButton.addEventListener('click', function () {
            localStorage.removeItem('sidebar-focused');
        });
    }
</script>