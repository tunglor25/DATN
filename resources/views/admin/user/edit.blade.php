@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cập nhật người dùng</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Tên</label>
                            <div class="form-control bg-light">{{ $user->name }}</div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="form-control bg-light">{{ $user->email }}</div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <div class="form-control bg-light">{{ $user->phone ? $user->phone : 'Chưa cập nhật' }}</div>
                        </div>
                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <div class="form-control bg-light">{{ $user->address ? $user->address : 'Chưa cập nhật' }}</div>
                        </div>
                        <div class="form-group">
                            <label for="status">Trạng thái</label>
                            <div class="form-control bg-light">
                                @if ($user->status === 'active')
                                    <span class="badge bg-success">Đang hoạt động</span>
                                @elseif($user->status === 'inactive')
                                    <span class="badge bg-secondary">Không hoạt động</span>
                                @elseif($user->status === 'banned')
                                    <span class="badge bg-danger">Bị cấm</span>
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="role">Vai trò</label>
                                <select class="form-control" id="role" name="role">
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Người dùng</option>
                                    @if(Auth::user()->isSuperAdmin())
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                                    @endif
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ session('user_back_url', route('admin.user.index')) }}"
                                class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
