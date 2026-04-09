@extends('layouts.app_client')

@section('title', isset($featuredPost) && $featuredPost ? $featuredPost->title . ' - TLO Fashion' : 'Tin tức - TLO Fashion')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ===== RESET PARENT CONTAINER ===== */
.main-content:has(.news-page) {
    max-width: 100% !important;
    padding: 0 !important;
}

.news-page {
    font-family: 'Inter', sans-serif;
    --accent: #ff6b6b;
    --accent-dark: #e85d5d;
    --accent-gradient: linear-gradient(135deg, #ff6b6b, #ee5a24);
    --dark: #0f0f0f;
    --text-primary: #1a1a2e;
    --text-secondary: #64748b;
    --text-light: #94a3b8;
    --surface: #ffffff;
    --surface-alt: #f8fafc;
    --border: #e2e8f0;
    overflow-x: hidden;
}

/* ===== ANIMATIONS ===== */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(25px);
    transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
}
.animate-on-scroll.visible {
    opacity: 1;
    transform: translateY(0);
}
.animate-delay-1 { transition-delay: 0.1s; }
.animate-delay-2 { transition-delay: 0.2s; }
.animate-delay-3 { transition-delay: 0.3s; }

/* ===== HERO BANNER ===== */
.news-hero {
    position: relative;
    background: var(--dark);
    padding: 100px 24px 60px;
    overflow: hidden;
}

.news-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse at 20% 50%, rgba(255, 107, 107, 0.12) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 30%, rgba(238, 90, 36, 0.08) 0%, transparent 50%);
}

.news-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.news-hero-inner {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
    color: #fff;
}

.news-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    background: rgba(255, 107, 107, 0.15);
    border: 1px solid rgba(255, 107, 107, 0.3);
    border-radius: 100px;
    color: var(--accent);
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 20px;
}

.news-hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 700;
    margin-bottom: 16px;
    line-height: 1.25;
}

.news-hero-desc {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.6);
    line-height: 1.7;
    max-width: 560px;
    margin: 0 auto;
}


/* ===== MAIN LAYOUT ===== */
.news-layout {
    max-width: 1320px;
    margin: 0 auto;
    padding: 48px 24px 80px;
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 40px;
    align-items: start;
}


/* ===== ARTICLE CONTENT ===== */
.article-card {
    background: var(--surface);
    border-radius: 24px;
    border: 1px solid var(--border);
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}

.article-thumbnail {
    position: relative;
    width: 100%;
    max-height: 460px;
    overflow: hidden;
}

.article-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.6s ease;
}

.article-thumbnail:hover img {
    transform: scale(1.03);
}

.article-thumbnail::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(transparent, rgba(0,0,0,0.1));
    pointer-events: none;
}

.article-body {
    padding: 36px 40px 40px;
}

.article-meta {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.article-date {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.article-date i {
    color: var(--accent);
    font-size: 0.8rem;
}

.article-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 14px;
    background: rgba(255, 107, 107, 0.08);
    border: 1px solid rgba(255, 107, 107, 0.15);
    border-radius: 100px;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--accent);
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.article-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.6rem, 3vw, 2.25rem);
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 24px;
    line-height: 1.35;
}

.article-content {
    font-size: 1.02rem;
    color: #4a5568;
    line-height: 1.85;
}

.article-content p {
    margin-bottom: 1.2em;
}

.article-content h1,
.article-content h2,
.article-content h3,
.article-content h4 {
    font-family: 'Playfair Display', serif;
    font-weight: 600;
    color: var(--text-primary);
    margin-top: 1.8em;
    margin-bottom: 0.8em;
}

.article-content h2 { font-size: 1.6rem; }
.article-content h3 { font-size: 1.3rem; }

.article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    margin: 1.5em 0;
}

.article-content blockquote {
    border-left: 4px solid var(--accent);
    padding: 16px 24px;
    margin: 1.5em 0;
    background: var(--surface-alt);
    border-radius: 0 12px 12px 0;
    font-style: italic;
    color: var(--text-secondary);
}

.article-content ul, .article-content ol {
    padding-left: 1.5em;
    margin-bottom: 1.2em;
}

.article-content li {
    margin-bottom: 0.5em;
}

.article-content a {
    color: var(--accent);
    text-decoration: underline;
    text-underline-offset: 2px;
}

.article-content a:hover {
    color: var(--accent-dark);
}


/* ===== POST NAVIGATION ===== */
.post-nav {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    padding: 0 40px 40px;
}

.post-nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 18px 20px;
    background: var(--surface-alt);
    border: 1px solid var(--border);
    border-radius: 16px;
    text-decoration: none;
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.post-nav-item:hover {
    border-color: var(--accent);
    box-shadow: 0 4px 16px rgba(255, 107, 107, 0.08);
    transform: translateY(-2px);
    color: var(--text-primary);
}

