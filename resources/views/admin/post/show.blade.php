@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <h1 class="mb-4 text-center fw-bold">{{ $post->title }}</h1>
            <div class="card shadow-sm border-0 mb-4">
                @if ($post->thumbnail)
                    <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="Ảnh bài viết" class="card-img-top" style="object-fit:cover; max-height:350px;">
                @endif
                <div class="card-body">
                    <div class="mb-2 text-muted small text-center">
                        <span><i class="far fa-calendar-alt me-1"></i> {{ $post->published_at ? $post->published_at->format('d/m/Y') : 'Chưa đăng' }}</span>
                        <span class="mx-2">|</span>
                        <span>
                            @if($post->is_published)
                                <span class="badge bg-success">Công khai</span>
                            @else
                                <span class="badge bg-secondary">Riêng tư</span>
                            @endif
                        </span>
                    </div>
                    
                    <div class="mb-4" style="min-height: 300px;">
                        {!! $post->content !!}
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.post.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection