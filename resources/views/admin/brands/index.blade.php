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
                <div class="">
                    @if ($brands->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có thương hiệu nào</h5>
                            <p class="text-muted small">Bắt đầu bằng cách thêm thương hiệu đầu tiên</p>
                        </div>
                    @else
                        <table class="table">
                            <thead>
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
                                            <a href="{{ route('admin.brands.index', ['edit' => $brand->id]) }}"
                                               class="btn btn-warning btn-sm"
                                               data-bs-toggle="tooltip" 
                                               title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.brands.destroy', $brand->id) }}"
                                                  method="POST" 
                                                  class="delete-form d-inline"
                                                  data-id="{{ $brand->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm delete-btn"
                                                        data-bs-toggle="tooltip" 
                                                        title="Xóa thương hiệu">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            @if ($brands->count() > 0)
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-center">
                        {{ $brands->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
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
/* 
========================================
CSS VARIABLES - DỄ DÀNG TÙY CHỈNH
========================================

Để thay đổi màu sắc hoặc style, chỉ cần sửa các giá trị bên dưới:

🎨 MÀU SẮC:
- --table-hover: Màu khi hover vào hàng table
- --header-bg: Màu nền header table
- --card-header-bg: Màu nền header card

🔘 BORDER RADIUS:
- --border-radius: Bo góc cho table, card
- --btn-radius: Bo góc cho buttons
- --badge-radius: Bo góc cho badges

📏 KHOẢNG CÁCH:
- --table-padding: Padding cho cells trong table
- --btn-padding: Padding cho buttons
- --badge-padding: Padding cho badges

Ví dụ: Để đổi màu hover thành đỏ: --table-hover: #ff0000;
*/
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

/* Card Styling */
.card {
    border: none;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: 0 8px 32px var(--card-shadow);
    background: var(--card-bg);
}

.card-header {
    background: var(--card-header-bg);
    color: var(--card-header-text);
    border: none;
    padding: 20px 24px;
}

.card-title {
    margin: 0;
    font-weight: 700;
    font-size: 1.25rem;
    letter-spacing: 0.5px;
}

/* Pagination Styling */
.pagination {
    margin-bottom: 0;
    gap: 4px;
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 0;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.page-link {
    border: none;
    border-radius: 0;
    margin: 0 2px;
    color: #2c3e50;
    font-weight: 500;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
    color: #667eea;
    transform: translateY(-1px);
}

/* SweetAlert Custom Styling */
.swal2-container .category-trash-alert.swal2-popup {
    width: 400px !important;
    font-size: 18px !important;
    padding: 20px !important;
    border-radius: 8px !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
}

.swal2-container .category-trash-alert .swal2-title {
    font-size: 20px !important;
    font-weight: 700 !important;
    color: #2c3e50 !important;
}

.swal2-container .category-trash-alert .swal2-html-container {
    font-size: 14px !important;
    margin-bottom: 10px !important;
    color: #5a6c7d !important;
}

.swal2-container .category-trash-alert .swal2-icon {
    width: 80px !important;
    height: 80px !important;
    margin: 0 auto !important;
}

.swal2-container .category-trash-alert .swal2-actions {
    gap: 12px !important;
    margin: 0 !important;
}

.swal2-container .category-trash-alert .swal2-styled {
    padding: 10px 20px !important;
    font-size: 14px !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
}

/* Action Buttons Container */
.table tbody td:last-child {
    white-space: nowrap;
}

.table tbody td:last-child .btn {
    margin: 0 2px;
}

/* Form Styling */
.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-label {
    color: #2c3e50;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .table {
        font-size: 0.8rem;
    }
    
    .table thead th,
    .table tbody td {
        padding: 12px 8px;
    }
    
    .btn-sm {
        padding: 6px 10px;
        font-size: 0.75rem;
    }
    
    .card-header .input-group {
        width: 200px !important;
    }
}

@media (max-width: 576px) {
    .card-header .input-group {
        width: 100% !important;
        margin-top: 10px;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
    }
}
</style>
@endsection
