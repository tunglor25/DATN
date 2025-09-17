@php
    $rating = $rating ?? 0;
    $size = $size ?? 'sm';
@endphp

<div class="star-rating d-flex justify-content-center align-items-center">
    <div class="stars">
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= $rating)
                {{-- Sao đầy --}}
                <i class="fas fa-star text-warning"></i>
            @elseif ($i - 0.5 <= $rating)
                {{-- Sao nửa --}}
                <i class="fas fa-star-half-alt text-warning"></i>
            @else
                {{-- Sao rỗng --}}
                <i class="far fa-star text-warning"></i>
            @endif
        @endfor
    </div>
</div>

<style>
.star-rating {
    width: 100%;
}

.star-rating .stars {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.star-rating .stars i {
    font-size: {{ $size == 'sm' ? '12px' : ($size == 'md' ? '14px' : '16px') }};
    margin-right: 1px;
}

.star-rating .stars i:last-child {
    margin-right: 0;
}
</style> 