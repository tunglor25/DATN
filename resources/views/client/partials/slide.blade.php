@if (isset($slides) && $slides->count())
    <div id="mainSlide" class="carousel slide mb-4" data-bs-ride="carousel" style="height: 500px;">
        <div class="carousel-indicators">
            @foreach ($slides as $index => $slide)
                <button type="button" data-bs-target="#mainSlide" data-bs-slide-to="{{ $index }}"
                    @if ($index === 0) class="active" aria-current="true" @endif
                    aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>

        <div class="carousel-inner h-100">
            @foreach ($slides as $index => $slide)
                <div class="carousel-item h-100 @if ($index === 0) active @endif">
                    <a href="{{ $slide->link ?? '#' }}">
                        <img src="{{ asset('storage/' . $slide->image) }}"
                            class="d-block w-100 h-100 .img-fill"
                            alt="{{ $slide->title ?? 'Slide' }}"
                            style="object-fit: cover;">
                    </a>
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#mainSlide" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainSlide" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
@endif