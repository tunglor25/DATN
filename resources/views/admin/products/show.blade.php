@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>
            <i class="fas fa-eye me-2"></i>
            Chi tiết sản phẩm: {{ $product->name }}
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i>
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Thông tin sản phẩm -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <!-- Ảnh sản phẩm -->
                <div class="col-md-4 mb-3">
                    @if($product->product_image)
                        <img src="{{ asset('storage/' . $product->product_image) }}" 
                             class="img-fluid rounded border" 
                             alt="Ảnh bìa"
                             style="max-height: 250px;">
                    @else
                        <div class="border rounded p-4 text-center bg-light">
                            <i class="fas fa-image fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Không có ảnh</p>
                        </div>
                    @endif
                </div>

                <!-- Thông tin chi tiết -->
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Slug:</strong></td>
                            <td><code>{{ $product->slug }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Giá gốc:</strong></td>
                            <td class="text-primary fw-bold">{{ number_format($product->price) }}đ</td>
                        </tr>
                        <tr>
                            <td><strong>Tồn kho:</strong></td>
                            <td>
                                <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Danh mục:</strong></td>
                            <td>
                                @if($product->category)
                                    <span class="badge bg-info">{{ $product->category->name }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Thương hiệu:</strong></td>
                            <td>
                                @if($product->brand)
                                    <span class="badge bg-secondary">{{ $product->brand->name }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Trạng thái:</strong></td>
                            <td>
                                <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                    {{ $product->is_active ? 'Kích hoạt' : 'Ẩn' }}
                                </span>
                            </td>
                        </tr>
                    </table>

                    @if($product->description)
                    <div class="mt-3">
                        <strong>Mô tả:</strong>
                        <div class="border rounded p-3 mt-2 bg-light">
                            {!! $product->description !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách biến thể -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách biến thể</h5>
            <span class="badge bg-primary">{{ $product->variants->count() }}</span>
        </div>
        <div class="card-body p-0">
            @if ($product->variants->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">Chưa có biến thể</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>SKU</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                                <th>Thuộc tính</th>
                                <th width="80" class="text-center">Ảnh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variants as $variant)
                                <tr>
                                    <td><code>{{ $variant->sku }}</code></td>
                                    <td class="fw-bold">{{ number_format($variant->price) }}đ</td>
                                    <td>
                                        <span class="badge bg-{{ $variant->stock > 0 ? 'success' : 'danger' }}">
                                            {{ $variant->stock }}
                                        </span>
                                    </td>
                                    <td>
                                        @foreach($variant->attributeValues as $value)
                                            <span class="badge bg-secondary me-1">
                                                {{ $value->attribute->name }}: {{ $value->value }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        @if ($variant->image)
                                            <img src="{{ asset('storage/' . $variant->image) }}"
                                                 alt="Ảnh biến thể"
                                                 class="img-thumbnail"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <i class="fas fa-image text-muted"></i>
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