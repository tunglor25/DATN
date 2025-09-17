@extends('layouts.app_client')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4 text-center" style="font-weight: 600; letter-spacing: 2px; color: #333;">
                GIỎ HÀNG CỦA BẠN
            </h1>
        </div>
    </div>

    @if($cart->isEmpty())
        <!-- Empty Cart -->
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                    <h3 class="text-muted mb-3">Giỏ hàng trống</h3>
                    <p class="text-muted mb-4">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                    <a href="{{ route('client.products.index') }}" class="btn btn-dark">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Cart Items -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Select All Section -->
                <div class="select-all-section mb-3 p-3 border rounded bg-light">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll" checked>
                        <label class="form-check-label fw-semibold" for="selectAll">
                            Chọn tất cả sản phẩm
                        </label>
                    </div>
                </div>

                <div class="cart-items">
                    @foreach($cart->items as $item)
                    <div class="cart-item mb-3 p-3 border rounded d-flex align-items-center flex-wrap" data-item-id="{{ $item->id }}">
                        <!-- Checkbox -->
                        <div class="cart-checkbox me-3">
                            <input class="form-check-input item-checkbox" type="checkbox" 
                                   id="item_{{ $item->id }}" 
                                   data-item-id="{{ $item->id }}"
                                   data-price="{{ $item->price }}"
                                   data-quantity="{{ $item->quantity }}"
                                   checked>
                        </div>
                        <!-- Image -->
                        <div class="cart-image me-3">
                            <img src="{{ $item->product_image }}" 
                                 class="img-fluid rounded" 
                                 alt="{{ $item->product_name }}">
                        </div>
                        <!-- Info -->
                        <div class="cart-info flex-grow-1 me-3">
                            <div class="fw-bold mb-1">{{ $item->product_name }}</div>
                            <div class="text-muted small mb-1">SKU: {{ $item->sku }}</div>
                            @if($item->product_variant_id && $item->variant_attributes->count() > 0)
                            <div class="variant-attributes mb-1">
                                @foreach($item->variant_attributes as $attrValue)
                                <span class="badge bg-light text-dark me-1 small">
                                    {{ $attrValue->attribute->name }}: {{ $attrValue->value }}
                                </span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <!-- Price -->
                        <div class="cart-price text-danger fw-bold me-3" style="min-width: 90px; text-align: right;">
                            {{ number_format($item->price) }}đ
                        </div>
                        <!-- Actions: Subtotal + Remove (top), Quantity (bottom) -->
                        <div class="cart-actions d-flex flex-column align-items-end justify-content-between" style="min-width: 120px;">
                            <div class="d-flex align-items-center mb-2 gap-2 w-100 justify-content-end">
                                <div class="cart-subtotal fw-bold cart-subtotal-value" style="min-width: 70px; text-align: right;">
                                    {{ number_format($item->subtotal) }}đ
                                </div>
                                <div class="cart-remove">
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="cart-qty">
                                <div class="quantity-controls d-flex align-items-center justify-content-end">
                                    <button type="button" class="btn btn-sm btn-outline-secondary quantity-btn" data-action="decrease">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="form-control text-center mx-1 quantity-input" 
                                           value="{{ $item->quantity }}" 
                                           data-original-quantity="{{ $item->quantity }}"
                                           min="1" 
                                           max="{{ $item->max_stock }}"
                                           style="width: 80px;">
                                    <button type="button" class="btn btn-sm btn-outline-secondary quantity-btn" data-action="increase">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Clear Cart Button -->
                <div class="text-end mb-4">
                    <button type="button" class="btn btn-outline-secondary clear-cart-btn">
                        <i class="fas fa-trash me-2"></i>
                        Xóa tất cả
                    </button>
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="cart-summary p-4 border rounded">
                    <h5 class="mb-3">Tổng quan giỏ hàng</h5>
                    
                    <div class="summary-item d-flex justify-content-between mb-2">
                        <span>Sản phẩm đã chọn:</span>
                        <span id="selected-count">{{ $cart->total_items }}</span>
                    </div>
                    
                    <div class="summary-item d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <span id="selected-total">{{ number_format($cart->total_amount) }}đ</span>
                    </div>
                    
                    <div class="summary-item d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success">Miễn phí</span>
                    </div>
                    
                    <hr>
                    
                    <div class="summary-item d-flex justify-content-between mb-3">
                        <span class="fw-bold">Tổng cộng:</span>
                        <span class="fw-bold text-danger h5 mb-0" id="final-total">{{ number_format($cart->total_amount) }}đ</span>
                    </div>
                    
                    <button type="button" class="btn btn-dark w-100 mb-2 checkout-btn" onclick="window.location.href='{{ route('checkout.index') }}'" disabled>
                        <i class="fas fa-credit-card me-2"></i>
                        Thanh toán (<span id="checkout-count">{{ $cart->total_items }}</span> sản phẩm)
                    </button>
                    
                    <a href="{{ route('client.products.index') }}" class="btn btn-outline-dark w-100">
                        <i class="fas fa-arrow-left me-2"></i>
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.cart-item {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    background: #fff;
    transition: all 0.3s ease;
}
.cart-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.cart-checkbox {
    flex: 0 0 auto;
    display: flex;
    align-items: center;
}
.cart-image img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
}
.cart-info {
    min-width: 180px;
    flex: 1 1 180px;
}
.cart-price {
    min-width: 90px;
    text-align: right;
}
.cart-actions {
    min-width: 120px;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: space-between;
    gap: 0.5rem;
}
.cart-actions > .d-flex {
    width: 100%;
    justify-content: flex-end;
}
.cart-qty {
    min-width: 110px;
}
.cart-subtotal {
    min-width: 70px;
    text-align: right;
}
.cart-remove {
    margin-left: 8px;
    flex: 0 0 auto;
    display: flex;
    align-items: center;
}
.variant-attributes .badge {
    font-size: 0.7rem;
}

