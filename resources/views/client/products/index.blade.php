@extends('layouts.app_client')

@section('content')
    <div class="">

        <div class="row">
            <div class="container">
                <img src="storage/img/collection.webp" alt="" class="mb-5">
                <div class="row" id="product-section">
                    {{-- BỘ LỌC --}}
                    <div class="col-md-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold text-muted">TÌM KIẾM THEO</span>
                            <button type="button" onclick="clearAllFilters()" class="btn btn-sm d-flex align-items-center"
                                style="background-color: #e78a8a; color: #fff; border-radius: 20px; padding: 0.15rem 0.6rem; font-size: 0.75rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                                    class="bi bi-x-circle me-1" viewBox="0 0 16 16">
                                    <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z" />
                                    <path
                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                </svg>
                                Xóa
                            </button>
                        </div>

                        <form method="GET" id="filter-form">
                            <div class="filter-wrapper">
                                {{-- DANH MỤC --}}
                                <div class="filter-section">
                                    <div class="filter-title" onclick="toggleSection(this)">
                                        DANH MỤC
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                            fill="currentColor" class="ms-1 filter-icon" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </div>

                                    <div class="filter-content">
                                        @foreach ($categories->where('parent_id', null) as $parent)
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input category-parent"
                                                    data-id="{{ $parent->id }}" name="category_id[]"
                                                    value="{{ $parent->id }}"
                                                    {{ is_array(request('category_id')) && in_array($parent->id, request('category_id')) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold">{{ $parent->name }}</label>
                                            </div>

                                            @foreach ($parent->children as $child)
                                                <div class="form-check ms-3">
                                                    <input type="checkbox" class="form-check-input category-child"
                                                        data-parent-id="{{ $parent->id }}" name="category_id[]"
                                                        value="{{ $child->id }}"
                                                        {{ is_array(request('category_id')) && in_array($child->id, request('category_id')) ? 'checked' : '' }}>
                                                    <label class="form-check-label">{{ $child->name }}</label>
                                                </div>
                                            @endforeach
                                        @endforeach

                                    </div>
                                </div>

                                {{-- THƯƠNG HIỆU --}}
                                <div class="filter-section">
                                    <div class="filter-title" onclick="toggleSection(this)">
                                        THƯƠNG HIỆU <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                            fill="currentColor" class="ms-1 filter-icon" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </div>
                                    <div class="filter-content">
                                        @foreach ($brands as $brand)
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" class="form-check-input me-2" name="brand_id[]"
                                                    value="{{ $brand->id }}"
                                                    {{ is_array(request('brand_id')) && in_array($brand->id, request('brand_id')) ? 'checked' : '' }}
                                                    onchange="submitFilter()">
                                                <img src="{{ asset('storage/' . $brand->logo) }}"
                                                    alt="{{ $brand->name }}" width="30" height="30" class="me-2">
                                                <label class="form-check-label">{{ $brand->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- GIÁ --}}
                                <div class="filter-section">
                                    <div class="filter-title" onclick="toggleSection(this)">
                                        GIÁ <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                            fill="currentColor" class="ms-1 filter-icon" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </div>
                                    <div class="filter-content">
                                        <input type="range" name="price_min" min="0" max="10000000" step="10000"
                                            value="{{ request('price_min', 0) }}"
                                            oninput="updatePriceInput('minPrice', this.value)" onchange="submitFilter()"
                                            class="form-range mb-1">

                                        <input type="range" name="price_max" min="0" max="10000000" step="10000"
                                            value="{{ request('price_max', 10000000) }}"
                                            oninput="updatePriceInput('maxPrice', this.value)" onchange="submitFilter()"
                                            class="form-range mb-2">

                                        <div class="d-flex justify-content-between">
                                            <input id="minPrice" class="form-control form-control-sm" readonly
                                                value="{{ request('price_min', 0) }}"> <span class="mx-2">đ</span>
                                            <input id="maxPrice" class="form-control form-control-sm" readonly
                                                value="{{ request('price_max', 10000000) }}"> <span
                                                class="mx-2">đ</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- lọc theo biến thể động --}}
                                @csrf
                                @foreach ($attributes as $attribute)
                                    <div class="filter-section mb-3">
                                        <div class="filter-title mb-2 d-flex align-items-center justify-content-between"
                                            onclick="toggleSection(this)">
                                            {{ strtoupper($attribute->name) }}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                fill="currentColor" class="ms-1 filter-icon" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                            </svg>
                                        </div>

                                        <div class="filter-content no-scroll">
                                            <div class="size-grid">
                                                @foreach ($attribute->values as $value)
                                                    @php
                                                        $isChecked =
                                                            isset(request('attributes')[$attribute->id]) &&
                                                            in_array($value->id, request('attributes')[$attribute->id]);
                                                    @endphp

                                                    <input type="checkbox" class="btn-check"
                                                        id="attr-{{ $value->id }}"
                                                        name="attributes[{{ $attribute->id }}][]"
                                                        value="{{ $value->id }}" {{ $isChecked ? 'checked' : '' }}
                                                        onchange="submitFilter()">

                                                    <label class="size-btn" for="attr-{{ $value->id }}">
                                                        {{ $value->value }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                    </div>

                    {{-- DANH SÁCH SẢN PHẨM  --}}
                    <div class="col-md-9">
                        {{-- SẮP XẾP --}}
                        <div class="d-flex justify-content-end align-items-center mb-3">
                            <form method="GET" id="sort-form" class="d-flex align-items-center">
                                <select name="sort" id="sort" class="form-select form-select-sm shadow-sm"
                                    style="width: auto; min-width: 140px; font-size: 0.85rem; padding: 0.2rem 0.5rem;"
                                    title="Sắp xếp theo">
                                    <option value="">Mới nhất</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá ↑
                                    </option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá
                                        ↓</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A→Z
                                    </option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên
                                        Z→A</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất
                                    </option>
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất
                                    </option>
                                    <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Tồn
                                        kho ↓</option>
                                </select>
                            </form>
                        </div>

                        {{-- VÙNG AJAX --}}
                        <div id="product-list-container">
                            <div class="product-list-container">
                                @forelse($products as $product)
                                    <div class="product-card">
                                        <div class="image-container">
                                            <img src="{{ asset('storage/' . $product->product_image) }}"
                                                alt="{{ $product->name }}">
                                            <div class="price">{{ number_format($product->price) }}đ</div>
                                        </div>

                                        <form class="favorite-form" action="{{ route('wishlist.store') }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="favorite-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="#000000">
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
                                                    'size' => 'sm',
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
                                @empty
                                    <p class="text-center text-muted">Không tìm thấy sản phẩm phù hợp.</p>
                                @endforelse
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $products->withQueryString()->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            function toggleSection(header) {
                const icon = header.querySelector('.filter-icon');
                const content = header.nextElementSibling;

                if (content.style.display === 'none' || !content.style.display) {
                    content.style.display = 'block';
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    content.style.display = 'none';
                    icon.style.transform = 'rotate(-90deg)';
                }
            }

            function submitFilter() {
                fetchProducts();
            }

            function updatePriceInput(id, value) {
                document.getElementById(id).value = parseInt(value).toLocaleString('vi-VN');
            }

            function fetchProducts(url = null) {
                let filterData = $('#filter-form, #sort-form').serialize();
                let fetchUrl = url ?? "{{ route('client.products.index') }}";

                $.ajax({
                    url: fetchUrl,
                    type: "GET",
                    data: filterData,
                    beforeSend: function() {
                        $('#product-list-container').html('<p class="text-center">Đang tải...</p>');
                    },

                    success: function(response) {
                        let newContent = $(response).find('#product-list-container').html();
                        $('#product-list-container').html(newContent);
                        history.pushState(null, '', '/products?' + filterData);
                        const productSection = document.getElementById('product-section');
                        if (productSection) {
                            const yOffset = -100;
                            const y = productSection.getBoundingClientRect().top + window.pageYOffset + yOffset;

                            window.scrollTo({
                                top: y,
                                behavior: 'smooth'
                            });
                        }
                    },
                    error: function() {
                        $('#product-list-container').html('<p class="text-danger">Lỗi khi tải sản phẩm.</p>');
                    }
                });
            }

            function clearAllFilters() {
                $('#filter-form')[0].reset();
                $('#sort').val('');
                $('#filter-form input[type="checkbox"]').prop('checked', false);
                $('#filter-form input[name="price_min"]').val(0);
                $('#filter-form input[name="price_max"]').val(10000000);
                updatePriceInput('minPrice', 0);
                updatePriceInput('maxPrice', 10000000);
                fetchProducts();
            }

            $(document).ready(function() {
                // Sắp xếp
                $('#sort').on('change', function(e) {
                    e.preventDefault();
                    fetchProducts();
                });
                // Phân trang
                $(document).on('click', '.pagination a', function(e) {
                    e.preventDefault();
                    let pageUrl = $(this).attr('href');
                    fetchProducts(pageUrl);
                });
                // Checkbox cha → con
                $('.category-parent').on('change', function() {
                    const parentId = $(this).data('id');
                    const isChecked = $(this).is(':checked');
                    // Bật/tắt tất cả con
                    $(`.category-child[data-parent-id="${parentId}"]`).prop('checked', isChecked);
                    submitFilter();
                });
                // Checkbox con → cập nhật cha
                $('.category-child').on('change', function() {
                    const parentId = $(this).data('parent-id');
                    const $children = $(`.category-child[data-parent-id="${parentId}"]`);
                    const $parent = $(`.category-parent[data-id="${parentId}"]`);

                    if ($children.length === $children.filter(':checked').length) {
                        // Nếu tất cả con được bật → cha bật
                        $parent.prop('checked', true);
                    } else {
                        // Nếu chưa chọn đủ hoặc bỏ chọn 1 → cha tắt
                        $parent.prop('checked', false);
                    }
                    submitFilter();
                });
            });
        </script>
    @endsection


    <style>
        /* lọc */
        form#sort-form select {
            border-radius: 20px;
            padding: 0.2rem 0.8rem;
            font-size: 0.85rem;
            background-color: #f8f8f8;
        }

        .size-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px 12px;
            margin-top: 10px;
            justify-items: center;
        }

        .size-btn {
            display: inline-block;
            width: 46px;
            height: 38px;
            line-height: 38px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            background-color: #fff;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .size-btn:hover {
            border-color: #999;
            background-color: #f8f8f8;
        }

        .btn-check:checked+.size-btn {
            background-color: #000000;
            color: #fff;
            font-weight: bold;
            border-color: #000000;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .size-btn:hover {
            border-color: #000000;
        }

        .filter-wrapper::-webkit-scrollbar {
            width: 6px;
        }

        .filter-wrapper::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 3px;
        }

        .filter-section {
            margin-bottom: 1rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 0.5rem;
        }

        .filter-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .filter-icon {
            font-size: 0.8rem;
            color: #888;
            margin-left: 8px;
            transition: transform 0.2s, color 0.2s;
        }

        .filter-title:hover .filter-icon {
            color: #e78a8a;
            transform: scale(1.2);
        }


        .filter-content {
            max-height: 150px;
            overflow-y: auto;
        }

        .filter-content.no-scroll {
            max-height: none;
            overflow: visible;
        }

        .filter-content::-webkit-scrollbar {
            width: 6px;
        }

        .filter-content::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        /* Thanh kéo màu hồng */
        input[type="range"].form-range::-webkit-slider-thumb {
            background-color: #e78a8a;
        }

        input[type="range"].form-range::-moz-range-thumb {
            background-color: #e78a8a;
        }

        input[type="range"].form-range::-ms-thumb {
            background-color: #e78a8a;
        }

        input[type="range"].form-range::-webkit-slider-runnable-track {
            background-color: #ffe5e5;
        }

        input[type="range"].form-range::-moz-range-track {
            background-color: #ffe5e5;
        }

        input[type="range"].form-range::-ms-track {
            background-color: #ffe5e5;
        }


        /* danh sách */
        form#sort-form select {
            border-radius: 8px;
            padding: 0.25rem 0.5rem;
            font-size: 0.85rem;
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');

        .product-list-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.5rem;
            font-family: 'Inter', sans-serif;
        }

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
    </style>
