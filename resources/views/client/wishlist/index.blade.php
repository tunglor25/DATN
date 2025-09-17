
   
@extends('layouts.app_client')
@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item active">Sản phẩm yêu thích</li>
        </ol>
    </nav>

    <div class="row content">
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="user-profile-card">
                <div class="d-flex align-items-center mb-3">
                    <div class="user-avatar me-3"><i class="fas fa-user"></i></div>
                    <div>
                        <h6 class="mb-1 fw-bold">{{ Auth::user()->name }}</h6>
                        <small class="text-muted">{{ Auth::user()->phone }}</small>
                    </div>
                </div>
                <a href="{{ route('profile.index') }}" class="text-primary small text-decoration-none">Xem hồ sơ</a>
            </div>

            <div class="promo-card d-flex">
                <div class="flex-grow-1 me-3">
                    <p class="small fw-bold mb-2">Quý khách là thành viên tại TLO Fashion</p>
                    <p class="small text-muted mb-3">Quan tâm TLO Shop để kích hoạt điểm thưởng</p>
                </div>
                <div class="promo-image"><i class="fas fa-gift"></i></div>
            </div>

            <div class="sidebar">
            <ul class="nav-menu">
                <li><a href="{{ route('orders.index') }}"><i class="fas fa-box"></i> Đơn hàng của tôi</a></li>
                <li><a href="{{ route('wishlist.index') }}"><i class="fas fa-heart"></i> Sản phẩm yêu thích</a></li>
                <li><a href="{{ route('addresses.index') }}"><i class="fas fa-map-marker-alt"></i> Sổ địa chỉ</a></li>
                <li><a href="{{ route('discounts.my-discounts') }}"><i class="fas fa-wallet"></i> Mã của tôi</a></li>
                <li><a href="{{ route('logout') }}" class="text-danger"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
            </ul>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="main-content p-4">
                <h5 class="fw-bold mb-4">Danh sách sản phẩm yêu thích</h5>

                @if($wishlists->count())
                    <div class="products-slider">
                        <div class="slider-container">
                            @foreach($wishlists as $wishlist)
                                @php $product = $wishlist->product; @endphp
                                <div class="product-card">
                            <div class="image-container">
                                <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}">
                                <div class="price">{{ number_format($product->price) }}đ</div>
                            </div>

                            <form class="favorite-form" action="{{ route('wishlist.destroy', $wishlist->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="favorite-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#000000">
                                        <path
                                            d="M12 20a1 1 0 0 1-.437-.1C11.214 19.73 3 15.671 3 9a5 5 0 0 1 8.535-3.536l.465.465.465-.465A5 5 0 0 1 21 9c0 6.646-8.212 10.728-8.562 10.9A1 1 0 0 1 12 20z">
                                        </path>
                                    </svg>
                                </button>
                            </form>

                            <div class="content">
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
                        </div>

                    </div>
                @else
                    <p class="text-muted">Bạn chưa có sản phẩm yêu thích nào.</p>
                @endif
            </div>
        </div>
    </div>
