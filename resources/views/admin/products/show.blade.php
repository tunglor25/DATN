@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}" class="text-decoration-none" style="color: #667eea;">Sản phẩm</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0" style="color: #1a202c;">{{ $product->name }}</h4>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary px-3">
                <i class="fas fa-pen me-1"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-3">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left: Product Image --}}
        <div class="col-lg-5">
            <div class="card product-image-card">
                <div class="card-body p-4 text-center">
                    @if($product->product_image)
                        <img src="{{ asset('storage/' . $product->product_image) }}" 
                             class="product-detail-img" 
                             alt="{{ $product->name }}">
                    @else
                        <div class="no-image-box">
                            <i class="fas fa-image"></i>
                            <p>Chưa có ảnh sản phẩm</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="row g-3 mt-1">
                <div class="col-4">
                    <div class="mini-stat text-center">
                        <div class="mini-stat-value" style="color: #667eea;">{{ number_format($product->price) }}₫</div>
                        <div class="mini-stat-label">Giá gốc</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mini-stat text-center">
                        <div class="mini-stat-value" style="color: {{ $product->stock > 10 ? '#38a169' : ($product->stock > 0 ? '#ed8936' : '#e53e3e') }};">{{ $product->stock }}</div>
                        <div class="mini-stat-label">Tồn kho</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mini-stat text-center">
                        <div class="mini-stat-value" style="color: #5a67d8;">{{ $product->variants->count() }}</div>
                        <div class="mini-stat-label">Biến thể</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Product Info --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2" style="color: #667eea;"></i>Thông tin sản phẩm
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-link me-2"></i>Slug</div>
                            <div class="info-value"><code>{{ $product->slug }}</code></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-folder-open me-2"></i>Danh mục</div>
                            <div class="info-value">
                                @if($product->category)
                                    <span class="category-pill"><i class="fas fa-folder me-1"></i>{{ $product->category->name }}</span>
                                @else
                                    <span class="text-muted">Chưa phân loại</span>
                                @endif
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-copyright me-2"></i>Thương hiệu</div>
                            <div class="info-value">
                                @if($product->brand)
                                    <span class="brand-pill"><i class="fas fa-tag me-1"></i>{{ $product->brand->name }}</span>
                                @else
                                    <span class="text-muted">Chưa có</span>
                                @endif
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-toggle-on me-2"></i>Trạng thái</div>
                            <div class="info-value">
                                @if($product->is_active)
                                    <span class="status-pill status-active-pill">
                                        <span class="dot"></span> Đang bán
                                    </span>
                                @else
                                    <span class="status-pill status-inactive-pill">
                                        <span class="dot"></span> Đã ẩn
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-calendar me-2"></i>Ngày tạo</div>
                            <div class="info-value">{{ $product->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-clock me-2"></i>Cập nhật</div>
                            <div class="info-value">{{ $product->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Product Description --}}
    @if($product->description)
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="card-title mb-0">
                <i class="fas fa-align-left me-2" style="color: #38a169;"></i>Mô tả sản phẩm
            </h6>
        </div>
        <div class="card-body">
            <div class="description-content">
                {!! $product->description !!}
            </div>
        </div>
    </div>
    @endif

    {{-- Variants Section --}}
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0">
                <i class="fas fa-layer-group me-2" style="color: #ed8936;"></i>Biến thể sản phẩm
            </h6>
            <span class="variant-count-badge">{{ $product->variants->count() }} biến thể</span>
        </div>
        <div class="card-body p-0">
            @if ($product->variants->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-cubes" style="font-size: 3rem; color: #cbd5e0;"></i>
                    </div>
                    <h6 class="text-muted fw-semibold">Sản phẩm đơn giản</h6>
                    <p class="text-muted small">Sản phẩm này không có biến thể</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>SKU</th>
                                <th>THUỘC TÍNH</th>
                                <th>GIÁ</th>
                                <th>TỒN KHO</th>
                                <th style="width: 80px;" class="text-center">ẢNH</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variants as $index => $variant)
                                <tr>
                                    <td><span class="text-muted">{{ $index + 1 }}</span></td>
                                    <td><code>{{ $variant->sku }}</code></td>
                                    <td>
                                        @foreach($variant->attributeValues as $value)
                                            <span class="attr-pill">
                                                {{ $value->attribute->name }}: <strong>{{ $value->value }}</strong>
                                            </span>
                                        @endforeach
                                    </td>
                                    <td><span class="fw-bold" style="color: #2d3748;">{{ number_format($variant->price) }}₫</span></td>
                                    <td>
                                        @if($variant->stock > 10)
                                            <span class="stock-badge stock-ok">{{ $variant->stock }}</span>
                                        @elseif($variant->stock > 0)
                                            <span class="stock-badge stock-low">{{ $variant->stock }}</span>
                                        @else
                                            <span class="stock-badge stock-out">Hết</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($variant->image)
                                            <img src="{{ asset('storage/' . $variant->image) }}"
                                                 alt="Ảnh biến thể"
                                                 class="variant-thumb">
                                        @else
                                            <span class="text-muted"><i class="fas fa-image"></i></span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Product Image Card */
.product-image-card {
    overflow: hidden;
}
.product-detail-img {
    max-height: 350px;
    max-width: 100%;
    object-fit: contain;
    border-radius: 12px;
    transition: transform 0.3s ease;
}
.product-detail-img:hover {
    transform: scale(1.03);
}
.no-image-box {
    padding: 60px 20px;
    color: #cbd5e0;
}
.no-image-box i {
    font-size: 3.5rem;
    margin-bottom: 12px;
    display: block;
}
.no-image-box p {
    font-size: 0.9rem;
    margin: 0;
}

/* Mini Stats */
.mini-stat {
    background: #fff;
    border-radius: 12px;
    padding: 16px 12px;
    box-shadow: 0 1px 8px rgba(0,0,0,0.06);
    border: 1px solid #f0f4f8;
}
.mini-stat-value {
    font-size: 1.25rem;
    font-weight: 800;
    line-height: 1;
}
.mini-stat-label {
    font-size: 0.7rem;
    color: #718096;
    font-weight: 500;
    margin-top: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Info Grid */
.info-grid {
    padding: 0;
}
.info-row {
    display: flex;
    align-items: center;
    padding: 14px 20px;
    border-bottom: 1px solid #f0f4f8;
    transition: background 0.15s ease;
}
.info-row:last-child {
    border-bottom: none;
}
.info-row:hover {
    background: #f7fafc;
}
.info-label {
    flex: 0 0 180px;
    font-weight: 600;
    color: #4a5568;
    font-size: 0.85rem;
}
.info-value {
    flex: 1;
    color: #2d3748;
    font-size: 0.875rem;
}

/* Pills */
.category-pill {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    background: #ebf4ff;
    color: #3c4d7e;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 500;
    border: 1px solid #c3dafe;
}
.brand-pill {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    background: #f0f4f8;
    color: #4a5568;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 500;
    border: 1px solid #e2e8f0;
}
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 600;
}
.status-pill .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}
.status-active-pill {
    background: #f0fff4;
    color: #22543d;
    border: 1px solid #c6f6d5;
}
.status-active-pill .dot {
    background: #38a169;
}
.status-inactive-pill {
    background: #f7fafc;
    color: #718096;
    border: 1px solid #e2e8f0;
}
.status-inactive-pill .dot {
    background: #a0aec0;
}

/* Variant Count Badge */
.variant-count-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    background: #f0f5ff;
    color: #5a67d8;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid #c3dafe;
}

/* Attribute Pills in table */
.attr-pill {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    background: #f0f4f8;
    color: #4a5568;
    border-radius: 6px;
    font-size: 0.75rem;
    margin: 2px 4px 2px 0;
    border: 1px solid #e2e8f0;
}

/* Stock Badges */
.stock-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}
.stock-ok { background: #f0fff4; color: #22543d; border: 1px solid #c6f6d5; }
.stock-low { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.stock-out { background: #fff5f5; color: #9b2c2c; border: 1px solid #fed7d7; }

/* Variant Thumbnail */
.variant-thumb {
    width: 44px;
    height: 44px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #f0f4f8;
    transition: transform 0.2s ease;
}
.variant-thumb:hover {
    transform: scale(1.15);
}

/* Description Content */
.description-content {
    font-size: 0.9rem;
    line-height: 1.7;
    color: #2d3748;
}
.description-content img {
    max-width: 100%;
    border-radius: 8px;
    margin: 12px 0;
}
</style>
@endsection