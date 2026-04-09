@extends('layouts.app_client')

@section('title', 'Sản phẩm - TLO Fashion')

@section('styles')
<style>
/* ===== PRODUCTS PAGE ===== */
.products-wrapper {
    font-family: 'Inter', sans-serif;
}

.products-hero-banner {
    width: 100%;
    max-width: 100%;
    position: relative;
    overflow: hidden;
    border-radius: 0;
    height: 320px;
}

.products-hero-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.products-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(15, 15, 15, 0.7), rgba(26, 26, 46, 0.5));
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 24px;
}

.products-hero-overlay .tlo-hero-badge {
    margin-bottom: 14px;
}

.products-hero-overlay h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 3.5vw, 2.6rem);
    font-weight: 700;
    color: #fff;
    margin: 0;
}

.products-hero-overlay p {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 10px;
}

/* Main Layout */
.products-main {
    max-width: 1360px;
    margin: 0 auto;
    padding: 32px 24px 60px;
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 32px;
    align-items: start;
}

/* Filter Sidebar */
.filter-sidebar {
    position: sticky;
    top: 92px;
}

.filter-card {
    background: var(--tlo-surface);
    border-radius: var(--tlo-radius-lg);
    border: 1px solid var(--tlo-border);
    box-shadow: var(--tlo-shadow-sm);
    padding: 20px;
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--tlo-border);
}

.filter-header-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem;
    font-weight: 600;
    color: var(--tlo-text-primary);
}

.filter-clear-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 14px;
    background: rgba(255, 107, 107, 0.08);
    border: 1px solid rgba(255, 107, 107, 0.15);
    border-radius: 100px;
    color: var(--tlo-accent);
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--tlo-transition);
}

.filter-clear-btn:hover {
    background: rgba(255, 107, 107, 0.15);
}

.filter-section {
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--tlo-border);
}

.filter-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.filter-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--tlo-text-primary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 4px 0;
    margin-bottom: 10px;
}

.filter-icon {
    font-size: 0.7rem;
    color: var(--tlo-text-light);
    transition: transform 0.2s;
}

.filter-title:hover .filter-icon {
    color: var(--tlo-accent);
}

.filter-content {
    max-height: 160px;
    overflow-y: auto;
}

.filter-content.no-scroll {
    max-height: none;
    overflow: visible;
}

.filter-content::-webkit-scrollbar {
    width: 4px;
}

.filter-content::-webkit-scrollbar-thumb {
    background: var(--tlo-border);
    border-radius: 4px;
}

.filter-content .form-check {
    padding: 3px 0 3px 1.6em;
}

.filter-content .form-check-input:checked {
    background-color: var(--tlo-accent);
    border-color: var(--tlo-accent);
}

.filter-content .form-check-input:focus {
    border-color: var(--tlo-accent);
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.15);
}

.filter-content .form-check-label {
    font-size: 0.85rem;
    color: var(--tlo-text-secondary);
    cursor: pointer;
}

/* Size & Attribute grid */
.size-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 4px;
}

.size-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 36px;
    padding: 0 12px;
    min-width: 48px;
    border: 1px solid var(--tlo-border);
    border-radius: 10px;
    font-size: 0.82rem;
    font-weight: 500;
    color: var(--tlo-text-secondary);
    background: var(--tlo-surface);
    cursor: pointer;
    transition: var(--tlo-transition);
    white-space: nowrap;
}

.size-btn:hover {
    border-color: var(--tlo-accent);
    color: var(--tlo-accent);
}

.btn-check:checked + .size-btn {
    background: var(--tlo-dark);
    color: #fff;
    border-color: var(--tlo-dark);
    font-weight: 600;
}

/* Price Range */
input[type="range"].form-range::-webkit-slider-thumb {
    background-color: var(--tlo-accent);
}
input[type="range"].form-range::-moz-range-thumb {
    background-color: var(--tlo-accent);
}
input[type="range"].form-range::-webkit-slider-runnable-track {
    background-color: rgba(255, 107, 107, 0.15);
}
input[type="range"].form-range::-moz-range-track {
    background-color: rgba(255, 107, 107, 0.15);
}

