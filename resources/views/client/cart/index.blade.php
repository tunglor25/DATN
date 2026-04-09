@extends('layouts.app_client')

@section('title', 'Giỏ hàng - TLO Fashion')

@section('styles')
<style>
.cart-wrapper {
    font-family: 'Inter', sans-serif;
}

.cart-layout {
    max-width: 1200px;
    margin: 0 auto;
    padding: 32px 24px 60px;
}

.cart-page-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--tlo-text-primary);
    text-align: center;
    margin-bottom: 32px;
}

.cart-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 28px;
    align-items: start;
}

/* Select All */
.select-all-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 18px;
    background: var(--tlo-surface);
    border: 1px solid var(--tlo-border);
    border-radius: var(--tlo-radius-md);
    margin-bottom: 16px;
}

.select-all-bar .form-check-input:checked {
    background-color: var(--tlo-accent);
    border-color: var(--tlo-accent);
}

.select-all-bar .form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.2);
}

.select-all-bar label {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--tlo-text-primary);
}

/* Cart Item */
.cart-item-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 18px;
    background: var(--tlo-surface);
    border: 1px solid var(--tlo-border);
    border-radius: var(--tlo-radius-md);
    margin-bottom: 12px;
    transition: var(--tlo-transition);
}

.cart-item-card:hover {
    box-shadow: var(--tlo-shadow-sm);
    border-color: rgba(255, 107, 107, 0.15);
}

.cart-item-card.disabled {
    opacity: 0.5;
    pointer-events: none;
}

.cart-item-card .form-check-input:checked {
    background-color: var(--tlo-accent);
    border-color: var(--tlo-accent);
}

.cart-item-card .form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.2);
}

.cart-item-img {
    width: 72px;
    height: 72px;
    border-radius: 14px;
    overflow: hidden;
    flex-shrink: 0;
    border: 1px solid var(--tlo-border);
}

.cart-item-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cart-item-info {
    flex: 1;
    min-width: 0;
}

.cart-item-name {
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--tlo-text-primary);
    margin-bottom: 3px;
}

.cart-item-sku {
    font-size: 0.78rem;
    color: var(--tlo-text-light);
}

.cart-item-attrs .badge {
    font-size: 0.7rem;
    background: var(--tlo-surface-alt) !important;
    color: var(--tlo-text-secondary) !important;
    border: 1px solid var(--tlo-border);
    border-radius: 6px;
    font-weight: 500;
}

.cart-item-price {
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--tlo-accent);
    min-width: 90px;
    text-align: right;
}

.cart-item-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
    min-width: 130px;
}

.cart-item-subtotal {
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--tlo-text-primary);
}

.cart-item-remove {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    border: 1px solid var(--tlo-border);
    background: var(--tlo-surface);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--tlo-transition);
    color: var(--tlo-text-light);
}

.cart-item-remove:hover {
    border-color: #ef4444;
    color: #ef4444;
    background: rgba(239, 68, 68, 0.05);
}

.quantity-controls {
    display: flex;
    align-items: center;
    border: 1px solid var(--tlo-border);
    border-radius: 10px;
    overflow: hidden;
}

.quantity-controls .quantity-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: var(--tlo-surface-alt);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.8rem;
    color: var(--tlo-text-secondary);
    transition: var(--tlo-transition);
}

.quantity-controls .quantity-btn:hover {
    background: var(--tlo-accent);
    color: #fff;
}

.quantity-controls .quantity-input {
    width: 50px;
    height: 32px;
    border: none;
    text-align: center;
    font-size: 0.85rem;
    font-weight: 600;
    background: var(--tlo-surface);
    color: var(--tlo-text-primary);
}

/* Summary Card */
.cart-summary-card {
    background: var(--tlo-surface);
    border: 1px solid var(--tlo-border);
    border-radius: var(--tlo-radius-lg);
    padding: 24px;
    position: sticky;
    top: 92px;
    box-shadow: var(--tlo-shadow-sm);
}

.cart-summary-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem;
    font-weight: 600;
    color: var(--tlo-text-primary);
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--tlo-border);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    font-size: 0.88rem;
}

.summary-row span:first-child {
    color: var(--tlo-text-secondary);
}

.summary-row span:last-child {
    font-weight: 600;
    color: var(--tlo-text-primary);
}

.summary-total {
    padding-top: 14px;
    margin-top: 14px;
    border-top: 2px solid var(--tlo-border);
}

.summary-total span:last-child {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--tlo-accent);
}

