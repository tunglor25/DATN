@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark mb-0">
            <i class="fas fa-trash-restore text-warning me-2"></i>
            Danh mục đã xóa mềm
        </h3>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay về
        </a>
    </div>

    <!-- Main Table Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 fw-semibold">ID</th>
                            <th class="border-0 fw-semibold">Tên</th>
                            <th class="border-0 fw-semibold">Slug</th>
                            <th class="border-0 fw-semibold">Danh mục cha</th>
                            <th class="border-0 fw-semibold">Đã xóa lúc</th>
                            <th class="border-0 fw-semibold text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                        <tr class="align-middle">
                            <td class="fw-bold text-primary">#{{ $cat->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-folder text-muted me-2"></i>
                                    <span>{{ $cat->name }}</span>
                                </div>
                            </td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded small">{{ $cat->slug }}</code>
                            </td>
                            <td>
                                @if($cat->parent)
                                    <span class="badge bg-light text-dark border">
                                        {{ $cat->parent->name }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-muted">
                                {{ $cat->deleted_at->format('d-m-Y H:i') }}
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <form action="{{ route('admin.categories.restore', $cat->id) }}" method="POST" class="d-inline restore-form" data-name="{{ $cat->name }}">
                                        @csrf
                                        <button type="button" class="btn btn-success btn-sm restore-btn" title="Khôi phục">
                                            <i class="fas fa-undo me-1"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.categories.force-delete', $cat->id) }}" method="POST" class="d-inline delete-form" data-name="{{ $cat->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm delete-btn" title="Xóa vĩnh viễn">
                                            <i class="fas fa-trash-alt me-1"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $categories->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .btn {
        transition: all 0.2s ease;
        border-radius: 6px;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .btn-group .btn {
        margin: 0 2px;
    }
    
    .table th {
        font-size: 0.875rem;
        color: #495057;
        background-color: #f8f9fa !important;
    }
    
    code {
        font-size: 0.8rem;
        color: #6f42c1;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    /* SweetAlert Custom Styles */
/* Custom SweetAlert Styles - Chung cho cả restore và delete */
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
                const categoryName = form.getAttribute('data-name');

                Swal.fire({
                    title: 'Xác nhận khôi phục',
                    html: `Bạn có chắc chắn muốn khôi phục danh mục <strong>"${categoryName}"</strong>?`,
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
                const categoryName = form.getAttribute('data-name');

                Swal.fire({
                    title: 'Cảnh báo!',
                    html: `Bạn có chắc chắn muốn <strong>xóa vĩnh viễn</strong> danh mục <strong>"${categoryName}"</strong>?`,
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