.select-all-section {
    background: #f8f9fa !important;
}

.form-check-input:checked {
    background-color: #ff6b6b;
    border-color: #ff6b6b;
}

.form-check-input:focus {
    border-color: #ff6b6b;
    box-shadow: 0 0 0 0.25rem rgba(255, 107, 107, 0.25);
}

.cart-item.disabled {
    opacity: 0.6;
    pointer-events: none;
}

.checkout-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.empty-cart {
    padding: 60px 20px;
}

@media (max-width: 768px) {
    .cart-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    .cart-actions {
        align-items: flex-start;
    }
    .cart-actions > .d-flex {
        justify-content: flex-start;
    }
    .cart-price, .cart-subtotal, .cart-qty {
        text-align: left;
        min-width: unset;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize checkbox functionality
    initializeCheckboxes();
    
    // Quantity controls
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.quantity-input');
            const action = this.dataset.action;
            let currentValue = parseInt(input.value);
            
            if (action === 'increase') {
                input.value = currentValue + 1;
            } else if (action === 'decrease' && currentValue > 1) {
                input.value = currentValue - 1;
            }
            
            // Trigger update
            updateCartItem(input);
        });
    });

    // Quantity input change
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            updateCartItem(this);
        });
    });

    // Remove item
    document.querySelectorAll('.remove-item-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.closest('.cart-item').dataset.itemId;
            removeCartItem(itemId);
        });
    });

    // Clear cart
    document.querySelector('.clear-cart-btn')?.addEventListener('click', function() {
        if (confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm trong giỏ hàng?')) {
            clearCart();
        }
    });

    // Checkout
    document.querySelector('.checkout-btn')?.addEventListener('click', function() {
        // Lấy danh sách selected items
        const selectedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
        if (selectedCheckboxes.length === 0) {
            showNotification('Lỗi', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán!', 'error');
            return;
        }
        
        const selectedItemIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.dataset.itemId);
        
        // Tạo form và submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("checkout.selected") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const selectedItemsInput = document.createElement('input');
        selectedItemsInput.type = 'hidden';
        selectedItemsInput.name = 'selected_items';
        selectedItemsInput.value = selectedItemIds.join(',');
        
        form.appendChild(csrfToken);
        form.appendChild(selectedItemsInput);
        document.body.appendChild(form);
        form.submit();
    });

    // Update cart item quantity
    function updateCartItem(input) {
        const itemId = input.closest('.cart-item').dataset.itemId;
        const quantity = parseInt(input.value);
        const maxStock = parseInt(input.getAttribute('max'));
        
        if (quantity <= 0) {
            removeCartItem(itemId);
            return;
        }

        if (quantity > maxStock) {
            showNotification('Lỗi', 'Số lượng vượt quá tồn kho', 'error');
            input.value = maxStock;
            return;
        }

        // Show loading state
        const originalText = input.value;
        input.disabled = true;
        input.style.opacity = '0.6';

        fetch(`{{ route('cart.update', ':itemId') }}`.replace(':itemId', itemId), {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update subtotal
                const cartItem = input.closest('.cart-item');
                const price = parseFloat(cartItem.querySelector('.text-danger').textContent.replace(/[^\d]/g, ''));
                const subtotal = price * quantity;
                // Chỉ cập nhật thành tiền
                const subtotalElem = cartItem.querySelector('.cart-subtotal-value');
                if (subtotalElem) {
                    subtotalElem.textContent = subtotal.toLocaleString() + 'đ';
                }
                // Update original quantity for future resets
                input.setAttribute('data-original-quantity', quantity);
                // Update checkbox data-quantity
                const checkbox = cartItem.querySelector('.item-checkbox');
                if (checkbox) {
                    checkbox.dataset.quantity = quantity;
                }
                // Update selected summary
                updateSelectedSummary();
                // Update cart count
                if (data.cart_count !== undefined) {
                    updateCartCount(data.cart_count);
                }
            } else {
                showNotification('Lỗi', data.message, 'error');
                // Reset input value to original quantity
                const originalQuantity = parseInt(input.getAttribute('data-original-quantity') || '1');
                input.value = originalQuantity;
                // Update cart count even on error
                if (data.cart_count !== undefined) {
                    updateCartCount(data.cart_count);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Lỗi', 'Có lỗi xảy ra, vui lòng thử lại!', 'error');
        })
        .finally(() => {
            // Restore input state
            input.disabled = false;
            input.style.opacity = '1';
        });
    }

    // Remove cart item
    function removeCartItem(itemId) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            return;
        }

        const cartItem = document.querySelector(`[data-item-id="${itemId}"]`);
        if (cartItem) {
            cartItem.style.opacity = '0.5';
        }

        fetch(`{{ route('cart.remove', ':itemId') }}`.replace(':itemId', itemId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Thành công', data.message, 'success');
                if (cartItem) {
                    cartItem.remove();
                }
                updateSelectedSummary();
                // Update cart count
                if (data.cart_count !== undefined) {
                    updateCartCount(data.cart_count);
                }
                // Check if cart is empty
                const remainingItems = document.querySelectorAll('.cart-item');
                if (remainingItems.length === 0) {
                    location.reload(); // Reload to show empty cart message
                }
            } else {
                showNotification('Lỗi', data.message, 'error');
                if (cartItem) {
                    cartItem.style.opacity = '1';
                }
                // Update cart count even on error
                if (data.cart_count !== undefined) {
                    updateCartCount(data.cart_count);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Lỗi', 'Có lỗi xảy ra, vui lòng thử lại!', 'error');
            if (cartItem) {
                cartItem.style.opacity = '1';
            }
        });
    }

    // Clear cart
    function clearCart() {
        if (!confirm('Bạn có chắc muốn xóa tất cả sản phẩm khỏi giỏ hàng?')) {
            return;
        }

        const clearBtn = document.querySelector('.clear-cart-btn');
        const originalText = clearBtn.innerHTML;
        clearBtn.disabled = true;
        clearBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xóa...';

        fetch('{{ route("cart.clear") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Thành công', data.message, 'success');
                // Update cart count before reload
                if (data.cart_count !== undefined) {
                    updateCartCount(data.cart_count);
                }
                location.reload(); // Reload to show empty cart message
            } else {
                showNotification('Lỗi', data.message, 'error');
                // Update cart count even on error
                if (data.cart_count !== undefined) {
                    updateCartCount(data.cart_count);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Lỗi', 'Có lỗi xảy ra, vui lòng thử lại!', 'error');
        })
        .finally(() => {
            clearBtn.disabled = false;
            clearBtn.innerHTML = originalText;
        });
    }

    // Update cart total (legacy function - now handled by updateSelectedSummary)
    function updateCartTotal(total) {
        // This function is kept for backward compatibility
        // The actual total is now calculated based on selected items
    }

    // Initialize checkbox functionality
    function initializeCheckboxes() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        
        // Select all functionality
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                updateCartItemVisibility(checkbox);
            });
            updateSelectedSummary();
        });
        
        // Individual checkbox functionality
        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateCartItemVisibility(this);
                updateSelectAllState();
                updateSelectedSummary();
            });
        });
        
        // Initial calculation
        updateSelectedSummary();
    }
    
    // Update cart item visibility based on checkbox state
    function updateCartItemVisibility(checkbox) {
        const cartItem = checkbox.closest('.cart-item');
        if (checkbox.checked) {
            cartItem.classList.remove('disabled');
        } else {
            cartItem.classList.add('disabled');
        }
    }
    
    // Update select all checkbox state
    function updateSelectAllState() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        
        if (checkedBoxes.length === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedBoxes.length === itemCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
            selectAllCheckbox.checked = false;
        }
    }
    
    // Update selected summary
    function updateSelectedSummary() {
        const selectedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
        let totalItems = 0;
        let totalAmount = 0;

        selectedCheckboxes.forEach(checkbox => {
            const quantity = parseInt(checkbox.dataset.quantity || '1');
            const price = parseFloat(checkbox.dataset.price || '0');
            totalItems += quantity;
            totalAmount += price * quantity;
        });

        // Update summary display
        document.getElementById('selected-count').textContent = totalItems;
        document.getElementById('selected-total').textContent = totalAmount.toLocaleString() + 'đ';
        document.getElementById('final-total').textContent = totalAmount.toLocaleString() + 'đ';
        document.getElementById('checkout-count').textContent = totalItems;

        // Enable/disable checkout button
        const checkoutBtn = document.querySelector('.checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.disabled = totalItems === 0;
        }
    }

    function updateCartCount(count) {
        const cartCountElement = document.querySelector('.cart-count');
        if (cartCountElement) {
            cartCountElement.textContent = count;
            cartCountElement.style.display = count > 0 ? 'flex' : 'none';
        } else if (count > 0) {
            // Nếu chưa có badge, tạo mới
            const cartBtn = document.querySelector('.cart-btn');
            if (cartBtn) {
                const badge = document.createElement('span');
                badge.className = 'cart-count';
                badge.textContent = count;
                badge.style.display = 'flex';
                cartBtn.appendChild(badge);
            }
        }
    }
});
</script>
@endsection 
