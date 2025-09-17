@extends('layouts.app_client')

@section('content')
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            line-height: 1.6;
            background-color: #f8f8f8;
            color: #333;
        }

        .news-page {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        /* MAIN CONTENT */
        .news-main {
            flex: 3;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        #postThumbnail {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        #postTitle {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #222;
        }

        #postDate {
            font-size: 0.9rem;
            color: #888;
            margin-bottom: 20px;
        }

        #postContent {
            font-size: 1rem;
            color: #444;
        }

        /* SIDEBAR */
        .news-sidebar {
            flex: 1;
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .sidebar-header {
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .sidebar-list {
            list-style: none;
            padding-left: 0;
            margin-top: 15px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
        }

        .sidebar-list.open {
            max-height: 1000px;
        }

        .sidebar-link {
            display: block;
            color: black;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 12px;
            transition: color 0.3s;
        }

        .sidebar-link:hover {
            color: orange;

        }

        .published {
            font-style: italic;
            font-size: 0.85rem;
            /* hoặc 13px nếu bạn thích px */
            color: #666;
            /* tuỳ chọn để màu nhẹ hơn */
        }

        .post-navigation {
            width: 100%;
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .btn-prev,
        .btn-next {
            flex: 1;
            padding: 12px 20px;
            background-color: rgb(0, 147, 245);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
            transition: background 0.3s, transform 0.2s;
            cursor: pointer;
            display: inline-block;
        }

        .btn-prev:hover,
        .btn-next:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px) scale(1.03);
            color: #fff;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .post-navigation {
                flex-direction: column;
            }

            .btn-prev,
            .btn-next {
                width: 100%;
                margin-bottom: 8px;
            }
        }

        .no-posts-message {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-size: 1.1rem;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .news-page {
                flex-direction: column;
            }

            .news-sidebar {
                order: 2;
            }

            .news-main {
                order: 1;
            }

            .post-navigation {
                flex-direction: column;
            }

            .post-navigation button {
                width: 100%;
            }
        }
    </style>

    <div class="news-page">
        @if ($featuredPost)
            <!-- Main content -->
            <div class="news-main" id="postDetail">
                <img id="postThumbnail" src="{{ asset('storage/' . $featuredPost->thumbnail) }}">
                <h2 id="postTitle">{{ $featuredPost->title }}</h2>
                <div id="postDate">{{ $featuredPost->published_at->format('d/m/Y') }}</div>
                <p id="postContent">{!! $featuredPost->content !!}</p>
            </div>

            <!-- Sidebar -->
            <div class="news-sidebar">
                <div class="sidebar-header">
                    Bài viết mới nhất
                </div>
                <ul>
                    @foreach ($postsNewest as $post)
                        <li>
                            <a href="{{ route('posts.show', $post->slug) }}" class="sidebar-link">
                                {{ $post->title }}
                            </a>
                            <p class="published"> {{ $post->published_at->format('d/m/Y') }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="post-navigation" style="margin-top: 30px; display: flex; justify-content: space-between;">
                <div class="post-navigation">
                    @if ($previous)
                        <a href="{{ route('posts.show', $previous->slug) }}" class="btn-prev">← Bài trước</a>
                    @endif

                    @if ($next)
                        <a href="{{ route('posts.show', $next->slug) }}" class="btn-next">Bài sau →</a>
                    @endif
                </div>
            </div>
        @else
            <!-- Hiển thị thông báo khi không có bài viết -->
            <div class="news-main">
                <div class="no-posts-message">
                    <h3>Chưa có bài viết nào</h3>
                    <p>Hiện tại chưa có bài viết nào được xuất bản. Vui lòng quay lại sau!</p>
                </div>
            </div>
        @endif
    </div>



    {{-- <script>
        function toggleDropdown() {
            const dropdown = document.getElementById("sidebarDropdown");
            dropdown.classList.toggle("open");
        }

        @if ($featuredPost)
            let currentPostId = {{ $featuredPost->id }};
        @else
            let currentPostId = null;
        @endif

        document.addEventListener("DOMContentLoaded", function() {
            const links = document.querySelectorAll(".sidebar-link");

            links.forEach(link => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();
                    const postId = this.dataset.id;

                    fetch(`/tin-tuc/bai-viet/${postId}`)
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 404) {
                                    throw new Error('Bài viết không tồn tại');
                                }
                                throw new Error('Có lỗi xảy ra khi tải bài viết');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.error) {
                                throw new Error(data.error);
                            }
                            
                            const thumbnail = document.getElementById("postThumbnail");
                            const title = document.getElementById("postTitle");
                            const date = document.getElementById("postDate");
                            const content = document.getElementById("postContent");
                            
                            if (thumbnail && title && date && content) {
                                thumbnail.src = `/storage/${data.thumbnail}`;
                                title.textContent = data.title;
                                date.textContent = data.published_at;
                                content.innerHTML = data.content;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching post:', error);
                            alert(error.message || 'Có lỗi xảy ra khi tải bài viết.');
                        });
                });
            });
        });

        function navigatePost(direction) {
            if (currentPostId) {
                window.location.href = `/tin-tuc/dieu-huong/${currentPostId}/${direction}`;
            } else {
                alert('Không có bài viết nào để điều hướng.');
            }
        }
    </script> --}}
@endsection
