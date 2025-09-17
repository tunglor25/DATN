@extends('layouts.app_client')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Thanh toán đơn hàng</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary" id="back-to-cart">
                <i class="fas fa-arrow-left me-2"></i>
                Quay lại giỏ hàng
            </a>
        </div>
    </div>
    
    <!-- Hiển thị thông báo và link đến trang chi tiết vấn đề -->
    @if(session('warning') && session('checkout_issues'))
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-start">
                <i class="fas fa-exclamation-triangle me-3 mt-1"></i>
                <div class="flex-grow-1">
                    <strong>Lưu ý:</strong> {{ session('warning') }}
                    <div class="mt-2">
                        <a href="{{ route('checkout.issues') }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-info-circle me-1"></i>
                            Xem chi tiết vấn đề
                        </a>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    
    <div class="row">
        <div class="col-lg-7 mb-4">
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                @if(request('product_id'))
                    <input type="hidden" name="product_id" value="{{ request('product_id') }}">
                    <input type="hidden" name="variant_id" value="{{ request('variant_id') }}">
                    <input type="hidden" name="quantity" value="{{ request('quantity', 1) }}">
                @endif
                @if(request('selected_items'))
                    <input type="hidden" name="selected_items" value="{{ request('selected_items') }}">
                @endif
                <!-- Chọn địa chỉ giao hàng -->
                <div class="mb-4">
                    <!-- Hiển thị thông tin địa chỉ đã chọn -->
                    <div id="selected-address-info" class="p-3 border rounded" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Người nhận:</strong>
                                <span id="selected-name"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>Số điện thoại:</strong>
                                <span id="selected-phone"></span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong>Địa chỉ:</strong>
                            <span id="selected-address"></span>
                        </div>
                    </div>
                    <label class="form-label mt-3">Địa chỉ giao hàng</label>
                    <div class="d-flex gap-2 mb-2">
                        <select class="form-select" id="address-selector" style="flex: 1;">
                            <option value="">Chọn địa chỉ giao hàng</option>
                            @foreach(auth()->user()->addresses()->where('is_active', true)->get() as $address)
                                <option value="{{ $address->id }}" 
                                        data-name="{{ $address->receiver_name }}"
                                        data-phone="{{ $address->receiver_phone }}"
                                        data-address="{{ $address->full_address }}"
                                        {{ $defaultAddress && $defaultAddress->id == $address->id ? 'selected' : '' }}>
                                    {{ $address->full_address }}
                                    @if($address->is_default)
                                        <span class="text-muted">(Mặc định)</span>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="selected_address_id" id="selected_address_id" value="{{ $defaultAddress ? $defaultAddress->id : '' }}">
                        <a href="{{ route('addresses.create') }}?from_checkout=1" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>
                            Thêm mới
                        </a>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="note" class="form-label">Ghi chú (tuỳ chọn)</label>
                    <textarea class="form-control" id="note" name="note" rows="2">{{ old('note') }}</textarea>
                </div>
                
                <!-- Mã giảm giá -->
                <div class="mb-3">
                    <label for="user_discount_id" class="form-label">Mã giảm giá (tuỳ chọn)</label>
                    <div class="input-group">
                        <select class="form-select" id="user_discount_id" name="user_discount_id" style="border-radius: 5px;">
                            <option value="">Chọn mã giảm giá</option>
                            @foreach($userDiscounts as $userDiscount)
                                @php
                                    $discount = $userDiscount->discount;
                                    $discountText = $discount->code;
                                    if ($discount->type === 'fixed') {
                                        $discountText .= ' - Giảm ' . number_format($discount->value) . 'đ';
                                    } else {
                                        $discountText .= ' - Giảm ' . $discount->value . '%';
                                    }
                                    if ($discount->min_order_value > 0) {
                                        $discountText .= ' (Tối thiểu ' . number_format($discount->min_order_value) . 'đ)';
                                    }
                                @endphp
                                <option value="{{ $userDiscount->id }}" data-discount="{{ json_encode([
                                    'id' => $userDiscount->id,
                                    'code' => $discount->code,
                                    'type' => $discount->type,
                                    'value' => $discount->value,
                                    'min_order_value' => $discount->min_order_value,
                                    'description' => $discount->description
                                ]) }}">
                                    {{ $discountText }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-outline-secondary" id="apply-discount-btn">
                            Áp dụng
                        </button>
                    </div>
                    <div id="discount-message" class="mt-2"></div>
                    @if($userDiscounts->count() == 0)
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Bạn chưa có mã giảm giá nào. 
                            <a href="{{ route('discounts.index') }}" class="text-decoration-none">Nhận mã giảm giá ngay</a>
                        </small>
                    @endif
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Phương thức thanh toán</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                            <label class="form-check-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_method" id="vnpay" value="vnpay">
                            <label class="form-check-label" for="vnpay">Thanh toán VNPay</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-dark w-100 py-3" id="order-btn">Xác nhận đặt hàng</button>
                <button type="button" class="btn w-100 mt-2 d-none d-flex justify-content-center align-items-center" id="vnpay-btn" style="background-color:#00FF99;">
                    <img src="https://cdn-new.topcv.vn/unsafe/150x/https://static.topcv.vn/company_logos/cong-ty-cp-giai-phap-thanh-toan-viet-nam-vnpay-6194ba1fa3d66.jpg" alt="VNPay" style="height:44px;" class="mr-3">
                    Thanh toán qua VNPay
                </button>
            </form>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header bg-light fw-bold">Sản phẩm mua</div>
                <ul class="list-group list-group-flush">
                    @foreach($cart->items as $item)
                        <li class="list-group-item d-flex align-items-center">
                            <div class="me-3">
                                @php
                                    $productImage = null;
                                    // Kiểm tra xem item có method getProductImageAttribute không (CartItem)
                                    if (is_object($item) && method_exists($item, 'getProductImageAttribute')) {
                                        $productImage = $item->getProductImageAttribute();
                                    } elseif (isset($item->product_image)) {
                                        // Cho trường hợp mua lại và mua ngay (object giả lập)
                                        $productImage = $item->product_image;
                                    }
                                    
                                    // Kiểm tra ảnh có hợp lệ không
                                    $hasValidImage = !empty($productImage) && $productImage !== '';
                                @endphp
                                @if($hasValidImage)
                                    @if(str_starts_with($productImage, 'http'))
                                        {{-- Nếu là URL đầy đủ (từ CartItem) --}}
                                        <img src="{{ $productImage }}" 
                                             alt="{{ $item->product_name }}" 
                                             class="img-thumbnail" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        {{-- Nếu là đường dẫn tương đối (từ mua lại/mua ngay) --}}
                                        <img src="{{ asset('storage/' . $productImage) }}" 
                                             alt="{{ $item->product_name }}" 
                                             class="img-thumbnail" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @endif
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $item->product_name }}</div>
                                @if(is_array($item->variant_attributes) && count($item->variant_attributes) > 0)
                                    <div class="small text-muted">
                                        @foreach($item->variant_attributes as $attrKey => $attrValue)
                                            @if(is_array($attrValue) && isset($attrValue['attribute_name']) && isset($attrValue['value']))
                                                {{ $attrValue['attribute_name'] }}: {{ $attrValue['value'] }}@if(!$loop->last), @endif
                                            @elseif(is_string($attrKey) && is_string($attrValue))
                                                {{ ucfirst($attrKey) }}: {{ $attrValue }}@if(!$loop->last), @endif
                                            @endif
                                        @endforeach
                                    </div>
                                @elseif(is_object($item->variant_attributes) && $item->variant_attributes->count() > 0)
                                    <div class="small text-muted">
                                        @foreach($item->variant_attributes as $attrValue)
                                            {{ $attrValue->attribute->name }}: {{ $attrValue->value }}@if(!$loop->last), @endif
                                        @endforeach
                                    </div>
                                @endif
                                <div class="small">Số lượng: {{ $item->quantity }}</div>
                            </div>
                            <div class="text-end">
                                <div>{{ number_format($item->price) }}đ</div>
                                <div class="fw-bold text-danger">{{ number_format($item->subtotal) }}đ</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Tạm tính:</span>
                        <span id="subtotal-amount">{{ number_format($cart->total_amount) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2" id="discount-row" style="display: none;">
                        <span>Giảm giá:</span>
                        <span class="text-success" id="discount-amount">-0đ</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2" id="after-discount-row" style="display: none;">
                        <span>Sau giảm giá:</span>
                        <span id="after-discount-amount">{{ number_format($cart->total_amount) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>VAT ({{ config('app.vat_rate', 10) }}%):</span>
                        <span id="tax-amount">{{ number_format($cart->total_amount * (config('app.vat_rate', 10) / 100)) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success">Miễn phí</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Tổng cộng:</span>
                        <span class="fw-bold text-danger h5 mb-0" id="final-total">{{ number_format($cart->total_amount * (1 + config('app.vat_rate', 10) / 100)) }}đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const applyDiscountBtn = document.getElementById('apply-discount-btn');
    const userDiscountSelect = document.getElementById('user_discount_id');
    const discountMessage = document.getElementById('discount-message');
    const discountRow = document.getElementById('discount-row');
    const discountAmount = document.getElementById('discount-amount');
    const afterDiscountRow = document.getElementById('after-discount-row');
    const afterDiscountAmount = document.getElementById('after-discount-amount');
    const taxAmount = document.getElementById('tax-amount');
    const finalTotal = document.getElementById('final-total');
    const subtotalAmount = document.getElementById('subtotal-amount');
    
    let currentDiscount = null;
    const subtotal = {{ $cart->total_amount }};
    const vatRate = {{ config('app.vat_rate', 10) }} / 100;

    applyDiscountBtn.addEventListener('click', function() {
        const userDiscountId = userDiscountSelect.value;
        if (!userDiscountId) {
            showDiscountMessage('Vui lòng chọn mã giảm giá!', 'error');
            return;
        }

        // Disable button while checking
        applyDiscountBtn.disabled = true;
        applyDiscountBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch('{{ route("checkout.check-discount") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                user_discount_id: userDiscountId,
                subtotal: subtotal
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentDiscount = data.discount;
                showDiscountMessage(data.message, 'success');
                updateDiscountDisplay();
            } else {
                currentDiscount = null;
                showDiscountMessage(data.message, 'error');
                hideDiscountDisplay();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showDiscountMessage('Có lỗi xảy ra, vui lòng thử lại!', 'error');
        })
        .finally(() => {
            applyDiscountBtn.disabled = false;
            applyDiscountBtn.innerHTML = 'Áp dụng';
        });
    });

    // Thêm event listener cho select để tự động áp dụng khi chọn
    userDiscountSelect.addEventListener('change', function() {
        if (this.value) {
            applyDiscountBtn.click();
        } else {
            currentDiscount = null;
            showDiscountMessage('', '');
            hideDiscountDisplay();
        }
    });

    function showDiscountMessage(message, type) {
        discountMessage.innerHTML = `<div class="alert alert-${type === 'success' ? 'success' : 'danger'} alert-sm">${message}</div>`;
    }

    function updateDiscountDisplay() {
        if (currentDiscount) {
            const discountValue = currentDiscount.discount_amount;
            const taxValue = currentDiscount.tax_amount;
            const finalValue = currentDiscount.final_total;
            const afterDiscountValue = subtotal - discountValue;
            
            discountAmount.textContent = '-' + discountValue.toLocaleString() + 'đ';
            afterDiscountAmount.textContent = afterDiscountValue.toLocaleString() + 'đ';
            taxAmount.textContent = taxValue.toLocaleString() + 'đ';
            finalTotal.textContent = finalValue.toLocaleString() + 'đ';
            
            discountRow.style.display = 'flex';
            afterDiscountRow.style.display = 'flex';
        }
    }

    function hideDiscountDisplay() {
        const taxValue = subtotal * vatRate;
        const finalValue = subtotal + taxValue;
        
        discountAmount.textContent = '-0đ';
        afterDiscountAmount.textContent = subtotal.toLocaleString() + 'đ';
        taxAmount.textContent = taxValue.toLocaleString() + 'đ';
        finalTotal.textContent = finalValue.toLocaleString() + 'đ';
        
        discountRow.style.display = 'none';
        afterDiscountRow.style.display = 'none';
    }

    // Clear discount when select changes to empty
    userDiscountSelect.addEventListener('change', function() {
        if (!this.value && currentDiscount) {
            currentDiscount = null;
            showDiscountMessage('', '');
            hideDiscountDisplay();
        }
    });

    const vnpayRadio = document.getElementById('vnpay');
    const orderBtn = document.getElementById('order-btn');
    const vnpayBtn = document.getElementById('vnpay-btn');
    const checkoutForm = document.querySelector('form[action="{{ route('checkout.process') }}"]');

    // Hiển thị nút VNPay khi chọn phương thức thanh toán là vnpay
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (vnpayRadio.checked) {
                orderBtn.classList.add('d-none');
                vnpayBtn.classList.remove('d-none');
            } else {
                orderBtn.classList.remove('d-none');
                vnpayBtn.classList.add('d-none');
            }
        });
    });

    // Xử lý click nút VNPay
    vnpayBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Disable button để tránh double click
        vnpayBtn.disabled = true;
        vnpayBtn.textContent = 'Đang xử lý...';
        
        // Submit form để tạo đơn hàng trước, sau đó gọi VNPay
        const formData = new FormData(checkoutForm);
        formData.set('payment_method', 'vnpay');
        
        // Tạo AbortController để timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 giây timeout
        
        fetch(checkoutForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Cache-Control': 'no-cache',
            },
            body: formData,
            signal: controller.signal
        })
        .then(response => {
            clearTimeout(timeoutId);
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            console.log('Response headers:', [...response.headers.entries()]);
            
            if (!response.ok) {
                // Đọc response body để lấy thông báo lỗi
                return response.text().then(responseText => {
                    console.log('Response text:', responseText);
                    try {
                        const errorData = JSON.parse(responseText);
                        console.log('Error data:', errorData);
                        // Decode Unicode escape sequences trong message
                        const decodedMessage = errorData.message ? 
                            errorData.message.replace(/\\u([0-9a-fA-F]{4})/g, (match, p1) => 
                                String.fromCharCode(parseInt(p1, 16))
                            ) : 
                            `HTTP error! status: ${response.status}`;
                        throw new Error(decodedMessage);
                    } catch (parseError) {
                        console.log('Parse error:', parseError);
                        console.log('Response is not JSON, showing raw text');
                        // Nếu không parse được JSON, hiển thị response text
                        throw new Error(`${responseText.substring(0, 150)}`);
                    }
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.order_id) {
                // Gọi API tạo payment VNPay
                const vnpayController = new AbortController();
                const vnpayTimeoutId = setTimeout(() => vnpayController.abort(), 30000);
                
                return fetch('/vnpay/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ order_id: data.order_id }),
                    signal: vnpayController.signal
                })
                .then(res => {
                    clearTimeout(vnpayTimeoutId);
                    if (!res.ok) {
                        throw new Error(`VNPay API error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then(vnpay => {
                    if (vnpay.success) {
                        // Lưu payment_id để có thể kiểm tra trạng thái sau
                        sessionStorage.setItem('vnpay_payment_id', vnpay.payment_id);
                        
                        // Hiển thị thông báo timeout
                        showTimeoutNotification();
                        
                        // Thêm delay nhỏ để đảm bảo VNPay sẵn sàng
                        setTimeout(() => {
                            // Redirect ngay lập tức
                            window.location.replace(vnpay.payment_url);
                        }, 1000);
                    } else {
                        // Xử lý lỗi VNPay cụ thể
                        if (vnpay.error && vnpay.error.includes('bảo trì')) {
                            showVnpayMaintenanceError(vnpay.error, vnpay.details);
                        } else {
                            throw new Error(vnpay.error || 'Không thể tạo thanh toán VNPay!');
                        }
                    }
                });
            } else {
                throw new Error(data.error || 'Không thể tạo đơn hàng!');
            }
        })
        .catch((error) => {
            clearTimeout(timeoutId);
            console.error('VNPay payment error:', error);
            console.log('Error name:', error.name);
            console.log('Error message:', error.message);
            
            if (error.name === 'AbortError') {
                showNotification('Lỗi', 'Yêu cầu thanh toán bị timeout. Vui lòng thử lại!', 'error');
            } else {
                // Hiển thị thông báo lỗi từ server hoặc error message
                const errorMessage = error.message || 'Có lỗi xảy ra khi xử lý thanh toán';
                console.log('Final error message:', errorMessage);
                showNotification('Lỗi', errorMessage, 'error');
            }
        })
        .finally(() => {
            // Re-enable button
            vnpayBtn.disabled = false;
        });
    });

    // Function hiển thị thông báo timeout
    function showTimeoutNotification() {
        showNotification(
            'Lưu ý!', 
            'Giao dịch VNPay sẽ tự động hủy sau 10 phút nếu không hoàn tất thanh toán.',
            'warning',
            10000
        );
    }

    // Function hiển thị lỗi VNPay bảo trì
    function showVnpayMaintenanceError(message, details) {
        showNotification(
            'VNPay đang bảo trì!',
            `${message}<br><small class="text-muted">${details}</small><br><br>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="retryVnpayPayment()">Thử lại</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="switchToOtherPayment()">Chọn phương thức khác</button>
            </div>`,
            'error',
            0 // Không tự động ẩn
        );
    }

    // Function thử lại thanh toán VNPay
    function retryVnpayPayment() {
        // Thử lại thanh toán
        vnpayBtn.click();
    }

    // Function chuyển sang phương thức thanh toán khác
    function switchToOtherPayment() {
        // Chọn COD
        const codRadio = document.getElementById('cod');
        if (codRadio) {
            codRadio.checked = true;
            codRadio.dispatchEvent(new Event('change'));
        }
        
        // Hiển thị thông báo
        showNotification(
            'Thông báo',
            'Đã chuyển sang thanh toán khi nhận hàng (COD)',
            'info',
            5000
        );
    }

    // Function kiểm tra trạng thái payment (có thể sử dụng sau khi quay lại từ VNPay)
    function checkPaymentStatus(paymentId) {
        if (!paymentId) return;
        
        fetch(`/vnpay/status?payment_id=${paymentId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.is_expired) {
                // Bỏ thông báo alert, chỉ xóa payment_id cũ
                console.log('Payment expired, clearing old payment_id');
                sessionStorage.removeItem('vnpay_payment_id');
            }
        })
        .catch(error => {
            console.error('Error checking payment status:', error);
            // Xóa payment_id nếu có lỗi
            sessionStorage.removeItem('vnpay_payment_id');
        });
    }

    // Xóa payment_id cũ khi vào trang checkout mới (để tránh thông báo hết hạn)
    // Chỉ kiểm tra payment status nếu user quay lại từ VNPay (có URL parameter)
    const urlParams = new URLSearchParams(window.location.search);
    const isReturnFromVnpay = urlParams.has('vnp_ResponseCode') || urlParams.has('vnp_TxnRef');
    
    if (!isReturnFromVnpay) {
        // Nếu không phải quay lại từ VNPay, xóa payment_id cũ
        sessionStorage.removeItem('vnpay_payment_id');
    } else {
        // Chỉ kiểm tra payment status khi quay lại từ VNPay
        const paymentId = sessionStorage.getItem('vnpay_payment_id');
        if (paymentId) {
            checkPaymentStatus(paymentId);
        }
    }

    // Xử lý chọn địa chỉ giao hàng
    const addressSelector = document.getElementById('address-selector');
    const selectedAddressInfo = document.getElementById('selected-address-info');
    const selectedAddressIdInput = document.getElementById('selected_address_id');

    // Khởi tạo hiển thị địa chỉ mặc định
    if (addressSelector && addressSelector.value) {
        showSelectedAddress();
    }

    // Xử lý khi chọn địa chỉ
    if (addressSelector) {
        addressSelector.addEventListener('change', function() {
            if (this.value) {
                showSelectedAddress();
            } else {
                hideSelectedAddress();
            }
        });
    }

    // Function hiển thị thông tin địa chỉ đã chọn
    function showSelectedAddress() {
        if (addressSelector && addressSelector.value) {
            const selectedOption = addressSelector.options[addressSelector.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const nameElement = document.getElementById('selected-name');
                const phoneElement = document.getElementById('selected-phone');
                const addressElement = document.getElementById('selected-address');
                
                if (nameElement) nameElement.textContent = selectedOption.dataset.name || '';
                if (phoneElement) phoneElement.textContent = selectedOption.dataset.phone || '';
                if (addressElement) addressElement.textContent = selectedOption.dataset.address || '';
                if (selectedAddressInfo) selectedAddressInfo.style.display = 'block';
                
                // Cập nhật hidden input
                if (selectedAddressIdInput) {
                    selectedAddressIdInput.value = selectedOption.value;
                }
            }
        }
    }

    // Function ẩn thông tin địa chỉ
    function hideSelectedAddress() {
        if (selectedAddressInfo) {
            selectedAddressInfo.style.display = 'none';
        }
        // Xóa hidden input
        if (selectedAddressIdInput) {
            selectedAddressIdInput.value = '';
        }
    }

    // Function lưu thông tin checkout trước khi chuyển đến trang thêm địa chỉ
    function saveCheckoutInfo() {
        // Lấy thông tin checkout hiện tại
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('product_id');
        const variantId = urlParams.get('variant_id');
        const quantity = urlParams.get('quantity');
        const selectedItems = urlParams.get('selected_items');
        
        // Tạo object thông tin checkout
        const checkoutInfo = {
            timestamp: Math.floor(Date.now() / 1000),
            return_to_checkout: true
        };
        
        // Thêm thông tin tùy theo loại checkout
        if (productId) {
            checkoutInfo.product_id = productId;
            if (variantId) checkoutInfo.variant_id = variantId;
            if (quantity) checkoutInfo.quantity = quantity;
        } else if (selectedItems) {
            checkoutInfo.selected_items = selectedItems;
        }
        
        // Lưu vào session storage để có thể truy cập sau
        sessionStorage.setItem('pending_checkout_info', JSON.stringify(checkoutInfo));
        
        // Gửi request để lưu thông tin checkout vào session
        fetch('{{ route("checkout.save-info") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(checkoutInfo)
        }).then(() => {
            // Chuyển đến trang thêm địa chỉ với parameter from_checkout
            window.location.href = '{{ route("addresses.create") }}?from_checkout=1';
        }).catch(() => {
            // Nếu có lỗi, vẫn chuyển đến trang thêm địa chỉ
            window.location.href = '{{ route("addresses.create") }}?from_checkout=1';
        });
    }

    // Validation form trước khi submit
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!addressSelector.value) {
            e.preventDefault();
            alert('Vui lòng chọn địa chỉ giao hàng.');
            return false;
        }
    });

    // Xóa session buy_again_items khi quay lại giỏ hàng
    document.getElementById('back-to-cart').addEventListener('click', function(e) {
        // Gửi request để xóa session
        fetch('{{ route("checkout.clear-buy-again-session") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        }).catch(() => {
            // Bỏ qua lỗi nếu có
        });
    });
});
</script>
@endsection 