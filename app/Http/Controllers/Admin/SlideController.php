<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SlideController extends Controller
{
    /**
     * Hiển thị danh sách slide
     */
    public function index()
    {
        $slides = Slide::orderBy('position', 'asc')->paginate(10);
        return view('admin.slide.index', compact('slides'));
    }

    /**
     * Thêm mới slide
     */
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'title' => 'required|string|max:255',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'link' => 'nullable|url|max:255',
    //         'position' => 'nullable|integer|min:1',
    //         'is_active' => 'boolean',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first()
    //         ], 422);
    //     }

    //     try {
    //         $data = $request->only(['title', 'link', 'position', 'is_active']);
    //         $data['is_active'] = $request->has('is_active') ? 1 : 0;

    //         if ($request->hasFile('image')) {
    //             $data['image'] = $request->file('image')->store('slides', 'public');
    //         }

    //         $slide = Slide::create($data);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Thêm slide thành công',
    //             'data' => [
    //                 'id' => $slide->id,
    //                 'title' => $slide->title,
    //                 'image' => $slide->image,
    //                 'image_url' => $slide->image ? asset('storage/' . $slide->image) : null,
    //                 'link' => $slide->link,
    //                 'position' => $slide->position,
    //                 'is_active' => $slide->is_active,
    //             ]
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Lỗi khi thêm slide: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
        public function store(Request $request)
    {
        $messages = [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.max' => 'Tiêu đề không được vượt quá :max ký tự.',
            'image.required' => 'Hình ảnh là bắt buộc.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'image.max' => 'Hình ảnh không được vượt quá 2MB.',
            'link.required' => 'Liên kết là bắt buộc.',
            'link.url' => 'Liên kết phải là một URL hợp lệ.',
            'link.max' => 'Liên kết không được vượt quá :max ký tự.',
            'position.required' => 'Vị trí là bắt buộc.',
            'position.integer' => 'Vị trí phải là một số nguyên.',
            'position.min' => 'Vị trí phải lớn hơn hoặc bằng :min.',
            'position.unique' => 'Vị trí đã được sử dụng. Vui lòng chọn vị trí khác.',
            'is_active.boolean' => 'Giá trị trạng thái không hợp lệ.',
        ];

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link' => 'required|url|max:255',
            'position' => 'required|integer|min:1|unique:slides,position',
            'is_active' => 'boolean',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $data = $request->only(['title', 'link', 'position', 'is_active']);
            $data['is_active'] = $request->boolean('is_active') ? 1 : 0;
            $data['image'] = $request->file('image')->store('slides', 'public');

            $slide = Slide::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Thêm slide thành công',
                'data' => [
                    'id' => $slide->id,
                    'title' => $slide->title,
                    'image' => $slide->image,
                    'image_url' => asset('storage/' . $slide->image),
                    'link' => $slide->link,
                    'position' => $slide->position,
                    'is_active' => $slide->is_active,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm slide: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật slide
     */
    // public function update(Request $request, Slide $slide)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'title' => 'required|string|max:255',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'link' => 'nullable|url|max:255',
    //         'position' => 'nullable|integer|min:1',
    //         'is_active' => 'boolean',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors()->first()
    //         ], 422);
    //     }

    //     try {
    //         $data = $request->only(['title', 'link', 'position', 'is_active']);
    //         $data['is_active'] = $request->boolean('is_active') ? 1 : 0;

    //         if ($request->hasFile('image')) {
    //             // Xóa hình ảnh cũ nếu tồn tại
    //             if ($slide->image) {
    //                 Storage::disk('public')->delete($slide->image);
    //             }
    //             $data['image'] = $request->file('image')->store('slides', 'public');
    //         }

    //         $slide->update($data);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Cập nhật slide thành công',
    //             'data' => [
    //                 'id' => $slide->id,
    //                 'title' => $slide->title,
    //                 'image' => $slide->image,
    //                 'image_url' => $slide->image ? asset('storage/' . $slide->image) : null,
    //                 'link' => $slide->link,
    //                 'position' => $slide->position,
    //                 'is_active' => $slide->is_active,
    //             ]
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Lỗi khi cập nhật slide: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
        public function update(Request $request, Slide $slide)
    {
        $messages = [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.max' => 'Tiêu đề không được vượt quá :max ký tự.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'image.max' => 'Hình ảnh không được vượt quá 2MB.',
            'link.required' => 'Liên kết là bắt buộc.',
            'link.url' => 'Liên kết phải là một URL hợp lệ.',
            'link.max' => 'Liên kết không được vượt quá :max ký tự.',
            'position.required' => 'Vị trí là bắt buộc.',
            'position.integer' => 'Vị trí phải là một số nguyên.',
            'position.min' => 'Vị trí phải lớn hơn hoặc bằng :min.',
            'position.unique' => 'Vị trí đã được sử dụng.',
            'is_active.boolean' => 'Giá trị trạng thái không hợp lệ.',
        ];

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link' => 'required|url|max:255',
            'position' => 'required|integer|min:1|unique:slides,position,' . $slide->id,
            'is_active' => 'boolean',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $data = $request->only(['title', 'link', 'position', 'is_active']);
            $data['is_active'] = $request->boolean('is_active') ? 1 : 0;

            if ($request->hasFile('image')) {
                if ($slide->image) {
                    Storage::disk('public')->delete($slide->image);
                }
                $data['image'] = $request->file('image')->store('slides', 'public');
            }

            $slide->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật slide thành công',
                'data' => [
                    'id' => $slide->id,
                    'title' => $slide->title,
                    'image' => $slide->image,
                    'image_url' => $slide->image ? asset('storage/' . $slide->image) : null,
                    'link' => $slide->link,
                    'position' => $slide->position,
                    'is_active' => $slide->is_active,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật slide: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa mềm slide
     */
    public function destroy(Slide $slide)
    {
        try {
            $slide->delete();
            return response()->json([
                'success' => true,
                'message' => 'Xóa slide thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa slide: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị danh sách slide đã xóa mềm
     */
    public function trash()
    {
        $slides = Slide::onlyTrashed()->orderBy('position', 'asc')->paginate(10);
        return view('admin.slide.trash', compact('slides'));
    }

    /**
     * Khôi phục slide
     */
    public function restore($id)
    {
        try {
            $slide = Slide::onlyTrashed()->findOrFail($id);
            $slide->restore();
            return redirect()->route('admin.slide.index')->with('success', 'Khôi phục slide thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.slide.trash')->with('error', 'Lỗi khi khôi phục slide: ' . $e->getMessage());
        }
    }

    /**
     * Xóa vĩnh viễn slide
     */
    public function forceDelete($id)
    {
        try {
            $slide = Slide::onlyTrashed()->findOrFail($id);
            if ($slide->image) {
                Storage::disk('public')->delete($slide->image);
            }
            $slide->forceDelete();
            return redirect()->route('admin.slide.trash')->with('success', 'Xóa vĩnh viễn slide thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.slide.trash')->with('error', 'Lỗi khi xóa vĩnh viễn slide: ' . $e->getMessage());
        }
    }
}