/* Sort Bar */
.products-sort-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 12px 16px;
    background: var(--tlo-surface);
    border-radius: var(--tlo-radius-md);
    border: 1px solid var(--tlo-border);
}

.products-sort-bar .result-count {
    font-size: 0.85rem;
    color: var(--tlo-text-secondary);
}

.products-sort-bar .result-count strong {
    color: var(--tlo-text-primary);
}

.sort-select {
    border: 1px solid var(--tlo-border);
    border-radius: 10px;
    padding: 6px 32px 6px 14px;
    font-size: 0.85rem;
    font-family: 'Inter', sans-serif;
    color: var(--tlo-text-primary);
    background: var(--tlo-surface);
    transition: var(--tlo-transition);
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
}

.sort-select:focus {
    border-color: var(--tlo-accent);
    box-shadow: 0 0 0 2px rgba(255, 107, 107, 0.1);
    outline: none;
}

/* Product Grid */
.product-list-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 20px;
}

/* Product Card - unified */
.product-card {
    --accent-color: var(--tlo-accent);
    position: relative;
    background: var(--tlo-surface);
    border-radius: 16px;
    padding: 0.3rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    transition: var(--tlo-transition);
    overflow: hidden;
    border: 1px solid var(--tlo-border);
    width: auto;
}

.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.09);
    border-color: rgba(255, 107, 107, 0.2);
}

.image-container {
    position: relative;
    width: 100%;
    height: 180px;
    border-radius: 14px 14px 14px 4rem;
    margin-bottom: 0.8rem;
    overflow: hidden;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.product-card:hover .image-container img {
    transform: scale(1.05);
}

.price {
    position: absolute;
    right: 0.7rem;
    bottom: -0.5rem;
    background: #fff;
    color: var(--accent-color);
    font-weight: 800;
    font-size: 0.85rem;
    padding: 6px 12px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    z-index: 2;
}

.favorite-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 3;
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
    opacity: 0;
    backdrop-filter: blur(4px);
}

.product-card:hover .favorite-btn {
    opacity: 1;
}

.favorite-btn svg {
    width: 18px;
    height: 18px;
    fill: #94a3b8;
    transition: var(--tlo-transition);
}

.favorite-btn:hover svg {
    fill: var(--tlo-accent);
    transform: scale(1.15);
}

.content {
    padding: 0 0.8rem;
    margin-bottom: 0.5rem;
}

