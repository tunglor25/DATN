@if ($order && $order instanceof \App\Models\Order)
<div class="order-item" data-order-id="{{ $order->order_number }}">
    <div class="order-header">
        <div class="store-info">
            <i class="fas fa-store text-success me-2"></i>
            <strong>TLO Fashion</strong>
            <span class="store-badge">Chính hãng</span>
            <span class="store-badge official-badge">Official</span>
            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm ms-2">
                <i class="fas fa-eye me-1"></i>Chi tiết
            </a>
        </div>
        <div class="order-details text-muted">
            <div class="d-flex align-items-center gap-2">
                <span class="badge {{ $order->getStatusBadgeClass() }}">{{ $order->getStatusText() }}</span>
                <span class="badge {{ $order->getPaymentStatusBadgeClass() }}">{{ $order->getPaymentStatusText() }}</span>
                <span class="badge bg-success-subtle text-dark">
                    <i class="fas fa-credit-card me-1"></i>
                    {{ $order->getPaymentMethodText() }}
                </span>
            </div>
        </div>
    </div>
    @php
        $items = $order->items ?? collect();
    @endphp
    @if ($items->isNotEmpty())
        @foreach ($items as $index => $item)
            <div class="order-body {{ $index > 0 ? 'additional-products' : '' }}">
                <div class="product-info">
                    <img src="{{ $item->getProductImage() }}"
                         alt="{{ $item->product_name ?? 'Product' }}" class="product-image">
                    <div class="product-details">
                        <div class="product-name">{{ $item->product_name ?? 'Không có tên' }}</div>
                        <div class="product-attributes-container">
                            <div class="total-variant d-flex align-items-center mb-2">
                                <div class="product-quantity me-3">Số lượng: {{ $item->quantity ?? 1 }}</div>
                                <div class="order-number">Mã đơn hàng: {{ $order->order_number }}</div>
                            </div>
                            <div class="product-attributes">
                                @if ($item->variant_attributes && is_array($item->variant_attributes))
                                    @foreach ($item->variant_attributes as $attrName => $attrValue)
                                        <div class="{{ Str::lower($attrName) == 'size' ? 'product-size me-3' : (Str::lower($attrName) == 'color' ? 'product-color me-3' : 'attribute me-3') }}">
                                            {{ ucfirst($attrName) }}: {{ $attrValue ?? 'Không có giá trị' }}
                                        </div>
                                    @endforeach
                                @elseif ($item->variant && $item->variant->attributeValues->isNotEmpty())
                                    @foreach ($item->variant->attributeValues as $attributeValue)
                                        <div class="{{ Str::lower($attributeValue->attribute->name) == 'size' ? 'product-size me-3' : (Str::lower($attributeValue->attribute->name) == 'color' ? 'product-color me-3' : 'attribute me-3') }}">
                                            {{ ucfirst($attributeValue->attribute->name) }}: {{ $attributeValue->value ?? 'Không có giá trị' }}
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-muted"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="product-price">
                        <div class="original-price">₫{{ number_format($item->price ?? 0, 0, ',', '.') }}</div>
                        <div class="current-price">₫{{ number_format($item->subtotal ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
                {{-- Nút đánh giá: cho phép COD đã giao (bất kể payment_status) --}}
                @if ($order->canReviewProducts())
                    <div class="text-end">
                        @if ($item->product_id || ($item->variant && $item->variant->product_id))
                            @php
                                $productId = $item->product_id ?? $item->variant->product_id;
                                $isReviewed = isset($userReviews) && $userReviews->where('product_id', $productId)->where('order_id', $order->id)->isNotEmpty();
                            @endphp
                            @if ($isReviewed)
                                <button class="btn btn-outline-success btn-sm" disabled>
                                    <i class="fas fa-check me-1"></i>Đã đánh giá
                                </button>
                            @else
                                <a href="{{ route('product.review', ['productId' => $productId, 'orderId' => $order->id]) }}" class="btn btn-outline-dark btn-sm">Đánh giá sản phẩm</a>
                            @endif
                        @else
                            <button class="btn btn-outline-dark btn-sm" disabled>Đánh giá sản phẩm</button>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="order-body">
            <p class="text-muted">Không có sản phẩm trong đơn hàng này.</p>
        </div>
    @endif

    {{-- Hiển thị lý do trả hàng nếu có --}}
    @if ($order->status === 'return_requested' && $order->return_reason)
        <div class="return-reason-box">
            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
            <strong>Lý do trả hàng:</strong> {{ $order->return_reason }}
        </div>
    @endif

    <div class="order-footer">
        <div class="order-total">
            <span class="total-label">
                <i class="fas fa-shield-alt text-warning me-1"></i>
                Tổng số tiền:
            </span>
            <span class="total-amount">₫{{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="order-actions">
            @if ($items->count() > 1)
                <div class="seller-note">
                    <span class="show-more-btn">
                        <span class="show-text">Xem thêm ({{ $items->count() - 1 }} sản phẩm)</span>
                        <span class="hide-text" style="display: none;">Thu gọn</span>
                        <i class="fas fa-chevron-down ms-1 chevron-icon"></i>
                    </span>
                </div>
            @else
                <div class="seller-note"></div>
            @endif
            <div class="action-buttons">
                {{-- Form ẩn cho TIẾP TỤC THANH TOÁN VNPay --}}
                <form class="continue-vnpay-form" action="{{ route('vnpay.continue') }}" method="POST" style="display:none;">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                </form>
                
                {{-- Nút Tiếp tục thanh toán cho VNPay --}}
                @if ($order->canContinueVnPayPayment())
                    <button class="btn btn-sm btn-pay continue-vnpay" data-order-id="{{ $order->id }}">
                        <i class="fas fa-credit-card me-1"></i>
                        Tiếp tục thanh toán
                    </button>
                @endif

                {{-- NÚT XÁC NHẬN ĐÃ NHẬN HÀNG - khi đang giao (shipped) --}}
                @if ($order->canConfirmReceived())
                    <form action="{{ route('orders.confirm-received', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-confirm-received confirm-received-btn" 
                                onclick="return confirm('Bạn xác nhận đã nhận được hàng?')">
                            <i class="fas fa-check-circle me-1"></i>Đã nhận hàng
                        </button>
                    </form>
                @endif

                {{-- NÚT YÊU CẦU TRẢ HÀNG - khi đã giao (delivered) và trong thời hạn --}}
                @if ($order->canRequestReturn())
                    <button class="btn btn-sm btn-return-request" data-bs-toggle="modal" data-bs-target="#returnModal{{ $order->id }}">
                        <i class="fas fa-undo me-1"></i>Trả hàng/Hoàn tiền
                    </button>
                @endif
                
                {{-- Form ẩn cho MUA LẦN NỮA --}}
                <form class="buy-again-order-form" action="{{ route('orders.buy-again', $order->id) }}" method="POST" style="display:none;">
                    @csrf
                </form>
                {{-- Chỉ hiển thị nút Mua Lần Nữa cho đơn hàng đã giao, đã hủy, hoặc đã trả --}}
                @if (in_array($order->status, ['delivered', 'cancelled', 'returned']))
                    <button class="btn btn-sm buy-again" data-order-id="{{ $order->id }}">
                        Mua Lần Nữa
                    </button>
                @endif

                {{-- Nút hủy đơn: cho phép ở pending và confirmed --}}
                @if ($order->canBeCancelled())
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm cancel-order">
                            Hủy đơn
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal yêu cầu trả hàng --}}
@if ($order->canRequestReturn())
<div class="modal fade" id="returnModal{{ $order->id }}" tabindex="-1" aria-labelledby="returnModalLabel{{ $order->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('orders.request-return', $order->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel{{ $order->id }}">
                        <i class="fas fa-undo me-2"></i>Yêu cầu trả hàng/hoàn tiền
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        Đơn hàng <strong>#{{ $order->order_number }}</strong>
                        <br>Bạn còn <strong>{{ $order->getReturnDaysRemaining() }} ngày</strong> để yêu cầu trả hàng.
                    </div>
                    <div class="mb-3">
                        <label for="return_reason_{{ $order->id }}" class="form-label fw-bold">
                            Lý do trả hàng <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="return_reason_{{ $order->id }}" 
                                  name="return_reason" rows="4" 
                                  placeholder="Vui lòng mô tả chi tiết lý do bạn muốn trả hàng (ít nhất 10 ký tự)..." 
                                  required minlength="10" maxlength="1000"></textarea>
                        <div class="form-text">Ví dụ: Sản phẩm bị lỗi, sai màu, sai size, không đúng mô tả...</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-paper-plane me-1"></i>Gửi yêu cầu trả hàng
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endif
