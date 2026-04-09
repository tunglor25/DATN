@extends('layouts.app')

@section('title', 'Quản lý slide')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Form bên trái -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus-circle me-2"></i>
                        <span id="form-title-text">Thêm slide</span>
                    </h3>
                </div>
                <div class="card-body">
                    <form id="slide-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="form_method" value="POST">
                        <input type="hidden" id="editing_id" value="">

                        <div class="form-group mb-3">
                            <label for="title" class="form-label fw-semibold">
                                Tiêu đề slide <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" id="title" class="form-control" 
                                   placeholder="Nhập tiêu đề slide..." required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="image" class="form-label fw-semibold">Hình ảnh slide</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Hỗ trợ: JPG, PNG, GIF. Kích thước tối đa: 2MB
                            </div>
                            <div id="current-image" class="mt-2"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="link" class="form-label fw-semibold">Link liên kết</label>
                            <input type="text" name="link" id="link" class="form-control"
                                   placeholder="Nhập link (VD: https://example.com)">
                        </div>

                        <div class="form-group mb-3">
                            <label for="position" class="form-label fw-semibold">Vị trí</label>
                            <input type="number" name="position" id="position" class="form-control" 
                                   placeholder="Nhập vị trí hiển thị" min="1">
                        </div>

                        <div class="form-group mb-3 form-check">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" checked>
                            <label for="is_active" class="form-check-label fw-semibold">Kích hoạt slide</label>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-save me-1"></i>
                            <span class="btn-text">Thêm</span>
                        </button>
                    </form>

                    <div id="message" class="mt-3"></div>
                </div>
            </div>
        </div>

        <!-- Bảng bên phải -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-images me-2"></i>Danh sách slide
                        </h3>
                        <div class="d-flex gap-2">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Tìm kiếm slide...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    @if ($slides->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có slide nào</h5>
                            <p class="text-muted small">Bắt đầu bằng cách thêm slide đầu tiên</p>
                        </div>
                    @else
                        <table class="table" id="slide-list">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>TIÊU ĐỀ</th>
                                    <th>HÌNH ẢNH</th>
                                    <th>VỊ TRÍ</th>
                                    <th>TRẠNG THÁI</th>
                                    <th>HÀNH ĐỘNG</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($slides as $index => $slide)
                                    <tr data-id="{{ $slide->id }}">
                                        <td>{{ ($slides->currentPage() - 1) * $slides->perPage() + $index + 1 }}</td>
                                        <td class="title-cell">
                                            <div class="fw-semibold text-dark">{{ $slide->title }}</div>
                                        </td>
                                        <td class="image-cell">
                                            @if ($slide->image)
                                                <img src="{{ asset('storage/' . $slide->image) }}" 
                                                     alt="{{ $slide->title }}" 
                                                     class="rounded border" 
                                                     style="width: 80px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 80px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="position-cell">
                                            <span class="badge bg-info">{{ $slide->position ?? 'N/A' }}</span>
                                        </td>
                                        <td class="status-cell">
                                            @if ($slide->is_active)
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary">Không hoạt động</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-warning btn-sm edit-btn action-btn" 
                                                    data-id="{{ $slide->id }}"
                                                    data-title="{{ $slide->title }}" 
                                                    data-image="{{ $slide->image ?? '' }}"
                                                    data-link="{{ $slide->link ?? '' }}" 
                                                    data-position="{{ $slide->position ?? '' }}"
                                                    data-is_active="{{ $slide->is_active ? 1 : 0 }}"
                                                    data-bs-toggle="tooltip" 
                                                    title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm delete-btn action-btn" 
                                                    data-id="{{ $slide->id }}"
                                                    data-bs-toggle="tooltip" 
                                                    title="Xóa slide">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <button class="btn btn-secondary btn-sm reset-form-btn action-btn"
                                                    data-bs-toggle="tooltip" 
                                                    title="Thêm mới">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                @if ($slides->hasPages())
                    <div class="card-footer bg-white py-3 border-top-0">
                        <div class="d-flex justify-content-center">
                            {{ $slides->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
let editingId = null;

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            delay: { show: 300, hide: 100 }
        });
    });
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll('#slide-list tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.indexOf(query) > -1 ? '' : 'none';
    });
});