.post-nav-item.next {
    text-align: right;
    flex-direction: row-reverse;
}

.post-nav-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 107, 107, 0.08);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
    font-size: 0.9rem;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.post-nav-item:hover .post-nav-icon {
    background: var(--accent-gradient);
    color: #fff;
}

.post-nav-label {
    font-size: 0.75rem;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin-bottom: 2px;
}

.post-nav-title {
    font-size: 0.9rem;
    font-weight: 600;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 280px;
}


/* ===== SIDEBAR ===== */
.news-sidebar {
    position: sticky;
    top: 92px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.sidebar-card {
    background: var(--surface);
    border-radius: 20px;
    border: 1px solid var(--border);
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
}

.sidebar-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 20px 24px 16px;
    border-bottom: 1px solid var(--border);
}

.sidebar-card-header i {
    color: var(--accent);
    font-size: 1rem;
}

.sidebar-card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.sidebar-card-body {
    padding: 16px 20px 20px;
}

/* Sidebar Post Items */
.sidebar-post {
    display: flex;
    gap: 14px;
    padding: 12px 4px;
    border-bottom: 1px solid var(--border);
    text-decoration: none;
    transition: all 0.3s ease;
    border-radius: 12px;
}

.sidebar-post:last-child {
    border-bottom: none;
}

.sidebar-post:hover {
    background: var(--surface-alt);
    padding-left: 8px;
    padding-right: 8px;
}

.sidebar-post-thumb {
    width: 72px;
    height: 72px;
    border-radius: 14px;
    overflow: hidden;
    flex-shrink: 0;
    border: 1px solid var(--border);
}

.sidebar-post-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.sidebar-post:hover .sidebar-post-thumb img {
    transform: scale(1.08);
}

.sidebar-post-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.sidebar-post-title {
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.4;
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.3s ease;
}

.sidebar-post:hover .sidebar-post-title {
    color: var(--accent);
}

.sidebar-post-date {
    font-size: 0.78rem;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 5px;
}

.sidebar-post-date i {
    font-size: 0.7rem;
    color: var(--accent);
}

/* About Box in Sidebar */
.sidebar-about {
    padding: 28px 24px;
    text-align: center;
    background: linear-gradient(135deg, #0f0f0f, #1a1a2e);
    color: #fff;
}

.sidebar-about-icon {
    width: 56px;
    height: 56px;
    margin: 0 auto 16px;
    background: var(--accent-gradient);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}

.sidebar-about h4 {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 8px;
}

.sidebar-about p {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
    line-height: 1.6;
    margin-bottom: 20px;
}

.sidebar-about-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: var(--accent-gradient);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.3);
}

.sidebar-about-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
    color: #fff;
}

/* Tags Box */
.sidebar-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.sidebar-tag-item {
    padding: 6px 16px;
    background: var(--surface-alt);
    border: 1px solid var(--border);
    border-radius: 100px;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--text-secondary);
    text-decoration: none;
    transition: all 0.3s ease;
}

.sidebar-tag-item:hover {
    background: rgba(255, 107, 107, 0.08);
    border-color: rgba(255, 107, 107, 0.2);
    color: var(--accent);
}


/* ===== EMPTY STATE ===== */
.news-empty {
    text-align: center;
    padding: 80px 24px;
    max-width: 500px;
    margin: 0 auto;
}

.news-empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 24px;
    background: rgba(255, 107, 107, 0.08);
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
    font-size: 2rem;
}

.news-empty h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 12px;
}

.news-empty p {
    font-size: 1rem;
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: 28px;
}

.news-empty-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    background: var(--accent-gradient);
    color: #fff;
    border-radius: 14px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.25);
}

.news-empty-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(255, 107, 107, 0.35);
    color: #fff;
}


/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .news-layout {
        grid-template-columns: 1fr;
        gap: 32px;
    }

    .news-sidebar {
        position: static;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .news-hero {
        padding: 80px 16px 40px;
    }

    .article-body {
        padding: 24px 20px 28px;
    }

    .post-nav {
        padding: 0 20px 28px;
        grid-template-columns: 1fr;
    }

    .news-sidebar {
        grid-template-columns: 1fr;
    }

    .article-title {
        font-size: 1.4rem;
    }
}
</style>
@endsection

