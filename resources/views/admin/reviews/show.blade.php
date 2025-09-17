@extends('layouts.app')

@section('title', 'Chi tiết đánh giá - ' . $user->name)

@section('content')
<div class="container-fluid">
    {{-- Header với thông tin người dùng --}}
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title">
                        <i class="fas fa-user me-2"></i>
                        Chi tiết đánh giá của {{ $user->name }}
                    </h3>
                    <small class="text-muted">Email: {{ $user->email }}</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-white"></i>
                        </div>
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-2">{{ $user->email }}</p>
                        <div class="badge bg-info">
                            <i class="fas fa-calendar me-1"></i>
                            Tham gia: {{ $user->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h4 class="text-primary mb-1">{{ $reviews->count() }}</h4>
                                    <small class="text-muted">Tổng số đánh giá</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h4 class="text-success mb-1">{{ $reviews->where('is_verified', true)->count() }}</h4>
                                    <small class="text-muted">Đánh giá đã xác thực</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h4 class="text-warning mb-1">{{ $reviews->where('is_hidden', true)->count() }}</h4>
                                    <small class="text-muted">Đánh giá bị ẩn</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách đánh giá --}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-star me-2"></i>
                    Danh sách đánh giá ({{ $reviews->count() }})
                </h3>
            </div>
        </div>
        <div class="">
            @if ($reviews->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có đánh giá nào</h5>
                    <p class="text-muted small">Người dùng này chưa đánh giá sản phẩm nào</p>
                </div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>SẢN PHẨM</th>
                            <th>ĐÁNH GIÁ</th>
                            <th>BÌNH LUẬN</th>
                            <th>TRẠNG THÁI</th>
                            <th>NGÀY ĐÁNH GIÁ</th>
                            <th>HÀNH ĐỘNG</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $index => $review)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                    @if ($review->product)
                                            @if ($review->product->product_image)
                                                <img src="{{ asset('storage/' . $review->product->product_image) }}" 
                                                     alt="{{ $review->product->name }}"
                                                     class="rounded me-3" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $review->product->name }}</div>
                                                <small class="text-muted">{{ $review->product->brand->name ?? 'N/A' }}</small>
                                            </div>
                                        @else
                                            <div class="bg-danger rounded d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-exclamation-triangle text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-danger">Sản phẩm đã bị xóa</div>
                                                <small class="text-muted">Không thể hiển thị thông tin</small>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="ms-2 fw-semibold">{{ $review->rating }}/5</span>
                                    </div>
                                </td>
                                <td>
                                    @if ($review->comment)
                                        <div class="text-truncate" style="max-width: 300px;" 
                                             data-bs-toggle="tooltip" 
                                             title="{{ $review->comment }}">
                                            {{ $review->comment }}
                                        </div>
                                        @if ($review->hasImages())
                                            <small class="text-info">
                                                <i class="fas fa-image me-1"></i>
                                                {{ count($review->images) }} ảnh
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">Không có bình luận</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @if ($review->is_verified)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Đã xác thực
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-clock me-1"></i>Chưa xác thực
                                            </span>
                                        @endif
                                        
                                        @if ($review->is_hidden)
                                            <span class="badge bg-danger">
                                                <i class="fas fa-eye-slash me-1"></i>Đã ẩn
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="fas fa-eye me-1"></i>Đang hiện
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $review->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        {{-- Xem chi tiết --}}
                                        <button type="button" 
                                                class="btn btn-info btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#reviewModal{{ $review->id }}"
                                                data-bs-toggle="tooltip" 
                                                title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        {{-- Ẩn/Hiện bình luận --}}
                                        @if ($review->comment)
                                            <form action="{{ route('admin.reviews.hide-comment', $review->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Bạn có chắc muốn ẩn bình luận này?')">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-warning btn-sm"
                                                        data-bs-toggle="tooltip" 
                                                        title="Ẩn bình luận">
                                                    <i class="fas fa-comment-slash"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        {{-- Ẩn/Hiện đánh giá --}}
                                        <form action="{{ route('admin.reviews.toggle-hidden', $review->id) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-{{ $review->is_hidden ? 'success' : 'danger' }} btn-sm"
                                                    data-bs-toggle="tooltip" 
                                                    title="{{ $review->is_hidden ? 'Hiện đánh giá' : 'Ẩn đánh giá' }}">
                                                <i class="fas fa-{{ $review->is_hidden ? 'eye' : 'eye-slash' }}"></i>
                                            </button>
                                        </form>
                                        
                                        {{-- Xác thực/Bỏ xác thực --}}
                                        <form action="{{ route('admin.reviews.toggle-verified', $review->id) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-{{ $review->is_verified ? 'secondary' : 'success' }} btn-sm"
                                                    data-bs-toggle="tooltip" 
                                                    title="{{ $review->is_verified ? 'Bỏ xác thực' : 'Xác thực' }}">
                                                <i class="fas fa-{{ $review->is_verified ? 'times' : 'check' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

