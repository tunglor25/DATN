@extends('layouts.app_client')

@section('title', 'Chỉnh sửa địa chỉ')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Chỉnh sửa địa chỉ</h1>
            <p class="text-gray-600 mt-2">Cập nhật thông tin địa chỉ giao hàng</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('addresses.update', $address) }}" method="POST" id="addressForm">
                @csrf
                @method('PUT')
                
                <!-- Receiver Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Thông tin người nhận</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="receiver_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="receiver_name" name="receiver_name" 
                                   value="{{ old('receiver_name', $address->receiver_name) }}"
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
                                   value="{{ old('receiver_phone', $address->receiver_phone) }}"
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
                                            {{ old('province_code', $address->province_code) == $province['code'] ? 'selected' : '' }}>
                                        {{ $province['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name', $address->province_name) }}">
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
                                @foreach($wards as $ward)
                                    <option value="{{ $ward['code'] }}" 
                                            data-name="{{ $ward['name'] }}"
                                            {{ old('ward_code', $address->ward_code) == $ward['code'] ? 'selected' : '' }}>
                                        {{ $ward['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="ward_name" id="ward_name" value="{{ old('ward_name', $address->ward_name) }}">
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
                                  placeholder="Nhập địa chỉ chi tiết (số nhà, tên đường, phường/xã...)">{{ old('street_address', $address->street_address) }}</textarea>
                        @error('street_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Default Address -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_default" value="1" 
                               {{ old('is_default', $address->is_default) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Đặt làm địa chỉ mặc định</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('addresses.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Hủy
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Cập nhật địa chỉ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province_code');
    const wardSelect = document.getElementById('ward_code');
    const provinceNameInput = document.getElementById('province_name');
    const wardNameInput = document.getElementById('ward_name');

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
