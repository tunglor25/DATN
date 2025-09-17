@extends('layouts.app')

@section('content')
    <h2 class="mb-4 text-center">Thêm mới mã giảm giá</h2>

    <form action="{{ route('admin.discount.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            {{--  Code --}}
            <div class="col-md-12 mb-3">
                <label for="code" class="form-label required">Mã giảm giá</label>
                <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror">
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- Type --}}
            <div class="col-md-12 mb-3">
                <label for="type" class="form-label required">Loại giảm giá</label>
                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                    <option value="">Chọn loại</option>
                    <option value="percent">Theo phần trăm</option>
                    <option value="fixed">Theo giá cố định</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Value --}}
            <div class="col-md-6 mb-3">
                <label for="value" class="form-label required">Giá trị giảm giá</label>
                <input type="number" name="value" id="value"
                    class="form-control @error('value') is-invalid @enderror">
                @error('value')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            {{-- Min Order Value --}}
            <div class="col-md-6 mb-3">
                <label for="min_order_value" class="form-label required">Giá trị đơn hàng tối thiểu</label>
                <input type="number" name="min_order_value" id="min_order_value"
                    class="form-control @error('min_order_value') is-invalid @enderror">
                @error('min_order_value')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            

            {{-- is_claimable --}}
            <div class="col-md-6 mb-3">
                <label for="is_claimable" class="form-label">Phát hành</label>
                <select name="is_claimable" id="is_claimable"
                    class="form-select @error('is_claimable') is-invalid @enderror">
                    <option value="">Chọn</option>
                    <option value="1" {{ old('is_claimable') == 1 ? 'selected' : '' }}>Có</option>
                    <option value="0" {{ old('is_claimable') == 0 ? 'selected' : '' }}>Không</option>
                </select>
                @error('is_claimable')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Claim Limit --}}
            <div class="col-md-6 mb-3">
                <label for="claim_limit" class="form-label required">Giới hạn yêu cầu</label>
                <input type="number" name="claim_limit" id="claim_limit"
                    class="form-control @error('claim_limit') is-invalid @enderror">
                @error('claim_limit')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- Description --}}
            <div class="col-md-12 mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                    rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- Starts At --}}
            <div class="col-md-6 mb-3">
                <label for="starts_at" class="form-label">Bắt đầu</label>
                <input type="date" name="starts_at" id="starts_at"
                    class="form-control @error('starts_at') is-invalid @enderror">
                @error('starts_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>
            {{-- Expires At --}}
            <div class="col-md-6 mb-3">
                <label for="expires_at" class="form-label">Hết hạn</label>
                <input type="date" name="expires_at" id="expires_at"
                    class="form-control @error('expires_at') is-invalid @enderror">
                @error('expires_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Tạo mới</button>
                <a href="{{ route('admin.discount.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </form>
    </div>
    <style>
        .required:after {
            content: " *";
            color: red;
            font-weight: bold;
            margin-left: 2px;
        }
    </style>

    </style>
@endsection
