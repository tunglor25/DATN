@extends('layouts.app')
@section('content')
    <h2 class="mb-4 text-center">Thùng rác</h2>
    <div class="d-flex gap-2 mb-3 justify-content-end">
        <a href="{{ route('admin.post.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;">STT</th>
                    <th style="min-width: 200px;">Tiêu đề</th>
                    <th style="min-width: 150px;">Slug</th>
                    <th style="width: 120px;">Thumbnail</th>
                    <th style="width: 100px;">Trạng thái</th>
                    <th style="width: 120px;">Ngày đăng</th>
                    <th style="width: 170px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $index => $post)
                    <tr>
                        <td>{{ ($posts->currentPage() - 1) * $posts->perPage() + $index + 1 }}</td>
                        <td class="text-start">{{ $post->title }}</td>
                        <td>{{ $post->slug }}</td>
                        <td>
                            @if ($post->thumbnail)
                                <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="Ảnh bài viết"
                                    class="img-thumbnail" style="width:80px; height:60px; object-fit:cover;">
                            @else
                                <span class="text-muted">Không có</span>
                            @endif
                        </td>
                        <td>
                            @if ($post->is_published)
                                <span class="badge bg-success">Công khai</span>
                            @else
                                <span class="badge bg-secondary">Riêng tư</span>
                            @endif
                        </td>
                        <td>{{ $post->published_at ? $post->published_at->format('d/m/Y') : '-' }}</td>
                        <td>
                            <form action="{{ route('admin.post.restore', $post->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-success me-1" title="Khôi phục">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.post.forceDelete', $post->id) }}" method="POST"
                                class="delete-form d-inline" data-id="{{ $post->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-bs-toggle="tooltip"
                                    title="Xóa bài viết">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Không có bài viết nào trong thùng rác.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- FontAwesome CDN nếu chưa có --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
