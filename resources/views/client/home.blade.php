@extends('layouts.app_client')
<link rel="stylesheet" href="{{ asset('build/css/style_home.css') }}">
@section('content')
    <div class="tlo-full-width home-page-content">
        <div class="d-flex justify-content-center mb-5">
            <div class="top-banners-container">
                <div class="top-banners-wrapper">
                    <!-- Hình ảnh 1 -->
                    <a href="{{ route('discounts.index') }}" class="top-banner-link">
                        <div class="position-relative top-banner-item">
                            <img src="storage/img/poster-home.png" alt="Image 1"
                                class="img-fluid w-100 h-100 img-fill">
                            <div class="image-overlay d-flex justify-content-center align-items-center">
                                <h3 class="text-white overlay-text">Nhận ngay các ưu đãi
                                    giảm giá siêu hời tại đây !</h3>
                            </div>
                        </div>
                    </a>

                    <!-- Hình ảnh 2 -->
                    <a href="#" class="top-banner-link">
                        <div class="position-relative top-banner-item">
                            <img src="storage/img/Frontpage_img-background-Sale-off.jpg" alt="Image 2"
                                class="img-fluid w-100 h-100 img-fill">
                            <div class="image-overlay d-flex justify-content-center align-items-center">
                                <h3 class="text-white overlay-text">Danh mục những sản phẩm bán tại "giá tốt hơn" chỉ được
                                    bán
                                    kênh online - Online Only, chúng đã từng làm mưa làm gió một thời gian và hiện đang rơi
                                    vào
                                    tình trạng bể size, bể số.</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="product-section product-man">
            {{-- ảnh banner --}}
            <div class="wide-banner">
                <img src="storage/img/banner_MenShoe.jpg" alt="Banner" class="banner-image">
                <div class="banner-overlay">
                    <h2>Thời trang Nam</h2>
                </div>
            </div>
            {{-- ảnh bên trái --}}
            <div class="two-column-layout">
                <div class="tall-image d-none d-md-block">
                    <img src="storage/img/nam.jpg" alt="Featured Product">
                </div>
                {{-- list sản phẩm --}}
                <div class="products-slider">
                    <button class="slider-nav-btn slider-prev" aria-label="Previous slide">
                        <svg viewBox="0 0 24 24">
                            <path d="M15.41 16.58L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.42z" />
                        </svg>
                    </button>

                    <div class="slider-container">
                        @if($productsMan->count() > 0)
                            @foreach($productsMan as $product)
                                <div class="product-card">
                                    <!-- Your existing product card content -->
                                    <div class="image-container">
                                        <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}">
                                        <div class="price">{{ number_format($product->price) }}đ</div>
                                    </div>

                                    <form class="favorite-form{{ in_array($product->id, $wishlistProductIds) ? ' active' : '' }}"
                                        action="{{ route('wishlist.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="favorite-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#000000">
                                                <path
                                                    d="M12 20a1 1 0 0 1-.437-.1C11.214 19.73 3 15.671 3 9a5 5 0 0 1 8.535-3.536l.465.465.465-.465A5 5 0 0 1 21 9c0 6.646-8.212 10.728-8.562 10.9A1 1 0 0 1 12 20z">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>

                                    <div class="content_product">
                                        <div class="brand">{{ $product->brand->name ?? 'Brand' }}</div>
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="short-description">
                                            {{ Str::limit(strip_tags($product->description), 60) }}
                                        </div>

                                        <div class="rating">
                                            @include('components.star-rating', [
                                                'rating' => $product->rounded_rating,
                                                'count' => $product->reviews_count,
                                                'size' => 'sm'
                                            ])
                                        </div>
                                    </div>

                                    <div class="product-actions">
                                        <a href="{{ route('detail', $product->slug) }}" class="detail-button">
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
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted">Chưa có sản phẩm nam nào.</p>
                            </div>
                        @endif
                    </div>

                    <button class="slider-nav-btn slider-next" aria-label="Next slide">
                        <svg viewBox="0 0 24 24">
                            <path d="M8.59 16.58L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.42z" />
                        </svg>
                    </button>

                    <div class="view-more-btn mt-4">
                        @if($categoryMan)
                            <a href="{{ route('client.products.index', ['category_id[]' => $categoryMan->id]) }}"
                                class="custom-learn-more-btn">
                                <span class="button-text">Xem thêm</span>
                                <div class="circle"></div>
                                <div class="arrow"></div>
                            </a>
                        @else
                            <a href="{{ route('client.products.index') }}" class="custom-learn-more-btn">
                                <span class="button-text">Xem thêm</span>
                                <div class="circle"></div>
                                <div class="arrow"></div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="product-section product-woman mt-5">
                {{-- ảnh banner --}}
                <div class="wide-banner">
                    <img src="storage/img/banner_womanShoe.jpg" alt="Banner" class="banner-image">
                    <div class="banner-overlay">
                        <h2>Thời trang Nữ</h2>
                    </div>
                </div>
                {{-- ảnh bên trái --}}
                <div class="two-column-layout">
                    <div class="tall-image d-none d-md-block">
                        <img src="storage/img/nu.jpg" alt="Featured Product">
                    </div>
                    {{-- list sản phẩm --}}
                    <div class="products-slider">
                        <button class="slider-nav-btn slider-prev" aria-label="Previous slide">
                            <svg viewBox="0 0 24 24">
                                <path d="M15.41 16.58L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.42z" />
                            </svg>
                        </button>

                        <div class="slider-container">
                            @if($productsWoman->count() > 0)
                                @foreach($productsWoman as $product)
                                    <div class="product-card">
                                        <!-- Your existing product card content -->
                                        <div class="image-container">
                                            <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}">
                                            <div class="price">{{ number_format($product->price) }}đ</div>
                                        </div>

                                        <form
                                            class="favorite-form{{ in_array($product->id, $wishlistProductIds) ? ' active' : '' }}"
                                            action="{{ route('wishlist.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="favorite-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 20a1 1 0 0 1-.437-.1C11.214 19.73 3 15.671 3 9a5 5 0 0 1 8.535-3.536l.465.465.465-.465A5 5 0 0 1 21 9c0 6.646-8.212 10.728-8.562 10.9A1 1 0 0 1 12 20z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>

                                        <div class="content_product">
                                            <div class="brand">{{ $product->brand->name ?? 'Brand' }}</div>
                                            <div class="product-name">{{ $product->name }}</div>
                                            <div class="short-description">
                                                {{ Str::limit(strip_tags($product->description), 60) }}
                                            </div>

                                            <div class="rating">
                                                @include('components.star-rating', [
                                                    'rating' => $product->rounded_rating,
                                                    'count' => $product->reviews_count,
                                                    'size' => 'sm'
                                                ])
                                            </div>
                                        </div>

                                        <div class="product-actions">
                                            <a href="{{ route('detail', $product->slug) }}" class="detail-button">
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
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <p class="text-muted">Chưa có sản phẩm nữ nào.</p>
                                </div>
                            @endif
                        </div>

                        <button class="slider-nav-btn slider-next" aria-label="Next slide">
                            <svg viewBox="0 0 24 24">
                                <path d="M8.59 16.58L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.42z" />
                            </svg>
                        </button>

                        <div class="view-more-btn mt-4">
                            @if($categoryWoman)
                                <a href="{{ route('client.products.index', ['category_id[]' => $categoryWoman->id]) }}"
                                    class="custom-learn-more-btn">
                                    <span class="button-text">Xem thêm</span>
                                    <div class="circle"></div>
                                    <div class="arrow"></div>
                                </a>
                            @else
                                <a href="{{ route('client.products.index') }}" class="custom-learn-more-btn">
                                    <span class="button-text">Xem thêm</span>
                                    <div class="circle"></div>
                                    <div class="arrow"></div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="top_brand mt-5">
                <!-- Updated banner with lines -->
                <div class="section-title-container">
                    <div class="section-title-line"></div>
                    <h2 class="section-title">THƯƠNG HIỆU NỔI BẬT</h2>
                    <div class="section-title-line"></div>
                </div>

                {{-- danh sách top brand --}}
                <div class="brands-container">
                    @if($brands->count() > 0)
                        @foreach($brands as $brand)
                            <div class="brand-card">
                                <div class="brand-image">
                                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="brand-logo">
                                    <div class="brand-name-container">
                                        <span class="brand-name">{{ Str::limit($brand->name, 20) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Chưa có thương hiệu nào.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="news mt-5">
                <!-- Updated banner with lines -->
                <div class="section-title-container">
                    <div class="section-title-line"></div>
                    <h2 class="section-title">TIN TỨC THỜI TRANG</h2>
                    <div class="section-title-line"></div>
                </div>

                @if($news->count() > 0)
                    <div class="news-slider-container">
                        <div class="news-slider-wrapper">
                            <button class="news-slider-prev" aria-label="Previous slide">
                                <svg viewBox="0 0 24 24" width="24" height="24">
                                    <path fill="currentColor" d="M15.41 16.58L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.42z" />
                                </svg>
                            </button>

                            <div class="news-slider">
                                @foreach($news as $post)
                                    <div class="news-slide">
                                        <div class="news-date-box">
                                            <span class="news-month">{{ $post->created_at->format('M') }}</span>
                                            <span class="news-date">{{ $post->created_at->format('d') }}</span>
                                        </div>

                                        <div class="news-image"
                                            style="background-image: url('{{ asset('storage/' . $post->thumbnail) }}')">
                                        </div>

                                        <div class="news-content">
                                            <span class="news-publish-date">{{ $post->created_at->format('F j, Y') }}</span>
                                            <h3 class="news-title">{{Str::limit(strip_tags($post->title), 100) }}</h3>
                                            <p class="news-excerpt">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                                            <a href="{{ route('posts.show', $post->slug) }}" class="readmore-btn">
                                                <span class="book-wrapper">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#ab4b38" viewBox="0 0 126 75"
                                                        class="book">
                                                        <rect stroke-width="3" stroke="#fff" rx="7.5" height="70" width="121"
                                                            y="2.5" x="2.5"></rect>
                                                        <line stroke-width="3" stroke="#fff" y2="75" x2="63.5" x1="63.5"></line>
                                                        <path stroke-linecap="round" stroke-width="4" stroke="#fff" d="M25 20H50">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-width="4" stroke="#fff" d="M101 20H76">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-width="4" stroke="#fff"
                                                            d="M16 30L50 30">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-width="4" stroke="#fff"
                                                            d="M110 30L76 30">
                                                        </path>
                                                    </svg>

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 65 75"
                                                        class="book-page">
                                                        <path stroke-linecap="round" stroke-width="4" stroke="#fff" d="M40 20H15">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-width="4" stroke="#fff"
                                                            d="M49 30L15 30">
                                                        </path>
                                                        <path stroke-width="3" stroke="#fff"
                                                            d="M2.5 2.5H55C59.1421 2.5 62.5 5.85786 62.5 10V65C62.5 69.1421 59.1421 72.5 55 72.5H2.5V2.5Z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span class="text">Đọc ngay</span>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button class="news-slider-next" aria-label="Next slide">
                                <svg viewBox="0 0 24 24" width="24" height="24">
                                    <path fill="currentColor" d="M8.59 16.58L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.42z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">Chưa có bài viết nào được xuất bản.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- thêm file js : public/build/js --}}
        <script src="{{ asset('build/js/home.js') }}"></script>
@endsection