@extends('layouts.app_client')

@section('content')
    <div class="py-4">
        <div class="row">
            <!-- Product Image Section -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="product-image-container">
                    <img id="variant-image"
                        src="{{ $product->product_image ? asset('storage/' . $product->product_image) : '/placeholder-image.jpg' }}"
                        class="img-fluid rounded shadow-sm w-100" alt="{{ $product->name }}"
                        style="max-height: 600px; object-fit: cover;">
                </div>
            </div>

            <!-- Product Details Section -->
            <div class="col-lg-6 col-md-6">
                <div class="product-details">
                    <!-- Product Title -->
                    <h1 class="product-title mb-3"
                        style="font-size: 1.8rem; font-weight: 600; letter-spacing: 2px; color: #333; text-transform: uppercase;">
                        {{ $product->name }}
                    </h1>
                    <div class="d-flex align-items-center">
                    <!-- SKU -->
                    <div class="mb-3">
                        <small class="text-muted">SKU: <span id="sku-display">{{ $product->sku }}</span></small>
                    </div>

                    <!-- Rating Section -->
                    <div class="rating-section mb-3 ms-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rating-display">
                                <span class="rating-number fw-bold text-dark">{{ $averageRating }}</span>
                                <div class="stars d-inline-block ms-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $averageRating)
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif ($i - $averageRating < 1)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <div class="rating-divider"></div>
                            <div class="reviews-count">
                                <span class="text-muted">{{ $product->reviews->count() }} Đánh Giá</span>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- Price Section -->
                    <div class="price-section mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <span id="price" class="current-price h3 mb-0 fw-bold text-dark">
                                {{ number_format($product->price) }}đ
                            </span>
                            @if ($product->original_price && $product->original_price > $product->price)
                                <span class="original-price text-muted text-decoration-line-through">
                                    {{ number_format($product->original_price) }}đ
                                </span>
                                <span class="badge bg-danger">
                                    -{{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}%
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Attributes Selection - Only show if product has variants -->
                    @if(!empty($attributes) && count($attributes) > 0)
                        @foreach ($attributes as $attributeName => $values)
                            <div class="attribute-section mb-4">
                                <label class="form-label fw-semibold mb-3">{{ ucfirst($attributeName) }}:</label>
                                <div class="attribute-options d-flex flex-wrap gap-2">
                                    @foreach ($values as $id => $value)
                                        <div class="form-check-option">
                                            <input type="radio" class="btn-check variant-radio" name="attribute[{{ $attributeName }}]"
                                                data-attr-name="{{ $attributeName }}"
                                                data-attr-id="{{ $attributeGroups[$attributeName] }}" value="{{ $id }}"
                                                id="attr_{{ $attributeName }}_{{ $id }}">
                                            <label class="btn btn-outline-dark btn-size" for="attr_{{ $attributeName }}_{{ $id }}">
                                                {{ $value }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Product without variants - show stock info directly -->
                        <div class="stock-info mb-3">
                            <small class="text-muted">Số lượng: <span id="stock"
                                    class="fw-semibold">{{ $product->stock }}</span></small>
                        </div>
                    @endif

                    <!-- Stock Info - Only show for products with variants -->
                    @if(!empty($attributes) && count($attributes) > 0)
                        <div class="stock-info mb-3" style="display: none;">
                            <small class="text-muted">Số lượng: <span id="stock"
                                    class="fw-semibold">{{ $product->stock }}</span></small>
                        </div>
                    @endif

                    <!-- Out of Stock Message -->
                    <div class="out-of-stock-message mb-3" style="display: none;">
                        <div class="alert alert-secondary text-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>SẢN PHẨM HIỆN ĐÃ HẾT HÀNG</strong>
                        </div>
                    </div>

                    <!-- Quantity and Action Buttons in one row -->
                    <div class="purchase-section mb-4">
                        <div class="row align-items-center g-2">
                            <!-- Quantity Selector -->
                            <div class="col-auto">
                                <div class="quantity-selector d-flex align-items-center">
                                    <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn"
                                        data-action="decrease">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="form-control text-center mx-1" id="quantity" value="1"
                                        min="1" max="{{ $product->stock }}" style="width: 80px;">
                                    <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn"
                                        data-action="increase">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden Inputs -->
                            <input type="hidden" id="variant_id" value="">

                            <!-- Action Buttons -->
                            <div class="col">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-dark btn-sm flex-fill add-to-cart-btn"
                                        id="add-to-cart-btn">
                                        THÊM VÀO GIỎ
                                    </button>
                                    <!-- Form ẩn cho MUA NGAY -->
                                    <form id="buyNowForm" action="{{ route('checkout.index') }}" method="GET"
                                        style="display:none;">
                                        <input type="hidden" name="product_id" id="buyNowProductId">
                                        <input type="hidden" name="variant_id" id="buyNowVariantId">
                                        <input type="hidden" name="quantity" id="buyNowQuantity">
                                    </form>
                                    <button type="button" class="btn btn-outline-dark btn-sm flex-fill buy-now-btn">
                                        MUA NGAY
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Order Info -->
                    <div class="quick-order-info p-3 bg-light rounded mb-4">
                        <div class="text-success fw-semibold mb-2">
                            <i class="fas fa-phone"></i> GỌI ĐỂ MUA HÀNG NHANH HƠN
                        </div>
                        <div class="h5 mb-2 text-dark">0973.***.**8</div>
                        <small class="text-muted">(8h30 - 18h30)</small>
                    </div>

                    <!-- Shipping Policy -->
                    <div class="shipping-policy">
                        <div class="text-success fw-semibold mb-3">CHÍNH SÁCH BÁN HÀNG</div>
                        <div class="policy-items">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-truck text-muted me-2"></i>
                                <small class="text-muted">Giao hàng miễn phí (Hóa đơn trên 500k)</small>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-undo text-muted me-2"></i>
                                <small class="text-muted">Đổi trả miễn phí 14 ngày (Với mua online)</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-money-bill-wave text-muted me-2"></i>
                                <small class="text-muted">Thanh toán COD (Và các hình thức khác)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Information Tabs Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="product-info-tabs">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs border-0 mb-4" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active custom-tab" id="description-tab" data-bs-toggle="tab"
                                data-bs-target="#description" type="button" role="tab">
                                Thông Tin Sản Phẩm
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link custom-tab" id="guide-tab" data-bs-toggle="tab" data-bs-target="#guide"
                                type="button" role="tab">
                                Hướng Dẫn Mua Hàng
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link custom-tab" id="policy-tab" data-bs-toggle="tab"
                                data-bs-target="#policy" type="button" role="tab">
                                Chính Sách Đổi Trả
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link custom-tab" id="reviews-tab" data-bs-toggle="tab"
                                data-bs-target="#reviews" type="button" role="tab">
                                Đánh giá
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="productTabsContent">
                        <!-- Product Description Tab -->
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <div class="product-description-content">
                                <div class="mt-4">
                                    <div class="description-content">
                                        {!! $product->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Guide Tab -->
                        <div class="tab-pane fade" id="guide" role="tabpanel">
                            <div class="purchase-guide-content">
                                <h5 class="mb-4 fw-bold">Hướng Dẫn Mua Hàng</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="guide-step mb-4">
                                            <div class="d-flex align-items-start">
                                                <div class="step-number bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 30px; height: 30px; font-size: 14px;">1</div>
                                                <div>
                                                    <h6 class="fw-semibold">Chọn sản phẩm</h6>
                                                    <p class="text-muted mb-0">Chọn màu sắc, kích thước và số lượng mong
                                                        muốn</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="guide-step mb-4">
                                            <div class="d-flex align-items-start">
                                                <div class="step-number bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 30px; height: 30px; font-size: 14px;">2</div>
                                                <div>
                                                    <h6 class="fw-semibold">Thêm vào giỏ hàng</h6>
                                                    <p class="text-muted mb-0">Nhấn "Thêm vào giỏ" hoặc "Mua ngay" để tiếp
                                                        tục</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="guide-step mb-4">
                                            <div class="d-flex align-items-start">
                                                <div class="step-number bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 30px; height: 30px; font-size: 14px;">3</div>
                                                <div>
                                                    <h6 class="fw-semibold">Điền thông tin</h6>
                                                    <p class="text-muted mb-0">Nhập thông tin giao hàng và chọn phương thức
                                                        thanh toán</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="guide-step mb-4">
                                            <div class="d-flex align-items-start">
                                                <div class="step-number bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 30px; height: 30px; font-size: 14px;">4</div>
                                                <div>
                                                    <h6 class="fw-semibold">Hoàn tất đơn hàng</h6>
                                                    <p class="text-muted mb-0">Xác nhận và chờ nhận hàng tại địa chỉ đã
                                                        cung cấp</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <h5 class="mb-4 fw-bold">Hướng Dẫn Chọn Size</h5>
                                <img src="{{ asset('storage/img/size-ao.jpg') }}"
                                    alt="Hướng Dẫn Chọn Size" class="col-md-6">
                                <img src="{{ asset('storage/img/size-giay.jpg') }}" alt="Hướng Dẫn Chọn Size"
                                    class="col-md-6">
                            </div>
                        </div>

                        <!-- Return Policy Tab -->
                        <div class="tab-pane fade" id="policy" role="tabpanel">
                            <div class="return-policy-content">
                                <h5 class="mb-4 fw-bold">Chính Sách Đổi Trả</h5>
                                <div class="policy-section mb-4">
                                    <h6 class="fw-semibold text-success mb-3">Điều kiện đổi trả:</h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Sản phẩm còn
                                            nguyên tem, mác, chưa qua sử dụng</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Thời gian đổi trả
                                            trong vòng 14 ngày kể từ ngày nhận hàng</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Có hóa đơn mua
                                            hàng hoặc đơn hàng online</li>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Sản phẩm không bị
                                            lỗi do người sử dụng</li>
                                    </ul>
                                </div>
                                <div class="policy-section mb-4">
                                    <h6 class="fw-semibold text-primary mb-3">Quy trình đổi trả:</h6>
                                    <ol class="list-unstyled">
                                        <li class="mb-2">1. Liên hệ hotline: <strong>0973.***.**8</strong></li>
                                        <li class="mb-2">2. Cung cấp thông tin đơn hàng và lý do đổi trả</li>
                                        <li class="mb-2">3. Đóng gói sản phẩm và gửi về địa chỉ được hướng dẫn</li>
                                        <li class="mb-2">4. Nhận sản phẩm mới hoặc hoàn tiền trong 3-5 ngày làm việc</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <div class="reviews-content">
                                <h5 class="mb-4 fw-bold">Đánh Giá Sản Phẩm</h5>

                                <!-- AJAX START -->
                                <div id="review-section">

                                    <!-- Tổng quan đánh giá -->
                                    <div class="bg-light rounded p-4 mb-4 shadow-sm">
                                        <div class="row align-items-center">
                                            <div class="col-md-3 text-center border-end">
                                                <div class="display-4 fw-bold text-danger">
                                                    {{ number_format($averageRating, 1) }}
                                                </div>
                                                <div class="text-muted">trên 5</div>
                                                <div class="text-warning">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @php
                                                            $decimal = $averageRating - floor($averageRating);
                                                        @endphp

                                                        @if ($i <= floor($averageRating))
                                                            <i class="fas fa-star"></i> {{-- Sao đầy --}}
                                                        @elseif ($i == ceil($averageRating))
                                                            @if ($decimal >= 0.75)
                                                                <i class="fas fa-star"></i> {{-- Gần đầy thì vẫn là sao đầy --}}
                                                            @elseif ($decimal >= 0.25)
                                                                <i class="fas fa-star-half-alt"></i> {{-- Từ 0.25 đến dưới 0.75 là nửa
                                                                sao --}}
                                                            @else
                                                                <i class="far fa-star"></i> {{-- Dưới 0.25 là sao rỗng --}}
                                                            @endif
                                                        @else
                                                            <i class="far fa-star"></i> {{-- Các sao còn lại rỗng --}}
                                                        @endif
                                                    @endfor
                                                </div>

                                            </div>

                                            <!-- Bộ lọc -->
                                            <div
                                                class="col-md-9 mt-3 mt-md-0 d-flex flex-wrap gap-2 justify-content-md-start justify-content-center">
                                                <a data-url="?rating=all"
                                                    class="btn btn-outline-danger rounded-pill {{ request('rating') == 'all' || !request('rating') ? 'active' : '' }}">
                                                    Tất Cả
                                                </a>
                                                @foreach($ratingsCount as $star => $count)
                                                    <a data-url="?rating={{ $star }}"
                                                        class="btn btn-outline-secondary rounded-pill {{ request('rating') == $star ? 'active' : '' }}">
                                                        {{ $star }} Sao ({{ $count }})
                                                    </a>
                                                @endforeach
                                                <a data-url="?has_images=1"
                                                    class="btn btn-outline-secondary rounded-pill {{ request('has_images') ? 'active' : '' }}">
                                                    Có Hình Ảnh / Video ({{ $reviewsWithImages }})
                                                </a>
                                                <a data-url="?has_comment=1"
                                                    class="btn btn-outline-secondary rounded-pill {{ request('has_comment') ? 'active' : '' }}">
                                                    Có Bình Luận ({{ $reviewsWithComment }})
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Danh sách đánh giá -->
                                    @if($reviews->count())
                                        <div class="review-timeline">
                                            @foreach($reviews as $review)
                                                <div class="d-flex mb-4 pb-4 border-bottom">
                                                    <!-- Avatar -->
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="rounded-circle bg-danger-subtle text-danger-emphasis d-flex align-items-center justify-content-center shadow-sm"
                                                            style="width: 50px; height: 50px; font-size: 20px; font-weight: bold;">
                                                            {{ strtoupper(mb_substr($review->user->name ?? 'U', 0, 1)) }}
                                                        </div>
                                                    </div>

                                                    <!-- Nội dung đánh giá -->
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between">
                                                            <strong
                                                                class="text-dark">{{ $review->user->name ?? 'Người dùng' }}</strong>
                                                            <span class="text-muted small">
                                                                {{ $review->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y') }}
                                                            </span>
                                                        </div>

                                                        <!-- Sao -->
                                                        <div class="text-warning mb-2">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                                            @endfor
                                                            <span class="ms-1 text-muted small">({{ $review->rating }}/5)</span>
                                                        </div>

                                                        <!-- Nội dung -->
                                                        @if($review->comment)
                                                            <div class="text-dark mb-2" style="line-height: 1.6;">
                                                                {{ $review->comment }}
                                                            </div>
                                                        @endif

                                                        <!-- Hình ảnh -->
                                                        @if($review->hasImages())
                                                            <div class="review-images d-flex flex-wrap gap-2 mt-2">
                                                                @foreach($review->images as $image)
                                                                    <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                                                        <img src="{{ asset('storage/' . $image) }}" alt="Hình đánh giá"
                                                                            style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px; border: 1px solid #ddd;"
                                                                            class="shadow-sm">
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- PHÂN TRANG -->
                                        <div class="d-flex justify-content-center mt-4">
                                            <div class="pagination-wrapper shadow-sm p-2 bg-white rounded">
                                                {!! $reviews->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') !!}
                                            </div>
                                        </div>
                                    @else
                                        <!-- Trường hợp chưa có đánh giá -->
                                        <div class="text-center py-5">
                                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                            <h5 class="text-secondary mb-2">Chưa có đánh giá nào!</h5>
                                            <p class="text-muted">Hãy là người đầu tiên chia sẻ trải nghiệm của bạn.</p>
                                        </div>
                                    @endif

                                </div>
                                <!-- AJAX END -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hidden Variants Data -->
    <div id="variants-data" style="display:none;">
        @foreach ($product->variants as $variant)
            <div class="variant-item" data-variant-id="{{ $variant->id }}" data-sku="{{ $variant->sku }}"
                data-price="{{ $variant->price }}" data-stock="{{ $variant->stock }}"
                data-image="{{ $variant->image ? asset('storage/' . $variant->image) : '' }}"
                data-values="{{ implode(',', $variant->attributeValues->pluck('id')->sort()->toArray()) }}">
            </div>
        @endforeach
    </div>

    <div>
        <h2 class="text-center letter-spacing-3 mb-4">SẢN PHẨM LIÊN QUAN</h2>

        @if ($relatedProducts->count() > 0)
            <div class="row">
                @foreach ($relatedProducts as $relatedProduct)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="product-card">
                            <div class="image-container">
                                <img src="{{ asset('storage/' . $relatedProduct->product_image) }}"
                                    alt="{{ $relatedProduct->name }}">
                                <div class="price">{{ number_format($relatedProduct->price) }}đ</div>
                            </div>

                            <form class="favorite-form" action="#" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                <button type="submit" class="favorite-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#000000">
                                        <path
                                            d="M12 20a1 1 0 0 1-.437-.1C11.214 19.73 3 15.671 3 9a5 5 0 0 1 8.535-3.536l.465.465.465-.465A5 5 0 0 1 21 9c0 6.646-8.212 10.728-8.562 10.9A1 1 0 0 1 12 20z">
                                        </path>
                                    </svg>
                                </button>
                            </form>

                            <div class="content">
                                <div class="brand">{{ $relatedProduct->brand->name ?? 'Brand' }}</div>
                                <div class="product-name">{{ $relatedProduct->name }}</div>
                                <div class="short-description">
                                    {{ Str::limit(strip_tags($relatedProduct->description), 60) }}
                                </div>
                                <div class="rating d-flex align-items-center justify-content-center gap-2">
                                    @include('components.star-rating', [
                                        'rating' => $relatedProduct->rounded_rating,
                                        'count' => $relatedProduct->reviews_count,
                                        'size' => 'sm'
                                    ])
                                </div>
                            </div>

                            <div class="product-actions">
                                <a href="{{ route('detail', $relatedProduct->slug) }}" class="detail-button">
                                    <span class="detail-text">Xem chi tiết</span>
                                    <span class="detail-arrow">
                                        <svg viewBox="0 0 24 24" width="24" height="24">
                                            <path fill="currentColor"
                                                d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" />
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted">Không có sản phẩm liên quan</p>
            </div>
        @endif
    </div>
    <!-- AJAX REVIEW -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadReviews(url) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    const newContent = $(data).find('#review-section').html();
                    $('#review-section').html(newContent);
                    window.history.pushState({}, '', url);
                },
                error: function () {
                    alert("Truy cập thất bại.");
                }
            });
        }

        // Click lọc
        $(document).on('click', '[data-url]', function (e) {
            e.preventDefault();
            const url = $(this).data('url');
            loadReviews(url);
        });

        // Click phân trang
        $(document).on('click', '#review-section .pagination a', function (e) {
            e.preventDefault();
            const url = $(this).attr('href');
            loadReviews(url);
        });
    </script>

    <style>
        .review-timeline {
            border-left: 3px solid #eee;
            padding-left: 15px;
        }

        .review-images img:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease-in-out;
        }

        .bg-danger-subtle {
            background-color: #f8d7da !important;
        }

        .text-danger-emphasis {
            color: #f58989 !important;
        }

        .btn-outline-secondary:hover,
        .btn-outline-danger:hover {
            background-color: #e78a8a !important;
            color: white !important;
            border-color: #e78a8a !important;
        }

        .btn.active {
            background-color: #e78a8a !important;
            color: white !important;
            border-color: #e78a8a !important;
        }

        /* phan trang */
        .page-item .page-link {
            color: #e78a8a;
            border-radius: 30px !important;
            margin: 0 4px;
            border: 1px solid #e78a8a;
            padding: 0.35rem 0.8rem;
            font-size: 0.9rem;
            transition: all 0.2s ease-in-out;
        }

        .page-item .page-link:hover {
            background-color: #e78a8a;
            color: white;
        }

        .page-item.active .page-link {
            background-color: #e78a8a;
            color: white;
            border-color: #e78a8a;
            font-weight: bold;
        }

        .page-item.disabled .page-link {
            color: #ccc;
            background-color: #f9f9f9;
            border-color: #ddd;
            cursor: not-allowed;
        }

        .btn-size {
            min-width: 40px;
            height: 40px;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .btn-size:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-check:checked+.btn-size {
            background-color: #212529;
            border-color: #212529;
            color: white;
        }

        .quantity-selector {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            overflow: hidden;
        }

        .quantity-selector input {
            border: none;
            border-left: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
            border-radius: 0;
            height: 40px;
            font-size: 0.9rem;
        }

        .quantity-selector .btn {
            border-radius: 0;
            border: none;
            width: 32px;
            height: 40px;
            font-size: 0.8rem;
        }

        .add-to-cart-btn,
        .buy-now-btn {
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 10px 16px;
            border-radius: 4px;
            transition: all 0.2s ease;
            font-size: 0.85rem;
            height: 40px;
        }

        .add-to-cart-btn:hover,
        .buy-now-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .product-image-container {
            position: static;
            top: 20px;
        }

        .current-price {
            color: #c81758 !important;
        }

        .policy-items i {
            width: 16px;
        }

        /* Custom Tab Styles */
        .custom-tab {
            border: none !important;
            border-bottom: 2px solid transparent !important;
            background: none !important;
            color: #666 !important;
            font-weight: 500;
            padding: 12px 20px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .custom-tab:hover {
            color: #333 !important;
            border-bottom-color: #ddd !important;
        }


        .product-info-tabs {
            background: #fff;
            border-radius: 8px;
            padding: 20px 0px;
        }

        .product-specs ul li:last-child {
            border-bottom: none;
        }

        .guide-step {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #333;
        }

        .step-number {
            flex-shrink: 0;
        }

        .policy-section {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Rating Section Styles */
        .rating-section {
            padding: 8px 0;
        }

        .rating-number {
            font-size: 1.2rem;
            color: #333;
        }

        .stars {
            font-size: 0.9rem;
        }

        .stars .fas.fa-star,
        .stars .fas.fa-star-half-alt {
            color: #ffc107 !important;
        }

        .stars .far.fa-star {
            color: #ddd !important;
        }

        .rating-divider {
            width: 1px;
            height: 20px;
            background-color: #ddd;
        }

        .reviews-count {
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .product-title {
                font-size: 1.4rem !important;
            }

            .purchase-section .row {
                flex-direction: column;
                gap: 10px;
            }

            .purchase-section .col-auto,
            .purchase-section .col {
                width: 100%;
            }

            .btn-size {
                min-width: 35px;
                height: 35px;
                font-size: 0.8rem;
            }

            .add-to-cart-btn,
            .buy-now-btn {
                font-size: 0.8rem;
                height: 34px;
            }

            .custom-tab {
                font-size: 0.85rem;
                padding: 10px 15px;
                margin-right: 5px;
            }

            .product-info-tabs {
                padding: 15px;
            }
        }

        @media (max-width: 576px) {
            .quantity-selector {
                justify-content: center;
                margin-bottom: 10px;
            }

            .d-flex.gap-2 {
                flex-direction: column;
                gap: 8px !important;
            }

            .nav-tabs {
                flex-wrap: wrap;
            }

            .custom-tab {
                margin-bottom: 5px;
            }
        }

        /* list product */
        .product-card {
            --accent-color: var(--primary-color);
            position: relative;
            width: 240px;
            background: #fff;
            border-radius: var(--border-radius);
            padding: .3rem;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            flex: 0 0 auto;
            scroll-snap-align: start;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, .15);
        }

        /* Hình đại diện */
        .image-container {
            position: relative;
            width: 100%;
            height: 150px;
            border-radius: calc(var(--border-radius) - 2px);
            border-top-right-radius: 4rem;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease;
        }

        .product-card:hover .image-container img {
            transform: scale(1.05);
        }

        /* Giá */
        .price {
            position: absolute;
            right: .7rem;
            bottom: -.5rem;
            background: #fff;
            color: var(--accent-color);
            font-weight: 900;
            font-size: .9rem;
            padding: .5rem .8rem;
            border-radius: 1rem;
            box-shadow: var(--box-shadow);
            z-index: 2;
            min-width: 60px;
            text-align: center;
        }

        /* Nút yêu thích  */
        .favorite-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 3;
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, .8);
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .3s ease;
            opacity: 0;
        }

        .product-card:hover .favorite-btn {
            opacity: 1;
        }

        .favorite-btn:hover {
            background: #fff;
            transform: scale(1.1);
        }

        .favorite-btn svg {
            width: 18px;
            height: 18px;
            fill: #a8a8a8;
            transition: all .3s ease;
        }

        .favorite-btn:hover svg {
            fill: var(--primary-color);
            transform: scale(1.2);
        }

        /* Nội dung */
        .content {
            padding: 0 .8rem;
            margin-bottom: .5rem;
            transition: all .3s ease;
        }

        .product-card:hover .content {
            transform: translateY(-10px);
        }

        .brand {
            font-weight: 900;
            color: #a6a6a6;
            font-size: .8rem;
            margin-bottom: .3rem;
        }

        .product-name {
            font-weight: 700;
            color: var(--dark-color);
            font-size: .9rem;
            margin: .3rem 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .short-description {
            color: #666;
            font-size: .75rem;
            line-height: 1.3;
            margin: .5rem 0;
            height: 35px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .product-card:hover .short-description {
            color: #444;
        }

        /* Đánh giá */
        .rating {
            color: #a8a8a8;
            font-size: .8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: .3rem;
            margin-top: .3rem;
        }

        .rating svg {
            height: 12px;
        }

        /* Khu vực nút hành động */
        .product-actions {
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            display: flex;
            gap: .5rem;
            padding: 0 .8rem .8rem;
            transition: all .3s ease;
            opacity: 0;
        }

        .product-card:hover .product-actions {
            bottom: 0;
            opacity: 1;
        }

        /* Nút chi tiết */
        .detail-button {
            flex: 1;
            text-align: center;
            text-decoration: none;
            background: var(--primary-color);
            color: #fff;
            font-weight: 600;
            font-size: .8rem;
            padding: .5rem .8rem .5rem 1rem;
            border-radius: var(--border-radius);
            transition: all .3s ease;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .detail-text {
            transition: all .3s ease;
            display: inline-block;
        }

        .detail-arrow {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .3s ease;
            transform: translateX(-5px);
            opacity: 0;
        }

        .detail-button:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            padding-right: .8rem;
            padding-left: 1.2rem;
        }

        .detail-button:hover .detail-text {
            transform: translateX(-5px);
        }

        .detail-button:hover .detail-arrow {
            transform: translateX(0);
            opacity: 1;
            animation: arrowBounce .6s infinite alternate;
        }

        @keyframes arrowBounce {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(3px);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll('.variant-radio');
            const variantInfo = {
                price: document.getElementById('price'),
                stock: document.getElementById('stock'),
                image: document.getElementById('variant-image'),
                sku: document.getElementById('sku-display'),
                stockInfo: document.querySelector('.stock-info'),
                outOfStockMessage: document.querySelector('.out-of-stock-message'),
                purchaseSection: document.querySelector('.purchase-section')
            };

            // Quantity controls
            const quantityInput = document.getElementById('quantity');
            const quantityBtns = document.querySelectorAll('.quantity-btn');

            quantityBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const action = this.dataset.action;
                    let currentValue = parseInt(quantityInput.value);

                    if (action === 'increase') {
                        quantityInput.value = currentValue + 1;
                    } else if (action === 'decrease' && currentValue > 1) {
                        quantityInput.value = currentValue - 1;
                    }
                });
            });

            // Variant selection logic (only if product has variants)
            if (radios.length > 0) {
                radios.forEach(radio => {
                    radio.addEventListener('change', handleSelection);
                });
            }

            // Auto-select first variant as default
            function selectDefaultVariant() {
                const variants = document.querySelectorAll('.variant-item');
                const hasAttributes = document.querySelectorAll('.attribute-section').length > 0;

                if (variants.length > 0 && hasAttributes) {
                    // Get the first variant
                    const firstVariant = variants[0];
                    const variantValues = firstVariant.dataset.values.split(',').map(v => v.trim());

                    // Select the corresponding radio buttons
                    variantValues.forEach(valueId => {
                        const radio = document.querySelector(`input[value="${valueId}"]`);
                        if (radio) {
                            radio.checked = true;
                        }
                    });

                    // Update the display
                    handleSelection();
                } else {
                    // If no variants or no attributes, show default product info
                    updateStockDisplay('{{ $product->stock }}');
                    // For products without variants, clear variant_id to use product_id
                    const variantIdInput = document.getElementById('variant_id');
                    if (variantIdInput) {
                        variantIdInput.value = '';
                    }
                }
            }

            function handleSelection() {
                const quantityInput = document.getElementById('quantity');
                let selectedValues = [];
                const totalAttributes = document.querySelectorAll('.attribute-section').length;

                // Nếu không có thuộc tính nào (sản phẩm không có biến thể), không cần xử lý
                if (totalAttributes === 0) {
                    return;
                }

                // Lấy tất cả giá trị đã chọn
                document.querySelectorAll('.variant-radio:checked').forEach(radio => {
                    selectedValues.push(radio.value);
                });

                // Sắp xếp để so sánh chính xác
                selectedValues.sort();
                let found = false;

                // Kiểm tra xem đã chọn đủ tất cả thuộc tính chưa
                if (selectedValues.length === totalAttributes) {
                    document.querySelectorAll('.variant-item').forEach(variant => {
                        const dataValues = variant.dataset.values.split(',').map(v => v.trim()).sort();

                        if (arraysEqual(dataValues, selectedValues)) {
                            variantInfo.price.textContent = parseFloat(variant.dataset.price)
                                .toLocaleString() + 'đ';
                            variantInfo.stock.textContent = variant.dataset.stock;
                            variantInfo.sku.textContent = variant.dataset.sku;

                            // Update variant_id for cart
                            const variantIdInput = document.getElementById('variant_id');
                            if (variantIdInput) {
                                variantIdInput.value = variant.dataset.variantId;
                            }

                            // Update stock display
                            updateStockDisplay(variant.dataset.stock);

                            if (variant.dataset.image && variant.dataset.image.trim() !== '') {
                                variantInfo.image.src = variant.dataset.image;
                            } else {
                                variantInfo.image.src =
                                    '{{ $product->product_image ? asset('storage/' . $product->product_image) : '/placeholder-image.jpg' }}';
                            }
                            variantInfo.image.style.display = 'block';

                            // Đảm bảo cập nhật max cho input số lượng
                            quantityInput.setAttribute('max', variant.dataset.stock);

                            found = true;
                        }
                    });
                }

                if (!found) {
                    console.log('Không tìm thấy tổ hợp phù hợp hoặc chưa chọn đủ thuộc tính');
                    // Reset to default values
                    variantInfo.price.textContent = '{{ number_format($product->price) }}đ';
                    variantInfo.stock.textContent = '{{ $product->stock }}';
                    variantInfo.sku.textContent = '{{ $product->sku }}'; // Use product SKU for default
                    variantInfo.image.src =
                        '{{ $product->product_image ? asset('storage/' . $product->product_image) : '/placeholder-image.jpg' }}';

                    // Clear variant_id
                    const variantIdInput = document.getElementById('variant_id');
                    if (variantIdInput) {
                        variantIdInput.value = '';
                    }

                    // Update stock display for default product
                    updateStockDisplay('{{ $product->stock }}');
                    // Đảm bảo cập nhật max cho input số lượng khi reset
                    quantityInput.setAttribute('max', '{{ $product->stock }}');
                }
            }

            function arraysEqual(arr1, arr2) {
                if (arr1.length !== arr2.length) return false;
                for (let i = 0; i < arr1.length; i++) {
                    if (arr1[i] !== arr2[i]) return false;
                }
                return true;
            }

            // Function to update stock display
            function updateStockDisplay(stock) {
                const stockNumber = parseInt(stock);
                const hasAttributes = document.querySelectorAll('.attribute-section').length > 0;

                if (stockNumber > 0) {
                    // Show stock info and purchase section, hide out of stock message
                    if (hasAttributes) {
                        variantInfo.stockInfo.style.display = 'block';
                    }
                    variantInfo.outOfStockMessage.style.display = 'none';
                    variantInfo.purchaseSection.style.display = 'block';
                } else {
                    // Hide stock info and purchase section, show out of stock message
                    if (hasAttributes) {
                        variantInfo.stockInfo.style.display = 'none';
                    }
                    variantInfo.outOfStockMessage.style.display = 'block';
                    variantInfo.purchaseSection.style.display = 'none';
                }
            }

            // Initialize default variant selection
            selectDefaultVariant();

            // For products without variants, ensure SKU is displayed correctly
            const hasAttributes = document.querySelectorAll('.attribute-section').length > 0;
            if (!hasAttributes) {
                const skuDisplay = document.getElementById('sku-display');
                if (skuDisplay) {
                    skuDisplay.textContent = '{{ $product->sku }}';
                }
            }

            // Add to cart functionality
            document.querySelector('.add-to-cart-btn').addEventListener('click', function () {
                addToCart();
            });

            // Buy now functionality
            document.querySelector('.buy-now-btn').addEventListener('click', function (e) {
                e.preventDefault();

                // Kiểm tra trạng thái đăng nhập
                const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

                if (!isAuthenticated) {
                    showNotification('Yêu cầu đăng nhập', 'Vui lòng đăng nhập để tiếp tục mua hàng!',
                        'warning');
                    return;
                }

                const quantityInput = document.getElementById('quantity');
                const quantity = parseInt(quantityInput.value);
                const maxStock = parseInt(quantityInput.getAttribute('max'));

                if (quantity > maxStock) {
                    showNotification('Lỗi', 'Số lượng mua đã vượt quá tồn kho!', 'error');
                    return;
                }

                // Xóa session buy_again_items trước khi mua ngay
                fetch('{{ route("checkout.clear-buy-again-session") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                }).finally(() => {
                    // Tiếp tục với việc mua ngay sau khi xóa session
                    const variantId = document.getElementById('variant_id').value;
                    document.getElementById('buyNowProductId').value = {{ $product->id }};
                    document.getElementById('buyNowVariantId').value = variantId;
                    document.getElementById('buyNowQuantity').value = quantity;
                    document.getElementById('buyNowForm').submit();
                });
            });

            // Function to add product to cart
            function addToCart() {
                const variantId = document.getElementById('variant_id').value;
                const quantity = parseInt(document.getElementById('quantity').value);
                const addToCartBtn = document.getElementById('add-to-cart-btn');
                const originalText = addToCartBtn.innerHTML;

                if (quantity <= 0) {
                    showNotification('Lỗi', 'Số lượng phải lớn hơn 0', 'error');
                    return;
                }

                // Disable button and show loading
                addToCartBtn.disabled = true;
                addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang thêm...';

                // Prepare request data
                const requestData = {
                    quantity: quantity
                };

                // Nếu có variant_id, gửi variant_id, nếu không có thì gửi product_id
                if (variantId) {
                    requestData.variant_id = variantId;
                } else {
                    requestData.product_id = {{ $product->id }};
                }

                fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(requestData)
                })
                    .then(response => {
                        if (response.status === 401) {
                            showNotification('yêu cầu đăng nhập',
                                'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!', 'warning');
                            // Ngăn không chạy tiếp .then(data => ...)
                            return Promise.reject('Unauthorized');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showNotification('Thành công', data.message, 'success');
                            // Update cart count
                            if (data.cart_count !== undefined) {
                                updateCartCount(data.cart_count);
                            }
                            // Reset quantity to 1
                            document.getElementById('quantity').value = 1;
                        } else {
                            showNotification('Lỗi', data.message, 'error');
                            // Update cart count even on error
                            if (data.cart_count !== undefined) {
                                updateCartCount(data.cart_count);
                            }
                        }
                    })
                    .catch(error => {
                        if (error !== 'Unauthorized') {
                            console.error('Error:', error);
                            showNotification('Lỗi', 'Có lỗi xảy ra, vui lòng thử lại!', 'error');
                        }
                    })
                    .finally(() => {
                        // Restore button
                        addToCartBtn.disabled = false;
                        addToCartBtn.innerHTML = originalText;
                    });
            }

            function updateQuantity(action) {
                const quantityInput = document.getElementById('quantity');
                const currentQuantity = parseInt(quantityInput.value);
                const maxStock = parseInt(quantityInput.getAttribute('max'));

                if (action === 'increase') {
                    if (currentQuantity < maxStock) {
                        quantityInput.value = currentQuantity + 1;
                    } else {
                        showNotification('Thông báo', 'Số lượng đã đạt tối đa tồn kho', 'warning');
                    }
                } else if (action === 'decrease') {
                    if (currentQuantity > 1) {
                        quantityInput.value = currentQuantity - 1;
                    }
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