{{-- Modal chi tiết đánh giá --}}
@foreach ($reviews as $review)
    <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-star me-2"></i>
                        Chi tiết đánh giá
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Thông tin sản phẩm --}}
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-box me-2"></i>
                                        Thông tin sản phẩm
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                                                 @if ($review->product)
                                        @if ($review->product->product_image)
                                            <img src="{{ asset('storage/' . $review->product->product_image) }}" 
                                                 alt="{{ $review->product->name }}"
                                                 class="img-fluid rounded mb-3" 
                                                 style="max-height: 200px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" 
                                                 style="height: 200px;">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <h6 class="fw-semibold">{{ $review->product->name }}</h6>
                                        <p class="text-muted mb-2">{{ $review->product->brand->name ?? 'N/A' }}</p>
                                        <div class="badge bg-primary">{{ $review->product->category->name ?? 'N/A' }}</div>
                                        
                                        {{-- Hiển thị thêm hình ảnh sản phẩm --}}
                                        @if ($review->product->variants && $review->product->variants->count() > 0)
                                            <div class="mt-3">
                                                <h6 class="fw-semibold mb-2">Hình ảnh biến thể:</h6>
                                                <div class="row">
                                                    @foreach ($review->product->variants as $variant)
                                                        @if ($variant->image)
                                                            <div class="col-6 mb-2">
                                                                <img src="{{ asset('storage/' . $variant->image) }}" 
                                                                     alt="Hình ảnh biến thể"
                                                                     class="img-fluid rounded"
                                                                     style="height: 80px; object-fit: cover;">
                                                                <small class="d-block text-muted mt-1">{{ $variant->sku }}</small>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="bg-danger rounded d-flex align-items-center justify-content-center mb-3" 
                                             style="height: 200px;">
                                            <i class="fas fa-exclamation-triangle fa-3x text-white"></i>
                                        </div>
                                        <h6 class="fw-semibold text-danger">Sản phẩm đã bị xóa</h6>
                                        <p class="text-muted mb-2">Không thể hiển thị thông tin</p>
                                        <div class="badge bg-danger">Đã xóa</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        {{-- Thông tin đánh giá --}}
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-star me-2"></i>
                                        Đánh giá chi tiết
                                    </h6>
                                </div>
                                <div class="card-body">
                                    {{-- Rating --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Số sao đánh giá:</label>
                                        <div class="d-flex align-items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}" 
                                                   style="font-size: 20px;"></i>
                                            @endfor
                                            <span class="ms-2 fw-bold fs-5">{{ $review->rating }}/5</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Bình luận --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Bình luận:</label>
                                        @if ($review->comment)
                                            <div class="border rounded p-3">
                                                {{ $review->comment }}
                                            </div>
                                        @else
                                            <div class="text-muted">Không có bình luận</div>
                                        @endif
                                    </div>
                                    
                                    {{-- Hình ảnh đánh giá --}}
                                    @if ($review->hasImages())
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Hình ảnh đánh giá:</label>
                                            <div class="row">
                                                @foreach ($review->images as $image)
                                                    <div class="col-md-4 mb-2">
                                                        <img src="{{ asset('storage/' . $image) }}" 
                                                             alt="Hình ảnh đánh giá"
                                                             class="img-fluid rounded"
                                                             style="height: 120px; object-fit: cover;">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    
                                    {{-- Thông tin đơn hàng --}}
                                    @if ($review->order)
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Thông tin đơn hàng:</label>
                                            <div class="border rounded p-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong>Mã đơn hàng:</strong> #{{ $review->order->id }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Trạng thái:</strong> 
                                                        <span class="badge bg-{{ $review->order->status == 'delivered' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($review->order->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    {{-- Thông tin thời gian --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Thông tin thời gian:</label>
                                        <div class="border rounded p-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Ngày đánh giá:</strong><br>
                                                    {{ $review->created_at->format('d/m/Y H:i:s') }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Cập nhật lần cuối:</strong><br>
                                                    {{ $review->updated_at->format('d/m/Y H:i:s') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Trạng thái --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Trạng thái:</label>
                                        <div class="d-flex gap-2">
                                            @if ($review->is_verified)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Đã xác thực
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-clock me-1"></i>Chưa xác thực
                                                </span>
                                            @endif
                                            
                                            @if ($review->is_hidden)
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-eye-slash me-1"></i>Đã ẩn
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="fas fa-eye me-1"></i>Đang hiện
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection


@section('scripts')
<script>
    // Khởi tạo tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
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
    border-radius: var(--border-radius) !important;
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
    color: #ffffff;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: var(--badge-radius);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.bg-danger {
    background: var(--badge-danger) !important;
    color: #ffffff;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: var(--badge-radius);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.bg-primary {
    background: var(--badge-primary) !important;
    color: #ffffff;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: var(--badge-radius);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.bg-secondary {
    background: var(--badge-secondary) !important;
    color: #ffffff;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: var(--badge-radius);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.bg-info {
    background: var(--badge-info) !important;
    color: #ffffff;
    font-weight: 600;
    padding: var(--badge-padding);
    border-radius: var(--badge-radius);
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
    border-radius: var(--btn-radius);
    padding: var(--btn-padding);
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
    border-radius: var(--btn-radius);
    padding: var(--btn-padding);
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
    border-radius: var(--btn-radius);
    padding: var(--btn-padding);
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
    border-radius: var(--btn-radius);
    padding: var(--btn-padding);
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
    border-radius: var(--btn-radius);
    padding: var(--btn-padding);
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
    border-radius: var(--btn-radius);
    padding: var(--btn-padding);
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}
</style>
@endsection