</div>
 <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .content {
            border-radius: 10px;
            padding: 10px 0px;
        }

        .sidebar {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .user-profile-card {
            background-color: #fff5f5;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            background-color: #ff6b35;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .promo-card {
            background-color: #fff5f5;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ffe6e6;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-menu li {
            border-bottom: 1px solid #f0f0f0;
        }

        .nav-menu li:last-child {
            border-bottom: none;
        }

        .nav-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }

        .nav-menu a:hover {
            background-color: #f8f9fa;
            border-left: 3px solid #ff6b35;
        }

        .nav-menu i {
            margin-right: 12px;
            width: 20px;
            color: #666;
        }

        .main-content {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile-avatar-large {
            width: 120px;
            height: 120px;
            background-color: #ffdbcc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .profile-avatar-inner {
            width: 60px;
            height: 60px;
            background-color: #ff6b35;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .btn-edit {
            background-color: #dc3545;
            border-color: #dc3545;
            padding: 10px 30px;
            font-weight: 500;
        }

        .btn-promo {
            background-color: #dc3545;
            border-color: #dc3545;
            font-size: 12px;
            padding: 6px 12px;
        }

        .breadcrumb-item a {
            color: #007bff;
            text-decoration: none;
        }

        .form-label-custom {
            color: #666;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .promo-image {
            width: 50px;
            height: 60px;
            background: linear-gradient(45deg, #ff6b35, #ffa500);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        /* Khung chứa toàn bộ slider */
        .products-slider {
            width: 100%;
            height: 300px;
            flex-shrink: 0;
            position: relative;
        }

        /* Khung trượt ngang sản phẩm */
        .slider-container {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 20px;
        }

        /* Scrollbar tùy chỉnh */
        .slider-container::-webkit-scrollbar {
            height: 8px;
        }

        .slider-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .slider-container::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        .slider-container::-webkit-scrollbar-thumb:hover {
            background: var(--primary-hover);
        }

        /* Nút "Xem thêm" */
        .view-more-btn {
            display: flex;
            width: 220px;
            height: 40px;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
            margin: 20px auto 0;
            position: relative;
        }

        /* Thẻ sản phẩm */
        .product-card {
            --accent-color: var(--primary-color);
            position: relative;
            width: 240px;
            height: 300px;
            background: white;
            border-radius: var(--border-radius);
            padding: 0.3rem;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            flex: 0 0 auto;
            scroll-snap-align: start;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        /* Hình ảnh trong thẻ sản phẩm */
        .product-card .image-container {
            position: relative;
            width: 100%;
            height: 150px;
            border-radius: calc(var(--border-radius) - 2px);
            border-top-right-radius: 4rem;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .product-card .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .image-container img {
            transform: scale(1.05);
        }

        /* Giá sản phẩm */
        .product-card .price {
            position: absolute;
            right: 0.7rem;
            bottom: -0.5rem;
            background: white;
            color: var(--accent-color);
            font-weight: 900;
            font-size: 0.9rem;
            padding: 0.5rem 0.8rem;
            border-radius: 1rem;
            box-shadow: var(--box-shadow);
            z-index: 2;
            min-width: 60px;
            text-align: center;
        }

        /* Nút yêu thích */
        .favorite-form {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 3;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .product-card:hover .favorite-form {
            opacity: 1;
        }

        .favorite-btn {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 0;
            transition: all 0.3s ease;
        }

        .favorite-btn:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.1);
        }

        .favorite-btn svg {
            width: 18px;
            height: 18px;
            fill: #a8a8a8;
            transition: all 0.3s ease;
        }

        .favorite-btn:hover svg {
            fill: var(--primary-color);
            transform: scale(1.2);
        }

        /* Trạng thái đã thêm */
        .favorite-form.active .favorite-btn svg {
            fill: var(--primary-color);
        }

        /* Nội dung sản phẩm */
        .product-card .content {
            padding: 0 0.8rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .product-card:hover .content {
            transform: translateY(-10px);
        }

        .product-card .brand {
            font-weight: 900;
            color: #a6a6a6;
            font-size: 0.8rem;
            margin-bottom: 0.3rem;
        }

        .product-card .product-name {
            font-weight: 700;
            color: var(--dark-color);
            font-size: 0.9rem;
            margin: 0.3rem 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-card .short-description {
            color: #666;
            font-size: 0.75rem;
            line-height: 1.3;
            margin: 0.5rem 0;
            height: 35px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            transition: all 0.3s ease;
        }

        .product-card:hover .short-description {
            color: #444;
        }

        .product-card .rating {
            color: #a8a8a8;
            font-size: 0.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            margin-top: 0.3rem;
        }

        .product-card .rating svg {
            height: 12px;
        }

        /* Hành động (Xem chi tiết) */
        .product-actions {
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            display: flex;
            gap: 0.5rem;
            padding: 0 0.8rem 0.8rem;
            transition: all 0.3s ease;
            opacity: 0;
            background: white;
        }

        .product-card:hover .product-actions {
            bottom: 0;
            opacity: 1;
        }

        .detail-button {
            flex: 1;
            text-align: center;
            text-decoration: none;
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.5rem 0.8rem;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .detail-button:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
        }

        .detail-text {
            transition: all 0.3s ease;
            display: inline-block;
        }

        .detail-arrow {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            transform: translateX(-5px);
            opacity: 0;
        }

        .detail-button:hover .detail-arrow {
            transform: translateX(0);
            opacity: 1;
            animation: arrowBounce 0.6s infinite alternate;
        }

        @keyframes arrowBounce {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(3px);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .products-slider {
                width: 100%;
            }
            .product-card {
                width: 200px;
            }
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(successAlert);
                bsAlert.close();
            }, 3000);
        }
        const errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(errorAlert);
                bsAlert.close();
            }, 3000);
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection

