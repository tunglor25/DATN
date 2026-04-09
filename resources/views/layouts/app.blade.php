<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chartist@0.11.4/dist/chartist.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-styles.css') }}">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fe;
            color: #2b3674;
            padding-top: 70px;
            padding-left: 260px;
            transition: padding-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.sidebar-collapsed {
            padding-left: 0;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 24px;
            background-color: transparent;
            margin: 20px;
            border-radius: 0;
            box-shadow: none;
        }

        #nprogress .bar {
            background: #333;
            height: 2px;
            position: fixed;
            z-index: 9999;
            top: 0;
            left: 0;
            width: 100%;
        }

        .sidebar-overlay {
            position: fixed;
            top: 60px;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1015;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        @media (max-width: 991.98px) {
            body {
                padding-left: 0;
            }

            .main-content {
                margin: 15px;
            }
        }

        @media (max-width: 767.98px) {
            .main-content {
                margin: 10px;
                padding: 15px;
            }
        }






    </style>
    <style>
        @include('admin.partials.admin-styles')
    </style>
    @yield('styles')
</head>

<body>
   @include('admin.partials.nav')
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    @include('admin.partials.sidebar')

    <div class="wrapper">
        <main class="main-content">
            @yield('content')
        </main>
        @include('admin.partials.footer')
    </div>
    @include('auth.auth_modals')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartist@0.11.4/dist/chartist.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        // Initialize NProgress
        NProgress.configure({ showSpinner: false });
        window.addEventListener('beforeunload', () => NProgress.start());
        window.addEventListener('load', () => NProgress.done());

        // Sidebar functionality
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('adminSidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const body = document.body;

            sidebarToggle?.addEventListener('click', function () {
                if (window.innerWidth <= 991.98) {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                    sidebarToggle.classList.toggle('active');
                } else {
                    body.classList.toggle('sidebar-collapsed');
                    sidebarToggle.classList.toggle('active');
                }
            });

            sidebarOverlay?.addEventListener('click', function () {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                sidebarToggle.classList.remove('active');
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 991.98) {
                    sidebar?.classList.remove('show');
                    sidebarOverlay?.classList.remove('show');
                }
            });
        });
    </script>
    @yield('scripts')

    <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Hiển thị alert từ session -->
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Thành công!',
        text: '{{ session('success') }}',
        width: '400',
        timer: 1500,
        showConfirmButton: false,
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Lỗi!',
        text: '{{ session('error') }}',
        width: '400',
        timer: 1500,
        showConfirmButton: false,
    });
</script>
@endif

</body>

</html>