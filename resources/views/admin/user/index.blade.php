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
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <form method="GET" class="w-100" style="max-width: 500px;">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            class="form-control border-start-0 ps-0"
                                            placeholder="Tìm kiếm tên hoặc email...">
                                        <button type="submit" class="btn btn-primary px-4">Tìm kiếm</button>
                                        @if(request('search'))
                                            <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary px-3" data-bs-toggle="tooltip" title="Đặt lại bộ lọc">
                                                <i class="fas fa-undo"></i>
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
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
                                                <div class="d-flex gap-2">
                                                    @if(Auth::user()->canManageUser($user))
                                                        <a href="{{ route('admin.user.edit', $user->id) }}"
                                                            class="btn btn-warning btn-sm action-btn" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ route('admin.user.show', $user->id) }}"
                                                            class="btn btn-info btn-sm action-btn" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if ($user->status === 'active' || $user->status === 'banned')
                                                            <form action="{{ route('admin.user.toggleStatus', $user->id) }}"
                                                                method="POST" class="d-inline border-0">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm action-btn {{ $user->status === 'active' ? 'btn-secondary' : 'btn-success' }}"
                                                                    data-bs-toggle="tooltip"
                                                                    title="{{ $user->status === 'active' ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}">
                                                                    <i class="fas {{ $user->status === 'active' ? 'fa-lock-open' : 'fa-lock' }}"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <span class="text-muted small">Không có quyền</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