.brand {
    font-weight: 600;
    color: var(--tlo-text-light);
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.product-name {
    font-weight: 700;
    color: var(--tlo-text-primary);
    font-size: 0.88rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 4px;
}

.short-description {
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

.rating {
    color: #94a3b8;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.3rem;
    margin-top: 4px;
}

.product-actions {
    position: absolute;
    bottom: -50px;
    left: 0;
    right: 0;
    display: flex;
    gap: 0.5rem;
    padding: 0 0.8rem 0.8rem;
    transition: all 0.35s ease;
    opacity: 0;
}

.product-card:hover .product-actions {
    bottom: 0;
    opacity: 1;
}

.detail-button {
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

.detail-button:hover {
    background: linear-gradient(135deg, #e85d5d, #d14e1e);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.35);
    color: #fff;
}

.detail-text { display: inline-block; }
.detail-arrow {
    width: 20px; height: 20px;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.3s; transform: translateX(-5px); opacity: 0;
}
.detail-button:hover .detail-arrow { transform: translateX(0); opacity: 1; }

/* Pagination */
.page-item .page-link {
    color: var(--tlo-accent);
    border-radius: 12px !important;
    margin: 0 3px;
    border: 1px solid var(--tlo-border);
    padding: 8px 14px;
    font-size: 0.85rem;
    font-weight: 500;
    transition: var(--tlo-transition);
}

.page-item .page-link:hover {
    background: rgba(255, 107, 107, 0.08);
    border-color: var(--tlo-accent);
}

.page-item.active .page-link {
    background: var(--tlo-accent);
    color: #fff;
    border-color: var(--tlo-accent);
}

.page-item.disabled .page-link {
    color: var(--tlo-text-light);
    background: var(--tlo-surface-alt);
}

/* Mobile Filter Toggle */
.mobile-filter-toggle {
    display: none;
    width: 100%;
    padding: 12px;
    background: var(--tlo-surface);
    border: 1px solid var(--tlo-border);
    border-radius: var(--tlo-radius-md);
    font-weight: 600;
    color: var(--tlo-text-primary);
    cursor: pointer;
    margin-bottom: 16px;
    gap: 8px;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .products-main {
        grid-template-columns: 240px 1fr;
        gap: 24px;
    }
}

@media (max-width: 768px) {
    .products-hero-banner {
        height: 200px;
    }

    .products-main {
        grid-template-columns: 1fr;
        padding: 20px 16px 40px;
    }

    .filter-sidebar {
        position: static;
    }

    .filter-card {
        display: none;
    }

    .filter-card.show-mobile {
        display: block;
    }

    .mobile-filter-toggle {
        display: flex;
    }

    .product-list-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .product-card {
        padding: 0.2rem;
    }

    .image-container {
        height: 140px;
    }
}

@media (max-width: 480px) {
    .product-list-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
}
</style>
@endsection

@section('content')
<div class="products-wrapper tlo-full-width">

    <!-- Hero Banner -->
    <div class="products-hero-banner">
        <img src="{{ asset('storage/img/collection.webp') }}" alt="Bộ sưu tập TLO Fashion">
        <div class="products-hero-overlay">
            <div class="tlo-hero-badge"><i class="fas fa-store"></i> Bộ Sưu Tập</div>
            <h1>Khám phá sản phẩm</h1>
            <p>Tìm kiếm phong cách hoàn hảo cho riêng bạn</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="products-main">
        <!-- Filter Sidebar -->
        <div class="filter-sidebar" id="product-section">
            <button class="mobile-filter-toggle" onclick="toggleMobileFilter()">
                <i class="fas fa-sliders-h"></i> Bộ lọc sản phẩm
            </button>

            <div class="filter-card" id="filterCard">
                <div class="filter-header">
                    <span class="filter-header-title"><i class="fas fa-filter" style="color: var(--tlo-accent); margin-right: 6px;"></i> Bộ lọc</span>
                    <button type="button" onclick="clearAllFilters()" class="filter-clear-btn">
                        <i class="fas fa-times-circle"></i> Xóa tất cả
                    </button>
                </div>

                <form method="GET" id="filter-form">
                    {{-- DANH MỤC --}}
                    <div class="filter-section">
                        <div class="filter-title" onclick="toggleSection(this)">
                            Danh mục
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="filter-icon" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
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
                            Thương hiệu
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="filter-icon" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                            </svg>
                        </div>
                        <div class="filter-content">
                            @foreach ($brands as $brand)
                                <div class="form-check d-flex align-items-center">
                                    <input type="checkbox" class="form-check-input me-2" name="brand_id[]"
                                        value="{{ $brand->id }}"
                                        {{ is_array(request('brand_id')) && in_array($brand->id, request('brand_id')) ? 'checked' : '' }}
                                        onchange="submitFilter()">
                                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" width="24" height="24" class="me-2" style="border-radius: 6px; object-fit: contain;">
                                    <label class="form-check-label">{{ $brand->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- GIÁ --}}
                    <div class="filter-section">
                        <div class="filter-title" onclick="toggleSection(this)">
                            Khoảng giá
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="filter-icon" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
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
                            <div class="d-flex justify-content-between gap-2">
                                <input id="minPrice" class="form-control form-control-sm" readonly value="{{ request('price_min', 0) }}" style="border-radius: 8px; font-size: 0.8rem;">
                                <span style="color: var(--tlo-text-light); font-size: 0.85rem;">—</span>
                                <input id="maxPrice" class="form-control form-control-sm" readonly value="{{ request('price_max', 10000000) }}" style="border-radius: 8px; font-size: 0.8rem;">
                            </div>
                        </div>
                    </div>

                    {{-- BIẾN THỂ ĐỘNG --}}
                    @csrf
                    @foreach ($attributes as $attribute)
                        <div class="filter-section">
                            <div class="filter-title" onclick="toggleSection(this)">
                                {{ $attribute->name }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="filter-icon" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                </svg>
                            </div>
                            <div class="filter-content no-scroll">
                                <div class="size-grid">
                                    @foreach ($attribute->values as $value)
                                        @php
                                            $isChecked = isset(request('attributes')[$attribute->id]) && in_array($value->id, request('attributes')[$attribute->id]);
                                        @endphp
                                        <input type="checkbox" class="btn-check" id="attr-{{ $value->id }}"
                                            name="attributes[{{ $attribute->id }}][]"
                                            value="{{ $value->id }}" {{ $isChecked ? 'checked' : '' }}
                                            onchange="submitFilter()">
                                        <label class="size-btn" for="attr-{{ $value->id }}">{{ $value->value }}</label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>

        <!-- Product Grid -->
        <div>
            <!-- Sort Bar -->
            <div class="products-sort-bar tlo-animate">
                <span class="result-count"><strong>{{ $products->total() }}</strong> sản phẩm</span>
                <form method="GET" id="sort-form">
                    <select name="sort" id="sort" class="sort-select" title="Sắp xếp theo">
                        <option value="">Mới nhất</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A → Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z → A</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Tồn kho giảm</option>
                    </select>
                </form>
            </div>

            <!-- Product List (AJAX zone) -->
            <div id="product-list-container">
                <div class="product-list-container">
                    @forelse($products as $product)
                        <div class="product-card tlo-animate">
                            <div class="image-container">
                                <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}">
                                <div class="price">{{ number_format($product->price) }}đ</div>
                            </div>

                            <form class="favorite-form" action="{{ route('wishlist.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="favorite-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#000000">
                                        <path d="M12 20a1 1 0 0 1-.437-.1C11.214 19.73 3 15.671 3 9a5 5 0 0 1 8.535-3.536l.465.465.465-.465A5 5 0 0 1 21 9c0 6.646-8.212 10.728-8.562 10.9A1 1 0 0 1 12 20z"></path>
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
                                            <path fill="currentColor" d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" />
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="tlo-empty" style="grid-column: 1 / -1;">
                            <div class="tlo-empty-icon"><i class="fas fa-search"></i></div>
                            <h3>Không tìm thấy sản phẩm</h3>
                            <p>Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4 tlo-pagination">
                    {{ $products->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function toggleMobileFilter() {
        const card = document.getElementById('filterCard');
        card.classList.toggle('show-mobile');
    }

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

    function submitFilter() { fetchProducts(); }

    function updatePriceInput(id, value) {
        document.getElementById(id).value = parseInt(value).toLocaleString('vi-VN');
    }

    function fetchProducts(url = null) {
        let filterData = $('#filter-form, #sort-form').serialize();
        let fetchUrl = url ?? "{{ route('client.products.index') }}";
        $.ajax({
            url: fetchUrl, type: "GET", data: filterData,
            beforeSend: function() {
                $('#product-list-container').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x" style="color: var(--tlo-accent);"></i></div>');
            },
            success: function(response) {
                let newContent = $(response).find('#product-list-container').html();
                $('#product-list-container').html(newContent);
                history.pushState(null, '', '/products?' + filterData);
                const productSection = document.getElementById('product-section');
                if (productSection) {
                    const yOffset = -100;
                    const y = productSection.getBoundingClientRect().top + window.pageYOffset + yOffset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            },
            error: function() {
                $('#product-list-container').html('<p class="text-danger text-center">Lỗi khi tải sản phẩm.</p>');
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
        $('#sort').on('change', function(e) { e.preventDefault(); fetchProducts(); });
        $(document).on('click', '.pagination a', function(e) { e.preventDefault(); fetchProducts($(this).attr('href')); });
        $('.category-parent').on('change', function() {
            const parentId = $(this).data('id');
            $(`.category-child[data-parent-id="${parentId}"]`).prop('checked', $(this).is(':checked'));
            submitFilter();
        });
        $('.category-child').on('change', function() {
            const parentId = $(this).data('parent-id');
            const $children = $(`.category-child[data-parent-id="${parentId}"]`);
            const $parent = $(`.category-parent[data-id="${parentId}"]`);
            $parent.prop('checked', $children.length === $children.filter(':checked').length);
            submitFilter();
        });
    });
</script>
@endsection
