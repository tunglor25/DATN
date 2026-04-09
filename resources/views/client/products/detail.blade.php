@extends('layouts.app_client')

@section('title', $product->name . ' - TLO Fashion')

@section('content')
<div class="tlo-full-width">
    <div class="pd-container">
        <!-- Breadcrumb -->
        <nav class="pd-breadcrumb">
            <a href="/">Trang chủ</a>
            <i class="fas fa-chevron-right"></i>
            <a href="{{ route('client.products.index') }}">Sản phẩm</a>
            <i class="fas fa-chevron-right"></i>
            <span>{{ $product->name }}</span>
        </nav>

        <!-- Product Main -->
        <div class="pd-main">
            <!-- Image Section -->
            <div class="pd-gallery">
                <div class="pd-image-wrap">
                    <img id="variant-image"
                         src="{{ $product->product_image ? asset('storage/' . $product->product_image) : '/placeholder-image.jpg' }}"
                         alt="{{ $product->name }}">
                </div>
            </div>

            <!-- Info Section -->
            <div class="pd-info">
                <h1 class="pd-title">{{ $product->name }}</h1>

                <div class="pd-meta">
                    <span class="pd-sku">SKU: <strong id="sku-display">{{ $product->sku }}</strong></span>
                    <span class="pd-separator"></span>
                    <div class="pd-rating-inline">
                        <span class="pd-rating-num">{{ $averageRating }}</span>
                        <div class="pd-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $averageRating)
                                    <i class="fas fa-star"></i>
                                @elseif ($i - $averageRating < 1)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="pd-review-count">({{ $product->reviews->count() }})</span>
                    </div>
                </div>

                <!-- Price -->
                <div class="pd-price-box">
                    <span id="price" class="pd-price-current">{{ number_format($product->price) }}đ</span>
                    @if ($product->original_price && $product->original_price > $product->price)
                        <span class="pd-price-old">{{ number_format($product->original_price) }}đ</span>
                        <span class="pd-discount-badge">
                            -{{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}%
                        </span>
                    @endif
                </div>

                <!-- Attributes -->
                @if(!empty($attributes) && count($attributes) > 0)
                    @foreach ($attributes as $attributeName => $values)
                        <div class="pd-attr-group">
                            <label class="pd-attr-label">{{ ucfirst($attributeName) }}:</label>
                            <div class="pd-attr-options">
                                @foreach ($values as $id => $value)
                                    <div class="pd-attr-item">
                                        <input type="radio" class="btn-check variant-radio" name="attribute[{{ $attributeName }}]"
                                               data-attr-name="{{ $attributeName }}"
                                               data-attr-id="{{ $attributeGroups[$attributeName] }}" value="{{ $id }}"
                                               id="attr_{{ $attributeName }}_{{ $id }}">
                                        <label class="pd-attr-btn" for="attr_{{ $attributeName }}_{{ $id }}">{{ $value }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="pd-stock-info">
                        <span>Số lượng: <strong id="stock">{{ $product->stock }}</strong></span>
                    </div>
                @endif

                @if(!empty($attributes) && count($attributes) > 0)
                    <div class="pd-stock-info" style="display: none;">
                        <span>Số lượng: <strong id="stock">{{ $product->stock }}</strong></span>
                    </div>
                @endif

                <!-- Out of Stock -->
                <div class="out-of-stock-message" style="display: none;">
                    <div class="pd-out-of-stock">
                        <i class="fas fa-exclamation-triangle"></i> SẢN PHẨM HIỆN ĐÃ HẾT HÀNG
                    </div>
                </div>

                <!-- Purchase Actions -->
                <div class="purchase-section">
                    <div class="pd-actions-row">
                        <div class="pd-qty">
                            <button type="button" class="pd-qty-btn quantity-btn" data-action="decrease">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}">
                            <button type="button" class="pd-qty-btn quantity-btn" data-action="increase">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <input type="hidden" id="variant_id" value="">
                        <button type="button" class="pd-btn-cart add-to-cart-btn" id="add-to-cart-btn">
                            <i class="fas fa-shopping-bag"></i> THÊM VÀO GIỎ
                        </button>
                        <form id="buyNowForm" action="{{ route('checkout.index') }}" method="GET" style="display:none;">
                            <input type="hidden" name="product_id" id="buyNowProductId">
                            <input type="hidden" name="variant_id" id="buyNowVariantId">
                            <input type="hidden" name="quantity" id="buyNowQuantity">
                        </form>
                        <button type="button" class="pd-btn-buy buy-now-btn">MUA NGAY</button>
                    </div>
                </div>

                <!-- Quick Order -->
                <div class="pd-hotline">
                    <div class="pd-hotline-icon"><i class="fas fa-headset"></i></div>
                    <div>
                        <div class="pd-hotline-label">Gọi đặt mua</div>
                        <div class="pd-hotline-number">0973.***.**8</div>
                    </div>
                    <span class="pd-hotline-time">8h30 - 18h30</span>
                </div>

                <!-- Policies -->
                <div class="pd-policies">
                    <div class="pd-policy"><i class="fas fa-shipping-fast"></i> Giao hàng miễn phí (Hóa đơn trên 500k)</div>
                    <div class="pd-policy"><i class="fas fa-undo-alt"></i> Đổi trả miễn phí 14 ngày</div>
                    <div class="pd-policy"><i class="fas fa-shield-alt"></i> Thanh toán COD & các hình thức khác</div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="pd-tabs-section">
            <ul class="nav nav-tabs pd-nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active pd-tab" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                        <i class="fas fa-info-circle"></i> Thông Tin SP
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link pd-tab" id="guide-tab" data-bs-toggle="tab" data-bs-target="#guide" type="button" role="tab">
                        <i class="fas fa-book"></i> Hướng Dẫn
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link pd-tab" id="policy-tab" data-bs-toggle="tab" data-bs-target="#policy" type="button" role="tab">
                        <i class="fas fa-exchange-alt"></i> Đổi Trả
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link pd-tab" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                        <i class="fas fa-star"></i> Đánh Giá ({{ $product->reviews->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content pd-tab-content" id="productTabsContent">
                <!-- Description -->
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <div class="pd-description-content">{!! $product->description !!}</div>
                </div>

                <!-- Guide -->
                <div class="tab-pane fade" id="guide" role="tabpanel">
                    <h3 class="pd-section-title">Hướng Dẫn Mua Hàng</h3>
                    <div class="pd-steps">
                        <div class="pd-step">
                            <div class="pd-step-num">1</div>
                            <div><h4>Chọn sản phẩm</h4><p>Chọn màu sắc, kích thước và số lượng mong muốn</p></div>
                        </div>
                        <div class="pd-step">
                            <div class="pd-step-num">2</div>
                            <div><h4>Thêm vào giỏ hàng</h4><p>Nhấn "Thêm vào giỏ" hoặc "Mua ngay" để tiếp tục</p></div>
                        </div>
                        <div class="pd-step">
                            <div class="pd-step-num">3</div>
                            <div><h4>Điền thông tin</h4><p>Nhập thông tin giao hàng và chọn phương thức thanh toán</p></div>
                        </div>
                        <div class="pd-step">
                            <div class="pd-step-num">4</div>
                            <div><h4>Hoàn tất đơn hàng</h4><p>Xác nhận và chờ nhận hàng tại địa chỉ đã cung cấp</p></div>
                        </div>
                    </div>
                    <h3 class="pd-section-title" style="margin-top:32px;">Hướng Dẫn Chọn Size</h3>
                    <div class="pd-size-guide">
                        <img src="{{ asset('storage/img/size-ao.jpg') }}" alt="Size áo">
                        <img src="{{ asset('storage/img/size-giay.jpg') }}" alt="Size giày">
                    </div>
                </div>

                <!-- Policy -->
                <div class="tab-pane fade" id="policy" role="tabpanel">
                    <h3 class="pd-section-title">Chính Sách Đổi Trả</h3>
                    <div class="pd-policy-grid">
                        <div class="pd-policy-card">
                            <h4><i class="fas fa-check-circle"></i> Điều kiện đổi trả</h4>
                            <ul>
                                <li>Sản phẩm còn nguyên tem, mác, chưa qua sử dụng</li>
                                <li>Thời gian đổi trả trong vòng 14 ngày kể từ ngày nhận hàng</li>
                                <li>Có hóa đơn mua hàng hoặc đơn hàng online</li>
                                <li>Sản phẩm không bị lỗi do người sử dụng</li>
                            </ul>
                        </div>
                        <div class="pd-policy-card">
                            <h4><i class="fas fa-list-ol"></i> Quy trình đổi trả</h4>
                            <ol>
                                <li>Liên hệ hotline: <strong>0973.***.**8</strong></li>
                                <li>Cung cấp thông tin đơn hàng và lý do đổi trả</li>
                                <li>Đóng gói sản phẩm và gửi về địa chỉ được hướng dẫn</li>
                                <li>Nhận sản phẩm mới hoặc hoàn tiền trong 3-5 ngày</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div id="review-section">
                        <!-- Rating Summary -->
                        <div class="pd-review-summary">
                            <div class="pd-review-score">
                                <div class="pd-review-big">{{ number_format($averageRating, 1) }}</div>
                                <div class="pd-review-of">trên 5</div>
                                <div class="pd-stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @php $decimal = $averageRating - floor($averageRating); @endphp
                                        @if ($i <= floor($averageRating))
                                            <i class="fas fa-star"></i>
                                        @elseif ($i == ceil($averageRating))
                                            @if ($decimal >= 0.75) <i class="fas fa-star"></i>
                                            @elseif ($decimal >= 0.25) <i class="fas fa-star-half-alt"></i>
                                            @else <i class="far fa-star"></i>
                                            @endif
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <div class="pd-review-filters">
                                <a data-url="?rating=all" class="pd-filter-btn {{ request('rating') == 'all' || !request('rating') ? 'active' : '' }}">Tất Cả</a>
                                @foreach($ratingsCount as $star => $count)
                                    <a data-url="?rating={{ $star }}" class="pd-filter-btn {{ request('rating') == $star ? 'active' : '' }}">{{ $star }} Sao ({{ $count }})</a>
                                @endforeach
                                <a data-url="?has_images=1" class="pd-filter-btn {{ request('has_images') ? 'active' : '' }}">Có Hình ({{ $reviewsWithImages }})</a>
                                <a data-url="?has_comment=1" class="pd-filter-btn {{ request('has_comment') ? 'active' : '' }}">Có Bình Luận ({{ $reviewsWithComment }})</a>
                            </div>
                        </div>

                        <!-- Reviews List -->
                        @if($reviews->count())
                            <div class="pd-reviews-list">
                                @foreach($reviews as $review)
                                    <div class="pd-review-item">
                                        <div class="pd-review-avatar">
                                            {{ strtoupper(mb_substr($review->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="pd-review-body">
                                            <div class="pd-review-header">
                                                <strong>{{ $review->user->name ?? 'Người dùng' }}</strong>
                                                <span>{{ $review->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y') }}</span>
                                            </div>
                                            <div class="pd-review-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                                @endfor
                                            </div>
                                            @if($review->comment)
                                                <p class="pd-review-text">{{ $review->comment }}</p>
                                            @endif
                                            @if($review->hasImages())
                                                <div class="pd-review-images">
                                                    @foreach($review->images as $image)
                                                        <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $image) }}" alt="Review">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="pd-pagination">
                                {!! $reviews->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') !!}
                            </div>
                        @else
                            <div class="pd-no-reviews">
                                <i class="fas fa-comments"></i>
                                <h3>Chưa có đánh giá nào!</h3>
                                <p>Hãy là người đầu tiên chia sẻ trải nghiệm của bạn.</p>
                            </div>
                        @endif
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

        <!-- Related Products -->
        <div class="pd-related">
            <h2 class="pd-related-title">Sản Phẩm Liên Quan</h2>
            @if ($relatedProducts->count() > 0)
                <div class="pd-related-grid">
                    @foreach ($relatedProducts as $relatedProduct)
                        <div class="pd-related-card">
                            <a href="{{ route('detail', $relatedProduct->slug) }}" class="pd-related-img-link">
                                <img src="{{ asset('storage/' . $relatedProduct->product_image) }}" alt="{{ $relatedProduct->name }}">
                                @if($relatedProduct->original_price && $relatedProduct->original_price > $relatedProduct->price)
                                    <span class="pd-related-badge">-{{ round((($relatedProduct->original_price - $relatedProduct->price) / $relatedProduct->original_price) * 100) }}%</span>
                                @endif
                            </a>
                            <div class="pd-related-info">
                                <span class="pd-related-brand">{{ $relatedProduct->brand->name ?? 'Brand' }}</span>
                                <a href="{{ route('detail', $relatedProduct->slug) }}" class="pd-related-name">{{ $relatedProduct->name }}</a>
                                <div class="pd-related-price">{{ number_format($relatedProduct->price) }}đ</div>
                                <div class="pd-related-rating">
                                    @include('components.star-rating', ['rating' => $relatedProduct->rounded_rating, 'count' => $relatedProduct->reviews_count, 'size' => 'sm'])
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="text-align:center; color: var(--tlo-text-muted);">Không có sản phẩm liên quan</p>
            @endif
        </div>
    </div>
</div>

{{-- ===== STYLES ===== --}}
<style>
    .pd-container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }

    /* Breadcrumb */
    .pd-breadcrumb {
        display: flex; align-items: center; gap: 8px; padding: 18px 0;
        font-size: 0.82rem; color: var(--tlo-text-muted, #94a3b8);
    }
    .pd-breadcrumb a { color: var(--tlo-text-secondary, #64748b); text-decoration: none; transition: color 0.2s; }
    .pd-breadcrumb a:hover { color: var(--tlo-accent, #ff6b6b); }
    .pd-breadcrumb i { font-size: 0.55rem; }

    /* Main Layout */
    .pd-main {
        display: grid; grid-template-columns: 1fr 1fr; gap: 48px;
        padding-bottom: 48px; border-bottom: 1px solid var(--tlo-border, #e2e8f0);
    }

    /* Gallery */
    .pd-image-wrap {
        position: sticky; top: 90px; border-radius: 16px; overflow: hidden;
        background: #f8f9fa;
        border: 1px solid var(--tlo-border, #e2e8f0);
    }
    .pd-image-wrap img {
        width: 100%; height: auto; max-height: 650px; object-fit: cover; display: block;
        transition: transform 0.5s ease;
    }
    .pd-image-wrap:hover img { transform: scale(1.03); }

    /* Title */
    .pd-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem; font-weight: 700; color: var(--tlo-text-primary, #1e293b);
        margin: 0 0 16px; line-height: 1.3; letter-spacing: 0.5px;
    }

    /* Meta */
    .pd-meta {
        display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
        margin-bottom: 20px; font-size: 0.85rem;
    }
    .pd-sku { color: var(--tlo-text-muted, #94a3b8); }
    .pd-sku strong { color: var(--tlo-text-secondary, #64748b); }
    .pd-separator { width: 1px; height: 16px; background: var(--tlo-border, #e2e8f0); }
    .pd-rating-inline { display: flex; align-items: center; gap: 6px; }
    .pd-rating-num { font-weight: 700; color: var(--tlo-text-primary, #1e293b); }
    .pd-stars { display: flex; gap: 2px; }
    .pd-stars i { color: #f59e0b; font-size: 0.8rem; }
    .pd-review-count { color: var(--tlo-text-muted, #94a3b8); font-size: 0.82rem; }

    /* Price Box */
    .pd-price-box {
        display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
        padding: 16px 20px; border-radius: 12px;
        background: linear-gradient(135deg, rgba(255,107,107,0.05), rgba(238,90,36,0.05));
        border: 1px solid rgba(255,107,107,0.12); margin-bottom: 24px;
    }
    .pd-price-current {
        font-size: 1.6rem; font-weight: 800;
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .pd-price-old { text-decoration: line-through; color: var(--tlo-text-muted, #94a3b8); font-size: 1rem; }
    .pd-discount-badge {
        background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: #fff;
        padding: 3px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700;
    }

    /* Attribute Groups */
    .pd-attr-group { margin-bottom: 20px; }
    .pd-attr-label { font-weight: 600; font-size: 0.9rem; color: var(--tlo-text-primary, #1e293b); margin-bottom: 10px; display: block; }
    .pd-attr-options { display: flex; flex-wrap: wrap; gap: 8px; }
    .pd-attr-btn {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 44px; height: 42px; padding: 0 16px;
        border: 1.5px solid var(--tlo-border, #e2e8f0); border-radius: 10px;
        font-size: 0.88rem; font-weight: 500; cursor: pointer;
        color: var(--tlo-text-primary, #1e293b); background: var(--tlo-surface, #fff);
        transition: all 0.25s ease;
    }
    .pd-attr-btn:hover { border-color: var(--tlo-accent, #ff6b6b); color: var(--tlo-accent, #ff6b6b); }
    .btn-check:checked + .pd-attr-btn {
        background: linear-gradient(135deg, #1a1a2e, #0f0f0f); border-color: #1a1a2e;
        color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Stock */
    .pd-stock-info { font-size: 0.88rem; color: var(--tlo-text-secondary, #64748b); margin-bottom: 16px; }
    .pd-out-of-stock {
        background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);
        border-radius: 10px; padding: 14px 20px; text-align: center;
        color: #ef4444; font-weight: 600; margin-bottom: 16px;
    }

    /* Actions */
    .pd-actions-row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin-bottom: 20px; }
    .pd-qty {
        display: flex; align-items: center; border: 1.5px solid var(--tlo-border, #e2e8f0);
        border-radius: 10px; overflow: hidden;
    }
    .pd-qty-btn {
        width: 40px; height: 42px; border: none; background: none;
        cursor: pointer; font-size: 0.75rem; color: var(--tlo-text-secondary, #64748b);
        transition: background 0.2s;
    }
    .pd-qty-btn:hover { background: #f1f5f9; }
    .pd-qty input {
        width: 56px; height: 42px; border: none; text-align: center;
        font-size: 0.95rem; font-weight: 600; background: none;
        border-left: 1px solid var(--tlo-border, #e2e8f0);
        border-right: 1px solid var(--tlo-border, #e2e8f0);
        color: var(--tlo-text-primary, #1e293b); outline: none;
    }
    .pd-btn-cart, .pd-btn-buy {
        flex: 1; height: 44px; border-radius: 10px; border: none;
        font-size: 0.85rem; font-weight: 700; cursor: pointer;
        transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 8px;
        letter-spacing: 0.5px;
    }
    .pd-btn-cart {
        background: linear-gradient(135deg, #1a1a2e, #0f0f0f); color: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .pd-btn-cart:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.18); }
    .pd-btn-buy {
        background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: #fff;
        box-shadow: 0 4px 12px rgba(255,107,107,0.2);
    }
    .pd-btn-buy:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,107,107,0.35); }

    /* Hotline */
    .pd-hotline {
        display: flex; align-items: center; gap: 14px; padding: 14px 18px;
        border-radius: 12px; background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.15);
        margin-bottom: 16px;
    }
    .pd-hotline-icon { width: 40px; height: 40px; border-radius: 10px; background: #10b981; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1rem; }
    .pd-hotline-label { font-size: 0.78rem; color: var(--tlo-text-muted, #94a3b8); }
    .pd-hotline-number { font-size: 1.1rem; font-weight: 800; color: #10b981; }
    .pd-hotline-time { margin-left: auto; font-size: 0.78rem; color: var(--tlo-text-muted, #94a3b8); }

    /* Policies */
    .pd-policies { display: flex; flex-direction: column; gap: 8px; }
    .pd-policy {
        display: flex; align-items: center; gap: 10px; font-size: 0.84rem;
        color: var(--tlo-text-secondary, #64748b); padding: 8px 0;
    }
    .pd-policy i { width: 20px; color: var(--tlo-accent, #ff6b6b); font-size: 0.85rem; }

    /* Tabs */
    .pd-tabs-section {
        margin-top: 48px; background: var(--tlo-surface, #fff);
        border-radius: 16px; border: 1px solid var(--tlo-border, #e2e8f0);
        overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .pd-nav-tabs {
        border-bottom: 2px solid var(--tlo-border, #e2e8f0) !important; padding: 0 24px; background: #fafbfc;
    }
    .pd-tab {
        border: none !important; border-bottom: 2px solid transparent !important;
        background: none !important; color: var(--tlo-text-secondary, #94a3b8) !important;
        font-weight: 500; padding: 14px 20px !important; margin-bottom: -2px;
        transition: all 0.3s ease; font-size: 0.88rem; display: flex; align-items: center; gap: 6px;
    }
    .pd-tab.active { color: var(--tlo-accent, #ff6b6b) !important; border-bottom-color: var(--tlo-accent, #ff6b6b) !important; font-weight: 600; }
    .pd-tab:hover { color: var(--tlo-accent, #ff6b6b) !important; }
    .pd-tab-content { padding: 28px; }
    .pd-description-content { line-height: 1.8; color: var(--tlo-text-secondary, #64748b); }
    .pd-description-content img { max-width: 100%; height: auto; border-radius: 8px; }
    .pd-section-title { font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; color: var(--tlo-text-primary, #1e293b); margin-bottom: 20px; }

    /* Steps */
    .pd-steps { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .pd-step {
        display: flex; align-items: flex-start; gap: 14px; padding: 18px;
        border-radius: 12px; background: #f8fafc; border-left: 3px solid var(--tlo-accent, #ff6b6b);
    }
    .pd-step-num {
        width: 32px; height: 32px; border-radius: 50%;
        background: linear-gradient(135deg, #1a1a2e, #0f0f0f); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; font-weight: 700; flex-shrink: 0;
    }
    .pd-step h4 { font-size: 0.92rem; font-weight: 600; color: var(--tlo-text-primary, #1e293b); margin: 0 0 4px; }
    .pd-step p { font-size: 0.82rem; color: var(--tlo-text-secondary, #64748b); margin: 0; line-height: 1.5; }

    /* Size Guide */
    .pd-size-guide { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .pd-size-guide img { width: 100%; border-radius: 12px; border: 1px solid var(--tlo-border, #e2e8f0); }

    /* Policy Grid */
    .pd-policy-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .pd-policy-card {
        padding: 20px; border-radius: 12px; background: #f8fafc;
        border: 1px solid var(--tlo-border, #e2e8f0);
    }
    .pd-policy-card h4 { font-size: 0.95rem; font-weight: 600; color: var(--tlo-text-primary, #1e293b); margin-bottom: 14px; }
    .pd-policy-card h4 i { color: var(--tlo-accent, #ff6b6b); margin-right: 6px; }
    .pd-policy-card ul, .pd-policy-card ol { padding-left: 18px; margin: 0; }
    .pd-policy-card li { font-size: 0.85rem; color: var(--tlo-text-secondary, #64748b); margin-bottom: 8px; line-height: 1.6; }

    /* Reviews */
    .pd-review-summary {
        display: flex; align-items: flex-start; gap: 28px; padding: 20px;
        border-radius: 14px; background: #f8fafc; margin-bottom: 24px; flex-wrap: wrap;
    }
    .pd-review-score { text-align: center; min-width: 120px; }
    .pd-review-big { font-size: 2.4rem; font-weight: 800; background: linear-gradient(135deg, #ff6b6b, #ee5a24); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .pd-review-of { font-size: 0.82rem; color: var(--tlo-text-muted, #94a3b8); margin-bottom: 4px; }
    .pd-review-filters { display: flex; flex-wrap: wrap; gap: 8px; flex: 1; align-items: center; }
    .pd-filter-btn {
        padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;
        border: 1px solid var(--tlo-border, #e2e8f0); color: var(--tlo-text-secondary, #64748b);
        cursor: pointer; transition: all 0.25s ease; text-decoration: none; background: #fff;
    }
    .pd-filter-btn:hover, .pd-filter-btn.active {
        background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: #fff !important;
        border-color: #ff6b6b; box-shadow: 0 4px 12px rgba(255,107,107,0.2);
    }

    .pd-reviews-list { display: flex; flex-direction: column; gap: 0; }
    .pd-review-item {
        display: flex; gap: 14px; padding: 20px 0;
        border-bottom: 1px solid var(--tlo-border, #e2e8f0);
    }
    .pd-review-item:last-child { border-bottom: none; }
    .pd-review-avatar {
        width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; font-weight: 700;
    }
    .pd-review-body { flex: 1; min-width: 0; }
    .pd-review-header { display: flex; justify-content: space-between; margin-bottom: 4px; }
    .pd-review-header strong { font-size: 0.9rem; color: var(--tlo-text-primary, #1e293b); }
    .pd-review-header span { font-size: 0.78rem; color: var(--tlo-text-muted, #94a3b8); }
    .pd-review-stars { margin-bottom: 8px; }
    .pd-review-stars i { color: #f59e0b; font-size: 0.75rem; }
    .pd-review-text { font-size: 0.88rem; color: var(--tlo-text-secondary, #64748b); line-height: 1.7; margin: 0; }
    .pd-review-images { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
    .pd-review-images img { width: 80px; height: 80px; object-fit: cover; border-radius: 10px; border: 1px solid var(--tlo-border, #e2e8f0); transition: transform 0.2s; }
    .pd-review-images img:hover { transform: scale(1.08); }
    .pd-pagination { display: flex; justify-content: center; margin-top: 20px; }
    .pd-pagination .page-link { color: var(--tlo-accent, #ff6b6b); border-radius: 8px !important; margin: 0 3px; border-color: var(--tlo-border, #e2e8f0); font-size: 0.85rem; }
    .pd-pagination .page-item.active .page-link { background: var(--tlo-accent, #ff6b6b); border-color: var(--tlo-accent, #ff6b6b); color: #fff; }
    .pd-no-reviews { text-align: center; padding: 50px 20px; }
    .pd-no-reviews i { font-size: 2.5rem; color: var(--tlo-border, #e2e8f0); margin-bottom: 14px; display: block; }
    .pd-no-reviews h3 { font-size: 1.1rem; color: var(--tlo-text-secondary, #64748b); margin-bottom: 6px; }
    .pd-no-reviews p { color: var(--tlo-text-muted, #94a3b8); font-size: 0.88rem; }

    /* Related Products */
    .pd-related { margin-top: 48px; padding-bottom: 60px; }
    .pd-related-title {
        font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700;
        text-align: center; color: var(--tlo-text-primary, #1e293b); margin-bottom: 28px;
    }
    .pd-related-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .pd-related-card {
        border-radius: 14px; overflow: hidden; background: var(--tlo-surface, #fff);
        border: 1px solid var(--tlo-border, #e2e8f0); transition: all 0.3s ease;
    }
    .pd-related-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.08); }
    .pd-related-img-link { display: block; position: relative; overflow: hidden; height: 220px; }
    .pd-related-img-link img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
    .pd-related-card:hover .pd-related-img-link img { transform: scale(1.06); }
    .pd-related-badge {
        position: absolute; top: 10px; left: 10px;
        background: linear-gradient(135deg, #ff6b6b, #ee5a24); color: #fff;
        padding: 3px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 700;
    }
    .pd-related-info { padding: 14px 16px; }
    .pd-related-brand { font-size: 0.72rem; font-weight: 600; color: var(--tlo-text-muted, #94a3b8); text-transform: uppercase; letter-spacing: 1px; }
    .pd-related-name {
        display: block; font-weight: 600; font-size: 0.92rem; color: var(--tlo-text-primary, #1e293b);
        text-decoration: none; margin: 6px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .pd-related-name:hover { color: var(--tlo-accent, #ff6b6b); }
    .pd-related-price { font-weight: 700; color: var(--tlo-accent, #ff6b6b); font-size: 0.95rem; }
    .pd-related-rating { margin-top: 6px; }

    /* Responsive */
    @media (max-width: 992px) {
        .pd-main { grid-template-columns: 1fr; gap: 24px; }
        .pd-image-wrap { position: static; }
        .pd-steps, .pd-size-guide, .pd-policy-grid { grid-template-columns: 1fr; }
        .pd-related-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .pd-container { padding: 0 16px; }
        .pd-title { font-size: 1.3rem; }
        .pd-actions-row { flex-direction: column; }
        .pd-actions-row > * { width: 100%; }
        .pd-qty { justify-content: center;}
        .pd-related-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .pd-related-img-link { height: 180px; }
        .pd-tab { padding: 10px 14px !important; font-size: 0.8rem; }
        .pd-review-summary { flex-direction: column; }
    }
    @media (max-width: 480px) {
        .pd-related-grid { grid-template-columns: 1fr 1fr; }
        .pd-related-img-link { height: 150px; }
    }
</style>

{{-- ===== SCRIPTS ===== --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // AJAX Reviews
    function loadReviews(url) {
        $.ajax({
            url: url, type: 'GET',
            success: function(data) {
                const newContent = $(data).find('#review-section').html();
                $('#review-section').html(newContent);
                window.history.pushState({}, '', url);
            },
            error: function() { alert("Truy cập thất bại."); }
        });
    }
    $(document).on('click', '[data-url]', function(e) { e.preventDefault(); loadReviews($(this).data('url')); });
    $(document).on('click', '#review-section .pagination a', function(e) { e.preventDefault(); loadReviews($(this).attr('href')); });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('.variant-radio');
        const variantInfo = {
            price: document.getElementById('price'),
            stock: document.getElementById('stock'),
            image: document.getElementById('variant-image'),
            sku: document.getElementById('sku-display'),
            stockInfo: document.querySelector('.pd-stock-info'),
            outOfStockMessage: document.querySelector('.out-of-stock-message'),
            purchaseSection: document.querySelector('.purchase-section')
        };

        // Quantity controls
        const quantityInput = document.getElementById('quantity');
        const quantityBtns = document.querySelectorAll('.quantity-btn');
        quantityBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.dataset.action;
                let currentValue = parseInt(quantityInput.value);
                if (action === 'increase') { quantityInput.value = currentValue + 1; }
                else if (action === 'decrease' && currentValue > 1) { quantityInput.value = currentValue - 1; }
            });
        });

        // Variant selection
        if (radios.length > 0) {
            radios.forEach(radio => { radio.addEventListener('change', handleSelection); });
        }

        function selectDefaultVariant() {
            const variants = document.querySelectorAll('.variant-item');
            const hasAttributes = document.querySelectorAll('.pd-attr-group').length > 0;
            if (variants.length > 0 && hasAttributes) {
                const firstVariant = variants[0];
                const variantValues = firstVariant.dataset.values.split(',').map(v => v.trim());
                variantValues.forEach(valueId => {
                    const radio = document.querySelector(`input[value="${valueId}"]`);
                    if (radio) radio.checked = true;
                });
                handleSelection();
            } else {
                updateStockDisplay('{{ $product->stock }}');
                const variantIdInput = document.getElementById('variant_id');
                if (variantIdInput) variantIdInput.value = '';
            }
        }

        function handleSelection() {
            const quantityInput = document.getElementById('quantity');
            let selectedValues = [];
            const totalAttributes = document.querySelectorAll('.pd-attr-group').length;
            if (totalAttributes === 0) return;

            document.querySelectorAll('.variant-radio:checked').forEach(radio => { selectedValues.push(radio.value); });
            selectedValues.sort();
            let found = false;

            if (selectedValues.length === totalAttributes) {
                document.querySelectorAll('.variant-item').forEach(variant => {
                    const dataValues = variant.dataset.values.split(',').map(v => v.trim()).sort();
                    if (arraysEqual(dataValues, selectedValues)) {
                        variantInfo.price.textContent = parseFloat(variant.dataset.price).toLocaleString() + 'đ';
                        variantInfo.stock.textContent = variant.dataset.stock;
                        variantInfo.sku.textContent = variant.dataset.sku;
                        const variantIdInput = document.getElementById('variant_id');
                        if (variantIdInput) variantIdInput.value = variant.dataset.variantId;
                        updateStockDisplay(variant.dataset.stock);
                        if (variant.dataset.image && variant.dataset.image.trim() !== '') {
                            variantInfo.image.src = variant.dataset.image;
                        } else {
                            variantInfo.image.src = '{{ $product->product_image ? asset("storage/" . $product->product_image) : "/placeholder-image.jpg" }}';
                        }
                        variantInfo.image.style.display = 'block';
                        quantityInput.setAttribute('max', variant.dataset.stock);
                        found = true;
                    }
                });
            }

            if (!found) {
                variantInfo.price.textContent = '{{ number_format($product->price) }}đ';
                variantInfo.stock.textContent = '{{ $product->stock }}';
                variantInfo.sku.textContent = '{{ $product->sku }}';
                variantInfo.image.src = '{{ $product->product_image ? asset("storage/" . $product->product_image) : "/placeholder-image.jpg" }}';
                const variantIdInput = document.getElementById('variant_id');
                if (variantIdInput) variantIdInput.value = '';
                updateStockDisplay('{{ $product->stock }}');
                quantityInput.setAttribute('max', '{{ $product->stock }}');
            }
        }

        function arraysEqual(a, b) {
            if (a.length !== b.length) return false;
            for (let i = 0; i < a.length; i++) { if (a[i] !== b[i]) return false; }
            return true;
        }

        function updateStockDisplay(stock) {
            const stockNumber = parseInt(stock);
            const hasAttributes = document.querySelectorAll('.pd-attr-group').length > 0;
            if (stockNumber > 0) {
                if (hasAttributes) variantInfo.stockInfo.style.display = 'block';
                variantInfo.outOfStockMessage.style.display = 'none';
                variantInfo.purchaseSection.style.display = 'block';
            } else {
                if (hasAttributes) variantInfo.stockInfo.style.display = 'none';
                variantInfo.outOfStockMessage.style.display = 'block';
                variantInfo.purchaseSection.style.display = 'none';
            }
        }

        selectDefaultVariant();

        const hasAttributes = document.querySelectorAll('.pd-attr-group').length > 0;
        if (!hasAttributes) {
            const skuDisplay = document.getElementById('sku-display');
            if (skuDisplay) skuDisplay.textContent = '{{ $product->sku }}';
        }

        // Add to cart
        document.querySelector('.add-to-cart-btn').addEventListener('click', function() { addToCart(); });

        // Buy now
        document.querySelector('.buy-now-btn').addEventListener('click', function(e) {
            e.preventDefault();
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
            if (!isAuthenticated) {
                showNotification('Yêu cầu đăng nhập', 'Vui lòng đăng nhập để tiếp tục mua hàng!', 'warning');
                return;
            }
            const quantity = parseInt(document.getElementById('quantity').value);
            const maxStock = parseInt(document.getElementById('quantity').getAttribute('max'));
            if (quantity > maxStock) {
                showNotification('Lỗi', 'Số lượng mua đã vượt quá tồn kho!', 'error');
                return;
            }
            fetch('{{ route("checkout.clear-buy-again-session") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            }).finally(() => {
                const variantId = document.getElementById('variant_id').value;
                document.getElementById('buyNowProductId').value = {{ $product->id }};
                document.getElementById('buyNowVariantId').value = variantId;
                document.getElementById('buyNowQuantity').value = quantity;
                document.getElementById('buyNowForm').submit();
            });
        });

        function addToCart() {
            const variantId = document.getElementById('variant_id').value;
            const quantity = parseInt(document.getElementById('quantity').value);
            const btn = document.getElementById('add-to-cart-btn');
            const originalText = btn.innerHTML;
            if (quantity <= 0) { showNotification('Lỗi', 'Số lượng phải lớn hơn 0', 'error'); return; }
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
            const requestData = { quantity: quantity };
            if (variantId) { requestData.variant_id = variantId; }
            else { requestData.product_id = {{ $product->id }}; }

            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify(requestData)
            })
            .then(response => {
                if (response.status === 401) {
                    showNotification('Yêu cầu đăng nhập', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!', 'warning');
                    return Promise.reject('Unauthorized');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification('Thành công', data.message, 'success');
                    if (data.cart_count !== undefined) updateCartCount(data.cart_count);
                    document.getElementById('quantity').value = 1;
                } else {
                    showNotification('Lỗi', data.message, 'error');
                    if (data.cart_count !== undefined) updateCartCount(data.cart_count);
                }
            })
            .catch(error => { if (error !== 'Unauthorized') showNotification('Lỗi', 'Có lỗi xảy ra!', 'error'); })
            .finally(() => { btn.disabled = false; btn.innerHTML = originalText; });
        }

        function updateCartCount(count) {
            const el = document.querySelector('.cart-count');
            if (el) { el.textContent = count; el.style.display = count > 0 ? 'flex' : 'none'; }
            else if (count > 0) {
                const cartBtn = document.querySelector('.cart-btn');
                if (cartBtn) {
                    const badge = document.createElement('span');
                    badge.className = 'cart-count'; badge.textContent = count; badge.style.display = 'flex';
                    cartBtn.appendChild(badge);
                }
            }
        }
    });
</script>
@endsection