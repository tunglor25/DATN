@extends('layouts.app_client')

@section('title', 'Đơn hàng - TLO Fashion')

@section('content')
<div class="tlo-full-width">
    <!-- Hero -->
    <section class="tlo-page-hero">
        <div class="tlo-page-hero-inner">
            <div class="tlo-hero-badge"><i class="fas fa-box"></i> Đơn hàng</div>
            <h1 class="tlo-hero-title">Đơn hàng của tôi</h1>
            <p class="tlo-hero-desc">Theo dõi trạng thái và quản lý đơn hàng</p>
        </div>
    </section>

    <div class="user-page-layout">
        <!-- Sidebar -->
        <div>@include('client.partials.user-sidebar')</div>

        <!-- Main Content -->
        <div class="user-main-card tlo-animate">
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
                    <a class="nav-link" data-bs-toggle="tab" href="#return_requested">Trả hàng</a>
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
                    <div class="spinner-border" style="color: var(--tlo-accent);" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                    <p class="mt-3" style="color: var(--tlo-text-secondary);">Đang tải đơn hàng...</p>
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
                                <div class="tlo-empty">
                                    <div class="tlo-empty-icon"><i class="fas fa-box-open"></i></div>
                                    <h3>Không có đơn hàng nào</h3>
                                    <p>Bạn chưa có đơn hàng nào</p>
                                </div>
                            @endforelse
                        @else
                            <div class="tlo-empty">
                                <div class="tlo-empty-icon"><i class="fas fa-lock"></i></div>
                                <h3>Vui lòng đăng nhập</h3>
                                <p>Đăng nhập để xem đơn hàng</p>
                                <a href="{{ route('login') }}" class="tlo-btn tlo-btn-primary">Đăng nhập</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="tab-pane fade" id="pending"><div id="orders-content-pending"></div></div>
                <div class="tab-pane fade" id="confirmed"><div id="orders-content-confirmed"></div></div>
                <div class="tab-pane fade" id="processing"><div id="orders-content-processing"></div></div>
                <div class="tab-pane fade" id="shipped"><div id="orders-content-shipped"></div></div>
                <div class="tab-pane fade" id="delivered"><div id="orders-content-delivered"></div></div>
                <div class="tab-pane fade" id="return_requested"><div id="orders-content-return_requested"></div></div>
                <div class="tab-pane fade" id="returned"><div id="orders-content-returned"></div></div>
                <div class="tab-pane fade" id="cancelled"><div id="orders-content-cancelled"></div></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .order-tabs {
        border-bottom: 2px solid var(--tlo-border);
        padding: 0 24px;
        margin-bottom: 0;
        overflow-x: auto;
        flex-wrap: nowrap;
        white-space: nowrap;
    }

    .order-tabs .nav-link {
        border: none;
        color: var(--tlo-text-secondary);
        padding: 14px 18px;
        font-weight: 500;
        font-size: 0.88rem;
        border-bottom: 2px solid transparent;
        transition: var(--tlo-transition);
    }

    .order-tabs .nav-link:hover {
        color: var(--tlo-accent);
    }

    .order-tabs .nav-link.active {
        color: var(--tlo-accent);
        border-bottom-color: var(--tlo-accent);
        background: none;
        font-weight: 600;
    }

    .search-box {
        background: var(--tlo-surface-alt);
        border-bottom: 1px solid var(--tlo-border);
        padding: 12px 24px;
    }

    .search-box input {
        border: none;
        background: none;
        outline: none;
        width: 100%;
        font-size: 0.88rem;
        color: var(--tlo-text-primary);
    }

    .search-box input::placeholder {
        color: var(--tlo-text-light);
    }

    .tab-content {
        padding: 20px 24px;
    }

    .order-item {
        border: 1px solid var(--tlo-border);
        border-radius: var(--tlo-radius-md);
        margin-bottom: 16px;
        overflow: hidden;
        transition: var(--tlo-transition);
    }

    .order-item:hover {
        box-shadow: var(--tlo-shadow-sm);
        border-color: rgba(255, 107, 107, 0.15);
    }

    .order-header {
        background: var(--tlo-surface-alt);
        padding: 12px 20px;
        border-bottom: 1px solid var(--tlo-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .store-info { display: flex; align-items: center; }
    .order-details { text-align: right; }

    .store-badge {
        background: var(--tlo-accent);
        color: white;
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 6px;
        margin-left: 8px;
        font-weight: 600;
    }

    .official-badge { background: #2563eb; }

    .order-body {
        padding: 20px;
        border-bottom: 1px solid var(--tlo-border);
    }
    .order-body:last-of-type { border-bottom: none; }

    .product-info { display: flex; }
    .product-image {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        margin-right: 15px;
        object-fit: cover;
        border: 1px solid var(--tlo-border);
    }
    .product-details { flex: 1; }
    .product-name {
        font-weight: 600;
        margin-bottom: 4px;
        color: var(--tlo-text-primary);
        font-size: 0.9rem;
    }
    .product-attributes-container { margin-top: 5px; }
    .total-variant { margin-bottom: 5px; }
    .product-quantity, .order-number, .product-size, .product-color, .attribute {
        color: var(--tlo-text-secondary);
        font-size: 0.82rem;
    }
    .product-attributes { display: flex; align-items: center; gap: 10px; }
    .product-price { text-align: right; }
    .original-price { text-decoration: line-through; color: var(--tlo-text-light); font-size: 0.82rem; }
    .current-price { color: var(--tlo-accent); font-weight: 600; font-size: 0.95rem; }

    .order-footer {
        border-top: 1px solid var(--tlo-border);
        padding: 15px 20px;
        background: var(--tlo-surface-alt);
    }
    .order-total { text-align: right; margin-bottom: 15px; }
    .total-label { color: var(--tlo-text-secondary); margin-right: 10px; font-size: 0.88rem; }
    .total-amount { color: var(--tlo-accent); font-size: 1.1rem; font-weight: 700; }

    .order-actions { display: flex; justify-content: space-between; align-items: center; }
    .seller-note { color: var(--tlo-accent); font-size: 0.82rem; cursor: pointer; text-decoration: none; }
    .seller-note:hover { text-decoration: underline; }
    .action-buttons { display: flex; gap: 10px; }

    .buy-again {
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        border: none;
        color: white;
        font-weight: 500;
        border-radius: 10px;
        padding: 6px 14px;
        font-size: 0.85rem;
        box-shadow: 0 4px 12px rgba(255, 107, 107, 0.2);
    }
    .buy-again:hover { color: white; transform: translateY(-1px); }

    .btn-pay {
        background: linear-gradient(135deg, #00dd9e, #00bf89);
        color: white;
        font-weight: 500;
        border-radius: 10px;
        border: none;
    }

    .additional-products { display: none; }
    .additional-products.show { display: block; animation: slideDown 0.3s ease-in-out; }
    @keyframes slideDown { from { opacity: 0; } to { opacity: 1; } }

    #loading-spinner { position: relative; z-index: 1000; }
    .spinner-border { width: 3rem; height: 3rem; }
    .tab-pane { transition: opacity 0.3s ease-in-out; }
    .tab-pane.fade { opacity: 0; }
    .tab-pane.fade.show { opacity: 1; }

    .error-message {
        background: rgba(255, 107, 107, 0.06);
        border: 1px solid rgba(255, 107, 107, 0.15);
        border-radius: var(--tlo-radius-md);
        padding: 20px;
        text-align: center;
    }

    @media (max-width: 768px) {
        .order-tabs { padding: 0 16px; }
        .order-tabs .nav-link { padding: 10px 12px; font-size: 0.82rem; }
        .tab-content { padding: 16px; }
        .order-header, .order-body, .order-footer { padding: 12px 16px; }
    }

    /* Nút Xác nhận đã nhận hàng */
    .btn-confirm-received {
        background: linear-gradient(135deg, #00b894, #00cec9);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 10px;
        padding: 6px 14px;
        font-size: 0.85rem;
        box-shadow: 0 4px 12px rgba(0, 184, 148, 0.25);
        transition: all 0.2s;
    }
    .btn-confirm-received:hover {
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 184, 148, 0.35);
    }

    /* Nút Trả hàng/Hoàn tiền */
    .btn-return-request {
        background: linear-gradient(135deg, #fdcb6e, #e17055);
        border: none;
        color: white;
        font-weight: 500;
        border-radius: 10px;
        padding: 6px 14px;
        font-size: 0.85rem;
        box-shadow: 0 4px 12px rgba(225, 112, 85, 0.2);
        transition: all 0.2s;
    }
    .btn-return-request:hover {
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(225, 112, 85, 0.35);
    }

    /* Box hiển thị lý do trả hàng */
    .return-reason-box {
        background: rgba(253, 203, 110, 0.1);
        border-left: 4px solid #fdcb6e;
        padding: 12px 20px;
        font-size: 0.85rem;
        color: var(--tlo-text-primary);
    }

    /* Modal styles */
    .modal-content {
        border-radius: var(--tlo-radius-md, 12px);
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    .modal-header {
        border-bottom: 1px solid var(--tlo-border, #eee);
    }
    .modal-footer {
        border-top: 1px solid var(--tlo-border, #eee);
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