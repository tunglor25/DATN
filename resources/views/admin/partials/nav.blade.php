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
                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.user.edit', Auth::id()) }}"><i class="fas fa-user-edit me-2"></i> Profile</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
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
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
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
        height: 60px;
        z-index: 1030;
        background-color: white;
        border-bottom: 1px solid #e0e0e0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        padding: 0.5rem 1.5rem;
        transition: all 0.3s ease;
    }

    .navbar-brand {
        font-weight: 600;
        color: #333 !important;
        font-size: 1.2rem;
        white-space: nowrap;
    }

    .navbar-brand i {
        color: #555;
    }

    .btn-toggle {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .btn-toggle:hover {
        background: #f5f5f5;
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
        height: 2px;
        width: 100%;
        background: #333;
        border-radius: 2px;
        transition: all 0.25s ease;
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
        min-width: 150px;
    }

    .search-input {
        border: 1px solid #e0e0e0;
        border-radius: 20px 0 0 20px !important;
        padding: 0.375rem 1rem;
        height: 38px;
    }

    .btn-search {
        background: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-left: none !important;
        border-radius: 0 20px 20px 0 !important;
        height: 38px;
        width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .user-dropdown .btn {
        border: 1px solid #e0e0e0;
        color: #333;
        background: transparent;
        border-radius: 20px;
        padding: 0.375rem 1rem;
        white-space: nowrap;
        height: 38px;
    }

    .dropdown-menu {
        border: 1px solid #e0e0e0;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .dropdown-item {
        color: #333;
        transition: all 0.2s;
    }

    .dropdown-item:hover {
        background: #f8f9fa;
        color: #000;
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
        min-width: 200px;
        z-index: 1000;
        margin-top: 0.5rem;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        background: white;
    }

    .dropdown-menu.show {
        display: block;
        animation: fadeInDown 0.2s ease-out;
    }

    .dropdown-toggle .fa-chevron-down {
        transition: transform 0.2s ease;
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

    .dropdown-item {
        padding: 0.5rem 1rem;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        cursor: pointer;
        transition: all 0.2s;
    }

    .dropdown-item:hover {
        background: #f8f9fa;
        color: #000;
    }

    .dropdown-divider {
        margin: 0.5rem 0;
        border-top: 1px solid #e0e0e0;
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