document.getElementById('slide-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const method = document.getElementById('form_method').value;
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.classList.add('loading');

    let url = "{{ route('admin.slide.store') }}";
    if (method === 'PUT') {
        url = `/admin/slide/${editingId}/update`;
        formData.append('_method', 'PUT');
    }

    // Checkbox nếu không check sẽ không gửi value, xử lý gửi is_active = 0 khi unchecked
    if (!form.is_active.checked) {
        formData.set('is_active', 0);
    } else {
        formData.set('is_active', 1);
    }

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
            },
            body: formData,
        });
        const result = await response.json();

        if (result.success) {
            const slide = result.data;
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: result.message,
                width: '400',
                timer: 1500,
                showConfirmButton: false,
            });

            const rowHtml = `
                <tr data-id="${slide.id}">
                    <td>${slide.id}</td>
                    <td class="title-cell">
                        <div class="fw-semibold text-dark">${slide.title}</div>
                    </td>
                    <td class="image-cell">
                        ${slide.image_url ? `<img src="${slide.image_url}" class="rounded border" style="width: 80px; height: 50px; object-fit: cover;">` : 
                        '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 50px;"><i class="fas fa-image text-muted"></i></div>'}
                    </td>
                    <td class="link-cell">
                        ${slide.link ? `<a href="${slide.link}" target="_blank" class="text-primary text-decoration-none"><i class="fas fa-external-link-alt me-1"></i>${slide.link.length > 30 ? slide.link.substring(0, 30) + '...' : slide.link}</a>` : '<span class="text-muted">-</span>'}
                    </td>
                    <td class="position-cell">
                        <span class="badge bg-info">${slide.position ?? 'N/A'}</span>
                    </td>
                    <td class="status-cell">
                        ${slide.is_active ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-secondary">Không hoạt động</span>'}
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn action-btn" 
                            data-id="${slide.id}" 
                            data-title="${slide.title}" 
                            data-image="${slide.image ?? ''}" 
                            data-link="${slide.link ?? ''}" 
                            data-position="${slide.position ?? ''}" 
                            data-is_active="${slide.is_active ? 1 : 0}"
                            data-bs-toggle="tooltip" 
                            title="Chỉnh sửa">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn action-btn" 
                                data-id="${slide.id}"
                                data-bs-toggle="tooltip" 
                                title="Xóa slide">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <button class="btn btn-secondary btn-sm reset-form-btn action-btn"
                                data-bs-toggle="tooltip" 
                                title="Thêm mới">
                            <i class="fas fa-plus"></i>
                        </button>
                    </td>
                </tr>`;

            if (method === 'POST') {
                document.querySelector('#slide-list tbody').insertAdjacentHTML('afterbegin', rowHtml);
            } else {
                const row = document.querySelector(`#slide-list tr[data-id="${editingId}"]`);
                if (row) {
                    row.outerHTML = rowHtml;
                }
            }

            resetForm();
            attachEditButtons();
            attachDeleteButtons();
            attachResetButtons();

        } else {
            document.getElementById('message').innerHTML =
                `<div class="alert alert-danger">${result.message}</div>`;
        }
    } catch (error) {
        console.error(error);
        document.getElementById('message').innerHTML =
            `<div class="alert alert-danger">Có lỗi xảy ra</div>`;
    } finally {
        submitBtn.disabled = false;
        submitBtn.classList.remove('loading');
    }
});

function resetForm() {
    const form = document.getElementById('slide-form');
    form.reset();
    editingId = null;
    document.getElementById('form_method').value = 'POST';
    document.querySelector('#slide-form button[type="submit"] .btn-text').textContent = 'Thêm';
    document.getElementById('form-title-text').textContent = 'Thêm slide';
    document.getElementById('current-image').innerHTML = '';
    form.classList.remove('edit-mode');
}

function attachEditButtons() {
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.onclick = function() {
            editingId = this.getAttribute('data-id');
            document.getElementById('title').value = this.getAttribute('data-title');
            document.getElementById('link').value = this.getAttribute('data-link') || '';
            document.getElementById('position').value = this.getAttribute('data-position') || '';
            document.getElementById('is_active').checked = this.getAttribute('data-is_active') === '1' ?
                true : false;

            const img = this.getAttribute('data-image');
            if (img) {
                document.getElementById('current-image').innerHTML =
                    `<img src="${img.startsWith('http') ? img : '/storage/' + img}" class="rounded border" style="max-width: 200px;">`;
            } else {
                document.getElementById('current-image').innerHTML = '';
            }

            document.getElementById('form_method').value = 'PUT';
            document.querySelector('#slide-form button[type="submit"] .btn-text').textContent =
                'Cập nhật';
            document.getElementById('form-title-text').textContent = 'Chỉnh sửa slide';
            document.getElementById('slide-form').classList.add('edit-mode');
        }
    });
}

function attachDeleteButtons() {
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.onclick = function() {
            const id = this.getAttribute('data-id');
            const slideTitle = this.closest('tr').querySelector('.title-cell').textContent.trim();
            
            Swal.fire({
                title: 'Cảnh báo!',
                html: `Bạn có chắc chắn muốn <strong>xóa slide</strong> "${slideTitle}"?`,
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
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(
                            `/admin/slide/${id}/destroy`,
                            {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        '#slide-form input[name="_token"]').value,
                                }
                            });
                        const res = await response.json();
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Đã xóa!',
                                text: res.message,
                                width: '400',
                                timer: 1500,
                                showConfirmButton: false,
                            });
                            document.querySelector(`#slide-list tr[data-id="${id}"]`)
                                .remove();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: res.message,
                                width: '400',
                                timer: 1500,
                                showConfirmButton: false,
                            });
                        }
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra',
                            width: '400',
                            timer: 1500,
                            showConfirmButton: false,
                        });
                    }
                }
            });
        }
    });
}

function attachResetButtons() {
    document.querySelectorAll('.reset-form-btn').forEach(btn => {
        btn.onclick = resetForm;
    });
}

// Gọi lần đầu attach sự kiện
attachEditButtons();
attachDeleteButtons();
attachResetButtons();
</script>

<style>
/* Page styles - shared styles from admin-styles.css */</style>
@endsection
