    @extends('layouts.app')
    @section('title', 'Quản lý người dùng')
    @section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Danh sách người dùng</h3>
                        </div>
                        <div class="search-user mb-3">
                            <!-- Search Form -->
                            <form method="GET" class="d-flex align-items-center gap-2 flex-wrap" style="max-width: 400px;">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control form-control-sm flex-grow-1"
                                    placeholder="🔍 Tìm kiếm tên hoặc email...">
                                <button type="submit" class="btn btn-sm btn-primary px-3">
                                    <i class="fas fa-search me-1"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-outline-secondary px-3">
                                    <i class="fas fa-undo me-1"></i> Đặt lại
                                </a>
                            </form>
                        </div>
                        <div class="">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Trạng thái</th>
                                        <th>Vai trò</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>
                                                @if ($user->status === 'active')
                                                    <span class="badge bg-success">Hoạt động </span>
                                                @elseif($user->status === 'inactive')
                                                    <span class="badge bg-warning">Chưa kích hoạt</span>
                                                @else
                                                    <span class="badge bg-danger">Khóa</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($user->role === 'admin')
                                                    @if($user->isSuperAdmin())
                                                        <span class="badge bg-danger">Super Admin</span>
                                                    @else
                                                        <span class="badge bg-primary">Admin</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">User</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(Auth::user()->canManageUser($user))
                                                    <a href="{{ route('admin.user.edit', $user->id) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('admin.user.show', $user->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if ($user->status === 'active' || $user->status === 'banned')
                                                        <form action="{{ route('admin.user.toggleStatus', $user->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-sm {{ $user->status === 'active' ? 'btn-secondary' : 'btn-success' }}"
                                                                data-bs-toggle="tooltip"
                                                                title="{{ $user->status === 'active' ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}">
                                                                <i
                                                                    class="fas {{ $user->status === 'active' ? 'fa-lock-open' : 'fa-lock' }}"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Không có quyền</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        <script>
            // Initialize tooltips
            document.addEventListener('DOMContentLoaded', function() {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        delay: {
                            show: 300,
                            hide: 100
                        }
                    });
                });
            });

            // Delete confirmation
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('form');
                    const userId = form.getAttribute('data-id');

                    Swal.fire({
                        title: 'Cảnh báo!',
                        html: `Bạn có chắc chắn muốn <strong>xóa người dùng không?`,
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
                --card-header-text: #000000;

                /* Button Colors */
                --btn-warning: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
                --btn-info: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                --btn-danger: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
                --btn-success: linear-gradient(135deg, #00d4aa 0%, #00b894 100%);
                --btn-secondary: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);

                /* Badge Colors */
                --badge-success: linear-gradient(135deg, #00d4aa 0%, #00b894 100%);
                --badge-warning: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
                --badge-danger: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
                --badge-primary: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
                --badge-secondary: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);

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

            .badge.bg-warning {
                background: var(--badge-warning) !important;
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
            }

            .search-user form {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 10px 15px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            }

            .search-user input[type="text"] {
                min-width: 180px;
                border-radius: 5px;
            }

            .search-user .btn {
                min-width: 90px;
                font-size: 0.95rem;
            }

            @media (max-width: 576px) {
                .search-user form {
                    flex-direction: column !important;
                    gap: 8px !important;
                }

                .search-user .btn,
                .search-user input[type="text"] {
                    width: 100%;
                }
            }
        </style>
    @endsection
