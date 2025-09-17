# tạo giao diện hiển thị danh sách người dùng đã xóa
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách người dùng đã xóa</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.user.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($users->isEmpty())
                            <div class="alert alert-info">Không có người dùng đã xóa.</div>
                        @else
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th>Vai trò</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->address }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>{{ $user->status }}</td>
                                            <td>
                                                <form action="{{ route('admin.user.restore', $user->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn btn-sm btn-success me-1" title="Khôi phục">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.user.forceDelete', $user->id) }}"
                                                    method="POST" class="delete-form d-inline"
                                                    data-id="{{ $user->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                        data-bs-toggle="tooltip" title="Xóa người dùng">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $users->links() }}
                        @endif
                    </div>
                </div>
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
                const postId = form.getAttribute('data-id');

                Swal.fire({
                    title: 'Cảnh báo!',
                    html: `Bạn có chắc chắn muốn <strong>xóa bài viết</strong>"`,
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
        /* Custom SweetAlert Styles - Chung cho cả restore và delete */
        .swal2-container .category-trash-alert.swal2-popup {
            width: 400px !important;
            font-size: 18px !important;
            padding: 10px !important;
        }

        .swal2-container .category-trash-alert .swal2-title {
            font-size: 20px !important;
            font-weight: bold !important;
        }

        .swal2-container .category-trash-alert .swal2-html-container {
            font-size: 14px !important;
            margin-bottom: 10px !important;
        }

        .swal2-container .category-trash-alert .swal2-icon {
            width: 80px !important;
            height: 80px !important;
            margin: 0 auto !important;
        }

        .swal2-container .category-trash-alert .swal2-actions {
            gap: 10px !important;
            margin: 0 !important;
        }

        .swal2-container .category-trash-alert .swal2-styled {
            padding: 8px 16px !important;
            font-size: 13px !important;
        }
    </style>
@endsection
