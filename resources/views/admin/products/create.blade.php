@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="fas fa-plus-circle text-primary me-2"></i>
                Thêm mới sản phẩm
            </h4>
            <p class="text-muted mb-0">Tạo sản phẩm mới với các biến thể</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        
        <!-- Alert cho validation form -->
        <div id="form-validation-alert" class="alert alert-danger d-none" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <span id="form-validation-message"></span>
        </div>
        
        <!-- Thông tin cơ bản -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle text-info me-2"></i>
                    Thông tin cơ bản
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">
                            <i class="fas fa-tag me-1"></i> Tên sản phẩm <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nhập tên sản phẩm..." value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label fw-medium">
                            <i class="fas fa-folder me-1"></i> Danh mục
                        </label>
                        <select name="category_id" id="category" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">-- Chọn danh mục --</option>
                            {!! $categoryOptions !!}
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="brand_id" class="form-label fw-medium">
                            <i class="fas fa-copyright me-1"></i> Thương hiệu
                        </label>
                        <select name="brand_id" id="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                            <option value="">-- Chọn thương hiệu --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-medium">
                            <i class="fas fa-dollar-sign me-1"></i> Giá gốc <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" placeholder="0" value="{{ old('price') }}" min="0" step="0.01">
                        @error('price')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-medium">
                            <i class="fas fa-boxes me-1"></i> Tồn kho gốc
                        </label>
                        <input type="number" name="stock" id="product-stock" class="form-control @error('stock') is-invalid @enderror" placeholder="0" value="{{ old('stock') }}" min="0">
                        @error('stock')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                        <div id="stock-hint" class="form-text text-warning d-none">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Tồn kho được tính theo tổng tồn kho của các biến thể.
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-medium">
                            <i class="fas fa-align-left me-1"></i> Mô tả sản phẩm
                        </label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description-editor">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Hình ảnh -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-images text-success me-2"></i>
                    Hình ảnh sản phẩm
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="product_image" class="form-label fw-medium">Ảnh bìa sản phẩm <span class="text-danger">*</span></label>
                        <input type="file" name="product_image" id="product_image" class="form-control @error('product_image') is-invalid @enderror" accept="image/*" onchange="previewProductImage(event)">
                        @error('product_image')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">Chọn ảnh đại diện cho sản phẩm</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Xem trước</label>
                        <div class="border rounded p-3 text-center bg-light" style="min-height: 120px;">
                            <img id="product-image-preview" src="#" alt="Ảnh bìa" class="img-fluid d-none rounded" style="max-height: 100px;">
                            <div id="no-image-text" class="text-muted">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <p class="mb-0">Chưa chọn ảnh</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loại sản phẩm -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-cog text-primary me-2"></i>
                    Loại sản phẩm
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="has_variants" id="no-variants" value="0" checked>
                            <label class="form-check-label" for="no-variants">
                                <i class="fas fa-box text-success me-2"></i>
                                <strong>Sản phẩm đơn giản</strong>
                                <br>
                                <small class="text-muted">Sản phẩm không có biến thể (màu sắc, kích thước)</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="has_variants" id="has-variants" value="1">
                            <label class="form-check-label" for="has-variants">
                                <i class="fas fa-layer-group text-warning me-2"></i>
                                <strong>Sản phẩm có biến thể</strong>
                                <br>
                                <small class="text-muted">Sản phẩm có nhiều biến thể (màu sắc, kích thước)</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biến thể -->
        <div class="card mb-4" id="variants-section" style="display: none;">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-layer-group text-warning me-2"></i>
                    Chọn giá trị biến thể
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($attributes as $attribute)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <h6 class="fw-bold mb-3 text-primary">{{ $attribute->name }}</h6>
                                @foreach ($attribute->values as $value)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input variant-checkbox"
                                               type="checkbox"
                                               data-attribute-id="{{ $attribute->id }}"
                                               data-attribute-name="{{ $attribute->name }}"
                                               data-attribute-type="{{ $attribute->type }}"
                                               data-value-name="{{ $value->value }}"
                                               value="{{ $value->id }}"
                                               id="attr_{{ $attribute->id }}_val_{{ $value->id }}">
                                        <label class="form-check-label" for="attr_{{ $attribute->id }}_val_{{ $value->id }}">
                                            {{ $value->value }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="variant-error" class="alert alert-danger d-none"></div>
                
                @error('variants')
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                    </div>
                @enderror
                
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-outline-primary" onclick="generateVariants()">
                        <i class="fas fa-magic me-1"></i> Sinh tổ hợp biến thể
                    </button>
                </div>
            </div>
        </div>

        <!-- Danh sách biến thể -->
        <div id="variants-wrapper" class="mt-3"></div>

        <!-- Submit -->
        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg px-5" onclick="return validateForm()">
                <i class="fas fa-save me-2"></i> Lưu sản phẩm
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
    {{-- Summernote --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

    {{-- Dữ liệu cũ cho biến thể --}}
    <script>
        const oldVariantData = @json(old('variants', []));
        const validationErrors = @json($errors->get('variants.*'));
    </script>

<script>
function cartesianProduct(arr) {
    return arr.reduce((a, b) => a.flatMap(d => b.map(e => [...d, e])), [[]]);
}

function toggleStockInput() {
    const hasVariants = document.getElementById('has-variants').checked;
    const wrapper = document.getElementById('variants-wrapper');
    const stockInput = document.getElementById('product-stock');
    const hint = document.getElementById('stock-hint');

    if (hasVariants && wrapper && wrapper.querySelectorAll('.card').length > 0) {
        stockInput.disabled = true;
        stockInput.value = '';
        hint.classList.remove('d-none');
    } else if (!hasVariants) {
        stockInput.disabled = false;
        hint.classList.add('d-none');
    }
}

function generateVariants() {
    const errorBox = document.getElementById('variant-error');
    errorBox.classList.add('d-none');

    const checkboxes = document.querySelectorAll('.variant-checkbox:checked');
    const groups = {};
    const types = {};

    checkboxes.forEach(cb => {
        const attrId = cb.dataset.attributeId;
        const attrName = cb.dataset.attributeName;
        const attrType = cb.dataset.attributeType;
        const valueId = cb.value;
        const valueName = cb.dataset.valueName;

        if (!groups[attrId]) groups[attrId] = [];
        groups[attrId].push({
            attribute_id: attrId,
            value_id: valueId,
            value_name: valueName,
            attribute_name: attrName,
            attribute_type: attrType
        });

        if (!types[attrType]) {
            types[attrType] = new Set();
        }
        types[attrType].add(attrId);
    });

    for (const type in types) {
        if (types[type].size > 1) {
            errorBox.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>Chỉ được chọn một nhóm biến thể trong cùng loại "${type.toUpperCase()}" (ví dụ: chỉ chọn 1 loại SIZE).`;
            errorBox.classList.remove('d-none');
            return;
        }
    }

    const groupValues = Object.values(groups);
    if (groupValues.length === 0) {
        errorBox.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>Hãy chọn ít nhất 1 giá trị thuộc mỗi loại biến thể.`;
        errorBox.classList.remove('d-none');
        return;
    }

    errorBox.classList.add('d-none');

    const variants = cartesianProduct(groupValues);
    const wrapper = document.getElementById('variants-wrapper');
    wrapper.innerHTML = '';

    if (variants.length > 0) {
        wrapper.innerHTML = `
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs text-secondary me-2"></i>
                        Cấu hình biến thể (${variants.length} biến thể)
                    </h5>
                </div>
                <div class="card-body" id="variants-list"></div>
            </div>
        `;

        const variantsList = document.getElementById('variants-list');

        variants.forEach((variant, index) => {
            let attrInputs = '';
            let attrBadges = '';

            variant.forEach(v => {
                attrInputs += `<input type="hidden" name="variants[${index}][attribute_values][]" value="${v.value_id}">`;
                attrBadges += `<span class="badge bg-primary me-1 mb-1">${v.attribute_name}: ${v.value_name}</span>`;
            });

            // Lấy giá trị cũ nếu có lỗi validation
            const oldPrice = oldVariantData[index]?.price || '';
            const oldStock = oldVariantData[index]?.stock || '';

            variantsList.innerHTML += `
                <div class="border rounded p-3 mb-3 position-relative" id="variant-${index}">
                    <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2" onclick="document.getElementById('variant-${index}').remove(); toggleStockInput();" title="Xóa biến thể">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <strong class="text-muted">Biến thể #${index + 1}:</strong><br>
                            ${attrBadges}
                            ${attrInputs}
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label fw-medium">Giá bán <span class="text-danger">*</span></label>
                            <input type="number" name="variants[${index}][price]" class="form-control" placeholder="0" value="${oldPrice}" min="0" step="0.01">
                            <div class="invalid-feedback" id="variant-${index}-price-error"></div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label fw-medium">Tồn kho <span class="text-danger">*</span></label>
                            <input type="number" name="variants[${index}][stock]" class="form-control" placeholder="0" value="${oldStock}" min="0">
                            <div class="invalid-feedback" id="variant-${index}-stock-error"></div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label fw-medium">Ảnh biến thể</label>
                            <input type="file" name="variants[${index}][image]" class="form-control" accept="image/*">
                            <div class="invalid-feedback" id="variant-${index}-image-error"></div>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    toggleStockInput();
}

function previewProductImage(event) {
    const input = event.target;
    const preview = document.getElementById('product-image-preview');
    const noImageText = document.getElementById('no-image-text');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            noImageText.classList.add('d-none');
        };

        reader.readAsDataURL(input.files[0]);
    }
}

// Xử lý chọn loại sản phẩm
document.addEventListener('DOMContentLoaded', function() {
    const noVariantsRadio = document.getElementById('no-variants');
    const hasVariantsRadio = document.getElementById('has-variants');
    const variantsSection = document.getElementById('variants-section');
    const productStockInput = document.getElementById('product-stock');
    const stockHint = document.getElementById('stock-hint');

    function handleProductTypeChange() {
        const hasVariants = hasVariantsRadio.checked;
        
        if (hasVariants) {
            variantsSection.style.display = 'block';
            productStockInput.disabled = true;
            productStockInput.value = '';
            stockHint.classList.remove('d-none');
        } else {
            variantsSection.style.display = 'none';
            productStockInput.disabled = false;
            stockHint.classList.add('d-none');
            // Xóa tất cả biến thể đã tạo
            const variantsWrapper = document.getElementById('variants-wrapper');
            if (variantsWrapper) {
                variantsWrapper.innerHTML = '';
            }
        }
    }

    noVariantsRadio.addEventListener('change', handleProductTypeChange);
    hasVariantsRadio.addEventListener('change', handleProductTypeChange);

    // Khởi tạo trạng thái ban đầu
    handleProductTypeChange();
});

document.addEventListener('DOMContentLoaded', toggleStockInput);

// Xử lý hiển thị lỗi validation cho biến thể
document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra xem có lỗi validation không
    if (Object.keys(validationErrors).length > 0) {
        // Nếu có lỗi, tự động sinh lại biến thể
        generateVariants();
        
        // Hiển thị lỗi cho từng trường biến thể
        Object.keys(validationErrors).forEach(key => {
            const parts = key.split('.');
            const variantIndex = parts[1];
            const fieldName = parts[2];
            const errorMessage = validationErrors[key][0];
            
            // Tìm input và hiển thị lỗi
            const input = document.querySelector(`input[name="variants[${variantIndex}][${fieldName}]"]`);
            const errorDiv = document.getElementById(`variant-${variantIndex}-${fieldName}-error`);
            
            if (input && errorDiv) {
                input.classList.add('is-invalid');
                errorDiv.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>' + errorMessage;
                errorDiv.style.display = 'block';
            }
        });
    }
});
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        $('#description-editor').summernote({
            height: 300,
            placeholder: 'Nhập mô tả sản phẩm ở đây...',
            callbacks: {
                onImageUpload: function (files) {
                    for (let i = 0; i < files.length; i++) {
                        uploadImage(files[i]);
                    }
                }
            }
        });

        function uploadImage(file) {
            let data = new FormData();
            data.append("file", file);
            data.append("_token", '{{ csrf_token() }}');

            fetch('{{ route('admin.products.upload_image') }}', {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                $('#description-editor').summernote('insertImage', data.url);
            })
            .catch(error => {
                alert("Upload ảnh thất bại!");
                console.error(error);
            });
        }
    });
</script>

<script>
function validateForm() {
    // Ẩn alert cũ nếu có
    const alertDiv = document.getElementById('form-validation-alert');
    const alertMessage = document.getElementById('form-validation-message');
    alertDiv.classList.add('d-none');
    
    // Kiểm tra loại sản phẩm
    const hasVariants = document.getElementById('has-variants').checked;
    
    if (hasVariants) {
        // Kiểm tra có biến thể nào không nếu chọn sản phẩm có biến thể
        const variantsWrapper = document.getElementById('variants-wrapper');
        const hasVariantsCreated = variantsWrapper && variantsWrapper.querySelectorAll('.card').length > 0;
        
        if (!hasVariantsCreated) {
            alertMessage.textContent = 'Vui lòng tạo ít nhất một biến thể cho sản phẩm.';
            alertDiv.classList.remove('d-none');
            
            // Cuộn lên đầu trang để người dùng thấy thông báo lỗi
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return false;
        }
    } else {
        // Kiểm tra tồn kho nếu là sản phẩm đơn giản
        const productStock = document.getElementById('product-stock').value;
        if (!productStock || parseInt(productStock) < 0) {
            alertMessage.textContent = 'Vui lòng nhập tồn kho hợp lệ cho sản phẩm.';
            alertDiv.classList.remove('d-none');
            document.getElementById('product-stock').focus();
            return false;
        }
    }
    
    // Kiểm tra giá trị âm
    const priceInputs = document.querySelectorAll('input[name="price"], input[name^="variants"][name$="[price]"]');
    for (let input of priceInputs) {
        if (parseFloat(input.value) < 0) {
            alertMessage.textContent = 'Giá không được âm.';
            alertDiv.classList.remove('d-none');
            input.focus();
            return false;
        }
    }
    
    const stockInputs = document.querySelectorAll('input[name^="variants"][name$="[stock]"]');
    for (let input of stockInputs) {
        if (parseInt(input.value) < 0) {
            alertMessage.textContent = 'Tồn kho không được âm.';
            alertDiv.classList.remove('d-none');
            input.focus();
            return false;
        }
    }
    
    return true;
}
</script>

@endsection