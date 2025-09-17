@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Bộ lọc hàng ngang --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-filter me-2"></i>
                Bộ lọc đánh giá
            </h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label for="search" class="form-label fw-semibold">Tìm kiếm</label>
                                                 <input type="text" name="search" class="form-control" 
                                value="{{ request('search') }}" 
                                placeholder="Tên người dùng hoặc email...">
                    </div>

                                         <div class="col-md-2">
                         <label for="rating" class="form-label fw-semibold">Số đánh giá</label>
                         <select name="rating" class="form-select">
                             <option value="">Tất cả</option>
                             <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>≥ 1 đánh giá</option>
                             <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>≥ 2 đánh giá</option>
                             <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>≥ 3 đánh giá</option>
                             <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>≥ 5 đánh giá</option>
                             <option value="10" {{ request('rating') == '10' ? 'selected' : '' }}>≥ 10 đánh giá</option>
                         </select>
                     </div>

                    <div class="col-md-2">
                        <label for="verified" class="form-label fw-semibold">Trạng thái xác thực</label>
                        <select name="verified" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Đã xác thực</option>
                            <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Chưa xác thực</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Lọc
                            </button>
                            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Xóa lọc
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

        {{-- Bảng danh sách --}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Danh sách đánh giá</h3>
                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Tìm kiếm đánh giá...">
                    </div>
                </div>
            </div>
        </div>
                <div class="">
                                         @if ($users->isEmpty())
                         <div class="text-center py-5">
                             <i class="fas fa-users fa-3x text-muted mb-3"></i>
                             <h5 class="text-muted">Không có người dùng nào đánh giá</h5>
                             <p class="text-muted small">Chưa có người dùng nào tạo đánh giá</p>
                         </div>
                    @else
                        <table class="table">
                                                         <thead>
                                 <tr>
                                     <th>STT</th>
                                     <th>NGƯỜI DÙNG</th>
                                     <th>SỐ ĐÁNH GIÁ</th>
                                     <th>ĐÁNH GIÁ MỚI NHẤT</th>
                                     <th>TRẠNG THÁI</th>
                                     <th>NGÀY THAM GIA</th>
                                     <th>HÀNH ĐỘNG</th>
                                 </tr>
                             </thead>
                                                         <tbody>
                                 @foreach ($users as $index => $user)
                                     <tr>
                                         <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                                         <td>
                                             <div class="d-flex align-items-center">
                                                 <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                      style="width: 50px; height: 50px;">
                                                     <i class="fas fa-user text-white"></i>
                                                 </div>
                                                 <div>
                                                     <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                                     <small class="text-muted">{{ $user->email }}</small>
                                                 </div>
                                             </div>
                                         </td>
                                         <td>
                                             <div class="d-flex align-items-center">
                                                 <span class="badge bg-primary fs-6">{{ $user->reviews_count }}</span>
                                                 <small class="text-muted ms-2">đánh giá</small>
                                             </div>
                                         </td>
                                         <td>
                                             @if ($user->reviews->count() > 0)
                                                 @php $latestReview = $user->reviews->first(); @endphp
                                                 <div class="d-flex align-items-center">
                                                     <div class="me-2">
                                                         @for ($i = 1; $i <= 5; $i++)
                                                             <i class="fas fa-star {{ $i <= $latestReview->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                         @endfor
                                                     </div>
                                                     <div>
                                                        <div class="fw-semibold text-dark">
                                                             @if ($latestReview->product)
                                                                 {{ $latestReview->product->name }}
                                                             @else
                                                                 <span class="text-danger">Sản phẩm đã bị xóa</span>
                                                             @endif
                                                         </div>
                                                         <small class="text-muted">{{ $latestReview->created_at->format('d/m/Y') }}</small>
                                                     </div>
                                                 </div>
                                             @else
                                                 <span class="text-muted">Không có đánh giá</span>
                                             @endif
                                         </td>
                                         <td>
                                             @if ($user->reviews->where('is_verified', true)->count() > 0)
                                                 <span class="badge bg-success">
                                                     <i class="fas fa-check-circle me-1"></i>Có đánh giá xác thực
                                                 </span>
                                             @else
                                                 <span class="badge bg-secondary">
                                                     <i class="fas fa-clock me-1"></i>Chưa xác thực
                                                 </span>
                                             @endif
                                         </td>
                                         <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                         <td>
                                             <a href="{{ route('admin.reviews.show', $user->id) }}"
                                                class="btn btn-info btn-sm"
                                                data-bs-toggle="tooltip" 
                                                title="Xem tất cả đánh giá">
                                                 <i class="fas fa-eye"></i>
                                             </a>
                                         </td>
                                     </tr>
                                 @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
                         @if ($users->count() > 0)
                 <div class="card-footer bg-white py-3">
                     <div class="d-flex justify-content-center">
                         {{ $users->links('pagination::bootstrap-5') }}
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

         // Search functionality for user list
     $('#searchInput').on('input', function() {
         const query = $(this).val().toLowerCase();
         $('tbody tr').each(function() {
             const userName = $(this).find('td:nth-child(2) .fw-semibold').text().toLowerCase();
             const userEmail = $(this).find('td:nth-child(2) small').text().toLowerCase();
             $(this).toggle(userName.indexOf(query) > -1 || userEmail.indexOf(query) > -1);
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
.swal2-container .review-delete-alert.swal2-popup {
    width: 400px !important;
    font-size: 18px !important;
    padding: 20px !important;
    border-radius: 8px !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
}

.swal2-container .review-delete-alert .swal2-title {
    font-size: 20px !important;
    font-weight: 700 !important;
    color: #2c3e50 !important;
}

.swal2-container .review-delete-alert .swal2-html-container {
    font-size: 14px !important;
    margin-bottom: 10px !important;
    color: #5a6c7d !important;
}

.swal2-container .review-delete-alert .swal2-icon {
    width: 80px !important;
    height: 80px !important;
    margin: 0 auto !important;
}

.swal2-container .review-delete-alert .swal2-actions {
    gap: 12px !important;
    margin: 0 !important;
}

.swal2-container .review-delete-alert .swal2-styled {
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

/* Star Rating Styling */
.text-warning {
    color: #f39c12 !important;
}

.text-muted {
    color: #95a5a6 !important;
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
    
    /* Bộ lọc responsive */
    .row.align-items-end > div {
        margin-bottom: 15px;
    }
    
    .row.align-items-end > div:last-child {
        margin-bottom: 0;
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
    
    /* Bộ lọc mobile */
    .row.align-items-end {
        flex-direction: column;
    }
    
    .row.align-items-end > div {
        width: 100%;
        margin-bottom: 15px;
    }
    
    .row.align-items-end > div:last-child {
        margin-bottom: 0;
    }
    
    .d-flex.gap-2 {
        justify-content: center;
    }
}
</style>
@endsection
