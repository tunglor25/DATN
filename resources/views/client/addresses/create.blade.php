@extends('layouts.app_client')

@section('title', 'Thêm địa chỉ mới')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Thêm địa chỉ mới</h1>
            <p class="text-gray-600 mt-2">Thêm địa chỉ giao hàng mới vào sổ địa chỉ của bạn</p>
            
            @if(session('warning'))
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                {{ session('warning') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('addresses.store') }}" method="POST" id="addressForm">
                @csrf
                
                <!-- Receiver Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Thông tin người nhận</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="receiver_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="receiver_name" name="receiver_name" 
                                   value="{{ old('receiver_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('receiver_name') border-red-500 @enderror"
                                   placeholder="Nhập họ và tên">
                            @error('receiver_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="receiver_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="receiver_phone" name="receiver_phone" 
                                   value="{{ old('receiver_phone') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('receiver_phone') border-red-500 @enderror"
                                   placeholder="Nhập số điện thoại">
                            @error('receiver_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Thông tin địa chỉ</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="province_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Tỉnh/Thành phố <span class="text-red-500">*</span>
                            </label>
                            <select id="province_code" name="province_code" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('province_code') border-red-500 @enderror">
                                <option value="">Chọn tỉnh/thành phố</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province['code'] }}" 
                                            data-name="{{ $province['name'] }}"
                                            {{ old('province_code') == $province['code'] ? 'selected' : '' }}>
                                        {{ $province['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name') }}">
                            @error('province_code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ward_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Xã/Phường <span class="text-red-500">*</span>
                            </label>
                            <select id="ward_code" name="ward_code" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ward_code') border-red-500 @enderror">
                                <option value="">Chọn xã/phường</option>
                            </select>
                            <input type="hidden" name="ward_name" id="ward_name" value="{{ old('ward_name') }}">
                            @error('ward_code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="street_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Địa chỉ chi tiết <span class="text-red-500">*</span>
                        </label>
                        <textarea id="street_address" name="street_address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('street_address') border-red-500 @enderror"
                                  placeholder="Nhập địa chỉ chi tiết (số nhà, tên đường, phường/xã...)">{{ old('street_address') }}</textarea>
                        @error('street_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Default Address -->
                <div class="mb-6">
                    @if(auth()->user()->addresses()->count() == 0)
                        <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                                <div>
                                    <p class="text-sm text-blue-700 font-medium">Đây là địa chỉ đầu tiên của bạn</p>
                                    <p class="text-sm text-blue-600">Địa chỉ này sẽ tự động được đặt làm địa chỉ mặc định để thuận tiện cho việc thanh toán.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <label class="flex items-center">
                        <input type="checkbox" name="is_default" value="1" 
                               {{ old('is_default') ? 'checked' : (auth()->user()->addresses()->count() == 0 ? 'checked' : '') }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">
                            Đặt làm địa chỉ mặc định
                            @if(auth()->user()->addresses()->count() == 0)
                                <span class="text-blue-600 font-medium">(Tự động)</span>
                            @endif
                        </span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center">
                    <div>
                        @if(session('pending_checkout_info'))
                            <a href="{{ route('checkout.return-from-address') }}" 
                               class="px-4 py-2 border border-orange-300 rounded-md text-orange-700 hover:bg-orange-50 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Quay lại thanh toán
                            </a>
                        @endif
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('addresses.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                            Hủy
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Thêm địa chỉ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province_code');
    const wardSelect = document.getElementById('ward_code');
    const provinceNameInput = document.getElementById('province_name');
    const wardNameInput = document.getElementById('ward_name');

    // Load wards for pre-selected province (if any)
    const selectedOption = provinceSelect.options[provinceSelect.selectedIndex];
    const dataName = selectedOption.getAttribute('data-name');
    const selectedValue = provinceSelect.value;
    
    if(selectedValue && selectedValue != ''){
        loadWards(dataName);
    }

    // Load wards when province changes
    provinceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const provinceName = selectedOption.dataset.name;
        const provinceCode = this.value;

        // Update hidden inputs
        provinceNameInput.value = provinceName || '';
        wardNameInput.value = '';

        // Clear ward select
        wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';

        if (provinceCode) {
            loadWards(provinceName);
        }
    });

    // Update ward name when ward changes
    wardSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const wardName = selectedOption.dataset.name;
        wardNameInput.value = wardName || '';
    });

    // Function to load wards
    function loadWards(provinceName) {
        wardSelect.disabled = true;
        
        const url = `/ho-so/dia-chi/ajax/wards?province_name=${encodeURIComponent(provinceName)}`;
        
        fetch(url)
            .then(response => response.json())
            .then(wards => {
                wardSelect.disabled = false;
                
                if (Array.isArray(wards) && wards.length > 0) {
                    wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.code;
                        option.dataset.name = ward.name;
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading wards:', error);
                wardSelect.disabled = false;
            });
    }

    // Form validation
    const form = document.getElementById('addressForm');
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = ['receiver_name', 'receiver_phone', 'province_code', 'ward_code', 'street_address'];
        
        // Clear previous error styles
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field) {
                field.classList.remove('border-red-500');
                const errorElement = field.parentNode.querySelector('.text-red-500');
                if (errorElement) {
                    errorElement.remove();
                }
            }
        });

        // Check each required field
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field && !field.value.trim()) {
                field.classList.add('border-red-500');
                
                // Add error message
                const errorMessage = document.createElement('p');
                errorMessage.className = 'text-red-500 text-sm mt-1';
                errorMessage.textContent = `Trường ${getFieldLabel(fieldName)} là bắt buộc.`;
                
                // Insert after the field
                field.parentNode.appendChild(errorMessage);
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Helper function to get field labels
    function getFieldLabel(fieldName) {
        const labels = {
            'receiver_name': 'họ và tên',
            'receiver_phone': 'số điện thoại',
            'province_code': 'tỉnh/thành phố',
            'ward_code': 'xã/phường',
            'street_address': 'địa chỉ chi tiết'
        };
        return labels[fieldName] || fieldName;
    }
});
</script>
@endsection
@endsection
