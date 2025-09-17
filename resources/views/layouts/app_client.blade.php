<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SneakerHub - Premium Footwear')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --primary-color: #ff6b6b;
            --primary-hover: #ff5252;
            --dark-color: #111;
            --light-color: #f8f9fa;
            --border-radius: 8px;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-color);
            color: #333;
            padding-top: 72px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        #nprogress .bar {
            background: var(--primary-color);
            height: 3px;
        }

        #nprogress .peg {
            box-shadow: 0 0 10px var(--primary-color), 0 0 5px var(--primary-color);
        }

        /* Notification System */
        .notification-container {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .notification {
            position: relative;
            display: flex;
            align-items: flex-start;
            width: 320px;
            padding: 16px;
            border-radius: var(--border-radius);
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            transform: translateX(-110%);
            opacity: 0;
            animation: slideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .notification.hide {
            animation: slideOut 0.4s cubic-bezier(0.7, 0, 0.3, 1) forwards;
        }

        @keyframes slideIn {
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            to {
                transform: translateX(-110%);
                opacity: 0;
            }
        }

        .notification::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-color);
        }

        .notification-icon {
            flex-shrink: 0;
            margin-right: 12px;
            font-size: 20px;
            color: var(--primary-color);
        }

        .notification-content {
            flex-grow: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 4px;
            color: var(--dark-color);
        }

        .notification-message {
            font-size: 14px;
            color: #666;
            line-height: 1.4;
        }

        .notification-close {
            flex-shrink: 0;
            margin-left: 12px;
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .notification-close:hover {
            color: var(--dark-color);
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }

            .notification-container {
                left: 10px;
                right: 10px;
                width: calc(100% - 20px);
            }

            .notification {
                width: 100%;
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>

    @yield('styles')
</head>

<body>
    @include('client.partials.nav')

    <!-- Notification Container -->
    <div class="notification-container" id="notification-container"></div>

    <div class="wrapper fade-in">
        @include('client.partials.slide')
        <main class="main-content">
            @yield('content')
        </main>
        <a href="https://zalo.me/0973454928" target="_blank"
            style="position: fixed; bottom: 40px; right: 20px; z-index: 9999;">
            <img src="{{ asset('storage/img/zalo.jpg') }}"
            alt="Zalo" style="width:60px; height:60px;">
        </a>
        @include('client.partials.footer')
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>

    <script>
        function showNotification(title, message, type = 'error', duration = 5000) {
            const container = document.getElementById('notification-container');
            const notificationId = 'notification-' + Date.now();

            const typeConfig = {
                error: { icon: 'exclamation-circle', color: '#ff6b6b' },
                success: { icon: 'check-circle', color: '#4BB543' },
                warning: { icon: 'exclamation-triangle', color: '#FFCC00' },
                info: { icon: 'info-circle', color: '#17a2b8' }
            };

            const config = typeConfig[type] || typeConfig.error;

            const notification = document.createElement('div');
            notification.id = notificationId;
            notification.className = 'notification';
            notification.style.setProperty('--primary-color', config.color);

            notification.innerHTML = `
                <i class="notification-icon fas fa-${config.icon}"></i>
                <div class="notification-content">
                    <div class="notification-title">${title}</div>
                    <div class="notification-message">${message}</div>
                </div>
                <button class="notification-close">
                    <i class="fas fa-times"></i>
                </button>
            `;

            container.appendChild(notification);

            const closeBtn = notification.querySelector('.notification-close');
            closeBtn.addEventListener('click', () => closeNotification(notificationId));

            if (duration > 0) {
                setTimeout(() => closeNotification(notificationId), duration);
            }
        }

        function closeNotification(id) {
            const notification = document.getElementById(id);
            if (notification) {
                notification.classList.add('hide');
                notification.addEventListener('animationend', () => {
                    notification.remove();
                }, { once: true });
            }
        }

        @if(session('error'))
            showNotification('Error', '{{ session('error') }}', 'error');
        @endif

        @if(session('success'))
            showNotification('Success', '{{ session('success') }}', 'success');
        @endif

        @if(session('info'))
            showNotification('Info', '{{ session('info') }}', 'info');
        @endif

        @if(isset($errors) && $errors->any())
            @foreach($errors->all() as $error)
                showNotification('Lỗi', '{{ $error }}', 'error');
            @endforeach
        @endif


        document.addEventListener('ajaxError', function (e) {
            const error = e.detail.error;
            const message = error.responseJSON?.message || error.message || 'An error occurred';
            showNotification('Error', message, 'error');
        });
    </script>

    @yield('scripts')
</body>

</html>