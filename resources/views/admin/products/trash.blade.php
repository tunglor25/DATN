@extends('layouts.app')

@section('content')
<div class="">
    <div class="mb-3">
        <h5 class="mb-0">Danh sách sản phẩm đã xóa</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>Ảnh bìa</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá gốc</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trashedProducts as $product)
                    <tr>
                        <td>{{ ($trashedProducts->currentPage() - 1) * $trashedProducts->perPage() + $loop->iteration }}</td>

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
                        <td>{{ $product->category->name ?? '—' }}</td>
                        <td>{{ $product->brand->name ?? '—' }}</td>

                        <td class="text-center">
                           <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="d-inline restore-form" data-name="{{ $product->name }}">
                                 @csrf
                                 <button type="button" class="btn btn-success btn-sm restore-btn" title="Khôi phục">
                                    <i class="fas fa-undo me-1"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không có sản phẩm nào đã bị xóa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Phân trang --}}
<div class="mt-3 d-flex justify-content-center">
    {!! $trashedProducts->links('pagination::bootstrap-4') !!}
</div>
@endsection
@section('styles')
<style>
     .swal2-container .category-trash-alert.swal2-popup {
        width: 400px !important;
        font-size: 18px !important;
        padding: 10px !important;
    }

    .swal2-container .category-trash-alert .swal2-title {
        font-size: 20px !important;
        font-weight: bold !important;
    }

    .swal2-container .category-trash-alert .swal2-html-container {
        font-size: 14px !important;
        margin-bottom: 10px !important;
    }

    .swal2-container .category-trash-alert .swal2-icon {
        width: 80px !important;
        height: 80px !important;
        margin: 0 auto !important;
    }

    .swal2-container .category-trash-alert .swal2-actions {
        gap: 10px !important;
        margin: 0 !important;
    }

    .swal2-container .category-trash-alert .swal2-styled {
        padding: 8px 16px !important;
        font-size: 13px !important;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                delay: { show: 300, hide: 100 }
            });
        });

        // Restore confirmation
        document.querySelectorAll('.restore-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.restore-form');
                const productName = form.getAttribute('data-name');

                Swal.fire({
                    title: 'Xác nhận khôi phục',
                    html: `Bạn có chắc chắn muốn khôi phục sản phẩm <strong>"${productName}"</strong>?`,
                    icon: 'question',
                    iconColor: '#28a745',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-undo me-1"></i> Khôi phục',
                    cancelButtonText: '<i class="fas fa-times me-1"></i> Hủy',
                    reverseButtons: true,
                    width: '400px',
                    customClass: {
                        popup: 'category-trash-alert'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Delete confirmation
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.delete-form');
                const productName = form.getAttribute('data-name');

                Swal.fire({
                    title: 'Cảnh báo!',
                    html: `Bạn có chắc chắn muốn <strong>xóa vĩnh viễn</strong> danh mục <strong>"${productName}"</strong>?`,
                    icon: 'warning',
                    iconColor: '#dc3545',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Xóa vĩnh viễn',
                    cancelButtonText: '<i class="fas fa-times me-1"></i> Hủy',
                    reverseButtons: true,
                    width: '400px',
                    customClass: {
                        popup: 'category-trash-alert'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection

