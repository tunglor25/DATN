<div class="col-lg-4 col-md-6 mb-4">
    <div class="card h-100 shadow-sm border-0 
        {{ $userDiscount->status === 'used' ? 'border-success' : ($userDiscount->status === 'expired' ? 'border-danger' : 'border-primary') }}">
        
        @if($userDiscount->discount->image)
            <img src="{{ asset('storage/' . $userDiscount->discount->image) }}" class="card-img-top" alt="Discount" style="height: 200px; object-fit: cover;">
        @else
            <div class="card-img-top bg-gradient-primary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                <i class="fas fa-tag fa-3x"></i>
            </div>
        @endif
        
        <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title text-primary mb-0">{{ $userDiscount->discount->code }}</h5>
                <span class="badge bg-success">
                    {{ $userDiscount->discount->type === 'fixed' ? 'Giảm ' . number_format($userDiscount->discount->value) . 'đ' : 'Giảm ' . $userDiscount->discount->value . '%' }}
                </span>
            </div>
            
            @if($userDiscount->discount->description)
                <p class="card-text text-muted mb-3">{{ $userDiscount->discount->description }}</p>
            @endif
            
            <div class="discount-info mb-3">
                @if($userDiscount->discount->min_order_value > 0)
                    <div class="mb-2">
                        <i class="fas fa-shopping-cart text-info me-1"></i>
                        <small class="text-muted">Đơn tối thiểu: {{ number_format($userDiscount->discount->min_order_value) }}đ</small>
                    </div>
                @endif
                
                <div class="mb-2">
                    <i class="fas fa-calendar text-primary me-1"></i>
                    <small class="text-muted">Nhận lúc: {{ $userDiscount->claimed_at->format('d/m/Y H:i') }}</small>
                </div>
                
                @if($userDiscount->used_at)
                    <div class="mb-2">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        <small class="text-success">Đã sử dụng: {{ $userDiscount->used_at->format('d/m/Y H:i') }}</small>
                    </div>
                @endif
                
                @if($userDiscount->discount->expires_at)
                    <div class="mb-2">
                        <i class="fas fa-clock text-danger me-1"></i>
                        <small class="text-muted">Hết hạn: {{ $userDiscount->discount->expires_at->format('d/m/Y H:i') }}</small>
                    </div>
                @endif
            </div>
            
            <div class="status-info mt-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Trạng thái: 
                        @if($userDiscount->status === 'active')
                            <span class="text-success fw-bold">Có thể sử dụng</span>
                        @elseif($userDiscount->status === 'used')
                            <span class="text-info fw-bold">Đã sử dụng</span>
                        @else
                            <span class="text-danger fw-bold">Hết hạn</span>
                        @endif
                    </small>
                    
                    @if($userDiscount->status === 'active' && $userDiscount->discount->isValid())
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-shopping-cart me-1"></i>Sử dụng
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> 