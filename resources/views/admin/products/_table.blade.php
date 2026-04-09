<div class="card">
    <div class="card-body p-0">
        @if($products->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-box-open" style="font-size: 3.5rem; color: #cbd5e0;"></i>
                </div>
                <h5 class="text-muted fw-semibold">Không có sản phẩm nào</h5>
                <p class="text-muted small mb-3">Bắt đầu bằng cách thêm sản phẩm đầu tiên</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle me-1"></i> Thêm sản phẩm
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="productTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 280px;">SẢN PHẨM</th>
                            <th>GIÁ</th>
                            <th>TỒN KHO</th>
                            <th>PHÂN LOẠI</th>
                            <th>BIẾN THỂ</th>
                            <th>TRẠNG THÁI</th>
                            <th style="width: 140px;" class="text-center">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                {{-- STT --}}
                                <td>
                                    <span class="text-muted fw-medium">{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</span>
                                </td>

                                {{-- Product Info: Image + Name + Category --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="product-thumb">
                                            @if($product->product_image)
                                                <img src="{{ asset('storage/' . $product->product_image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="product-img">
                                            @else
                                                <div class="product-img-placeholder">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="product-info">
                                            <a href="{{ route('admin.products.show', $product->slug) }}" 
                                               class="product-name">{{ $product->name }}</a>
                                            <div class="product-meta">
                                                <span class="text-muted">{{ $product->brand->name ?? '—' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Price --}}
                                <td>
                                    <span class="price-tag">{{ number_format($product->price) }}₫</span>
                                </td>

                                {{-- Stock --}}
                                <td>
                                    @if($product->stock > 10)
                                        <span class="stock-badge stock-ok">
                                            <i class="fas fa-check-circle me-1"></i>{{ $product->stock }}
                                        </span>
                                    @elseif($product->stock > 0)
                                        <span class="stock-badge stock-low">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $product->stock }}
                                        </span>
                                    @else
                                        <span class="stock-badge stock-out">
                                            <i class="fas fa-times-circle me-1"></i>Hết hàng
                                        </span>
                                    @endif
                                </td>

                                {{-- Category  --}}
                                <td>
                                    @if($product->category)
                                        <span class="category-pill">
                                            <i class="fas fa-folder-open me-1"></i>{{ $product->category->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Variants Count --}}
                                <td>
                                    @if($product->variants_count > 0)
                                        <span class="variant-count">
                                            <i class="fas fa-layer-group me-1"></i>{{ $product->variants_count }}
                                        </span>
                                    @else
                                        <span class="text-muted small">Không có</span>
                                    @endif
                                </td>

                                {{-- Active Status --}}
                                <td>
                                    @if($product->is_active)
                                        <span class="status-dot status-active">
                                            <span class="dot"></span> Đang bán
                                        </span>
                                    @else
                                        <span class="status-dot status-inactive">
                                            <span class="dot"></span> Ẩn
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="text-center">
                                    <div class="action-group">
                                        <a href="{{ route('admin.products.show', $product->slug) }}" 
                                           class="action-btn action-view"
                                           data-bs-toggle="tooltip" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->slug) }}" 
                                           class="action-btn action-edit"
                                           data-bs-toggle="tooltip" title="Chỉnh sửa">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->slug) }}"
                                            method="POST" class="delete-form d-inline"
                                            data-id="{{ $product->slug }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="action-btn action-delete delete-btn"
                                                 data-bs-toggle="tooltip" title="Xóa sản phẩm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Pagination --}}
@if ($products->count() > 0)
    <div class="d-flex justify-content-between align-items-center px-3 py-3">
        <div class="text-muted small">
            Hiển thị {{ $products->firstItem() }}–{{ $products->lastItem() }} / {{ $products->total() }} sản phẩm
        </div>
        <div>
            {!! $products->links('pagination::bootstrap-5') !!}
        </div>
    </div>
@endif
