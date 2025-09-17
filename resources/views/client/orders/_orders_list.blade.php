@php
    $userReviews = $userReviews ?? collect();
@endphp

@if (Auth::check())
    @if ($status === 'all')
        @forelse ($orders as $statusGroup => $orderGroup)
            @foreach ($orderGroup as $order)
                @if ($order && $order instanceof \App\Models\Order)
                    @include('client.orders._order_item', ['order' => $order, 'status' => $statusGroup, 'userReviews' => $userReviews])
                @endif
            @endforeach
        @empty
            <div class="text-center py-5">
                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                <p class="text-muted">Không có đơn hàng nào</p>
            </div>
        @endforelse
    @else
        @if ($orders->isNotEmpty())
            @foreach ($orders as $order)
                @if ($order && $order instanceof \App\Models\Order)
                    @include('client.orders._order_item', ['order' => $order, 'status' => $status, 'userReviews' => $userReviews])
                @endif
            @endforeach
        @else
            <div class="text-center py-5">
                @if ($status === 'pending')
                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Không có đơn hàng chờ xác nhận</p>
                @elseif ($status === 'confirmed')
                    <i class="fas fa-check fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Không có đơn hàng đã xác nhận</p>
                @elseif ($status === 'processing')
                    <i class="fas fa-box fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Không có đơn hàng chờ lấy hàng</p>
                @elseif ($status === 'shipped')
                    <i class="fas fa-shipping-fast fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Không có đơn hàng đang giao</p>
                @elseif ($status === 'delivered')
                    <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Không có đơn hàng đã giao</p>
                @elseif ($status === 'cancelled')
                    <i class="fas fa-times-circle fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Không có đơn hàng đã hủy</p>
                @endif
            </div>
        @endif
    @endif
@else
    <div class="text-center py-5">
        <i class="fas fa-box fa-3x text-muted mb-3"></i>
        <p class="text-muted">Vui lòng đăng nhập để xem đơn hàng</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập</a>
    </div>
@endif
