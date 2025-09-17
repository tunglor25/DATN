@extends('layouts.app')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Header -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">
                                <i class="fas fa-shopping-cart me-2"></i>Quản lý đơn hàng
                            </h3>
                            <p class="text-muted mb-0 small">Theo dõi và quản lý tất cả đơn hàng trong hệ thống</p>
                        </div>
                        <div class="d-flex">
                            <button class="btn btn-outline-secondary btn-sm me-2" onclick="window.print()">
                                <i class="fas fa-print me-1"></i>In
                            </button>
                            <a href="{{ route('admin.orders.export') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-download me-1"></i>Xuất Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>


        <!-- Statistics Overview -->
        <div class="col-12">
            <div class="row g-3 mb-4">
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card stats-card">
                        <div class="card-body p-3 text-center">
                            <div class="text-primary mb-2">
                                <i class="fas fa-shopping-cart fa-lg"></i>
                            </div>
                            <h4 class="mb-1 fw-bold">{{ number_format($stats['total']) }}</h4>
                            <small class="text-muted">Tổng đơn hàng</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card stats-card">
                        <div class="card-body p-3 text-center">
                            <div class="text-warning mb-2">
                                <i class="fas fa-clock fa-lg"></i>
                            </div>
                            <h4 class="mb-1 fw-bold">{{ number_format($stats['pending']) }}</h4>
                            <small class="text-muted">Chờ xác nhận</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card stats-card">
                        <div class="card-body p-3 text-center">
                            <div class="text-info mb-2">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </div>
                            <h4 class="mb-1 fw-bold">{{ number_format($stats['confirmed']) }}</h4>
                            <small class="text-muted">Đã xác nhận</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card stats-card">
                        <div class="card-body p-3 text-center">
                            <div class="text-success mb-2">
                                <i class="fas fa-truck fa-lg"></i>
                            </div>
                            <h4 class="mb-1 fw-bold">{{ number_format($stats['delivered']) }}</h4>
                            <small class="text-muted">Đã giao hàng</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card stats-card">
                        <div class="card-body p-3 text-center">
                            <div class="text-danger mb-2">
                                <i class="fas fa-times-circle fa-lg"></i>
                            </div>
                            <h4 class="mb-1 fw-bold">{{ number_format($stats['cancelled']) }}</h4>
                            <small class="text-muted">Đã hủy</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card stats-card">
                        <div class="card-body p-3 text-center">
                            <div class="text-secondary mb-2">
                                <i class="fas fa-cogs fa-lg"></i>
                            </div>
                            <h4 class="mb-1 fw-bold">{{ number_format($stats['processing'] + $stats['shipped']) }}</h4>
                            <small class="text-muted">Đang xử lý</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter me-2"></i>Bộ lọc tìm kiếm
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
                        <div class="col-lg-2 col-md-6">
                            <label for="search" class="form-label fw-semibold">Tìm kiếm</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Mã đơn hàng, tên khách hàng...">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="status" class="form-label fw-semibold">Trạng thái</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Tất cả</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đã gửi hàng</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="payment_status" class="form-label fw-semibold">Thanh toán</label>
                            <select class="form-select" id="payment_status" name="payment_status">
                                <option value="">Tất cả</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Thanh toán thất bại</option>
                                <option value="refund_pending" {{ request('payment_status') == 'refund_pending' ? 'selected' : '' }}>Chờ hoàn tiền</option>
                                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="payment_method" class="form-label fw-semibold">Phương thức</label>
                            <select class="form-select" id="payment_method" name="payment_method">
                                <option value="">Tất cả</option>
                                <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>Tiền mặt</option>
                                <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản</option>
                                <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Thẻ tín dụng</option>
                                <option value="momo" {{ request('payment_method') == 'momo' ? 'selected' : '' }}>MoMo</option>
                                <option value="vnpay" {{ request('payment_method') == 'vnpay' ? 'selected' : '' }}>VNPay</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="date_from" class="form-label fw-semibold">Từ ngày</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="date_to" class="form-label fw-semibold">Đến ngày</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-12">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fas fa-search me-1"></i>Tìm kiếm
                                </button>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Làm mới
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Danh sách đơn hàng</h3>
                        <span class="badge bg-primary">{{ $orders->total() }} đơn hàng</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dataTable">
                            <thead>
                                <tr>
                                    <th>MÃ ĐƠN HÀNG</th>
                                    <th>KHÁCH HÀNG</th>
                                    <th>TỔNG TIỀN</th>
                                    <th>TRẠNG THÁI</th>
                                    <th>THANH TOÁN</th>
                                    <th>PHƯƠNG THỨC</th>
                                    <th>NGÀY TẠO</th>
                                    <th class="text-center">HÀNH ĐỘNG</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                <i class="fas fa-shopping-bag text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">#{{ $order->order_number }}</div>
                                                <small class="text-muted">{{ $order->items->count() }} sản phẩm</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-medium text-dark">{{ $order->shipping_name }}</div>
                                            <small class="text-muted">{{ $order->shipping_phone }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold text-success">{{ number_format($order->total_amount) }}đ</div>
                                            @if($order->discount_amount > 0)
                                            <small class="text-danger">-{{ number_format($order->discount_amount) }}đ</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusValue = $order->status;
                                            $statusText = '';
                                            $badgeClass = '';
                                            
                                            switch($statusValue) {
                                                case 'pending':
                                                    $statusText = 'Chờ xác nhận';
                                                    $badgeClass = 'bg-warning text-dark';
                                                    break;
                                                case 'confirmed':
                                                    $statusText = 'Đã xác nhận';
                                                    $badgeClass = 'bg-info text-white';
                                                    break;
                                                case 'processing':
                                                    $statusText = 'Đang xử lý';
                                                    $badgeClass = 'bg-primary text-white';
                                                    break;
                                                case 'shipped':
                                                    $statusText = 'Đã gửi hàng';
                                                    $badgeClass = 'bg-info text-white';
                                                    break;
                                                case 'delivered':
                                                    $statusText = 'Đã giao hàng';
                                                    $badgeClass = 'bg-success text-white';
                                                    break;
                                                case 'cancelled':
                                                    $statusText = 'Đã hủy';
                                                    $badgeClass = 'bg-danger text-white';
                                                    break;
                                                default:
                                                    $statusText = $statusValue ?? 'Chưa xác định';
                                                    $badgeClass = 'bg-secondary text-white';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $paymentStatusValue = $order->payment_status;
                                            $paymentStatusText = '';
                                            $paymentBadgeClass = '';
                                            
                                            switch($paymentStatusValue) {
                                                case 'pending':
                                                    $paymentStatusText = 'Chờ thanh toán';
                                                    $paymentBadgeClass = 'bg-warning text-dark';
                                                    break;
                                                case 'paid':
                                                    $paymentStatusText = 'Đã thanh toán';
                                                    $paymentBadgeClass = 'bg-success text-white';
                                                    break;
                                                case 'failed':
                                                    $paymentStatusText = 'Thanh toán thất bại';
                                                    $paymentBadgeClass = 'bg-danger text-white';
                                                    break;
                                                case 'refund_pending':
                                                    $paymentStatusText = 'Chờ hoàn tiền';
                                                    $paymentBadgeClass = 'bg-info text-dark';
                                                    break;
                                                case 'refunded':
                                                    $paymentStatusText = 'Đã hoàn tiền';
                                                    $paymentBadgeClass = 'bg-info text-white';
                                                    break;
                                                default:
                                                    $paymentStatusText = $paymentStatusValue ?? 'Chưa xác định';
                                                    $paymentBadgeClass = 'bg-secondary text-white';
                                            }
                                        @endphp
                                        <span class="badge {{ $paymentBadgeClass }}">{{ $paymentStatusText }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $paymentMethodValue = $order->payment_method;
                                            $paymentMethodText = '';
                                            
                                            switch($paymentMethodValue) {
                                                case 'cod':
                                                    $paymentMethodText = 'Tiền mặt';
                                                    break;
                                                case 'bank_transfer':
                                                    $paymentMethodText = 'Chuyển khoản';
                                                    break;
                                                case 'credit_card':
                                                    $paymentMethodText = 'Thẻ tín dụng';
                                                    break;
                                                case 'momo':
                                                    $paymentMethodText = 'MoMo';
                                                    break;
                                                case 'vnpay':
                                                    $paymentMethodText = 'VNPay';
                                                    break;
                                                default:
                                                    $paymentMethodText = 'Chưa chọn';
                                            }
                                        @endphp
                                        <span class="fw-medium text-dark">{{ $paymentMethodText }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-medium text-dark">{{ $order->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="btn btn-info btn-sm action-btn" 
                                           data-bs-toggle="tooltip" 
                                           title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="text-muted mb-3">
                            <i class="fas fa-inbox fa-3x"></i>
                        </div>
                        <h5 class="text-muted">Không có đơn hàng nào</h5>
                        <p class="text-muted small">Chưa có đơn hàng nào trong hệ thống hoặc không tìm thấy kết quả phù hợp.</p>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                            <i class="fas fa-refresh me-1"></i>Làm mới
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="col-12">
            <div class="card">
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Hiển thị {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} 
                            trong tổng số {{ $orders->total() }} đơn hàng
                        </div>
                        <div>
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
/* 
========================================
CSS VARIABLES - DỄ DÀNG TÙY CHỈNH
========================================

Để thay đổi màu sắc hoặc style, chỉ cần sửa các giá trị bên dưới:

🎨 MÀU SẮC:
- --table-hover: Màu khi hover vào hàng table
- --header-bg: Màu nền header table
- --card-header-bg: Màu nền header card

🔘 BORDER RADIUS:
- --border-radius: Bo góc cho table, card
- --btn-radius: Bo góc cho buttons
- --badge-radius: Bo góc cho badges

📏 KHOẢNG CÁCH:
- --table-padding: Padding cho cells trong table
- --btn-padding: Padding cho buttons
- --badge-padding: Padding cho badges

Ví dụ: Để đổi màu hover thành đỏ: --table-hover: #ff0000;
*/
:root {
    /* Table Colors */
    --table-bg: #ffffff;
    --table-border: #f0f0f0;
    --table-hover: #e4e4e4;
    --table-shadow: rgba(0, 0, 0, 0.08);
    
    /* Header Colors */
    --header-bg: #000000;
    --header-text: #ffffff;
    
    /* Card Colors */
    --card-bg: #ffffff;
    --card-shadow: rgba(0, 0, 0, 0.1);
    --card-header-bg: #ffffff;
    --card-header-text:#000000;
    
    /* Button Colors */
    --btn-warning: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    --btn-info: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    --btn-danger: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    --btn-success: linear-gradient(135deg, #00d4aa 0%, #00b894 100%);
    --btn-secondary: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
    --btn-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    
    /* Badge Colors */
    --badge-success: linear-gradient(135deg, #00d4aa 0%, #00b894 100%);
    --badge-danger: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    --badge-primary: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
    --badge-secondary: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
    --badge-info: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    --badge-warning: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    
    /* Border Radius */
    --border-radius: 0;
    --btn-radius: 0;
    --badge-radius: 0;
    
    /* Spacing */
    --table-padding: 16px 12px;
    --btn-padding: 8px 16px;
    --badge-padding: 6px 12px;
}

/* Modern Table Styling */
.table {
    border-collapse: separate !important;
    border-spacing: 0 !important;
    width: 100% !important;
    background: var(--table-bg) !important;
    border-radius: 0 !important;
    overflow: hidden !important;
    box-shadow: 0 4px 20px var(--table-shadow) !important;
}

/* Table Header */
.table thead {
    background: var(--header-bg) !important;
}

.table thead th {
    color: var(--header-text) !important;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.8px;
    padding: var(--table-padding);
    border: none !important;
    position: relative;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: transparent !important;
}

/* Table Body */
.table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid var(--table-border);
}

.table tbody tr:last-child {
    border-bottom: none;
}

.table tbody tr:hover {
    background-color: var(--table-hover) !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.table tbody td {
    padding: var(--table-padding);
    vertical-align: middle;
    border: none;
    color: #2c3e50;
    font-size: 0.9rem;
    font-weight: 500;
}

/* Custom Badge Colors */
.badge.bg-success {
    background: var(--badge-success) !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: 0 !important;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.bg-danger {
    background: var(--badge-danger) !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: 0 !important;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.bg-primary {
    background: var(--badge-primary) !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: 0 !important;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.bg-secondary {
    background: var(--badge-secondary) !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: 0 !important;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.bg-info {
    background: var(--badge-info) !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: 0 !important;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.bg-warning {
    background: var(--badge-warning) !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: 0 !important;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Custom Button Colors */
.btn-warning {
    background: var(--btn-warning) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: 0 !important;
    padding: var(--btn-padding) !important;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(243, 156, 18, 0.3);
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e67e22 0%, #d35400 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
}

.btn-info {
    background: var(--btn-info) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: 0 !important;
    padding: var(--btn-padding) !important;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
}

.btn-info:hover {
    background: linear-gradient(135deg, #2980b9 0%, #1f5f8b 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
}

.btn-danger {
    background: var(--btn-danger) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: 0 !important;
    padding: var(--btn-padding) !important;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
}

.btn-danger:hover {
    background: linear-gradient(135deg, #c0392b 0%, #a93226 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
}

.btn-secondary {
    background: var(--btn-secondary) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: 0 !important;
    padding: var(--btn-padding) !important;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(149, 165, 166, 0.3);
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #7f8c8d 0%, #6c7b7d 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(149, 165, 166, 0.4);
}

.btn-success {
    background: var(--btn-success) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: 0 !important;
    padding: var(--btn-padding) !important;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 212, 170, 0.3);
}

.btn-success:hover {
    background: linear-gradient(135deg, #00b894 0%, #00a085 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 212, 170, 0.4);
}

.btn-primary {
    background: var(--btn-primary) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 600;
    border-radius: 0 !important;
    padding: var(--btn-padding) !important;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

/* Card Styling */
.card {
    border: none !important;
    border-radius: 0 !important;
    overflow: hidden;
}

.card-header {
    background: var(--card-header-bg) !important;
    color: var(--card-header-text) !important;
    border: none !important;
    padding: 20px 24px;
}

.card-title {
    margin: 0;
    font-weight: 700;
    font-size: 1.25rem;
    letter-spacing: 0.5px;
}

/* Stats Cards */
.stats-card {
    transition: all 0.3s ease;
    border: none !important;
    background: var(--card-bg) !important;
    box-shadow: 0 10px 30px var(--card-shadow) !important;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
}

/* Action Buttons Container */
.table tbody td:last-child {
    white-space: nowrap;
}

.table tbody td:last-child .btn {
    margin: 0 2px;
}


/* Pagination Styling */
.pagination {
    margin-bottom: 0;
    gap: 4px;
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3) !important;
}

.page-link {
    border: none !important;
    border-radius: 0 !important;
    margin: 0 2px;
    color: #2c3e50;
    font-weight: 500;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%) !important;
    color: #667eea;
    transform: translateY(-1px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .table {
        font-size: 0.8rem;
    }
    
    .table thead th,
    .table tbody td {
        padding: 12px 8px;
    }
    
    .btn-sm {
        padding: var(--btn-padding) !important;
        font-size: 0.75rem;
    }
    
    .card-header .input-group {
        width: 200px !important;
    }
}

@media (max-width: 576px) {
    .card-header .input-group {
        width: 100% !important;
        margin-top: 10px;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
    }
}

</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize DataTable
    $('#dataTable').DataTable({
        "pageLength": 20,
        "order": [[6, "desc"]], // Sort by date created desc
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json"
        },
        "responsive": true,
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
               '<"row"<"col-sm-12"tr>>' +
               '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "Tất cả"]],
        "columnDefs": [
            {
                "targets": [7], // Actions column
                "orderable": false,
                "searchable": false
            }
        ]
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        document.getElementById('search').focus();
    }
    if (e.ctrlKey && e.key === 'e') {
        e.preventDefault();
        window.location.href = '{{ route("admin.orders.export") }}';
    }
});
</script>
@endpush 