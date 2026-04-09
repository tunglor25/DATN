@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Form thêm/sửa --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-{{ isset($category) ? 'edit' : 'plus' }}-circle me-2"></i>
                        {{ isset($category) ? 'Sửa danh mục' : 'Thêm danh mục' }}
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}">
                        @csrf
                        @if(isset($category)) @method('PUT') @endif

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">
                                <i class="fas fa-tag me-1"></i>
                                Tên danh mục <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $category->name ?? '') }}" 
                                   placeholder="Nhập tên danh mục" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="parent_id" class="form-label fw-semibold">
                                <i class="fas fa-sitemap me-1"></i>
                                Danh mục cha
                            </label>
                            <select name="parent_id" id="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                <option value="">-- Không chọn --</option>
                                {!! $categoryOptions !!}
                            </select>
                            @error('parent_id')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-{{ isset($category) ? 'primary' : 'success' }}">
                                <i class="fas fa-{{ isset($category) ? 'save' : 'plus' }} me-1"></i>
                                {{ isset($category) ? 'Cập nhật' : 'Lưu' }}
                            </button>
                            @if(isset($category))
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Hủy
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Bảng danh sách --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Danh sách danh mục</h3>
                        <span class="badge bg-primary">{{ $categories->count() }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        @if ($categories->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-folder fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không có danh mục nào</h5>
                                <p class="text-muted small">Bắt đầu bằng cách thêm danh mục đầu tiên</p>
                            </div>
                        @else
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>TÊN DANH MỤC</th>
                                        <th>SLUG</th>
                                        <th>DANH MỤC CHA</th>
                                        <th>NGÀY TẠO</th>
                                        <th>HÀNH ĐỘNG</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $index => $cat)
                                    <tr>
                                        <td>
                                            <span class="">{{ ($categories->currentPage() - 1) * $categories->perPage() + $index + 1 }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $cat->name }}</div>
                                        </td>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded">{{ $cat->slug }}</code>
                                        </td>
                                        <td>
                                            @if($cat->parent)
                                                <span class="badge bg-info">{{ $cat->parent->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $cat->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.categories.edit', $cat->id) }}" 
                                                   class="btn btn-warning btn-sm action-btn"
                                                   data-bs-toggle="tooltip" 
                                                   title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" 
                                                      method="POST" 
                                                      class="delete-form d-inline"
                                                      data-id="{{ $cat->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm delete-btn action-btn"
                                                            data-bs-toggle="tooltip" 
                                                            title="Xóa danh mục">
                                                        <i class="fas fa-trash-alt"></i>
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
                @if ($categories->hasPages())
                    <div class="card-footer bg-white py-3 border-top-0">
                        <div class="d-flex justify-content-center">
                            {{ $categories->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            delay: { show: 300, hide: 100 }
        });
    });

    // Delete confirmation with SweetAlert
    $(document).on('click', '.delete-btn', function() {
        const form = $(this).closest('form');
        const categoryName = form.closest('tr').find('td:nth-child(2)').text().trim();
        
        Swal.fire({
            title: 'Cảnh báo!',
            html: `Bạn có chắc chắn muốn <strong>xóa danh mục</strong> "${categoryName}"?`,
            icon: 'warning',
            iconColor: '#dc3545',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Xóa ngay',
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
</script>

<style>
/* Page styles - shared admin-styles.css handles all common styles */
code {
    background: #f0f4ff !important;
    color: #5a67d8 !important;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    border: 1px solid #e0e7ff;
}
</style>
@endsection