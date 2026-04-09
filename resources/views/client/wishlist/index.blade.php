@extends('layouts.app_client')

@section('title', 'Sản phẩm yêu thích - TLO Fashion')

@section('styles')
<style>
.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 18px;
}

/* Product card scoped for wishlist */
.wishlist-product-card {
    position: relative;
    background: var(--tlo-surface);
    border-radius: 16px;
    padding: 0.3rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    transition: var(--tlo-transition);
    overflow: hidden;
    border: 1px solid var(--tlo-border);
}

.wishlist-product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.09);
    border-color: rgba(255, 107, 107, 0.2);
}

.wishlist-product-card .image-container {
    position: relative;
    width: 100%;
    height: 160px;
    border-radius: 14px 14px 14px 4rem;
    overflow: hidden;
    margin-bottom: 0.8rem;
}

.wishlist-product-card .image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.wishlist-product-card:hover .image-container img {
    transform: scale(1.05);
}

.wishlist-product-card .price {
    position: absolute;
    right: 0.7rem;
    bottom: -0.5rem;
    background: #fff;
    color: var(--tlo-accent);
    font-weight: 800;
    font-size: 0.85rem;
    padding: 6px 12px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    z-index: 2;
}

.wishlist-product-card .favorite-form {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 3;
}

.wishlist-product-card .favorite-btn {
    width: 34px;
    height: 34px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--tlo-transition);
}

.wishlist-product-card .favorite-btn svg {
    width: 18px;
    height: 18px;
    fill: var(--tlo-accent);
    transition: var(--tlo-transition);
}

.wishlist-product-card .favorite-btn:hover {
    background: #fff;
    transform: scale(1.15);
}

.wishlist-product-card .favorite-btn:hover svg {
    fill: #ef4444;
}

.wishlist-product-card .content {
    padding: 0 0.8rem;
    margin-bottom: 0.5rem;
}

.wishlist-product-card .brand {
    font-weight: 600;
    color: var(--tlo-text-light);
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.wishlist-product-card .product-name {
    font-weight: 700;
    color: var(--tlo-text-primary);
    font-size: 0.88rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 4px;
}

.wishlist-product-card .short-description {
    color: var(--tlo-text-secondary);
    font-size: 0.75rem;
    line-height: 1.4;
    height: 35px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
}

.wishlist-product-card .rating {
    color: #94a3b8;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.3rem;
    margin-top: 4px;
}

.wishlist-product-card .product-actions {
    position: absolute;
    bottom: -50px;
    left: 0;
    right: 0;
    display: flex;
    gap: 0.5rem;
    padding: 0 0.8rem 0.8rem;
    transition: all 0.35s ease;
    opacity: 0;
    background: white;
}

.wishlist-product-card:hover .product-actions {
    bottom: 0;
    opacity: 1;
}

.wishlist-product-card .detail-button {
    flex: 1;
    text-align: center;
    text-decoration: none;
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: #fff;
    font-weight: 600;
    font-size: 0.8rem;
    padding: 8px 12px;
    border-radius: 12px;
    transition: var(--tlo-transition);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.2);
}

.wishlist-product-card .detail-button:hover {
    background: linear-gradient(135deg, #e85d5d, #d14e1e);
    transform: translateY(-2px);
    color: #fff;
}

@media (max-width: 768px) {
    .wishlist-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}
</style>
@endsection

@section('content')
<div class="tlo-full-width">
    <!-- Hero -->
    <section class="tlo-page-hero">
        <div class="tlo-page-hero-inner">
            <div class="tlo-hero-badge"><i class="fas fa-heart"></i> Yêu thích</div>
            <h1 class="tlo-hero-title">Sản phẩm yêu thích</h1>
            <p class="tlo-hero-desc">Những sản phẩm bạn đã lưu để mua sau</p>
        </div>
    </section>

    <div class="user-page-layout">
        <!-- Sidebar -->
        <div>@include('client.partials.user-sidebar')</div>

        <!-- Main Content -->
        <div class="user-main-card tlo-animate">
            <div class="user-main-header">
                <h5><i class="fas fa-heart" style="color: var(--tlo-accent); margin-right: 8px;"></i> Danh sách yêu thích ({{ $wishlists->count() }})</h5>
            </div>
            <div class="user-main-body">
                @if($wishlists->count())
                    <div class="wishlist-grid">
                        @foreach($wishlists as $wishlist)
                            @php $product = $wishlist->product; @endphp
                            <div class="wishlist-product-card tlo-animate">
                                <div class="image-container">
                                    <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}">
                                    <div class="price">{{ number_format($product->price) }}đ</div>
                                </div>

                                <form class="favorite-form" action="{{ route('wishlist.destroy', $wishlist->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="favorite-btn" title="Xóa khỏi yêu thích">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#000000">
                                            <path d="M12 20a1 1 0 0 1-.437-.1C11.214 19.73 3 15.671 3 9a5 5 0 0 1 8.535-3.536l.465.465.465-.465A5 5 0 0 1 21 9c0 6.646-8.212 10.728-8.562 10.9A1 1 0 0 1 12 20z"></path>
                                        </svg>
                                    </button>
                                </form>

                                <div class="content">
                                    <div class="brand">{{ $product->brand->name ?? 'Brand' }}</div>
                                    <div class="product-name">{{ $product->name }}</div>
                                    <div class="short-description">{{ Str::limit(strip_tags($product->description), 60) }}</div>
                                    <div class="rating">
                                        @include('components.star-rating', [
                                            'rating' => $product->rounded_rating,
                                            'count' => $product->reviews_count,
                                            'size' => 'sm',
                                        ])
                                    </div>
                                </div>

                                <div class="product-actions">
                                    <a href="{{ route('detail', $product->slug) }}" class="detail-button">
                                        <span>Xem chi tiết</span>
                                        <svg viewBox="0 0 24 24" width="18" height="18">
                                            <path fill="currentColor" d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="tlo-empty">
                        <div class="tlo-empty-icon"><i class="fas fa-heart"></i></div>
                        <h3>Chưa có sản phẩm yêu thích</h3>
                        <p>Hãy thêm sản phẩm vào danh sách yêu thích khi duyệt cửa hàng</p>
                        <a href="{{ route('client.products.index') }}" class="tlo-btn tlo-btn-primary">
                            <i class="fas fa-store"></i> Xem sản phẩm
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
