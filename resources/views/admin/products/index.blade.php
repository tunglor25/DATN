@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1a202c;">
                <i class="fas fa-box-open me-2" style="color: #667eea;"></i>Quản lý sản phẩm
            </h4>
            <p class="text-muted small mb-0">Quản lý tất cả sản phẩm trong cửa hàng</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary px-4">
            <i class="fas fa-plus me-2"></i>Thêm sản phẩm
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #5a67d8 100%);">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $products->total() }}</div>
                    <div class="stat-label">Tổng sản phẩm</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #68d391 0%, #38a169 100%);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ \App\Models\Product::where('is_active', 1)->count() }}</div>
                    <div class="stat-label">Đang bán</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ \App\Models\Product::where('stock', '<=', 10)->where('stock', '>', 0)->count() }}</div>
                    <div class="stat-label">Sắp hết hàng</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fc8181 0%, #e53e3e 100%);">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ \App\Models\Product::where('stock', 0)->count() }}</div>
                    <div class="stat-label">Hết hàng</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="card mb-3">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3 flex-grow-1">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm theo tên, danh mục, thương hiệu...">
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small me-1">
                        <i class="fas fa-sort-amount-down me-1"></i>Mới nhất
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Products Table --}}
    <div id="productTableContainer">
        @include('admin.products._table')
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Initialize tooltips
    function initTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                delay: { show: 300, hide: 100 }
            });
        });
    }
    initTooltips();

    // AJAX Search
    let searchTimer;
    function fetchData(query = '', page = 1) {
        $.ajax({
            url: "{{ route('admin.products.index') }}",
            data: { q: query, page: page },
            success: function (data) {
                $('#productTableContainer').html(data);
                initTooltips();
            }
        });
    }

    $('#searchInput').on('input', function () {
        clearTimeout(searchTimer);
        const query = $(this).val();
        searchTimer = setTimeout(() => fetchData(query), 300);
    });

    // Pagination click
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        const query = $('#searchInput').val();
        fetchData(query, page);
    });

    // Delete confirmation
    $(document).on('click', '.delete-btn', function() {
        const form = $(this).closest('form')[0];
        const productName = $(this).closest('tr').find('.product-name').text().trim();
        
        Swal.fire({
            title: 'Xóa sản phẩm?',
            html: `Bạn có chắc muốn xóa <strong>"${productName}"</strong>?<br><small class="text-muted">Sản phẩm sẽ được chuyển vào thùng rác</small>`,
            icon: 'warning',
            iconColor: '#e53e3e',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonColor: '#718096',
            confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Xóa',
            cancelButtonText: 'Hủy',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endsection

@section('styles')
<style>
/* Stat Cards */
.stat-card {
    display: flex;
    align-items: center;
    gap: 16px;
    background: var(--card-bg);
    border-radius: var(--border-radius);
    padding: 18px 20px;
    box-shadow: var(--card-shadow);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 10px 25px rgba(112, 144, 176, 0.15);
}
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #2b3674;
    line-height: 1;
}
.stat-label {
    font-size: 0.78rem;
    color: #a0aec0;
    font-weight: 600;
    margin-top: 2px;
}

/* Search Box */
.search-box {
    position: relative;
    max-width: 420px;
    width: 100%;
}
.search-box .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    font-size: 0.85rem;
}
.search-box .form-control {
    padding-left: 40px;
    padding-right: 16px;
    height: 42px;
    border-radius: 50px;
    border: 1px solid #e2e8f0;
    font-size: 0.875rem;
    background: #f4f7fe;
    transition: all 0.2s ease;
}
.search-box .form-control:focus {
    background: #fff;
    border-color: #4facfe;
    box-shadow: none;
}

/* Product Image */
.product-thumb {
    flex-shrink: 0;
}
.product-img {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    object-fit: cover;
    border: none;
    transition: transform 0.2s ease;
}
.product-img:hover {
    transform: scale(1.1);
}
.product-img-placeholder {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    background: #f4f7fe;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4facfe;
    font-size: 1.1rem;
}

/* Product Info */
.product-name {
    font-weight: 700;
    color: #2b3674;
    text-decoration: none;
    font-size: 0.875rem;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.15s ease;
}
.product-name:hover {
    color: #4facfe;
}
.product-meta {
    font-size: 0.75rem;
    margin-top: 2px;
}

/* Category Pill */
.category-pill {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background: #f4f7fe;
    color: #2b3674;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    border: none;
    white-space: nowrap;
}

/* Action Buttons */
.action-group {
    display: flex;
    align-items: center;
    gap: 6px;
}
.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}
.action-view {
    background: #e0f2fe;
    color: #0284c7;
}
.action-view:hover {
    background: #bae6fd;
    transform: translateY(-2px);
}
.action-edit {
    background: #fef9c3;
    color: #ca8a04;
}
.action-edit:hover {
    background: #fef08a;
    transform: translateY(-2px);
}
.action-delete {
    background: #ffe4e6;
    color: #e11d48;
}
.action-delete:hover {
    background: #fecdd3;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .stat-card {
        padding: 14px 16px;
    }
    .stat-value {
        font-size: 1.2rem;
    }
    .search-box {
        max-width: 100%;
    }
}
</style>
@endsection
