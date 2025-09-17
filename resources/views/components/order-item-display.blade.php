@props(['item'])

<div class="product-info">
    <img src="{{ $item->getProductImage() }}"
         alt="{{ $item->product_name ?? 'Product' }}" class="product-image">
    <div class="product-details">
        <div class="product-name">{{ $item->product_name ?? 'Không có tên' }}</div>
        <div class="product-attributes-container">
            <div class="total-variant d-flex align-items-center mb-2">
                <div class="product-quantity me-3">Số lượng: {{ $item->quantity ?? 1 }}</div>
                @if(isset($order))
                <div class="order-number">Mã đơn hàng: {{ $order->order_number }}</div>
                @endif
            </div>
            <div class="product-attributes">
                @php
                    $variantAttributes = $item->getVariantAttributesForDisplay();
                @endphp
                @if ($variantAttributes->isNotEmpty())
                    @foreach ($variantAttributes as $attributeValue)
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