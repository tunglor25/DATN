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
                <div class="">
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
            </div>
            @if ($slides->count() > 0)
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-center">
                        {{ $slides->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
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

.badge.bg-info {
    background: var(--badge-info) !important;
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

/* Force button styling for action buttons */
.table tbody td:last-child .btn.btn-sm {
    padding: var(--btn-padding) !important;
    border-radius: var(--btn-radius) !important;
    min-width: 40px !important;
    min-height: 40px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Action button specific styling - High specificity to override Bootstrap */
.action-btn,
.btn.action-btn,
.table .action-btn,
.table tbody td:last-child .action-btn {
    padding: var(--btn-padding) !important;
    border-radius: var(--btn-radius) !important;
    min-width: 40px !important;
    min-height: 40px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Override Bootstrap's default button styles */
.btn-sm.action-btn {
    padding: var(--btn-padding) !important;
    border-radius: var(--btn-radius) !important;
}

/* Maximum specificity to override Bootstrap */
html body .table tbody td:last-child .btn.btn-sm.action-btn {
    padding: var(--btn-padding) !important;
    border-radius: var(--btn-radius) !important;
    min-width: 40px !important;
    min-height: 40px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Form Styling */
.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-label {
    color: #2c3e50;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.form-text {
    color: #6c757d;
    font-size: 0.875rem;
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
        padding: var(--btn-padding) !important;
        font-size: 0.75rem;
    }
    
    .card-header .input-group {
        width: 200px !important;
    }
}

@media (max-width: 576px) {
    .card-header .input-group {
        width: 100% !important;
        margin-top: 10px;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
    }
}
</style>
@endsection
