@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number)

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-semibold text-dark">Chi tiết đơn hàng</h1>
            <p class="text-muted mb-0 small">Quản lý và theo dõi đơn hàng #{{ $order->order_number }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                <i class="fas fa-print me-1"></i>In
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Order Overview -->
            <div class="card border-0 bg-white mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-shopping-bag text-primary"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 fw-bold">#{{ $order->order_number }}</h4>
                                    <p class="text-muted mb-0 small">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="mb-2">
                                <span class="text-muted small">Tổng tiền</span>
                                <div class="h3 mb-0 fw-bold text-success">{{ number_format($order->total_amount) }}đ</div>
                            </div>
                            <div class="d-flex justify-content-md-end gap-2">
                                <span class="badge bg-light text-dark">{{ $order->items->count() }} sản phẩm</span>
                                <span class="badge bg-light text-dark">{{ $order->payment_method ?? 'COD' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Bar -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small">Trạng thái đơn hàng:</span>
                                <span class="badge {{ $order->getStatusBadgeClass() }} ms-2">{{ $order->getStatusText() }}</span>
                            </div>
                            <div>
                                <span class="text-muted small">Thanh toán:</span>
                                <span class="badge {{ $order->getPaymentStatusBadgeClass() }} ms-2">{{ $order->getPaymentStatusText() }}</span>
                                @if($order->isVnPayPayment())
                                    <i class="fas fa-lock text-muted ms-1" title="Trạng thái thanh toán VNPay được quản lý tự động"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card border-0 bg-white mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-user me-2 text-primary"></i>Thông tin khách hàng
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded-circle p-2 me-3">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $order->shipping_name }}</div>
                                    <small class="text-muted">{{ $order->user->email ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded-circle p-2 me-3">
                                    <i class="fas fa-phone text-muted"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $order->shipping_phone }}</div>
                                    <small class="text-muted">Số điện thoại</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="bg-light rounded-circle p-2 me-3">
                            <i class="fas fa-map-marker-alt text-muted"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Địa chỉ giao hàng</div>
                            <div class="text-muted">{{ $order->shipping_address }}</div>
                        </div>
                    </div>
                    @if($order->notes)
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex align-items-start">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fas fa-sticky-note text-muted"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Ghi chú</div>
                                <div class="text-muted">{{ $order->notes }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card border-0 bg-white">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-box me-2 text-primary"></i>Sản phẩm đã đặt
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 px-4 fw-medium text-muted small">Sản phẩm</th>
                                    <th class="border-0 py-3 px-3 fw-medium text-muted small text-center">Giá</th>
                                    <th class="border-0 py-3 px-3 fw-medium text-muted small text-center">SL</th>
                                    <th class="border-0 py-3 px-4 fw-medium text-muted small text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr class="border-bottom">
                                    <td class="align-middle px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            @php
                                                $imageUrl = $item->getSnapshotImageUrl();
                                            @endphp
                                            
                                            <div class="me-3" style="width: 48px; height: 48px;">
                                                @if($imageUrl)
                                                <img src="{{ $imageUrl }}" 
                                                     alt="{{ $item->product_name ?? 'Sản phẩm' }}" 
                                                     class="rounded w-100 h-100" 
                                                     style="object-fit: cover;">
                                                @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center w-100 h-100">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                                @endif
                                            </div>
                                            
                                            <div>
                                                <div class="fw-medium text-dark">{{ $item->product_name ?? 'Sản phẩm không tồn tại' }}</div>
                                                <div class="d-flex flex-column gap-1 mt-1">
                                                    @if($item->variant_sku)
                                                    <small class="text-muted">
                                                        <strong>SKU:</strong> {{ $item->variant_sku }}
                                                    </small>
                                                    @endif
                                                    @if($item->variant_attributes && is_array($item->variant_attributes))
                                                    <small class="text-muted">
                                                        <strong>Thuộc tính:</strong>
                                                        @foreach($item->variant_attributes as $attrName => $attrValue)
                                                            <span class="badge bg-light text-dark me-1">{{ $attrName }}: {{ $attrValue }}</span>
                                                        @endforeach
                                                    </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle px-3 py-3 text-center">
                                        <span class="fw-medium">{{ number_format($item->price) }}đ</span>
                                    </td>
                                    <td class="align-middle px-3 py-3 text-center">
                                        <span class="badge bg-light text-dark">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="align-middle px-4 py-3 text-end">
                                        <span class="fw-semibold text-success">{{ number_format($item->price * $item->quantity) }}đ</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card border-0 bg-white mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-calculator me-2 text-primary"></i>Tổng cộng
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-medium">{{ number_format($order->subtotal) }}đ</span>
                    </div>
                    
                    @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted">Giảm giá:</span>
                        <span class="fw-medium text-danger">-{{ number_format($order->discount_amount) }}đ</span>
                    </div>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span class="fw-medium">{{ number_format($order->shipping_fee) }}đ</span>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Tổng cộng:</span>
                        <span class="h5 mb-0 fw-bold text-success">{{ number_format($order->total_amount) }}đ</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 bg-white mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-bolt me-2 text-primary"></i>Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body p-4">
                    @php
                        $availablePaymentStatuses = $order->getAvailablePaymentStatuses();
                    @endphp
                    
                    @if(!empty($availablePaymentStatuses))
                        <form action="{{ route('admin.orders.updatePaymentStatus', $order) }}" method="POST" class="mb-3">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="payment_status" class="form-label small fw-medium">
                                    Trạng thái thanh toán
                                    @if($order->payment_method === 'cod')
                                        <i class="fas fa-info-circle text-muted ms-1" 
                                           title="Đơn hàng COD: Chỉ có thể cập nhật từ 'Chờ thanh toán' sang 'Đã thanh toán'"></i>
                                    @endif
                                </label>
                                <select class="form-select form-select-sm" id="payment_status" name="payment_status" required>
                                    @foreach($availablePaymentStatuses as $status)
                                        @php
                                            $tempOrder = clone $order;
                                            $tempOrder->payment_status = $status;
                                        @endphp
                                        <option value="{{ $status }}" {{ $order->payment_status == $status ? 'selected' : '' }}>
                                            {{ $tempOrder->getPaymentStatusText() }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($order->payment_method === 'cod')
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Đơn hàng COD: Chỉ có thể cập nhật từ "Chờ thanh toán" sang "Đã thanh toán" khi đơn hàng đã giao. 
                                        Sau khi đã thanh toán, không thể thay đổi trạng thái để đảm bảo tính minh bạch.
                                    </small>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                <i class="fas fa-save me-1"></i>Cập nhật thanh toán
                            </button>
                        </form>
                    @else
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Trạng thái thanh toán</label>
                            <div class="form-control form-control-sm bg-light" readonly>
                                <span class="badge {{ $order->getPaymentStatusBadgeClass() }}">{{ $order->getPaymentStatusText() }}</span>
                                <small class="text-muted ms-2">
                                    @if($order->isVnPayPayment())
                                        <i class="fas fa-lock me-1"></i>Không thể chỉnh sửa - Thanh toán VNPay
                                    @elseif($order->payment_method === 'cod' && $order->payment_status === 'paid')
                                        <i class="fas fa-lock me-1"></i>Không thể chỉnh sửa - Đã thanh toán COD
                                    @elseif($order->payment_method === 'cod' && $order->status !== 'delivered')
                                        <i class="fas fa-lock me-1"></i>Không thể chỉnh sửa - Đơn hàng chưa giao
                                    @endif
                                </small>
                            </div>
                            <small class="form-text text-muted">
                                @if($order->isVnPayPayment())
                                    Trạng thái thanh toán VNPay được quản lý tự động bởi hệ thống để đảm bảo tính toàn vẹn dữ liệu.
                                @elseif($order->payment_method === 'cod' && $order->payment_status === 'paid')
                                    Đơn hàng COD đã thanh toán không thể thay đổi trạng thái để đảm bảo tính minh bạch và tránh gian lận.
                                @elseif($order->payment_method === 'cod' && $order->status !== 'delivered')
                                    Đơn hàng COD chỉ có thể cập nhật trạng thái thanh toán khi đã giao hàng để tránh trường hợp thanh toán trước khi vận chuyển.
                                @endif
                            </small>
                        </div>
                    @endif

                    <!-- Nút xác nhận hoàn tiền cho đơn hàng VNPay -->
                    @if($order->isVnPayPayment() && $order->payment_status === 'refund_pending')
                        <div class="mb-3 p-3 border rounded bg-light">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-money-bill-wave text-warning me-2"></i>
                                <span class="fw-medium small">Xác nhận hoàn tiền</span>
                            </div>
                            <p class="text-muted small mb-3">
                                Đơn hàng đang chờ hoàn tiền. Sau khi thực hiện hoàn tiền cho khách hàng, hãy nhấn nút bên dưới để xác nhận.
                            </p>
                            <form action="{{ route('admin.orders.refund', $order) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Bạn có chắc chắn đã hoàn tiền cho khách hàng?')">
                                    <i class="fas fa-check me-1"></i>Xác nhận đã hoàn tiền
                                </button>
                            </form>
                        </div>
                    @endif
                    
                    @php
                        $availableStatuses = $order->getAvailableStatuses();
                    @endphp
                    
                    @if(!empty($availableStatuses))
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="status" class="form-label small fw-medium">
                                    Trạng thái đơn hàng
                                    <i class="fas fa-info-circle text-muted ms-1" 
                                       title="Chỉ hiển thị các trạng thái có thể cập nhật từ trạng thái hiện tại"></i>
                                </label>
                                <select class="form-select form-select-sm" id="status" name="status" required>
                                    @foreach($availableStatuses as $status)
                                        @php
                                            $tempOrder = clone $order;
                                            $tempOrder->status = $status;
                                        @endphp
                                        <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                            {{ $tempOrder->getStatusText() }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    Luồng trạng thái: Chờ xác nhận → Đã xác nhận → Đang xử lý → Đã gửi hàng → Đã giao hàng
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-save me-1"></i>Cập nhật trạng thái
                            </button>
                        </form>
                    @else
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Trạng thái đơn hàng</label>
                            <div class="form-control form-control-sm bg-light" readonly>
                                <span class="badge {{ $order->getStatusBadgeClass() }}">{{ $order->getStatusText() }}</span>
                                <small class="text-muted ms-2">
                                    <i class="fas fa-lock me-1"></i>Không thể thay đổi
                                </small>
                            </div>
                            <small class="form-text text-muted">
                                Đơn hàng đã hoàn thành hoặc bị hủy, không thể thay đổi trạng thái.
                            </small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card border-0 bg-white">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-history me-2 text-primary"></i>Lịch sử đơn hàng
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <div class="fw-medium small">Đơn hàng được tạo</div>
                                <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        
                        @if(in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']))
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <div class="fw-medium small">Đơn hàng được xác nhận</div>
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <div class="fw-medium small">Đang xử lý</div>
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if(in_array($order->status, ['shipped', 'delivered']))
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <div class="fw-medium small">Đã gửi hàng</div>
                                <small class="text-muted">{{ $order->shipped_at ? $order->shipped_at->format('d/m/Y H:i') : $order->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status == 'delivered')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <div class="fw-medium small">Đã giao hàng</div>
                                <small class="text-muted">{{ $order->delivered_at ? $order->delivered_at->format('d/m/Y H:i') : $order->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status == 'cancelled')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <div class="fw-medium small">Đơn hàng bị hủy</div>
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Clean Design */
body {
    background-color: #f8f9fa;
}

.card {
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: none;
}

.card-header {
    background: transparent;
    border-bottom: 1px solid #e9ecef;
}

.table {
    font-size: 0.875rem;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.75rem;
    color: #6c757d;
}

.table td {
    vertical-align: middle;
    border-top: 1px solid #f1f3f4;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e1e5e9;
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.btn {
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 24px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 16px;
}

.timeline-marker {
    position: absolute;
    left: -18px;
    top: 2px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    padding-left: 8px;
}

.timeline-content .fw-medium {
    font-size: 0.875rem;
    margin-bottom: 2px;
}

.timeline-content small {
    font-size: 0.75rem;
}

/* Hover effects */
.table tbody tr:hover {
    background-color: #f8f9fa;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}

/* Print styles */
@media print {
    .btn, .form-control, .form-select {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
}

/* Tooltip styles */
.tooltip {
    font-size: 0.875rem;
}

/* Lock icon styles */
.fa-lock {
    color: #6c757d;
}

/* Info icon styles */
.fa-info-circle {
    cursor: help;
}

/* Form control readonly styles */
.form-control[readonly] {
    background-color: #f8f9fa;
    border-color: #e9ecef;
    cursor: not-allowed;
}

/* Badge in form control */
.form-control .badge {
    font-size: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo tooltip Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Khởi tạo tooltip cho các icon info
    var infoIcons = document.querySelectorAll('.fa-info-circle');
    infoIcons.forEach(function(icon) {
        if (icon.title) {
            new bootstrap.Tooltip(icon);
        }
    });

    // Khởi tạo tooltip cho các icon lock
    var lockIcons = document.querySelectorAll('.fa-lock');
    lockIcons.forEach(function(icon) {
        if (icon.title) {
            new bootstrap.Tooltip(icon);
        }
    });
});
</script>
@endpush 