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
                @if($posts->count() > 0)
                <div class="card-footer">
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

    /* Card Styling */
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

    /* Table Styling */
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

    /* Badge Styling */
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

    /* Pagination Styling */
    .pagination {
        margin: 0;
    }

    .page-link {
        border-radius: var(--btn-radius);
        border: 1px solid var(--table-border);
        color: var(--card-header-text);
        padding: var(--btn-padding);
        margin: 0 2px;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background: var(--table-hover);
        border-color: var(--table-border);
        color: var(--card-header-text);
        transform: translateY(-1px);
    }

    .page-item.active .page-link {
        background: var(--btn-primary);
        border-color: var(--btn-primary);
        color: white;
    }

    /* Image Styling */
    .img-thumbnail {
        transition: transform 0.3s ease;
        border-radius: var(--border-radius);
    }

    .img-thumbnail:hover {
        transform: scale(1.1);
    }

    /* Code Styling */
    code {
        background: #f8f9fa;
        color: #495057;
        font-size: 0.875rem;
        border-radius: 4px;
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

    /* Responsive Design */
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
