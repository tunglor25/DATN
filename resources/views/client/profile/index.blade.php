@extends('layouts.app_client')

@section('title', 'Thông tin cá nhân - TLO Fashion')

@section('content')
<div class="tlo-full-width">
    <!-- Hero -->
    <section class="tlo-page-hero">
        <div class="tlo-page-hero-inner">
            <div class="tlo-hero-badge"><i class="fas fa-user-circle"></i> Tài khoản</div>
            <h1 class="tlo-hero-title">Thông tin cá nhân</h1>
            <p class="tlo-hero-desc">Quản lý thông tin hồ sơ của bạn</p>
        </div>
    </section>

    <div class="user-page-layout">
        <!-- Sidebar -->
        <div>@include('client.partials.user-sidebar')</div>

        <!-- Main Content -->
        <div class="user-main-card tlo-animate">
            <div class="user-main-header">
                <h5><i class="fas fa-id-card" style="color: var(--tlo-accent); margin-right: 8px;"></i> Thông tin cá nhân</h5>
            </div>
            <div class="user-main-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div style="width: 100px; height: 100px; margin: 0 auto 16px; background: linear-gradient(135deg, rgba(255,107,107,0.1), rgba(238,90,36,0.08)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #ff6b6b, #ee5a24); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.4rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <h6 style="font-weight: 700; color: var(--tlo-text-primary);">{{ Auth::user()->name }}</h6>
                            <p style="font-size: 0.85rem; color: var(--tlo-text-light); margin: 0;">{{ Auth::user()->phone }}</p>
                        </div>

                        <div class="col-md-8">
                            <div class="row mb-3">
                                <label for="name" class="col-sm-3 user-form-label">Họ và tên</label>
                                <div class="col-sm-9">
                                    <input type="text" id="name" name="name" class="form-control tlo-form-control"
                                        value="{{ old('name', Auth::user()->name) }}" readonly />
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-sm-3 user-form-label">Số điện thoại</label>
                                <div class="col-sm-9">
                                    <input type="number" id="phone" name="phone" class="form-control tlo-form-control"
                                        value="{{ old('phone', Auth::user()->phone) }}" readonly />
                                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label for="gender" class="col-sm-3 user-form-label">Giới tính</label>
                                <div class="col-sm-9">
                                    <select id="gender" name="gender" class="form-select tlo-form-control" disabled>
                                        <option value="">Chọn giới tính</option>
                                        <option value="M" {{ old('gender', Auth::user()->gender) == 'M' ? 'selected' : '' }}>Nam</option>
                                        <option value="F" {{ old('gender', Auth::user()->gender) == 'F' ? 'selected' : '' }}>Nữ</option>
                                        <option value="O" {{ old('gender', Auth::user()->gender) == 'O' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-9 offset-sm-3 d-flex gap-2">
                                    <button type="button" class="tlo-btn tlo-btn-primary btn-edit">
                                        <i class="fas fa-pen"></i> Chỉnh sửa
                                    </button>
                                    <button type="submit" class="tlo-btn tlo-btn-primary d-none btn-save">
                                        <i class="fas fa-save"></i> Lưu thông tin
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const btnEdit = document.querySelector('.btn-edit');
    const btnSave = document.querySelector('.btn-save');
    const inputs = document.querySelectorAll('form input[readonly], form select[disabled]');

    btnEdit.addEventListener('click', function () {
        inputs.forEach(el => { el.removeAttribute('readonly'); el.removeAttribute('disabled'); });
        btnEdit.classList.add('d-none');
        btnSave.classList.remove('d-none');
    });

    @if ($errors->any())
        inputs.forEach(el => { el.removeAttribute('readonly'); el.removeAttribute('disabled'); });
        btnEdit.classList.add('d-none');
        btnSave.classList.remove('d-none');
    @endif
</script>
@endsection
