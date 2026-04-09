@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Danh sách bài viết</h3>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.post.create') }}" class="btn btn-success">
                                <i class="fas fa-plus-circle me-1"></i> Thêm mới
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tiêu đề</th>
                                    <th>Đường dẫn</th>
                                    <th>Hình ảnh</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đăng</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posts as $index => $post)
                                    <tr>
                                        <td>
                                            <span class="">{{ ($posts->currentPage() - 1) * $posts->perPage() + $index + 1 }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $post->title }}</div>
                                        </td>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded">{{ $post->slug }}</code>
                                        </td>
                                        <td>
                                            @if ($post->thumbnail)
                                                <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="Ảnh bài viết"
                                                    class="img-thumbnail rounded shadow-sm" style="width:80px; height:60px; object-fit:cover;">
                                            @else
                                                <span class="badge bg-light text-secondary">Không có ảnh</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($post->is_published)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i> Công khai
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-lock me-1"></i> Riêng tư
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($post->published_at)
                                                <span class="badge bg-light text-dark border">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ $post->published_at->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.post.show', $post->id) }}" class="btn btn-sm btn-info" 
                                                   data-bs-toggle="tooltip" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.post.edit', $post->id) }}" class="btn btn-sm btn-warning"
                                                   data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.post.destroy', $post->id) }}" method="POST"
                                                    class="delete-form d-inline" data-id="{{ $post->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                            data-bs-toggle="tooltip" title="Xóa bài viết">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-inbox text-muted mb-3" style="font-size: 3rem;"></i>
                                                <p class="text-muted mb-0">Không có bài viết nào.</p>
                                                <a href="{{ route('admin.post.create') }}" class="btn btn-sm btn-primary mt-3">
                                                    <i class="fas fa-plus-circle me-1"></i> Tạo bài viết mới
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($posts->hasPages())
                <div class="card-footer bg-white py-3 border-top-0">
                    <div class="d-flex justify-content-center">
                        {{ $posts->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                @endif
            </div>
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
                delay: { show: 300, hide: 100 }
            });
        });

        // Delete confirmation with SweetAlert
        $(document).on('click', '.delete-btn', function() {
            const form = $(this).closest('form');
            const postTitle = form.closest('tr').find('td:nth-child(2) .fw-semibold').text().trim();
            
            Swal.fire({
                title: 'Cảnh báo!',
                html: `Bạn có chắc chắn muốn <strong>xóa bài viết</strong> "${postTitle}"?`,
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
                    popup: 'post-trash-alert'
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
