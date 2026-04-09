@extends('layouts.app')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Header -->
        <div class="col-12 mb-4">
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
        </div>

        <!-- Statistics Overview -->
        <div class="col-12 mb-4">
            <div class="stats-grid">
                <div class="card stats-card h-100">
                    <div class="card-body p-3 text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">{{ number_format($stats['total']) }}</h4>
                        <small class="text-muted">Tổng đơn hàng</small>
                    </div>
                </div>
                <div class="card stats-card h-100">
                    <div class="card-body p-3 text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">{{ number_format($stats['pending']) }}</h4>
                        <small class="text-muted">Chờ xác nhận</small>
                    </div>
                </div>
                <div class="card stats-card h-100">
                    <div class="card-body p-3 text-center">
                        <div class="text-info mb-2">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">{{ number_format($stats['confirmed']) }}</h4>
                        <small class="text-muted">Đã xác nhận</small>
                    </div>
                </div>
                <div class="card stats-card h-100">
                    <div class="card-body p-3 text-center">
                        <div class="text-success mb-2">
                            <i class="fas fa-truck fa-lg"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">{{ number_format($stats['delivered']) }}</h4>
                        <small class="text-muted">Đã giao hàng</small>
                    </div>
                </div>
                <div class="card stats-card h-100">
                    <div class="card-body p-3 text-center">
                        <div class="text-danger mb-2">
                            <i class="fas fa-times-circle fa-lg"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">{{ number_format($stats['cancelled']) }}</h4>
                        <small class="text-muted">Đã hủy</small>
                    </div>
                </div>
                <div class="card stats-card h-100">
                    <div class="card-body p-3 text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-undo fa-lg"></i>
                        </div>
                        <h4 class="mb-1 fw-bold">{{ number_format(($stats['return_requested'] ?? 0) + ($stats['returned'] ?? 0)) }}</h4>
                        <small class="text-muted">Trả hàng</small>
                    </div>
                </div>
                <div class="card stats-card h-100">
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

        <!-- Filters -->
        <div class="col-12 mb-4">
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
                                <option value="return_requested" {{ request('status') == 'return_requested' ? 'selected' : '' }}>Yêu cầu trả hàng</option>
                                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Đã trả hàng</option>
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
        <div class="col-12 mb-4">
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
                                                case 'return_requested':
                                                    $statusText = 'Yêu cầu trả hàng';
                                                    $badgeClass = 'bg-warning text-dark';
                                                    break;
                                                case 'returned':
                                                    $statusText = 'Đã trả hàng';
                                                    $badgeClass = 'bg-secondary text-white';
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
.stats-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1rem;
}
@media (max-width: 1400px) {
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}
@media (max-width: 900px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 500px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .stats-card .card-body {
        padding: 0.75rem !important;
    }
    .stats-card h4 {
        font-size: 1.1rem;
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