@section('content')
<div class="news-page">

    <!-- ===== HERO ===== -->
    <section class="news-hero">
        <div class="news-hero-inner">
            <div class="news-hero-badge">
                <i class="fas fa-newspaper"></i> Tin tức & Blog
            </div>
            <h1 class="news-hero-title">Cập nhật xu hướng thời trang</h1>
            <p class="news-hero-desc">
                Khám phá những bài viết mới nhất về thời trang, phong cách và cảm hứng sống từ TLO Fashion
            </p>
        </div>
    </section>

    @if ($featuredPost)
        <!-- ===== MAIN LAYOUT ===== -->
        <div class="news-layout">
            <!-- Article -->
            <div class="article-card animate-on-scroll">
                @if($featuredPost->thumbnail)
                <div class="article-thumbnail">
                    <img src="{{ asset('storage/' . $featuredPost->thumbnail) }}" alt="{{ $featuredPost->title }}">
                </div>
                @endif

                <div class="article-body">
                    <div class="article-meta">
                        <span class="article-date">
                            <i class="fas fa-calendar-alt"></i>
                            {{ $featuredPost->published_at->format('d/m/Y') }}
                        </span>
                        <span class="article-tag">
                            <i class="fas fa-fire"></i> Tin mới
                        </span>
                    </div>
                    <h1 class="article-title">{{ $featuredPost->title }}</h1>
                    <div class="article-content">
                        {!! $featuredPost->content !!}
                    </div>
                </div>

                <!-- Navigation -->
                @if($previous || $next)
                <div class="post-nav">
                    @if($previous)
                    <a href="{{ route('posts.show', $previous->slug) }}" class="post-nav-item prev">
                        <div class="post-nav-icon"><i class="fas fa-arrow-left"></i></div>
                        <div>
                            <div class="post-nav-label">Bài trước</div>
                            <div class="post-nav-title">{{ $previous->title }}</div>
                        </div>
                    </a>
                    @else
                    <div></div>
                    @endif

                    @if($next)
                    <a href="{{ route('posts.show', $next->slug) }}" class="post-nav-item next">
                        <div class="post-nav-icon"><i class="fas fa-arrow-right"></i></div>
                        <div>
                            <div class="post-nav-label">Bài sau</div>
                            <div class="post-nav-title">{{ $next->title }}</div>
                        </div>
                    </a>
                    @else
                    <div></div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <aside class="news-sidebar">
                <!-- Latest Posts -->
                <div class="sidebar-card animate-on-scroll animate-delay-1">
                    <div class="sidebar-card-header">
                        <i class="fas fa-clock"></i>
                        <h3>Bài viết mới nhất</h3>
                    </div>
                    <div class="sidebar-card-body">
                        @foreach ($postsNewest as $post)
                        <a href="{{ route('posts.show', $post->slug) }}" class="sidebar-post">
                            <div class="sidebar-post-thumb">
                                <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}">
                            </div>
                            <div class="sidebar-post-info">
                                <div class="sidebar-post-title">{{ $post->title }}</div>
                                <div class="sidebar-post-date">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $post->published_at->format('d/m/Y') }}
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Tags -->
                <div class="sidebar-card animate-on-scroll animate-delay-2">
                    <div class="sidebar-card-header">
                        <i class="fas fa-tags"></i>
                        <h3>Chủ đề phổ biến</h3>
                    </div>
                    <div class="sidebar-card-body">
                        <div class="sidebar-tags">
                            <a href="{{ route('client.products.index') }}" class="sidebar-tag-item">Thời trang nam</a>
                            <a href="{{ route('client.products.index') }}" class="sidebar-tag-item">Thời trang nữ</a>
                            <a href="{{ route('client.products.index') }}" class="sidebar-tag-item">Xu hướng 2024</a>
                            <a href="{{ route('client.products.index') }}" class="sidebar-tag-item">Phong cách</a>
                            <a href="{{ route('client.products.index') }}" class="sidebar-tag-item">Mẹo phối đồ</a>
                            <a href="{{ route('client.products.index') }}" class="sidebar-tag-item">Street Style</a>
                        </div>
                    </div>
                </div>

                <!-- About Box -->
                <div class="sidebar-card animate-on-scroll animate-delay-3">
                    <div class="sidebar-about">
                        <div class="sidebar-about-icon"><i class="fas fa-store"></i></div>
                        <h4>TLO Fashion</h4>
                        <p>Khám phá bộ sưu tập thời trang mới nhất với hàng trăm sản phẩm độc đáo</p>
                        <a href="{{ route('client.products.index') }}" class="sidebar-about-btn">
                            <i class="fas fa-shopping-bag"></i> Mua sắm ngay
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    @else
        <!-- Empty State -->
        <div class="news-empty animate-on-scroll">
            <div class="news-empty-icon"><i class="fas fa-newspaper"></i></div>
            <h3>Chưa có bài viết nào</h3>
            <p>Hiện tại chưa có bài viết nào được xuất bản. Hãy quay lại sau để cập nhật những tin tức thời trang mới nhất!</p>
            <a href="{{ route('home') }}" class="news-empty-btn">
                <i class="fas fa-home"></i> Về Trang Chủ
            </a>
        </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });

    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
});
</script>
@endsection
