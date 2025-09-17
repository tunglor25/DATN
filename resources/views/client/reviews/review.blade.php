@extends('layouts.app_client')

@section('title', 'Đánh giá sản phẩm')

@section('content')
    <div class="container my-5">
        <h3 class="mb-5 text-center text-uppercase fw-bold text-pink">Đánh giá sản phẩm</h3>

        <div class="card shadow rounded-4 border-0 overflow-hidden mx-auto" style="max-width: 700px;">
            <div class="card-body p-4">

                <div class="row align-items-center mb-5">
                    {{-- Ảnh sản phẩm --}}
                    <div class="col-md-5 text-center mb-3 mb-md-0">
                        <img src="{{ $product->product_image ? Storage::url($product->product_image) : asset('images/no-image.png') }}"
                            class="img-fluid rounded-3" style="max-height: 250px; object-fit: cover;"
                            alt="{{ $product->name }}">
                    </div>

                    {{-- Thông tin sản phẩm --}}
                    <div class="col-md-7">
                        <h4 class="fw-bold text-uppercase text-dark">{{ $product->name }}</h4>
                        <p class="mb-2"><span class="fw-semibold">Giá:</span>
                            <span class="text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                        </p>
                        <p class="mb-0"><span class="fw-semibold">Mô tả:</span><br>
                        <div style="max-height: 120px; overflow-y: auto;">
                            {!! $product->description ?? 'Không có mô tả' !!}
                        </div>
                        </p>
                    </div>
                </div>

                {{-- Form đánh giá --}}
                <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data"
                    class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                    {{-- Đánh giá sao --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Số sao đánh giá</label>
                        <div id="star-rating" style="font-size: 1.8rem; color: #ccc; cursor: pointer;">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-regular fa-star mx-1" data-value="{{ $i }}"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating" required>
                        @error('rating') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Thông tin đơn hàng --}}
                    <div class="mb-4">
                        <div class="alert alert-info">
                            <strong>Đánh giá cho đơn hàng:</strong> #{{ $order->order_number }}
                            <br>
                            <small class="text-muted">Ngày đặt: {{ $order->created_at->format('d/m/Y') }}</small>
                        </div>
                    </div>

                    {{-- Nội dung --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nội dung đánh giá</label>
                        <textarea name="comment" class="form-control rounded-3" rows="4"
                            placeholder="Nhập cảm nhận của bạn..." required></textarea>
                        @error('comment') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Ảnh minh hoạ --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Ảnh minh họa (tùy chọn)</label>
                        <input type="file" name="images[]" class="form-control rounded-3" multiple>
                        @error('images.*') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Nút gửi --}}
                    <div class="text-end">
                        <a href="{{ route('detail', ['product' => $product->slug]) }}" class="btn btn-outline-secondary me-2">Hủy</a>
                        <button type="submit" class="btn btn-pink px-4 py-2 rounded-pill">
                            <i class="fa-solid fa-paper-plane me-1"></i> Gửi đánh giá
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        // Star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('#star-rating i');
            const ratingInput = document.getElementById('rating');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    ratingInput.value = value;
                    
                    // Update star display
                    stars.forEach((s, index) => {
                        if (index < value) {
                            s.className = 'fa-solid fa-star mx-1';
                            s.style.color = '#ffc107';
                        } else {
                            s.className = 'fa-regular fa-star mx-1';
                            s.style.color = '#ccc';
                        }
                    });
                });

                star.addEventListener('mouseenter', function() {
                    const value = this.getAttribute('data-value');
                    stars.forEach((s, index) => {
                        if (index < value) {
                            s.style.color = '#ffc107';
                        }
                    });
                });

                star.addEventListener('mouseleave', function() {
                    const currentRating = ratingInput.value;
                    stars.forEach((s, index) => {
                        if (index < currentRating) {
                            s.style.color = '#ffc107';
                        } else {
                            s.style.color = '#ccc';
                        }
                    });
                });
            });
        });
    </script>
@endsection