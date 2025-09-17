@extends('layouts.app')

@section('title', 'Chi tiết người dùng')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center mb-3">
            <div class="col-md-10">
                <div class="card shadow-sm rounded-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Chi tiết người dùng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row row-cols-1 row-cols-md-2 g-4">
                            <div class="col">
                                <label class="form-label fw-bold">Tên</label>
                                <div class="form-control bg-light">{{ $user->name }}</div>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold">Email</label>
                                <div class="form-control bg-light">{{ $user->email }}</div>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                @if (!$user->phone)
                                    <div class="form-control bg-light text-muted">Chưa cập nhật</div>
                                @else
                                    <div class="form-control bg-light">{{ $user->phone }}</div>
                                @endif
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold">Địa chỉ</label>
                                @if (!$user->address)
                                    <div class="form-control bg-light text-muted">Chưa cập nhật</div>
                                @else
                                    <div class="form-control bg-light">{{ $user->address }}</div>
                                @endif
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold">Vai trò</label>
                                <div class="form-control bg-light text-capitalize">{{ $user->role }}</div>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <div class="form-control bg-light">
                                    <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $user->status === 'active' ? 'Đang hoạt động' : 'Đã khóa' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold">Ngày tạo</label>
                                <div class="form-control bg-light">{{ $user->created_at->format('d/m/Y ') }}</div>
                            </div>
                            <div class="col">
                                <label class="form-label fw-bold">Ngày cập nhật</label>
                                <div class="form-control bg-light">{{ $user->updated_at->format('d/m/Y ') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
