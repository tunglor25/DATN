@extends('layouts.app_client')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Đơn hàng của tôi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn hàng #{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Thông tin đơn hàng -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-box me-2"></i>
                        Chi tiết đơn hàng #{{ $order->order_number }}
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Trạng thái đơn hàng -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge {{ $order->getStatusBadgeClass() }} me-2">
                                    {{ $order->getStatusText() }}
                                </span>
                                <span class="badge {{ $order->getPaymentStatusBadgeClass() }}">
                                    {{ $order->getPaymentStatusText() }}
                                </span>
                            </div>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar me-1"></i>
                                Đặt hàng: {{ $order->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="text-muted mb-0">
                                <i class="fas fa-credit-card me-1"></i>
                                {{ $order->getPaymentMethodText() }}
                            </p>
                            @if($order->paid_at)
                                <p class="text-muted mb-0">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Thanh toán: {{ $order->paid_at->format('d/m/Y H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Danh sách sản phẩm -->
                    <h6 class="mb-3">Sản phẩm đã đặt</h6>
                    @forelse($order->items as $item)
                        <div class="order-item-detail mb-3 p-3 border rounded">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ $item->getSnapshotImageUrl() }}" 
                                         alt="{{ $item->product_name }}" 
                                         class="img-fluid rounded" 
                                         style="max-width: 80px;">
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-1">{{ $item->product_name ?? 'Không có tên sản phẩm' }}</h6>
                                    <p class="text-muted mb-1">
                                        <small>
                                            <i class="fas fa-hashtag me-1"></i>
                                            SKU: {{ $item->variant_sku ?? 'N/A' }}
                                        </small>
                                    </p>
                                    @if($item->variant_attributes && is_array($item->variant_attributes))
                                        <div class="product-attributes">
                                            @foreach($item->variant_attributes as $attrName => $attrValue)
                                                <span class="badge bg-light text-dark me-1">
                                                    {{ ucfirst($attrName) }}: {{ $attrValue }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @elseif($item->variant && $item->variant->attributeValues->isNotEmpty())
                                        <div class="product-attributes">
                                            @foreach($item->variant->attributeValues as $attributeValue)
                                                <span class="badge bg-light text-dark me-1">
                                                    {{ ucfirst($attributeValue->attribute->name) }}: {{ $attributeValue->value }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="text-muted">Số lượng: {{ $item->quantity }}</span>
                                </div>
                                <div class="col-md-2 text-end">
                                    <div class="price-info">
                                        <div class="text-muted">
                                            <small>₫{{ number_format($item->price, 0, ',', '.') }}</small>
                                        </div>
                                        <div class="fw-bold text-primary">
                                            ₫{{ number_format($item->subtotal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Nút đánh giá cho đơn hàng đã giao -->
                            @if($order->canReviewProducts() && ($item->product_id || ($item->variant && $item->variant->product_id)))
                                <div class="row mt-2">
                                    <div class="col-12 text-end">
                                        @php
                                            $productId = $item->product_id ?? $item->variant->product_id;
                                            $isReviewed = $userReviews->where('product_id', $productId)->where('order_id', $order->id)->isNotEmpty();
                                        @endphp
                                        @if($isReviewed)
                                            <button class="btn btn-outline-success btn-sm" disabled>
                                                <i class="fas fa-check me-1"></i>Đã đánh giá
                                            </button>
                                        @else
                                            <a href="{{ route('product.review', ['productId' => $productId, 'orderId' => $order->id]) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-star me-1"></i>Đánh giá sản phẩm
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-box fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Không có sản phẩm trong đơn hàng này.</p>
                        </div>
                    @endforelse

                    <!-- Thông tin thanh toán -->
                    <div class="row mt-4">
                        <div class="col-md-6 offset-md-6">
                            <div class="payment-summary">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tạm tính:</span>
                                    <span>₫{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                </div>
                                @if($order->tax_amount > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Thuế:</span>
                                        <span>₫{{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                @if($order->shipping_fee > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Phí vận chuyển:</span>
                                        <span>₫{{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                @if($order->discount_amount > 0)
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>Giảm giá:</span>
                                        <span>-₫{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Tổng cộng:</span>
                                    <span class="text-primary">₫{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ghi chú đơn hàng -->
                    @if($order->notes)
                        <div class="mt-4">
                            <h6>Ghi chú:</h6>
                            <div class="alert alert-info">
                                {{ $order->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline đơn hàng -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Lịch sử đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($timeline as $index => $event)
                            <div class="timeline-item">
                                <div class="timeline-marker {{ $event['status'] === 'completed' ? 'completed' : 'cancelled' }}">
                                    <i class="{{ $event['icon'] }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">{{ $event['title'] }}</h6>
                                    <p class="text-muted mb-1">{{ $event['description'] }}</p>
                                    <small class="text-muted">
                                        {{ $event['date']->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Thông tin khách hàng -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Thông tin khách hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="customer-info">
                        <div class="mb-3">
                            <strong>Họ tên:</strong>
                            <p class="mb-0">{{ $order->shipping_name ?? Auth::user()->name }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Số điện thoại:</strong>
                            <p class="mb-0">{{ $order->shipping_phone ?? Auth::user()->phone ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Địa chỉ giao hàng:</strong>
                            <p class="mb-0">{{ $order->shipping_address ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Email:</strong>
                            <p class="mb-0">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin thanh toán -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Thông tin thanh toán
                    </h5>
                </div>
                <div class="card-body">
                    <div class="payment-info">
                        <div class="mb-3">
                            <strong>Phương thức thanh toán:</strong>
                            <p class="mb-0">{{ $order->getPaymentMethodText() }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Trạng thái thanh toán:</strong>
                            <p class="mb-0">
                                <span class="badge {{ $order->getPaymentStatusBadgeClass() }}">
                                    {{ $order->getPaymentStatusText() }}
                                </span>
                            </p>
                        </div>
                        @if($order->paid_at)
                            <div class="mb-3">
                                <strong>Thời gian thanh toán:</strong>
                                <p class="mb-0">{{ $order->paid_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Hành động -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Hành động
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        {{-- Nút xác nhận đã nhận hàng --}}
                        @if($order->canConfirmReceived())
                            <form action="{{ route('orders.confirm-received', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" 
                                        onclick="return confirm('Bạn xác nhận đã nhận được hàng?')">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Xác nhận đã nhận hàng
                                </button>
                            </form>
                        @endif

                        {{-- Nút yêu cầu trả hàng --}}
                        @if($order->canRequestReturn())
                            <button class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#returnModalDetail">
                                <i class="fas fa-undo me-1"></i>
                                Trả hàng/Hoàn tiền (còn {{ $order->getReturnDaysRemaining() }} ngày)
                            </button>
                        @endif

                        {{-- Thông tin trả hàng --}}
                        @if($order->status === 'return_requested')
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-clock me-1"></i>
                                <strong>Đang chờ xét duyệt trả hàng</strong>
                                @if($order->return_reason)
                                    <br><small class="text-muted">Lý do: {{ $order->return_reason }}</small>
                                @endif
                            </div>
                        @endif

                        @if($order->status === 'returned')
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-check-double me-1"></i>
                                <strong>Đơn hàng đã được trả</strong>
                                @if($order->returned_at)
                                    <br><small class="text-muted">Ngày trả: {{ $order->returned_at->format('d/m/Y H:i') }}</small>
                                @endif
                            </div>
                        @endif

                        <!-- Form ẩn cho MUA LẦN NỮA -->
                        <form class="buy-again-order-form" action="{{ route('orders.buy-again', $order->id) }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                        @if(in_array($order->status, ['delivered', 'cancelled', 'returned']))
                            <button class="btn btn_info buy-again" data-order-id="{{ $order->id }}">
                                <i class="fas fa-shopping-cart me-1"></i>
                                Mua lại đơn hàng
                            </button>
                        @endif
                        
                        @if($order->canBeCancelled())
                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn_danger w-100 cancel-order">
                                    <i class="fas fa-times me-1"></i>
                                    Hủy đơn hàng
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('orders.index') }}" class="btn btn_secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>

            {{-- Modal yêu cầu trả hàng --}}
            @if($order->canRequestReturn())
            <div class="modal fade" id="returnModalDetail" tabindex="-1" aria-labelledby="returnModalDetailLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('orders.request-return', $order->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="returnModalDetailLabel">
                                    <i class="fas fa-undo me-2"></i>Yêu cầu trả hàng/hoàn tiền
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Đơn hàng <strong>#{{ $order->order_number }}</strong>
                                    <br>Bạn còn <strong>{{ $order->getReturnDaysRemaining() }} ngày</strong> để yêu cầu trả hàng.
                                </div>
                                <div class="mb-3">
                                    <label for="return_reason_detail" class="form-label fw-bold">
                                        Lý do trả hàng <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="return_reason_detail" 
                                              name="return_reason" rows="4" 
                                              placeholder="Vui lòng mô tả chi tiết lý do bạn muốn trả hàng (ít nhất 10 ký tự)..." 
                                              required minlength="10" maxlength="1000"></textarea>
                                    <div class="form-text">Ví dụ: Sản phẩm bị lỗi, sai màu, sai size, không đúng mô tả...</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-paper-plane me-1"></i>Gửi yêu cầu trả hàng
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
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
    
    /* Badge trạng thái đơn hàng - màu vàng nhạt */
    .badge.bg-warning {
      background: var(--badge-warning) !important;
    }
    
    /* Badge trạng thái thanh toán - màu xanh lá */
    .badge.bg-success {
        background-color: #4CAF50 !important;
        color: white !important;
    }
    
    /* Nút "Mua lại đơn hàng" - màu xanh dương */
    .btn_info {
        background: var(--btn-info) !important;
        border: none !important;
        color: #ffffff !important;
        font-weight: 600;
        border-radius: 0 !important;
    }
    
    /* Nút "Hủy đơn hàng" - màu đỏ */
    .btn_danger {
        background: var(--btn-danger) !important;
        border: none !important;
        color: #ffffff !important;
        font-weight: 600;
        border-radius: 0 !important;
    }
    
    /* Nút "Quay lại danh sách" - màu xám */
    .btn_secondary {
        background: var(--btn-secondary) !important;
        border: none !important;
        color: #ffffff !important;
        font-weight: 600;
        border-radius: 0 !important;
    }
    /* Giá tổng cộng - màu xanh dương */
    .text-primary {
        color: #2196F3 !important;
    }
    
    /* Giữ nguyên các style khác */
    .order-item-detail {
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .order-item-detail:hover {
        background-color: #e9ecef;
    }

    .product-attributes .badge {
        font-size: 0.75rem;
    }

    .payment-summary {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.375rem;
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-marker {
        position: absolute;
        left: -1.5rem;
        top: 0.25rem;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.875rem;
    }

    .timeline-marker.completed {
        background-color: #28a745;
    }

    .timeline-marker.cancelled {
        background-color: #dc3545;
    }

    .timeline-content {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.375rem;
        margin-left: 1rem;
    }

    .customer-info p,
    .payment-info p {
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
    }

    .btn {
        border-radius: 0.375rem;
    }

    .breadcrumb-item a {
        color: #007bff;
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        text-decoration: underline;
    }
</style>

<script>
    // Confirm before canceling order
    document.querySelectorAll('.cancel-order').forEach(button => {
        button.addEventListener('click', function (e) {
            if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
                e.preventDefault();
            }
        });
    });

    // Buy again functionality
    document.querySelectorAll('.buy-again').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const orderId = this.getAttribute('data-order-id');

            if (!orderId) {
                alert('Không thể mua lại đơn hàng này!');
                return;
            }

            // Tìm form gần nhất
            const form = this.closest('.card-body').querySelector('.buy-again-order-form');
            if (form) {
                form.submit();
            }
        });
    });
</script>
@endsection

