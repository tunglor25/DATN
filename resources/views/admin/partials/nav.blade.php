<nav class="admin-navbar">
    <div class="container-fluid d-flex flex-nowrap align-items-center">
        <div class="d-flex align-items-center flex-shrink-0">
            <button class="btn btn-toggle me-3" id="sidebarToggle" aria-label="Toggle sidebar">
                <span class="toggle-icon">
                    <span class="toggle-bar"></span>
                    <span class="toggle-bar"></span>
                    <span class="toggle-bar"></span>
                </span>
            </button>
            <a class="navbar-brand me-4" href="">
                <i class="fas fa-shield-alt me-2"></i>ADMIN PANEL
            </a>
        </div>

        <form class="search-box flex-grow-1 mx-2" style="max-width: 400px;">
            <div class="input-group">
                <input class="form-control search-input" type="search" placeholder="Search..." aria-label="Search">
                <button class="btn btn-search" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <div class="user-dropdown flex-shrink-0 ms-auto">
            <button class="btn dropdown-toggle d-flex align-items-center" type="button" id="userDropdown">
                <i class="fas fa-user-circle me-2"></i> {{ Auth::user()->name ?? 'Admin User' }}
                <i class="fas fa-chevron-down ms-2"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" id="userDropdownMenu">
                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i> Bảng điều khiển</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.user.edit', Auth::id()) }}"><i class="fas fa-user-edit me-2"></i> Hồ sơ cá nhân</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank"><i class="fas fa-external-link-alt me-2"></i> Xem website</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                    </button>
                </form>
            </ul>
        </div>
    </div>
</nav>

<style>
    .admin-navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 70px;
        z-index: 1030;
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 4px 20px rgba(112, 144, 176, 0.05);
        padding: 0 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
    }

    .navbar-brand {
        font-weight: 800;
        color: #2b3674 !important;
        font-size: 1.3rem;
        white-space: nowrap;
        letter-spacing: -0.5px;
    }

    .navbar-brand i {
        color: #4facfe;
    }

    .btn-toggle {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        border-radius: 12px;
        transition: all 0.2s;
        background-color: #f4f7fe;
    }

    .btn-toggle:hover {
        background: #e2e8f0;
    }

    .toggle-icon {
        width: 20px;
        height: 20px;
        display: block;
        position: relative;
    }

    .toggle-bar {
        display: block;
        position: absolute;
        height: 2.5px;
        width: 100%;
        background: #2b3674;
        border-radius: 4px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .toggle-bar:nth-child(1) {
        top: 4px;
    }

    .toggle-bar:nth-child(2) {
        top: 10px;
    }

    .toggle-bar:nth-child(3) {
        top: 16px;
    }

    #sidebarToggle.active .toggle-bar:nth-child(1) {
        top: 10px;
        transform: rotate(45deg);
    }

    #sidebarToggle.active .toggle-bar:nth-child(2) {
        opacity: 0;
    }

    #sidebarToggle.active .toggle-bar:nth-child(3) {
        top: 10px;
        transform: rotate(-45deg);
    }

    .search-box {
        min-width: 250px;
    }

    .search-input {
        border: 1px solid #e2e8f0;
        border-radius: 50px 0 0 50px !important;
        padding: 0.5rem 1.25rem;
        height: 42px;
        background-color: #f8fafc;
        font-size: 0.95rem;
        box-shadow: none !important;
    }
    
    .search-input:focus {
        border-color: #4facfe;
        background-color: #ffffff;
    }

    .btn-search {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: none !important;
        border-radius: 0 50px 50px 0 !important;
        height: 42px;
        width: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        color: #a0aec0;
    }

    .user-dropdown .btn {
        border: none;
        color: #2b3674;
        background: #f4f7fe;
        border-radius: 50px;
        padding: 0.5rem 1.25rem;
        white-space: nowrap;
        height: 42px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    
    .user-dropdown .btn:hover {
        background: #e2e8f0;
        transform: translateY(-1px);
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 10px 40px -10px rgba(112, 144, 176, 0.2);
    }

    .dropdown-item {
        color: #64748b;
        transition: all 0.2s;
        font-weight: 500;
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        margin: 0.25rem 0.5rem;
        width: calc(100% - 1rem);
    }

    .dropdown-item:hover {
        background: #f4f7fe;
        color: #2b3674;
    }

    /* Custom dropdown styles */
    .user-dropdown {
        position: relative;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        min-width: 220px;
        z-index: 1000;
        margin-top: 1rem;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
    }

    .dropdown-menu.show {
        display: block;
        animation: fadeInDown 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .dropdown-toggle .fa-chevron-down {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.8rem;
    }

    .dropdown-toggle.active .fa-chevron-down {
        transform: rotate(180deg);
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dropdown-divider {
        margin: 0.5rem 0;
        border-top: 1px solid #e2e8f0;
    }

    @media (max-width: 991.98px) {
        .navbar-brand {
            font-size: 1.1rem;
            margin-right: 1rem !important;
        }

        .search-box {
            max-width: 200px !important;
        }
    }

    @media (max-width: 767.98px) {
        .search-box {
            display: none !important;
        }

        .user-dropdown .btn {
            padding: 0.375rem 0.75rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userDropdown = document.getElementById('userDropdown');
        const userDropdownMenu = document.getElementById('userDropdownMenu');
        
        if (userDropdown && userDropdownMenu) {
            // Toggle dropdown khi click
            userDropdown.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                userDropdownMenu.classList.toggle('show');
                userDropdown.classList.toggle('active');
            });
            
            // Đóng dropdown khi click ra ngoài
            document.addEventListener('click', function(e) {
                if (!userDropdown.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                    userDropdownMenu.classList.remove('show');
                    userDropdown.classList.remove('active');
                }
            });
            
            // Đóng dropdown khi click vào item
            userDropdownMenu.addEventListener('click', function(e) {
                if (e.target.classList.contains('dropdown-item')) {
                    userDropdownMenu.classList.remove('show');
                    userDropdown.classList.remove('active');
                }
            });
            
            // Đóng dropdown khi nhấn ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    userDropdownMenu.classList.remove('show');
                    userDropdown.classList.remove('active');
                }
            });
        }
    });
</script>