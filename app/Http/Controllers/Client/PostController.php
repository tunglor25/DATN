<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function newsPage()
    {
        $featuredPost = Post::where('is_published', true)
            ->latest('published_at')
            ->first();

        if (!$featuredPost) {
            return view('client.posts.index', [
                'featuredPost' => null,
                'postsNewest' => collect(),
                'previous' => null,
                'next' => null,
            ]);
        }

        // Bài trước / Bài sau
        $previous = Post::where('is_published', true)
            ->where('published_at', '<', $featuredPost->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        $next = Post::where('is_published', true)
            ->where('published_at', '>', $featuredPost->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        $postsNewest = Post::where('is_published', true)
            ->where('id', '!=', $featuredPost->id)
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('client.posts.index', compact('featuredPost', 'postsNewest', 'previous', 'next'));
    }

    public function show($slug)
    {
        $featuredPost = Post::where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $previous = Post::where('is_published', true)
            ->where('published_at', '<', $featuredPost->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        $next = Post::where('is_published', true)
            ->where('published_at', '>', $featuredPost->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        $postsNewest = Post::where('is_published', true)
            ->where('id', '!=', $featuredPost->id)
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('client.posts.index', compact('featuredPost', 'previous', 'next', 'postsNewest'));
    }
}
