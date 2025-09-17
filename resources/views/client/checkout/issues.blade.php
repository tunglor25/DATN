@extends('layouts.app_client')

@section('title', 'Chi tiết vấn đề thanh toán')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Vấn đề khi thanh toán
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h5 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>
                            Không thể tiếp tục thanh toán
                        </h5>
                        <p class="mb-0">
                            Có một số vấn đề với sản phẩm bạn đang cố gắng mua. 
                            Vui lòng xem chi tiết bên dưới và chọn sản phẩm khác hoặc điều chỉnh số lượng.
                        </p>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            Chi tiết vấn đề:
                        </h5>
                        <ul class="list-group list-group-flush">
                            @foreach($issues as $issue)
                                <li class="list-group-item d-flex align-items-start">
                                    <i class="fas fa-exclamation-circle text-danger me-3 mt-1"></i>
                                    <span>{{ $issue }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Xem giỏ hàng
                        </a>
                        <div>
                            <a href="{{ route('client.products.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-2"></i>
                                Mua sản phẩm khác
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
