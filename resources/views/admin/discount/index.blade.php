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
            </div>
            @if ($discounts->count())
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-center">
                        {{ $discounts->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
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
        /* Sử dụng lại style của trang post cho bảng, nút, badge, ảnh, phân trang... */
        :root {
            --table-bg: #ffffff;
            --table-border: #f0f0f0;
            --table-hover: #e4e4e4;
            --table-shadow: rgba(0, 0, 0, 0.08);
            --header-bg: #000000;
            --header-text: #ffffff;
            --card-bg: #ffffff;
            --card-shadow: rgba(0, 0, 0, 0.1);
            --card-header-bg: #ffffff;
            --card-header-text: #000000;
            --btn-warning: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            --btn-info: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            --btn-danger: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            --btn-success: linear-gradient(135deg, #00d4aa 0%, #00b894 100%);
            --btn-secondary: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            --btn-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --badge-success: linear-gradient(135deg, #00d4aa 0%, #00b894 100%);
            --badge-danger: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            --badge-primary: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            --badge-secondary: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            --badge-info: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            --border-radius: 0;
            --btn-radius: 0;
            --badge-radius: 0;
            --table-padding: 16px 12px;
            --btn-padding: 8px 16px;
            --badge-padding: 6px 12px;
        }

        /* search */
        .search-form {
            min-width: 260px;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 6px 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .search-input {
            height: 40px !important;
            border-radius: 5px;
            font-size: 1rem;
            min-width: 160px;
            border: 1px solid #e0e0e0;
            transition: border-color 0.2s;
        }

        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 2px #e0e7ff;
        }

        .search-btn {
            height: 40px !important;
            border-radius: 5px !important;
            font-size: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        @media (max-width: 576px) {
            .search-form {
                flex-direction: column !important;
                gap: 8px !important;
                padding: 8px 6px;
            }

            .search-input,
            .search-btn {
                width: 100%;
            }
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--table-border);
            border-radius: var(--border-radius);
            box-shadow: 0 2px 10px var(--card-shadow);
            margin-bottom: 20px;
        }

        .card-header {
            background: var(--card-header-bg);
            border-bottom: 1px solid var(--table-border);
            padding: var(--table-padding);
        }

        .card-title {
            color: var(--card-header-text);
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .card-body {
            padding: 0;
        }

        .card-footer {
            background: var(--card-bg);
            border-top: 1px solid var(--table-border);
            padding: var(--table-padding);
        }

        .table {
            margin: 0;
            background: var(--table-bg);
        }

        .table th {
            background: var(--header-bg);
            color: var(--header-text);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: var(--table-padding);
            border: none;
            border-bottom: 2px solid var(--table-border);
        }

        .table td {
            padding: var(--table-padding);
            border-bottom: 1px solid var(--table-border);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: var(--table-hover);
            transition: background-color 0.2s ease;
        }

        .btn-warning {
            background: var(--btn-warning) !important;
            border: none !important;
            color: #fff !important;
            font-weight: 600;
            border-radius: var(--btn-radius);
            padding: var(--btn-padding);
            transition: all 0.3s;
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
            color: #fff !important;
            font-weight: 600;
            border-radius: var(--btn-radius);
            padding: var(--btn-padding);
            transition: all 0.3s;
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
            color: #fff !important;
            font-weight: 600;
            border-radius: var(--btn-radius);
            padding: var(--btn-padding);
            transition: all 0.3s;
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
            color: #fff !important;
            font-weight: 600;
            border-radius: var(--btn-radius);
            padding: var(--btn-padding);
            transition: all 0.3s;
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
            color: #fff !important;
            font-weight: 600;
            border-radius: var(--btn-radius);
            padding: var(--btn-padding);
            transition: all 0.3s;
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
            color: #fff !important;
            font-weight: 600;
            border-radius: var(--btn-radius);
            padding: var(--btn-padding);
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .badge {
            border-radius: var(--badge-radius);
            padding: var(--badge-padding);
            font-weight: 500;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bg-success {
            background: var(--badge-success) !important;
            color: white !important;
        }

        .bg-secondary {
            background: var(--badge-secondary) !important;
            color: white !important;
        }

        .bg-danger {
            background: var(--badge-danger) !important;
            color: white !important;
        }

        .bg-primary {
            background: var(--badge-primary) !important;
            color: white !important;
        }

        .bg-info {
            background: var(--badge-info) !important;
            color: white !important;
        }

        .img-thumbnail {
            transition: transform 0.3s ease;
            border-radius: var(--border-radius);
        }

        .img-thumbnail:hover {
            transform: scale(1.1);
        }

        /* SweetAlert Custom Styling */
        .swal2-container .post-trash-alert.swal2-popup {
            width: 400px !important;
            font-size: 18px !important;
            padding: 20px !important;
            border-radius: 8px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
        }

        .swal2-container .post-trash-alert .swal2-title {
            font-size: 20px !important;
            font-weight: bold !important;
            color: #dc3545 !important;
        }

        .swal2-container .post-trash-alert .swal2-html-container {
            font-size: 14px !important;
            margin: 10px 0 !important;
            line-height: 1.5 !important;
        }

        .swal2-container .post-trash-alert .swal2-icon {
            width: 80px !important;
            height: 80px !important;
            margin: 0 auto 15px !important;
        }

        .swal2-container .post-trash-alert .swal2-actions {
            gap: 10px !important;
            margin: 20px 0 0 !important;
        }

        .swal2-container .post-trash-alert .swal2-styled {
            padding: 10px 20px !important;
            font-size: 14px !important;
            border-radius: 5px !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
        }

        .swal2-container .post-trash-alert .swal2-styled:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        @media (max-width: 768px) {
            .card-title {
                font-size: 1.25rem;
            }

            .table th,
            .table td {
                padding: 8px 6px;
                font-size: 0.875rem;
            }

            .btn-sm {
                padding: 4px 8px;
                font-size: 0.75rem;
            }

            .badge {
                font-size: 0.7rem;
                padding: 4px 8px;
            }
        }
    </style>
@endsection
