@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-black fw-bold">Danh sách mã giảm giá</h5>
                <div class="d-flex align-items-center gap-2">
                    <form method="GET" class="d-flex gap-2 align-items-center search-form">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-sm search-input" placeholder="🔍 Tìm kiếm mã ">
                        <button type="submit" class="btn btn-sm btn-primary px-3 search-btn">
                            <i class="fas fa-search me-1"></i>
                        </button>
                        @if (request('search'))
                            <a href="{{ route('admin.discount.index') }}"
                                class="btn btn-sm btn-outline-secondary px-3 search-btn">
                                <i class="fas fa-undo me-1"></i>
                            </a>
                        @endif
                    </form>
                    <a href="{{ route('admin.discount.create') }}" class="btn btn-success ms-2" style="height:38px;">
                        <i class="fas fa-plus me-1"></i> Thêm mới
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 60px;">STT</th>
                                <th>Mã</th>
                                <th>Kiểu</th>
                                <th>Giá trị mã giảm giá</th>
                                <th>Giá trị tối thiểu</th>
                                <th>Giới hạn</th>
                                <th>Trạng thái</th>
                                <th>Bắt đầu</th>
                                <th>Hết hạn</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($discounts as $index => $discount)
                                <tr>
                                    <td>{{ ($discounts->currentPage() - 1) * $discounts->perPage() + $index + 1 }}</td>
                                    <td class="fw-semibold text-dark">{{ $discount->code }}</td>
                                    <td>
                                        <span class="badge bg-{{ $discount->type === 'percent' ? 'success' : 'info' }}">
                                            {{ $discount->type === 'percent' ? 'Phần trăm' : 'Cố định' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($discount->type === 'percent')
                                            {{ number_format($discount->value) }}%
                                        @else
                                            {{ number_format($discount->value) }}₫
                                        @endif
                                    </td>

                                    <td>{{ number_format($discount->min_order_value) }}₫</td>
                                    <td>{{ $discount->claim_limit }}</td>
                                    <td>
                                        <span class="badge bg-{{ $discount->status_class }}">
                                            {{ $discount->status_label }}
                                        </span>
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($discount->starts_at)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($discount->expires_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-center">

                                            <a href="{{ route('admin.discount.edit', $discount->id) }}"
                                                class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.discount.destroy', $discount->id) }}"
                                                method="POST" class="delete-form d-inline" data-id="{{ $discount->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                    data-bs-toggle="tooltip" title="Xóa mã giảm giá">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="py-5 text-muted">
                                        <i class="fas fa-tags fa-2x mb-2"></i><br>
                                        Không có mã giảm giá nào.<br>
                                        <a href="{{ route('admin.discount.create') }}"
                                            class="btn btn-sm btn-outline-primary mt-3">
                                            <i class="fas fa-plus me-1"></i> Tạo mã mới
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($discounts->hasPages())
                    <div class="card-footer bg-white py-3 border-top-0">
                        <div class="d-flex justify-content-center">
                            {{ $discounts->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    delay: {
                        show: 300,
                        hide: 100
                    }
                });
            });

            // Delete confirmation with SweetAlert
            $(document).on('click', '.delete-btn', function() {
                const form = $(this).closest('form');
                const discountCode = form.closest('tr').find('td:nth-child(2)').text().trim();

                Swal.fire({
                    title: 'Cảnh báo!',
                    html: `Bạn có chắc chắn muốn <strong>xóa mã giảm giá</strong> "${discountCode}"?`,
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
                        popup: 'discount-trash-alert'
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
