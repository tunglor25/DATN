<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmail as CustomVerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // ... các use ở đầu file
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'status',
        'gender', // nếu có cột gender thì thêm vào
        'google_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail());
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
    public function orders()
{
    return $this->hasMany(Order::class);
}

    public function userDiscounts()
    {
        return $this->hasMany(UserDiscount::class);
    }

    public function availableDiscounts()
    {
        return $this->userDiscounts()
            ->where('status', 'active')
            ->with('discount')
            ->get()
            ->filter(function ($userDiscount) {
                return $userDiscount->discount->isValid();
            });
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    // Thêm các method phân quyền dựa trên email
    public function isSuperAdmin(): bool
    {
        // Lấy danh sách email Super Admin từ config
        $superAdminEmails = config('admin.super_admin_emails', [
            'minh662005@gmail.com',  // Email mặc định
        ]);
        
        return $this->role === 'admin' && in_array($this->email, $superAdminEmails);
    }

    public function isRegularAdmin(): bool
    {
        return $this->role === 'admin' && !$this->isSuperAdmin();
    }

    public function canManageUser(User $targetUser): bool
    {
        // Super admin có thể quản lý tất cả
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        // Admin thường chỉ có thể quản lý user thường
        if ($this->isRegularAdmin()) {
            return $targetUser->role === 'user';
        }
        
        return false;
    }

    public function canManageAdmin(User $targetAdmin): bool
    {
        // Chỉ super admin mới có thể quản lý admin khác
        return $this->isSuperAdmin() && !$targetAdmin->isSuperAdmin();
    }

    public function canBeModifiedBy(User $modifier): bool
    {
        // Super admin không thể bị sửa đổi bởi admin thường
        if ($this->isSuperAdmin() && !$modifier->isSuperAdmin()) {
            return false;
        }
        
        // Admin thường có thể bị sửa đổi bởi super admin
        if ($this->isRegularAdmin()) {
            return $modifier->isSuperAdmin();
        }
        
        // User thường có thể bị sửa đổi bởi cả admin và super admin
        return $modifier->role === 'admin';
    }
}
