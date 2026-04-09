@extends('layouts.app_client')

@section('title', 'Chỉnh Sửa Địa Chỉ - TLO Fashion')

@section('content')
<div class="tlo-full-width">
    <!-- Hero -->
    <section class="tlo-page-hero">
        <div class="tlo-page-hero-inner">
            <div class="tlo-hero-badge"><i class="fas fa-map-marker-alt"></i> Sổ địa chỉ</div>
            <h1 class="tlo-hero-title">Chỉnh Sửa Địa Chỉ</h1>
            <p class="tlo-hero-desc">Cập nhật thông tin địa chỉ giao hàng</p>
        </div>
    </section>

    <div class="tlo-container" style="padding-top: 32px; padding-bottom: 60px;">
        <div class="user-page-layout">
            @include('client.partials.user-sidebar')

            <div class="user-page-main">
                <div class="user-page-card">
                    <form action="{{ route('addresses.update', $address) }}" method="POST" id="addressForm">
                        @csrf
                        @method('PUT')

                        <!-- Receiver Information -->
                        <div class="addr-form-section">
                            <h3 class="addr-form-section-title"><i class="fas fa-user"></i> Thông tin người nhận</h3>
                            <div class="addr-form-grid">
                                <div class="addr-form-group">
                                    <label for="receiver_name">Họ và tên <span class="required">*</span></label>
                                    <input type="text" id="receiver_name" name="receiver_name"
                                           value="{{ old('receiver_name', $address->receiver_name) }}"
                                           class="addr-form-input @error('receiver_name') has-error @enderror"
                                           placeholder="Nhập họ và tên">
                                    @error('receiver_name')
                                        <p class="addr-form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="addr-form-group">
                                    <label for="receiver_phone">Số điện thoại <span class="required">*</span></label>
                                    <input type="text" id="receiver_phone" name="receiver_phone"
                                           value="{{ old('receiver_phone', $address->receiver_phone) }}"
                                           class="addr-form-input @error('receiver_phone') has-error @enderror"
                                           placeholder="Nhập số điện thoại">
                                    @error('receiver_phone')
                                        <p class="addr-form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="addr-form-section">
                            <h3 class="addr-form-section-title"><i class="fas fa-location-dot"></i> Thông tin địa chỉ</h3>
                            <div class="addr-form-grid">
                                <div class="addr-form-group">
                                    <label for="province_code">Tỉnh/Thành phố <span class="required">*</span></label>
                                    <select id="province_code" name="province_code"
                                            class="addr-form-input @error('province_code') has-error @enderror">
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
                                        <p class="addr-form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="addr-form-group">
                                    <label for="ward_code">Xã/Phường <span class="required">*</span></label>
                                    <select id="ward_code" name="ward_code"
                                            class="addr-form-input @error('ward_code') has-error @enderror">
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
                                        <p class="addr-form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="addr-form-group">
                                <label for="street_address">Địa chỉ chi tiết <span class="required">*</span></label>
                                <textarea id="street_address" name="street_address" rows="3"
                                          class="addr-form-input @error('street_address') has-error @enderror"
                                          placeholder="Số nhà, tên đường, phường/xã...">{{ old('street_address', $address->street_address) }}</textarea>
                                @error('street_address')
                                    <p class="addr-form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Default Address -->
                        <div class="addr-form-section">
                            <label class="addr-form-checkbox">
                                <input type="checkbox" name="is_default" value="1"
                                       {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                                <span>Đặt làm địa chỉ mặc định</span>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="addr-form-actions">
                            <div></div>
                            <div class="addr-form-actions-right">
                                <a href="{{ route('addresses.index') }}" class="addr-form-btn outline">Hủy</a>
                                <button type="submit" class="addr-form-btn primary">
                                    <i class="fas fa-save"></i> Cập nhật địa chỉ
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('client.addresses._form_styles')

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province_code');
    const wardSelect = document.getElementById('ward_code');
    const provinceNameInput = document.getElementById('province_name');
    const wardNameInput = document.getElementById('ward_name');

    provinceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const provinceName = selectedOption.dataset.name;
        provinceNameInput.value = provinceName || '';
        wardNameInput.value = '';
        wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';
        if (this.value) { loadWards(provinceName); }
    });

    wardSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        wardNameInput.value = selectedOption.dataset.name || '';
    });

    function loadWards(provinceName) {
        wardSelect.disabled = true;
        fetch(`/ho-so/dia-chi/ajax/wards?province_name=${encodeURIComponent(provinceName)}`)
            .then(r => r.json())
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
            .catch(() => { wardSelect.disabled = false; });
    }

    document.getElementById('addressForm').addEventListener('submit', function(e) {
        let isValid = true;
        const fields = ['receiver_name', 'receiver_phone', 'province_code', 'ward_code', 'street_address'];
        const labels = { receiver_name: 'họ và tên', receiver_phone: 'số điện thoại', province_code: 'tỉnh/thành phố', ward_code: 'xã/phường', street_address: 'địa chỉ chi tiết' };
        fields.forEach(name => {
            const el = document.getElementById(name);
            if (el) {
                el.classList.remove('has-error');
                const err = el.parentNode.querySelector('.addr-form-error');
                if (err) err.remove();
            }
        });
        fields.forEach(name => {
            const el = document.getElementById(name);
            if (el && !el.value.trim()) {
                el.classList.add('has-error');
                const msg = document.createElement('p');
                msg.className = 'addr-form-error';
                msg.textContent = `Trường ${labels[name]} là bắt buộc.`;
                el.parentNode.appendChild(msg);
                isValid = false;
            }
        });
        if (!isValid) e.preventDefault();
    });
});
</script>
@endsection
@endsection
