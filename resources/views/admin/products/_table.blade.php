<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>Ảnh bìa</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá gốc</th>
                    <th>Tồn kho</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th>Biến thể</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>

                        <td>
                            @if($product->product_image)
                                <img src="{{ asset('storage/' . $product->product_image) }}" 
                                     alt="Ảnh bìa" 
                                     class="product-image"
                                     width="60" height="60">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center product-image" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>

                        <td>{{ $product->name }}</td>
                        <td><strong class="text-success">{{ number_format($product->price) }}đ</strong></td>
                        <td>
                            @if($product->stock > 0)
                                <span class="badge bg-success">{{ $product->stock }}</span>
                            @else
                                <span class="badge bg-danger">Hết hàng</span>
                            @endif
                        </td>
                        <td>{{ $product->category->name ?? '—' }}</td>
                        <td>{{ $product->brand->name ?? '—' }}</td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $product->variants_count }}</span>
                        </td>

                        <td class="text-center">
                            <a href="{{ route('admin.products.show', $product->slug) }}" 
                               class="btn btn-sm btn-info action-btn" 
                               data-bs-toggle="tooltip"
                               title="Chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product->slug) }}" 
                               class="btn btn-sm btn-warning action-btn" 
                               data-bs-toggle="tooltip"
                               title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->slug) }}"
                                method="POST" class="delete-form d-inline"
                                data-id="{{ $product->slug}}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger delete-btn action-btn"
                                     data-bs-toggle="tooltip" title="Xóa sản phẩm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                          </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Phân trang --}}
@if ($products->count() > 0)
    <div class="card-footer bg-white py-3">
        <div class="d-flex justify-content-center">
            {!! $products->links('pagination::bootstrap-5') !!}
        </div>
    </div>
@endif
