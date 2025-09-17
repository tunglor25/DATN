@extends('layouts.app')

@section('content')
    <h2 class="mb-4 text-center">Chỉnh sửa bài viết</h2>

    <form action="{{ route('admin.post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            {{-- Title --}}
            <div class="col-md-12 mb-3">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                    value="{{ old('title', $post->title) }}">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Slug --}}
            <div class="col-md-12 mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                    value="{{ old('slug', $post->slug) }}" placeholder="Nhập slug">
                @error('slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Content --}}
            <div class="col-md-12 mb-3">
                <label class="form-label">Nội dung</label>
                <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror"
                    placeholder="Nhập nội dung" style="min-height: 400px;">{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Thumbnail --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Hình ảnh</label>
                <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror"
                    accept="image/*">
                @error('thumbnail')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                {{-- Hiển thị ảnh hiện tại nếu có --}}
                @if ($post->thumbnail)
                    <div class="mt-2">
                        <p class="text-muted">Ảnh hiện tại:</p>
                        <img src="{{ Storage::url($post->thumbnail) }}" alt="Current thumbnail" class="img-thumbnail"
                            style="max-width: 200px; max-height: 200px;">
                    </div>
                @endif
            </div>

            {{-- Published Status --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="is_published" class="form-select @error('is_published') is-invalid @enderror">
                    <option value="1" {{ old('is_published', $post->is_published) == 1 ? 'selected' : '' }}>Công khai
                    </option>
                    <option value="0" {{ old('is_published', $post->is_published) == 0 ? 'selected' : '' }}>Riêng tư
                    </option>
                </select>
                @error('is_published')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            {{-- Published Date --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Ngày xuất bản</label>
                <input type="date" name="published_at"
                    class="form-control @error('published_at') is-invalid @enderror"
                    value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d') : '') }}">
                @error('published_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit Buttons --}}
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.post.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </div>
    </form>
@endsection

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
<script>
    function generateSlug(str) {
        return str
            .toLowerCase()
            .normalize('NFD') // loại bỏ dấu tiếng Việt
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '') // loại ký tự đặc biệt
            .trim()
            .replace(/\s+/g, '-') // khoảng trắng -> -
            .replace(/-+/g, '-'); // loại bỏ nhiều dấu - liên tiếp
    }

    $(document).ready(function() {
        console.log('Document ready');
        console.log('jQuery version:', $.fn.jquery);
        console.log('Summernote available:', typeof $.fn.summernote !== 'undefined');
        console.log('Content element exists:', $('#content').length > 0);
        
        // Đợi một chút để đảm bảo DOM đã load hoàn toàn
        setTimeout(function() {
            // Kiểm tra xem element có tồn tại không
            if ($('#content').length === 0) {
                console.error('Content element not found!');
                return;
            }
            
            // Summernote - phiên bản đơn giản để test
            try {
                $('#content').summernote({
                    height: 300,
                    placeholder: 'Nhập nội dung bài viết'
                });
                console.log('Summernote initialized successfully');
            } catch (error) {
                console.error('Error initializing Summernote:', error);
            }
        }, 100);

        // Tự động cập nhật slug theo title
        $('#title').on('input', function() {
            const title = $(this).val();
            const slug = generateSlug(title);
            $('input[name="slug"]').val(slug);
        });
    });
</script>
@endsection
