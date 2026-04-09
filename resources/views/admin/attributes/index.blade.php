@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        {{-- Form thêm/sửa --}}
        <div class="col-lg-4 col-md-5">
            <div class="card attr-card">
                <div class="card-header attr-card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-{{ isset($attribute) ? 'edit' : 'plus-circle' }} me-2"></i>
                        {{ isset($attribute) ? 'Sửa loại biến thể' : 'Thêm loại biến thể' }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ isset($attribute) ? route('admin.attributes.update', $attribute->id) : route('admin.attributes.store') }}">
                        @csrf
                        @if(isset($attribute)) @method('PUT') @endif

                        <div class="mb-4">
                            <label for="name" class="form-label">
                                <i class="fas fa-tag me-1 text-muted"></i>
                                Tên loại biến thể <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $attribute->name ?? '') }}" 
                                   placeholder="VD: Màu sắc, Kích thước..."
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="type" class="form-label">
                                <i class="fas fa-code me-1 text-muted"></i>
                                Loại hiển thị <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="type" 
                                   id="type"
                                   class="form-control @error('type') is-invalid @enderror" 
                                   placeholder="VD: color, size_number..." 
                                   value="{{ old('type', $attribute->type ?? '') }}" 
                                   required>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Dùng để xác định cách hiển thị
                            </div>
                            @error('type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-list-ul me-1 text-muted"></i>
                                Giá trị biến thể
                            </label>
                            <div id="attribute-values-wrapper">
                                @php
                                    $values = old('values', isset($attribute) ? $attribute->values->pluck('value')->toArray() : ['']);
                                @endphp
                                @foreach ($values as $val)
                                    <div class="input-group mb-2">
                                        <input type="text" 
                                               name="values[]" 
                                               class="form-control" 
                                               value="{{ $val }}" 
                                               placeholder="Nhập giá trị">
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-remove-value" 
                                                onclick="this.parentElement.remove()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-primary mt-1" 
                                    onclick="addValue()">
                                <i class="fas fa-plus me-1"></i> Thêm giá trị
                            </button>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-{{ isset($attribute) ? 'primary' : 'success' }}">
                                <i class="fas fa-{{ isset($attribute) ? 'save' : 'plus' }} me-1"></i>
                                {{ isset($attribute) ? 'Cập nhật' : 'Lưu' }}
                            </button>
                            @if(isset($attribute))
                                <a href="{{ route('admin.attributes.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Hủy
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Bảng danh sách --}}
        <div class="col-lg-8 col-md-7">
            <div class="card attr-card">
                <div class="card-header attr-card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-th-list me-2"></i>
                        Danh sách loại biến thể
                    </h5>
                    <span class="attr-count">{{ $attributes->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if ($attributes->isEmpty())
                        <div class="text-center py-5">
                            <div class="empty-icon mb-3">
                                <i class="fas fa-tags"></i>
                            </div>
                            <h6 class="text-muted fw-semibold">Chưa có loại biến thể nào</h6>
                            <p class="text-muted small mb-0">Bắt đầu bằng cách thêm loại biến thể đầu tiên</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table attr-table mb-0">
                                <thead>
                                    <tr>
                                        <th width="50">STT</th>
                                        <th>TÊN LOẠI</th>
                                        <th width="120">KIỂU</th>
                                        <th>GIÁ TRỊ</th>
                                        <th width="100" class="text-center">THAO TÁC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attributes as $attribute)
                                        <tr>
                                            <td class="text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="fw-semibold">{{ $attribute->name }}</span>
                                            </td>
                                            <td>
                                                <code>{{ $attribute->type }}</code>
                                            </td>
                                            <td>
                                                <div class="attr-values-wrap">
                                                    @foreach ($attribute->values as $value)
                                                        <span class="attr-value-tag">{{ $value->value }}</span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('admin.attributes.edit', $attribute->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Sửa">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <form method="POST" 
                                                        action="{{ route('admin.attributes.destroy', $attribute->id) }}" 
                                                        class="delete-form d-inline"
                                                        data-id="{{ $attribute->id }}">
                                                        @csrf @method('DELETE')
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger delete-btn" 
                                                                title="Xóa">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(el => new bootstrap.Tooltip(el, { delay: { show: 400, hide: 100 } }));

    // Delete confirmation
    $(document).on('click', '.delete-btn', function() {
        const form = $(this).closest('form');
        const name = form.closest('tr').find('td:nth-child(2)').text().trim();
        
        Swal.fire({
            title: 'Xác nhận xóa?',
            html: `Bạn muốn xóa loại biến thể <strong>"${name}"</strong>?<br><small class="text-muted">Hành động này không thể hoàn tác.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonColor: '#a0aec0',
            confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Xóa',
            cancelButtonText: 'Hủy',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});

function addValue() {
    const wrapper = document.getElementById('attribute-values-wrapper');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" name="values[]" class="form-control" placeholder="Nhập giá trị">
        <button type="button" class="btn btn-outline-danger btn-remove-value" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    wrapper.appendChild(div);
    div.querySelector('input').focus();
}
</script>
@endsection

@section('styles')
<style>
/* Attribute page - Clean minimal design */
.attr-card {
    border: none !important;
    border-radius: 12px !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06) !important;
    overflow: hidden;
}

.attr-card-header {
    background: #fff !important;
    border-bottom: 1px solid #edf2f7 !important;
    padding: 18px 24px !important;
}

.attr-card-header h5 {
    font-weight: 700;
    font-size: 1rem;
    color: #1a202c;
}

.attr-card-header i {
    color: #667eea;
}

.attr-count {
    background: #edf2f7;
    color: #4a5568;
    font-weight: 700;
    font-size: 0.8rem;
    padding: 4px 12px;
    border-radius: 20px;
}

/* Table */
.attr-table {
    box-shadow: none !important;
}

.attr-table thead {
    background: #f7fafc !important;
}

.attr-table thead th {
    color: #718096 !important;
    font-weight: 600;
    font-size: 0.7rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    padding: 12px 16px;
    border: none !important;
    border-bottom: 1px solid #edf2f7 !important;
    background: transparent !important;
}

.attr-table tbody tr {
    border-bottom: 1px solid #f7fafc;
    transition: background 0.15s ease;
}

.attr-table tbody tr:hover {
    background: #f7fafc !important;
    transform: none !important;
    box-shadow: none !important;
}

.attr-table tbody td {
    padding: 14px 16px;
    vertical-align: middle;
    border: none;
    font-size: 0.875rem;
    color: #2d3748;
}

/* Value Tags - Clean pill style */
.attr-values-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.attr-value-tag {
    display: inline-block;
    background: #edf2f7;
    color: #4a5568;
    font-size: 0.72rem;
    font-weight: 500;
    padding: 3px 10px;
    border-radius: 4px;
    border: 1px solid #e2e8f0;
    line-height: 1.4;
}

/* Code tag */
code {
    background: #f0f4ff !important;
    color: #5a67d8 !important;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 500;
    border: 1px solid #e0e7ff;
}

/* Form styling */
.form-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #4a5568;
}

.form-control {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 0.875rem;
    transition: border-color 0.15s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
}

.form-text {
    font-size: 0.78rem;
    color: #a0aec0;
}

/* Action buttons - outline style */
.btn-outline-primary {
    color: #667eea !important;
    border-color: #c3dafe !important;
    background: transparent !important;
    box-shadow: none !important;
    padding: 5px 10px !important;
    border-radius: 6px !important;
}

.btn-outline-primary:hover {
    background: #ebf4ff !important;
    color: #5a67d8 !important;
    border-color: #667eea !important;
    transform: none !important;
}

.btn-outline-danger {
    box-shadow: none !important;
    border-radius: 6px !important;
}

.btn-outline-danger:hover {
    transform: none !important;
}

.btn-remove-value {
    padding: 6px 12px !important;
    border-radius: 0 8px 8px 0 !important;
}

/* Empty state */
.empty-icon {
    width: 60px;
    height: 60px;
    background: #f7fafc;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.empty-icon i {
    font-size: 1.5rem;
    color: #a0aec0;
}

/* Responsive */
@media (max-width: 768px) {
    .attr-table thead th,
    .attr-table tbody td {
        padding: 10px 12px;
    }
    
    .attr-value-tag {
        font-size: 0.68rem;
        padding: 2px 8px;
    }
}
</style>
@endsection