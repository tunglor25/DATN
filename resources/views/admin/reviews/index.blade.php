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
            @if ($users->hasPages())
                <div class="card-footer bg-white py-3 border-top-0">
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
/* Reviews page specific styles - shared styles come from admin-styles.blade.php */

/* Star Rating */
.text-warning {
    color: #ed8936 !important;
}

/* SweetAlert Custom Styling */
.swal2-container .review-delete-alert.swal2-popup {
    width: 400px !important;
    font-size: 18px !important;
    padding: 20px !important;
    border-radius: 10px !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
}

.swal2-container .review-delete-alert .swal2-title {
    font-size: 20px !important;
    font-weight: 700 !important;
    color: #2d3748 !important;
}

.swal2-container .review-delete-alert .swal2-html-container {
    font-size: 14px !important;
    margin-bottom: 10px !important;
    color: #718096 !important;
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
</style>
@endsection