.checkout-btn {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: #fff;
    border: none;
    border-radius: var(--tlo-radius-sm);
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    transition: var(--tlo-transition);
    margin-top: 16px;
    box-shadow: 0 4px 16px rgba(255, 107, 107, 0.25);
}

.checkout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 24px rgba(255, 107, 107, 0.35);
}

.checkout-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

.continue-shopping-btn {
    display: block;
    width: 100%;
    padding: 12px;
    background: var(--tlo-surface);
    border: 1.5px solid var(--tlo-border);
    border-radius: var(--tlo-radius-sm);
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--tlo-text-primary);
    text-align: center;
    text-decoration: none;
    margin-top: 10px;
    transition: var(--tlo-transition);
}

.continue-shopping-btn:hover {
    border-color: var(--tlo-accent);
    color: var(--tlo-accent);
}

.clear-all-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border: 1px solid var(--tlo-border);
    border-radius: 10px;
    background: var(--tlo-surface);
    font-size: 0.82rem;
    font-weight: 500;
    color: var(--tlo-text-secondary);
    cursor: pointer;
    transition: var(--tlo-transition);
    margin-bottom: 20px;
}

.clear-all-btn:hover {
    border-color: #ef4444;
    color: #ef4444;
}

/* Empty Cart */
.cart-empty {
    text-align: center;
    padding: 80px 24px;
}

.cart-empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: rgba(255, 107, 107, 0.08);
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--tlo-accent);
}

.cart-empty h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--tlo-text-primary);
    margin-bottom: 8px;
}

.cart-empty p {
    color: var(--tlo-text-secondary);
    margin-bottom: 24px;
}

/* Responsive */
@media (max-width: 1024px) {
    .cart-grid { grid-template-columns: 1fr; }
    .cart-summary-card { position: static; }
}

@media (max-width: 768px) {
    .cart-layout { padding: 20px 16px 40px; }
    .cart-item-card { flex-wrap: wrap; gap: 10px; }
    .cart-item-price { min-width: auto; }
    .cart-item-actions { min-width: auto; flex-direction: row; width: 100%; justify-content: space-between; }
}
</style>
@endsection

