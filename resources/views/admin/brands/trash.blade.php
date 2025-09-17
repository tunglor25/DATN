@extends('layouts.app')

@section('content')
    <h1>Thùng rác thương hiệu</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Logo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brands as $index => $brand)
                <tr>
                    <td>{{ $loop->iteration + ($brands->currentPage() - 1) * $brands->perPage() }}</td>
                    <td>{{ $brand->name }}</td>
                    <td>
                        @if ($brand->logo && file_exists(public_path('storage/' . $brand->logo)))
                            <img src="{{ asset('storage/' . $brand->logo) }}" width="100">
                        @else
                            <span>Không có logo</span>
                        @endif
                    </td>
                    <td>
                         <form action="{{ route('admin.brands.restore', $brand->id) }}" method="POST" class="d-inline restore-form" data-name="{{ $brand->name }}">
                                        @csrf
                                        <button type="button" class="btn btn-success btn-sm restore-btn" title="Khôi phục">
                                            <i class="fas fa-undo me-1"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.brands.forceDelete', $brand->id) }}" method="POST" class="d-inline delete-form" data-name="{{ $brand->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm delete-btn" title="Xóa vĩnh viễn">
                                            <i class="fas fa-trash-alt me-1"></i>
                                        </button>
                                    </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách</a>
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
                const brandName = form.getAttribute('data-name');

                Swal.fire({
                    title: 'Xác nhận khôi phục',
                    html: `Bạn có chắc chắn muốn khôi phục thương hiệu <strong>"${brandName}"</strong>?`,
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
                const brandName = form.getAttribute('data-name');

                Swal.fire({
                    title: 'Cảnh báo!',
                    html: `Bạn có chắc chắn muốn <strong>xóa vĩnh viễn</strong> thương hiệu <strong>"${brandName}"</strong>?`,
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

