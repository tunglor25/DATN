<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as FacadesRequest;

class UserController extends Controller
{
    // Hiển thị danh sách tài khoản với các nút: khóa/mở, xóa, xem chi tiết
    public function index(Request $request)
    {
        $query = User::query();

        // Add search functionality
        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.user.index', compact('users'));
    }

    // Xem chi tiết tài khoản
    public function show(string $id, Request $request)
    {
        $user = User::findOrFail($id);
        $referer = $request->server('HTTP_REFERER');

        if ($referer) {
            // Nếu URL trước có chứa 'search=' thì lưu lại để quay về đúng trang kết quả tìm kiếm
            if (str_contains($referer, 'search=')) {
                session(['user_back_url' => $referer]);
            }
            // Nếu không có 'search=' nhưng đến từ trang danh sách user thì vẫn lưu
            elseif (str_contains($referer, route('admin.user.index'))) {
                session(['user_back_url' => $referer]);
            }
        }
        return view('admin.user.show', compact('user'));
    }

    // Hiển thị form chỉnh sửa tài khoản
    public function edit(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $referer = $request->server('HTTP_REFERER');
        if ($referer) {
            // Nếu đến từ trang có search hoặc là danh sách user thì lưu lại
            if (str_contains($referer, 'search=') || str_contains($referer, route('admin.user.index'))) {
                session(['user_back_url' => $referer]);
            }
        }
        return view('admin.user.edit', compact('user'));
    }

    // Cập nhật tài khoản
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        // Kiểm tra quyền thao tác
        if (!$currentUser->canManageUser($user)) {
            return redirect()->back()->with('error', 'Bạn không có quyền thao tác với tài khoản này.');
        }

        // Kiểm tra xem có phải admin đang thay đổi role của chính mình không
        if ($user->id === $currentUser->id) {
            return redirect()->back()->with('error', 'Bạn không thể thay đổi vai trò của chính mình.');
        }

        // Kiểm tra xem có phải đang thay đổi từ admin thành user không
        if ($user->role === 'admin' && $request->role === 'user') {
            // Đếm số lượng admin thường hiện tại (không tính super admin)
            $superAdminEmails = config('admin.super_admin_emails', ['minh662005@gmail.com']);
            $regularAdminCount = User::where('role', 'admin')
                ->whereNotIn('email', $superAdminEmails)
                ->count();
            
            // Nếu chỉ còn 1 admin thường và đang thay đổi admin đó thành user
            if ($regularAdminCount <= 1 && $user->isRegularAdmin()) {
                return redirect()->back()->with('error', 'Không thể thay đổi vai trò. Hệ thống cần ít nhất 1 Admin thường.');
            }
        }

        // Kiểm tra quyền tạo admin mới
        if ($request->role === 'admin' && !$currentUser->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Chỉ Super Admin mới có thể tạo tài khoản Admin.');
        }

        $validatedData = $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        $user->update($validatedData);
        
        // Lấy URL để quay lại, ưu tiên từ session, nếu không thì quay về danh sách user
        $backUrl = session('user_back_url', route('admin.user.index'));

        // Xóa session sau khi dùng
        session()->forget('user_back_url');

        return redirect($backUrl)->with('success', 'Cập nhật người dùng thành công.');
    }

    // Xóa tài khoản 
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        // Kiểm tra quyền
        if (!$currentUser->canManageUser($user)) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa tài khoản này.');
        }

        // Không cho phép xóa chính mình
        if ($user->id === $currentUser->id) {
            return redirect()->back()->with('error', 'Bạn không thể xóa tài khoản của chính mình.');
        }

        // Không cho phép xóa super admin
        if ($user->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Không thể xóa Super Admin.');
        }

        // Kiểm tra số lượng admin thường tối thiểu
        if ($user->isRegularAdmin()) {
            $superAdminEmails = config('admin.super_admin_emails', ['minh662005@gmail.com']);
            $regularAdminCount = User::where('role', 'admin')
                ->whereNotIn('email', $superAdminEmails)
                ->count();
            if ($regularAdminCount <= 1) {
                return redirect()->back()->with('error', 'Không thể xóa Admin. Hệ thống cần ít nhất 1 Admin thường.');
            }
        }

        $user->delete();
        return redirect()->route('admin.user.index')->with('success', 'Xóa người dùng thành công.');
    }

    // Khóa/mở tài khoản (chuyển đổi trạng thái)
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        // Kiểm tra quyền
        if (!$currentUser->canManageUser($user)) {
            return redirect()->back()->with('error', 'Bạn không có quyền thao tác với tài khoản này.');
        }

        // Không cho phép khóa chính mình
        if ($user->id === $currentUser->id) {
            return redirect()->back()->with('error', 'Bạn không thể khóa tài khoản của chính mình.');
        }

        // Không cho phép khóa super admin
        if ($user->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Không thể khóa Super Admin.');
        }

        // Kiểm tra số lượng admin thường active tối thiểu
        if ($user->isRegularAdmin() && $user->status === 'active') {
            $superAdminEmails = config('admin.super_admin_emails', ['minh662005@gmail.com']);
            $activeRegularAdminCount = User::where('role', 'admin')
                ->whereNotIn('email', $superAdminEmails)
                ->where('status', 'active')
                ->count();
            if ($activeRegularAdminCount <= 1) {
                return redirect()->back()->with('error', 'Không thể khóa Admin. Hệ thống cần ít nhất 1 Admin thường đang hoạt động.');
            }
        }

        try {
            $user->status = $user->status === 'active' ? 'banned' : 'active';
            $user->save();
            return redirect()->back()->with('success', 'Cập nhật trạng thái người dùng thành công.');
        } catch (\Exception $e) {
            Log::error('User status toggle failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Cập nhật trạng thái thất bại. Vui lòng thử lại.');
        }
    }
}