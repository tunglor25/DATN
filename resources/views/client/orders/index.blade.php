@extends('layouts.app_client')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">đơn hàng</li>
        </ol>
    </nav>

    <div class="row content">
        <!-- Left Sidebar -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="user-profile-card">
                <div class="d-flex align-items-center mb-3">
                    <div class="user-avatar me-3">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        @if (Auth::check())
                            <h6 class="mb-1 fw-bold">{{ Auth::user()->name }}</h6>
                            <small class="text-muted">{{ Auth::user()->phone ?? 'Chưa cập nhật số điện thoại' }}</small>
                        @else
                            <h6 class="mb-1 fw-bold">Khách</h6>
                            <small class="text-muted">Vui lòng đăng nhập</small>
                        @endif
                    </div>
                </div>
                <a href="{{ route('orders.index') }}" class="text-primary text-decoration-none small">Xem đơn hàng</a>
            </div>

            <div class="promo-card">
                <div class="d-flex">
                    <div class="flex-grow-1 me-3">
                        <p class="small mb-2 fw-bold">Quý khách là thành viên tại TLO Fashion</p>
                        <p class="small text-muted mb-3">Quan tâm TLO Shop để kích hoạt điểm thưởng</p>
                    </div>
                    <div class="promo-image">
                        <i class="fas fa-gift"></i>
                    </div>
                </div>
            </div>

            <div class="sidebar">
            <ul class="nav-menu">
                <li><a href="{{ route('orders.index') }}"><i class="fas fa-box"></i> Đơn hàng của tôi</a></li>
                <li><a href="{{ route('wishlist.index') }}"><i class="fas fa-heart"></i> Sản phẩm yêu thích</a></li>
                <li><a href="{{ route('addresses.index') }}""><i class="fas fa-map-marker-alt"></i> Sổ địa chỉ</a></li>
                <li><a href="{{ route('discounts.my-discounts') }}"><i class="fas fa-wallet"></i> Mã của tôi</a></li>
                <li><a href="{{ route('logout') }}" class="text-danger"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
            </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">
            <div class="main-content">
                <!-- Order Status Tabs -->
                <ul class="nav nav-tabs order-tabs" id="orderTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#all-orders">Tất cả</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#pending">Chờ xác nhận</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#confirmed">Đã xác nhận</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#processing">Chờ lấy hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#shipped">Đang giao</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#delivered">Đã giao</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#cancelled">Đã hủy</a>
                    </li>
                </ul>

                <!-- Search Box -->
                <div class="search-box">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-search text-muted me-2"></i>
                        <input type="text" placeholder="Tìm kiếm theo Tên Shop, ID đơn hàng hoặc Tên Sản phẩm"
                            class="form-control-plaintext" id="searchInput">
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Loading Spinner -->
                    <div id="loading-spinner" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p class="mt-3 text-muted">Đang tải đơn hàng...</p>
                    </div>

                    <!-- All Orders -->
                    <div class="tab-pane fade show active" id="all-orders">
                        <div id="orders-content">
                            @if (Auth::check())
                                @forelse ($orders as $status => $orderGroup)
                                    @foreach ($orderGroup as $order)
                                        @if ($order && $order instanceof \App\Models\Order)
                                            @include('client.orders._order_item', ['order' => $order, 'status' => $status])
                                        @endif
                                    @endforeach
                                @empty
                                    <div class="text-center py-5">
                                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Không có đơn hàng nào</p>
                                    </div>
                                @endforelse
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Vui lòng đăng nhập để xem đơn hàng</p>
                                    <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Pending Orders -->
                    <div class="tab-pane fade" id="pending">
                        <div id="orders-content-pending"></div>
                    </div>

                    <!-- Confirmed Orders -->
                    <div class="tab-pane fade" id="confirmed">
                        <div id="orders-content-confirmed"></div>
                    </div>

                    <!-- Processing Orders -->
                    <div class="tab-pane fade" id="processing">
                        <div id="orders-content-processing"></div>
                    </div>

                    <!-- Shipped Orders -->
                    <div class="tab-pane fade" id="shipped">
                        <div id="orders-content-shipped"></div>
                    </div>

                    <!-- Delivered Orders -->
                    <div class="tab-pane fade" id="delivered">
                        <div id="orders-content-delivered"></div>
                    </div>

                    <!-- Cancelled Orders -->
                    <div class="tab-pane fade" id="cancelled">
                        <div id="orders-content-cancelled"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .content {
        border-radius: 10px;
        padding: 10px 0px;
    }

    .sidebar {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .user-profile-card {
        background-color: #fff5f5;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        background-color: #ff6b35;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .promo-card {
        background-color: #fff5f5;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #ffe6e6;
    }

    .nav-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-menu li {
        border-bottom: 1px solid #f0f0f0;
    }

    .nav-menu li:last-child {
        border-bottom: none;
    }

    .nav-menu a {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        text-decoration: none;
        color: #333;
        transition: all 0.3s ease;
    }

    .nav-menu a:hover {
        background-color: #f8f9fa;
        border-left: 3px solid #ff6b35;
    }
    .nav-menu a.active {
        background-color: #f8f9fa;
        border-left: 3px solid #ff6b35;
    }

    .nav-menu i {
        margin-right: 12px;
        width: 20px;
        color: #666;
    }

    .main-content {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .btn-promo {
        background-color: #dc3545;
        border-color: #dc3545;
        font-size: 12px;
        padding: 6px 12px;
    }

    .breadcrumb-item a {
        color: #007bff;
        text-decoration: none;
    }

    .promo-image {
        width: 50px;
        height: 60px;
        background: linear-gradient(45deg, #ff6b35, #ffa500);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .order-tabs {
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 20px;
    }

    .order-tabs .nav-link {
        border: none;
        color: #666;
        padding: 15px 20px;
        font-weight: 500;
        border-bottom: 2px solid transparent;
    }

    .order-tabs .nav-link.active {
        color: #ff6b35;
        border-bottom-color: #ff6b35;
        background: none;
    }

    .search-box {
        background-color: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 20px;
    }

    .search-box input {
        border: none;
        background: none;
        outline: none;
        width: 100%;
    }

    .order-item {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 15px;
        overflow: hidden;
    }

    .order-header {
        background-color: #f8f9fa;
        padding: 12px 20px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .store-info {
        display: flex;
        align-items: center;
    }

    .order-details {
        text-align: right;
    }

    .store-badge {
        background-color: #28a745;
        color: white;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 3px;
        margin-left: 8px;
    }

    .official-badge {
        background-color: #007bff;
    }

    .order-body {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .order-body:last-of-type {
        border-bottom: none;
    }

    .product-info {
        display: flex;
    }

    .product-image {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        margin-right: 15px;
        object-fit: cover;
    }

    .product-details {
        flex: 1;
    }

    .product-name {
        font-weight: 500;
        margin-bottom: 5px;
        color: #333;
    }

    .product-attributes-container {
        margin-top: 5px;
    }

    .total-variant {
        margin-bottom: 5px;
    }

    .product-quantity, .order-number, .product-size, .product-color, .attribute {
        color: #666;
        font-size: 14px; /* Đảm bảo tất cả có cùng kích thước */
    }

    .product-attributes {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .product-price {
        text-align: right;
    }

    .original-price {
        text-decoration: line-through;
        color: #999;
        font-size: 14px;
    }

    .current-price {
        color: #ff6b35;
        font-weight: 500;
        font-size: 16px;
    }

    .order-footer {
        border-top: 1px solid #e0e0e0;
        padding: 15px 20px;
        background-color: #fafafa;
    }

    .order-total {
        text-align: right;
        margin-bottom: 15px;
    }

    .total-label {
        color: #666;
        margin-right: 10px;
    }

    .total-amount {
        color: #ff6b35;
        font-size: 18px;
        font-weight: 600;
    }

    .order-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .seller-note {
        color: #007bff;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
    }

    .seller-note:hover {
        text-decoration: underline;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-outline-primary {
        border-color: #007bff;
        color: #007bff;
    }

    .btn-outline-success {
        border-color: #28a745;
        color: #28a745;
    }

    .btn-outline-success:disabled {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .buy-again {
        background-color: #e5470d;
        border-color: #e5470d;
        color: white;
        font-weight: 400;
    }

    .buy-again:hover {
        background-color: #e4460db3;
        border-color: #e4460db3;
        color: white;
        font-weight: 400;
    }

    .btn-pay {
        background: linear-gradient(135deg, #00dd9e 0%, #00bf89 100%);
        color: white;
        font-weight: 400;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 14px;
    }

    .btn {
        font-weight: 400;
    }

    .additional-products {
        display: none;
    }

    .additional-products.show {
        display: block;
        animation: slideDown 0.3s ease-in-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
        }
        to {
            opacity: 1;
            max-height: 1000px;
        }
    }

    /* Loading spinner styles */
    #loading-spinner {
        position: relative;
        z-index: 1000;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    /* Tab transition effects */
    .tab-pane {
        transition: opacity 0.3s ease-in-out;
    }

    .tab-pane.fade {
        opacity: 0;
    }

    .tab-pane.fade.show {
        opacity: 1;
    }

    /* Error message styles */
    .error-message {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Global variables
    let currentTab = 'all';
    let isLoading = false;
    let loadedTabs = new Set(['all']); // Track which tabs have been loaded

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial active tab
        setActiveTab('all');
        
        // Ensure "all" tab is active by default
        const allTab = document.querySelector('[href="#all-orders"]');
        if (allTab) {
            allTab.classList.add('active');
        }
        
        // Add event listeners for tab switching
        document.querySelectorAll('.order-tabs .nav-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                const status = this.getAttribute('href').replace('#', '');
                switchTab(status);
            });
        });

        // Initialize other event listeners
        initializeEventListeners();
    });

    // Switch tab function
    function switchTab(status) {
        if (isLoading || currentTab === status) return;
        
        currentTab = status;
        setActiveTab(status);
        
        // Show loading spinner
        showLoading();
        
        // Load content if not already loaded
        if (!loadedTabs.has(status)) {
            loadOrdersByStatus(status);
        } else {
            // Just show the content
            showTabContent(status);
            hideLoading();
        }
    }

    // Set active tab
    function setActiveTab(status) {
        // Remove active class from all tabs
        document.querySelectorAll('.order-tabs .nav-link').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remove active class from all tab panes
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('show', 'active');
        });
        
        // Add active class to current tab
        const activeTab = document.querySelector(`[href="#${status}"]`);
        if (activeTab) {
            activeTab.classList.add('active');
        }
        
        // Show current tab pane
        const activePane = document.getElementById(status === 'all' ? 'all-orders' : status);
        if (activePane) {
            activePane.classList.add('show', 'active');
        }
    }

    // Load orders by status via AJAX
    function loadOrdersByStatus(status) {
        const url = `{{ route('orders.ajax', ':status') }}`.replace(':status', status);
        
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            // Update content
            const contentId = status === 'all' ? 'orders-content' : `orders-content-${status}`;
            const contentElement = document.getElementById(contentId);
            
            if (contentElement) {
                contentElement.innerHTML = data.html;
            }
            
            // Mark tab as loaded
            loadedTabs.add(status);
            
            // Hide loading and show content
            hideLoading();
            showTabContent(status);
            
            // Re-initialize event listeners for new content
            initializeEventListeners();
        })
        .catch(error => {
            console.error('Error loading orders:', error);
            hideLoading();
            showErrorMessage(status);
        });
    }

    // Show loading spinner
    function showLoading() {
        const spinner = document.getElementById('loading-spinner');
        if (spinner) {
            spinner.style.display = 'block';
        }
    }

    // Hide loading spinner
    function hideLoading() {
        const spinner = document.getElementById('loading-spinner');
        if (spinner) {
            spinner.style.display = 'none';
        }
    }

    // Show tab content
    function showTabContent(status) {
        // Hide all tab panes
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.remove('show', 'active');
        });
        
        // Show current tab pane
        const activePane = document.getElementById(status === 'all' ? 'all-orders' : status);
        if (activePane) {
            activePane.classList.add('show', 'active');
        }
    }

    // Show error message
    function showErrorMessage(status) {
        const contentId = status === 'all' ? 'orders-content' : `orders-content-${status}`;
        const contentElement = document.getElementById(contentId);
        
        if (contentElement) {
            contentElement.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p class="text-muted">Có lỗi xảy ra khi tải dữ liệu. Vui lòng thử lại.</p>
                    <button class="btn btn-primary" onclick="loadOrdersByStatus('${status}')">Thử lại</button>
                </div>
            `;
        }
    }

    // Initialize event listeners
    function initializeEventListeners() {
        // Use event delegation for better performance and safety
        const ordersContainer = document.querySelector('.tab-content');
        
        if (ordersContainer) {
            // Remove existing event listeners by using event delegation
            ordersContainer.removeEventListener('click', handleOrderEvents);
            ordersContainer.addEventListener('click', handleOrderEvents);
        }

        // Search functionality - only initialize once
        const searchInput = document.querySelector('#searchInput');
        if (searchInput && !searchInput.hasAttribute('data-initialized')) {
            searchInput.setAttribute('data-initialized', 'true');
            searchInput.addEventListener('input', handleSearch);
        }
    }

    // Event delegation handler for all order-related events
    function handleOrderEvents(e) {
        const target = e.target;
        
        // Handle show more button
        if (target.closest('.show-more-btn')) {
            e.preventDefault();
            e.stopPropagation();
            toggleProducts(target.closest('.show-more-btn'));
            return;
        }
        
        // Handle cancel order button
        if (target.closest('.cancel-order')) {
            if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
                e.preventDefault();
            }
            return;
        }
        
        // Handle buy again button
        if (target.closest('.buy-again')) {
            e.preventDefault();
            handleBuyAgain(target.closest('.buy-again'));
            return;
        }

        // Handle continue VNPay payment button
        if (target.closest('.continue-vnpay')) {
            e.preventDefault();
            handleContinueVnPay(target.closest('.continue-vnpay'));
            return;
        }
    }

    // Handle search functionality
    function handleSearch(e) {
        const searchTerm = e.target.value.toLowerCase();
        const orderItems = document.querySelectorAll('.order-item');

        orderItems.forEach(item => {
            const productNames = item.querySelectorAll('.product-name');
            const storeName = item.querySelector('.store-info strong')?.textContent.toLowerCase() || '';
            const orderId = item.getAttribute('data-order-id')?.toLowerCase() || '';
            let found = false;

            if (storeName.includes(searchTerm) || orderId.includes(searchTerm)) {
                found = true;
            }

            productNames.forEach(productName => {
                if (productName.textContent.toLowerCase().includes(searchTerm)) {
                    found = true;
                }
            });

            item.style.display = found ? 'block' : 'none';
        });
    }

    // Handle buy again functionality
    function handleBuyAgain(button) {
        // Kiểm tra trạng thái đăng nhập
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

        if (!isAuthenticated) {
            alert('Vui lòng đăng nhập để tiếp tục mua hàng!');
            return;
        }

        const orderId = button.getAttribute('data-order-id');

        if (!orderId) {
            alert('Không thể mua lại đơn hàng này!');
            return;
        }

        // Tìm form gần nhất
        const form = button.closest('.order-item').querySelector('.buy-again-order-form');
        if (form) {
            form.submit();
        }
    }

    // Handle continue VNPay payment functionality
    function handleContinueVnPay(button) {
        const orderId = button.getAttribute('data-order-id');
        if (!orderId) {
            showNotification('Lỗi', 'Không thể tiếp tục thanh toán VNPay!', 'error');
            return;
        }

        // Disable button để tránh click nhiều lần
        button.disabled = true;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý...';

        // Tạo timeout để tự động reset button nếu không có response
        const timeoutId = setTimeout(() => {
            if (button.disabled) {
                button.disabled = false;
                button.innerHTML = originalText;
                showNotification('Timeout', 'Yêu cầu thanh toán bị timeout. Vui lòng thử lại!', 'warning');
            }
        }, 10000); // 10 giây timeout

        // Gọi API tiếp tục thanh toán VNPay
        fetch('/vnpay/continue', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ order_id: orderId })
        })
        .then(response => {
            clearTimeout(timeoutId); // Clear timeout nếu có response
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Redirect đến VNPay
                window.location.href = data.payment_url;
            } else {
                showNotification('Lỗi', data.error || 'Không thể tiếp tục thanh toán VNPay', 'error');
                // Restore button
                button.disabled = false;
                button.innerHTML = originalText;
            }
        })
        .catch(error => {
            clearTimeout(timeoutId); // Clear timeout nếu có lỗi
            console.error('Error:', error);
            showNotification('Lỗi', 'Có lỗi xảy ra, vui lòng thử lại!', 'error');
            // Restore button
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }

    // Toggle products function
    function toggleProducts(element) {
        const orderItem = element.closest('.order-item');
        const additionalProducts = orderItem.querySelectorAll('.additional-products');
        const showText = element.querySelector('.show-text');
        const hideText = element.querySelector('.hide-text');
        const chevronIcon = element.querySelector('.chevron-icon');

        if (additionalProducts.length === 0) {
            return;
        }

        additionalProducts.forEach(product => {
            if (product.classList.contains('show')) {
                // Hide products
                product.classList.remove('show');
                if (showText) showText.style.display = 'inline';
                if (hideText) hideText.style.display = 'none';
                if (chevronIcon) {
                    chevronIcon.classList.remove('fa-chevron-up');
                    chevronIcon.classList.add('fa-chevron-down');
                }
            } else {
                // Show products
                product.classList.add('show');
                if (showText) showText.style.display = 'none';
                if (hideText) hideText.style.display = 'inline';
                if (chevronIcon) {
                    chevronIcon.classList.remove('fa-chevron-down');
                    chevronIcon.classList.add('fa-chevron-up');
                }
            }
        });
    }

    // Reset button states when page becomes visible (user comes back from VNPay)
    function resetButtonStates() {
        const continueButtons = document.querySelectorAll('.continue-vnpay');
        let hasResetButtons = false;
        
        continueButtons.forEach(button => {
            if (button.disabled) {
                button.disabled = false;
                hasResetButtons = true;
                // Restore original text based on order data
                const orderId = button.getAttribute('data-order-id');
                if (orderId) {
                    button.innerHTML = `
                        <i class="fas fa-credit-card me-1"></i>
                        Tiếp tục thanh toán VNPay
                    `;
                }
            }
        });

        // Hiển thị thông báo nếu có button được reset
        if (hasResetButtons) {
            showNotification('Thông báo', 'Đã khôi phục trạng thái thanh toán. Bạn có thể tiếp tục thanh toán VNPay.', 'info');
        }
    }



    // Listen for page visibility changes
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // Page became visible (user came back)
            resetButtonStates();
        }
    });

    // Also reset on page load/focus
    window.addEventListener('pageshow', function(event) {
        // Check if page is being restored from cache (back/forward navigation)
        if (event.persisted) {
            resetButtonStates();
        }
    });

    window.addEventListener('focus', function() {
        resetButtonStates();
    });
</script>

<!-- Smart Auto-Reload chỉ cho trang orders -->
<script>
class SmartAutoReload {
    constructor() {
        this.isUserActive = true;
        this.lastActivity = Date.now();
        this.autoReloadInterval = null;
        this.inactivityThreshold = 10000; // 30 giây
        this.reloadInterval = 10000; // 1 phút
        
        this.init();
    }
    
    init() {
        console.log('Smart Auto-Reload initialized for orders page');
        this.startAutoReload();
        this.trackUserActivity();
    }
    
    trackUserActivity() {
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.isUserActive = true;
                this.lastActivity = Date.now();
                this.pauseAutoReload();
            });
        });
        
        // Kiểm tra inactivity mỗi 10 giây
        setInterval(() => {
            if (Date.now() - this.lastActivity > this.inactivityThreshold) {
                this.isUserActive = false;
                this.resumeAutoReload();
            }
        }, 3000);
    }
    
    startAutoReload() {
        this.autoReloadInterval = setInterval(() => {
            if (!this.isUserActive && !this.isFormActive()) {
                console.log('Auto-reloading orders page due to inactivity...');
                window.location.reload();
            }
        }, this.reloadInterval);
    }
    
    pauseAutoReload() {
        if (this.autoReloadInterval) {
            clearInterval(this.autoReloadInterval);
            this.autoReloadInterval = null;
        }
    }
    
    resumeAutoReload() {
        if (!this.autoReloadInterval) {
            this.startAutoReload();
        }
    }
    
    isFormActive() {
        const activeElement = document.activeElement;
        const formElements = ['input', 'textarea', 'select', 'button'];
        
        if (activeElement && formElements.includes(activeElement.tagName.toLowerCase())) {
            return true;
        }
        
        const modals = document.querySelectorAll('.modal.show, .modal[style*="display: block"]');
        if (modals.length > 0) {
            return true;
        }
        
        const dropdowns = document.querySelectorAll('.dropdown-menu.show');
        if (dropdowns.length > 0) {
            return true;
        }
        
        return false;
    }
}

// Khởi tạo Smart Auto-Reload
document.addEventListener('DOMContentLoaded', () => {
    new SmartAutoReload();
});
</script>
@endsection