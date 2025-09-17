<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class PostController extends Controller
{
    public function index()
    {
        $query = Post::query();

        $posts = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.post.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug',
            'content' => 'required|string',
            'thumbnail'  => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('thumbnail')) {
            $imgPath = $request->file('thumbnail')->store('thumbnail/posts', 'public');
            $validatedData['thumbnail'] = $imgPath;
        }

        Post::create($validatedData);

        return redirect()->route('admin.post.index')->with('success', 'Thêm bài viết thành công.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id);

        return view('admin.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);

        return view('admin.post.edit', compact('post'));
    }


    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

         $post->update([
            
            'slug' => Str::slug($request->name),
            
        ]);

        // Chỉ xử lý thumbnail khi có file mới được upload
        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ nếu có
            if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
                Storage::disk('public')->delete($post->thumbnail);
            }

            // Upload ảnh mới
            $imgPath = $request->file('thumbnail')->store('thumbnail/posts', 'public');
            $validatedData['thumbnail'] = $imgPath;
        } else {
            // Nếu không có file mới, loại bỏ thumbnail khỏi validatedData
            // để giữ nguyên giá trị cũ trong database
            unset($validatedData['thumbnail']);
        }

        $post->update($validatedData);

        return redirect()->route('admin.post.index')->with('success', 'Cập nhật bài viết thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.post.index')->with('success', 'Xóa bài viết thành công.');
    }

    public function trash()
    {
        $posts = Post::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);

        return view('admin.post.trash', compact('posts'));
    }

    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();

        return redirect()->route('admin.post.trash')->with('success', 'Khôi phục bài viết thành công.');
    }

    public function forceDelete($id)
    {
        $post = Post::withTrashed()->findOrFail($id);

        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        $post->forceDelete();

        return redirect()->route('admin.post.trash')->with('success', 'Xóa vĩnh viễn bài viết thành công.');
    }
}