@section('content')
<div class="cart-wrapper tlo-full-width">
    <!-- Hero -->
    <section class="tlo-page-hero">
        <div class="tlo-page-hero-inner">
            <div class="tlo-hero-badge"><i class="fas fa-shopping-cart"></i> Giỏ hàng</div>
            <h1 class="tlo-hero-title">Giỏ hàng của bạn</h1>
            <p class="tlo-hero-desc">Kiểm tra lại sản phẩm trước khi tiến hành thanh toán</p>
        </div>
    </section>

    <div class="cart-layout">
        @if($cart->isEmpty())
            <div class="cart-empty tlo-animate">
                <div class="cart-empty-icon"><i class="fas fa-shopping-bag"></i></div>
                <h3>Giỏ hàng trống</h3>
                <p>Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                <a href="{{ route('client.products.index') }}" class="tlo-btn tlo-btn-primary">
                    <i class="fas fa-store"></i> Tiếp tục mua sắm
                </a>
            </div>
        @else
            <div class="cart-grid">
                <div>
                    <div class="select-all-bar tlo-animate">
                        <input class="form-check-input" type="checkbox" id="selectAll" checked>
                        <label for="selectAll">Chọn tất cả sản phẩm</label>
                    </div>

                    <div class="cart-items">
                        @foreach($cart->items as $item)
                        <div class="cart-item-card tlo-animate" data-item-id="{{ $item->id }}">
                            <input class="form-check-input item-checkbox" type="checkbox"
                                   id="item_{{ $item->id }}"
                                   data-item-id="{{ $item->id }}"
                                   data-price="{{ $item->price }}"
                                   data-quantity="{{ $item->quantity }}"
                                   checked>

                            <div class="cart-item-img">
                                <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}">
                            </div>

                            <div class="cart-item-info">
                                <div class="cart-item-name">{{ $item->product_name }}</div>
                                <div class="cart-item-sku">SKU: {{ $item->sku }}</div>
                                @if($item->product_variant_id && $item->variant_attributes->count() > 0)
                                <div class="cart-item-attrs mt-1">
                                    @foreach($item->variant_attributes as $attrValue)
                                    <span class="badge me-1">{{ $attrValue->attribute->name }}: {{ $attrValue->value }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            <div class="cart-item-price">{{ number_format($item->price) }}đ</div>

                            <div class="cart-item-actions">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="cart-item-subtotal cart-subtotal-value">{{ number_format($item->subtotal) }}đ</span>
                                    <button type="button" class="cart-item-remove remove-item-btn"><i class="fas fa-trash-alt" style="font-size: 0.75rem;"></i></button>
                                </div>
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn" data-action="decrease"><i class="fas fa-minus" style="font-size: 0.65rem;"></i></button>
                                    <input type="number" class="quantity-input"
                                           value="{{ $item->quantity }}"
                                           data-original-quantity="{{ $item->quantity }}"
                                           min="1" max="{{ $item->max_stock }}">
                                    <button type="button" class="quantity-btn" data-action="increase"><i class="fas fa-plus" style="font-size: 0.65rem;"></i></button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="text-end">
                        <button type="button" class="clear-all-btn clear-cart-btn">
                            <i class="fas fa-trash"></i> Xóa tất cả
                        </button>
                    </div>
                </div>

                <!-- Summary -->
                <div class="cart-summary-card tlo-animate">
                    <h3 class="cart-summary-title"><i class="fas fa-receipt" style="color: var(--tlo-accent); margin-right: 8px;"></i>Tổng quan đơn hàng</h3>

                    <div class="summary-row">
                        <span>Sản phẩm đã chọn:</span>
                        <span id="selected-count">{{ $cart->total_items }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Tạm tính:</span>
                        <span id="selected-total">{{ number_format($cart->total_amount) }}đ</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển:</span>
                        <span style="color: #16a34a;">Miễn phí</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span style="font-weight: 700; color: var(--tlo-text-primary);">Tổng cộng:</span>
                        <span id="final-total">{{ number_format($cart->total_amount) }}đ</span>
                    </div>

                    <button type="button" class="checkout-btn" disabled>
                        <i class="fas fa-credit-card me-2"></i>
                        Thanh toán (<span id="checkout-count">{{ $cart->total_items }}</span> sản phẩm)
                    </button>

                    <a href="{{ route('client.products.index') }}" class="continue-shopping-btn">
                        <i class="fas fa-arrow-left me-1"></i> Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeCheckboxes();

    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.quantity-input');
            const action = this.dataset.action;
            let currentValue = parseInt(input.value);
            if (action === 'increase') input.value = currentValue + 1;
            else if (action === 'decrease' && currentValue > 1) input.value = currentValue - 1;
            updateCartItem(input);
        });
    });

    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() { updateCartItem(this); });
    });

    document.querySelectorAll('.remove-item-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.closest('.cart-item-card').dataset.itemId;
            removeCartItem(itemId);
        });
    });

    document.querySelector('.clear-cart-btn')?.addEventListener('click', function() {
        if (confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm trong giỏ hàng?')) clearCart();
    });

    document.querySelector('.checkout-btn')?.addEventListener('click', function() {
        const selectedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
        if (selectedCheckboxes.length === 0) {
            showNotification('Lỗi', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán!', 'error');
            return;
        }
        const selectedItemIds = Array.from(selectedCheckboxes).map(c => c.dataset.itemId);
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("checkout.selected") }}';
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden'; csrfToken.name = '_token'; csrfToken.value = '{{ csrf_token() }}';
        const selectedInput = document.createElement('input');
        selectedInput.type = 'hidden'; selectedInput.name = 'selected_items'; selectedInput.value = selectedItemIds.join(',');
        form.appendChild(csrfToken); form.appendChild(selectedInput);
        document.body.appendChild(form); form.submit();
    });

    function updateCartItem(input) {
        const itemId = input.closest('.cart-item-card').dataset.itemId;
        const quantity = parseInt(input.value);
        const maxStock = parseInt(input.getAttribute('max'));
        if (quantity <= 0) { removeCartItem(itemId); return; }
        if (quantity > maxStock) { showNotification('Lỗi', 'Số lượng vượt quá tồn kho', 'error'); input.value = maxStock; return; }
        input.disabled = true; input.style.opacity = '0.6';
        fetch(`{{ route('cart.update', ':itemId') }}`.replace(':itemId', itemId), {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const cartItem = input.closest('.cart-item-card');
                const price = parseFloat(cartItem.querySelector('.cart-item-price').textContent.replace(/[^\d]/g, ''));
                const subtotalEl = cartItem.querySelector('.cart-subtotal-value');
                if (subtotalEl) subtotalEl.textContent = (price * quantity).toLocaleString() + 'đ';
                input.setAttribute('data-original-quantity', quantity);
                const checkbox = cartItem.querySelector('.item-checkbox');
                if (checkbox) checkbox.dataset.quantity = quantity;
                updateSelectedSummary();
                if (data.cart_count !== undefined) updateCartCount(data.cart_count);
            } else {
                showNotification('Lỗi', data.message, 'error');
                input.value = parseInt(input.getAttribute('data-original-quantity') || '1');
                if (data.cart_count !== undefined) updateCartCount(data.cart_count);
            }
        })
        .catch(() => showNotification('Lỗi', 'Có lỗi xảy ra, vui lòng thử lại!', 'error'))
        .finally(() => { input.disabled = false; input.style.opacity = '1'; });
    }

    function removeCartItem(itemId) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
        const cartItem = document.querySelector(`[data-item-id="${itemId}"]`);
        if (cartItem) cartItem.style.opacity = '0.5';
        fetch(`{{ route('cart.remove', ':itemId') }}`.replace(':itemId', itemId), {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showNotification('Thành công', data.message, 'success');
                if (cartItem) cartItem.remove();
                updateSelectedSummary();
                if (data.cart_count !== undefined) updateCartCount(data.cart_count);
                if (document.querySelectorAll('.cart-item-card').length === 0) location.reload();
            } else {
                showNotification('Lỗi', data.message, 'error');
                if (cartItem) cartItem.style.opacity = '1';
                if (data.cart_count !== undefined) updateCartCount(data.cart_count);
            }
        })
        .catch(() => { showNotification('Lỗi', 'Có lỗi xảy ra!', 'error'); if (cartItem) cartItem.style.opacity = '1'; });
    }

    function clearCart() {
        if (!confirm('Xóa tất cả sản phẩm?')) return;
        const btn = document.querySelector('.clear-cart-btn');
        const orig = btn.innerHTML;
        btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang xóa...';
        fetch('{{ route("cart.clear") }}', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { showNotification('Thành công', data.message, 'success'); if (data.cart_count !== undefined) updateCartCount(data.cart_count); location.reload(); }
            else { showNotification('Lỗi', data.message, 'error'); if (data.cart_count !== undefined) updateCartCount(data.cart_count); }
        })
        .catch(() => showNotification('Lỗi', 'Có lỗi xảy ra!', 'error'))
        .finally(() => { btn.disabled = false; btn.innerHTML = orig; });
    }

    function initializeCheckboxes() {
        const selectAll = document.getElementById('selectAll');
        const items = document.querySelectorAll('.item-checkbox');
        selectAll?.addEventListener('change', function() {
            items.forEach(cb => { cb.checked = this.checked; updateCartItemVisibility(cb); });
            updateSelectedSummary();
        });
        items.forEach(cb => {
            cb.addEventListener('change', function() { updateCartItemVisibility(this); updateSelectAllState(); updateSelectedSummary(); });
        });
        updateSelectedSummary();
    }

    function updateCartItemVisibility(cb) {
        const item = cb.closest('.cart-item-card');
        if (cb.checked) item.classList.remove('disabled');
        else item.classList.add('disabled');
    }

    function updateSelectAllState() {
        const selectAll = document.getElementById('selectAll');
        const items = document.querySelectorAll('.item-checkbox');
        const checked = document.querySelectorAll('.item-checkbox:checked');
        if (checked.length === 0) { selectAll.indeterminate = false; selectAll.checked = false; }
        else if (checked.length === items.length) { selectAll.indeterminate = false; selectAll.checked = true; }
        else { selectAll.indeterminate = true; selectAll.checked = false; }
    }

    function updateSelectedSummary() {
        const selected = document.querySelectorAll('.item-checkbox:checked');
        let totalItems = 0, totalAmount = 0;
        selected.forEach(cb => {
            const qty = parseInt(cb.dataset.quantity || '1');
            const price = parseFloat(cb.dataset.price || '0');
            totalItems += qty; totalAmount += price * qty;
        });
        document.getElementById('selected-count').textContent = totalItems;
        document.getElementById('selected-total').textContent = totalAmount.toLocaleString() + 'đ';
        document.getElementById('final-total').textContent = totalAmount.toLocaleString() + 'đ';
        document.getElementById('checkout-count').textContent = totalItems;
        const btn = document.querySelector('.checkout-btn');
        if (btn) btn.disabled = totalItems === 0;
    }

    function updateCartCount(count) {
        const el = document.querySelector('.cart-count');
        if (el) { el.textContent = count; el.style.display = count > 0 ? 'flex' : 'none'; }
        else if (count > 0) {
            const cartBtn = document.querySelector('.cart-btn');
            if (cartBtn) { const badge = document.createElement('span'); badge.className = 'cart-count'; badge.textContent = count; badge.style.display = 'flex'; cartBtn.appendChild(badge); }
        }
    }
});
</script>
@endsection
