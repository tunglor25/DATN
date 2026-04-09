@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Form thêm/sửa --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-{{ isset($editBrand) ? 'edit' : 'plus' }}-circle me-2"></i>
                        {{ isset($editBrand) ? 'Sửa thương hiệu' : 'Thêm thương hiệu' }}
                    </h3>
                </div>
                <div class="card-body">
                    <form
                        action="{{ isset($editBrand) ? route('admin.brands.update', $editBrand->id) : route('admin.brands.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($editBrand))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">
                                Tên thương hiệu <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', isset($editBrand) ? $editBrand->name : '') }}" 
                                placeholder="Nhập tên thương hiệu..."
                                required>
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label fw-semibold">Logo thương hiệu</label>
                            <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                            @if (isset($editBrand) && $editBrand->logo)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($editBrand->logo) }}" alt="Logo" width="100"
                                        class="rounded border">
                                </div>
                            @endif
                            @error('logo')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-{{ isset($editBrand) ? 'primary' : 'success' }}">
                                <i class="fas fa-{{ isset($editBrand) ? 'save' : 'plus' }} me-1"></i>
                                {{ isset($editBrand) ? 'Cập nhật' : 'Lưu' }}
                            </button>
                            @if (isset($editBrand))
                                <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Hủy
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
                        <h3 class="card-title">Danh sách thương hiệu</h3>
                        <div class="d-flex gap-2">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Tìm kiếm thương hiệu...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    @if ($brands->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có thương hiệu nào</h5>
                            <p class="text-muted small">Bắt đầu bằng cách thêm thương hiệu đầu tiên</p>
                        </div>
                    @else
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>STT</th>
                                    <th>TÊN</th>
                                    <th>LOGO</th>
                                    <th>SỐ SẢN PHẨM</th>
                                    <th>NGÀY TẠO</th>
                                    <th>HÀNH ĐỘNG</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brands as $index => $brand)
                                    <tr>
                                        <td>{{ ($brands->currentPage() - 1) * $brands->perPage() + $index + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $brand->name }}</div>
                                        </td>
                                        <td>
                                            @if ($brand->logo)
                                                <img src="{{ Storage::url($brand->logo) }}" 
                                                     alt="{{ $brand->name }}" 
                                                     class="rounded border" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $brand->products->count() }} sản phẩm
                                            </span>
                                        </td>
                                        <td>{{ $brand->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.brands.index', ['edit' => $brand->id]) }}"
                                                   class="btn btn-warning btn-sm action-btn"
                                                   data-bs-toggle="tooltip" 
                                                   title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.brands.destroy', $brand->id) }}"
                                                      method="POST" 
                                                      class="delete-form d-inline border-0"
                                                      data-id="{{ $brand->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm action-btn delete-btn"
                                                            data-bs-toggle="tooltip" 
                                                            title="Xóa thương hiệu">
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
                @if ($brands->hasPages())
                    <div class="card-footer bg-white py-3 border-top-0">
                        <div class="d-flex justify-content-center">
                            {{ $brands->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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

    // Search functionality
    $('#searchInput').on('input', function() {
        const query = $(this).val().toLowerCase();
        $('tbody tr').each(function() {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(query) > -1);
        });
    });

    // Delete confirmation
    $(document).on('click', '.delete-btn', function() {
        const form = $(this).closest('form');
        const brandName = form.closest('tr').find('td:nth-child(2)').text().trim();
        
        Swal.fire({
            title: 'Cảnh báo!',
            html: `Bạn có chắc chắn muốn <strong>xóa thương hiệu</strong> "${brandName}"?`,
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
