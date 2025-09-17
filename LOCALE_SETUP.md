# Cấu hình Locale Tiếng Việt

## Đã thực hiện

### 1. Tạo file validation messages tiếng Việt
- File: `lang/vi/validation.php`
- Chứa tất cả các thông báo lỗi validation bằng tiếng Việt
- Đã định nghĩa các attributes cho form địa chỉ

### 2. Cập nhật config/app.php
- Set locale mặc định: `'locale' => env('APP_LOCALE', 'vi')`
- Set fallback locale: `'fallback_locale' => env('APP_FALLBACK_LOCALE', 'vi')`
- Set faker locale: `'faker_locale' => env('APP_FAKER_LOCALE', 'vi_VN')`

### 3. Tạo middleware SetLocale
- File: `app/Http/Middleware/SetLocale.php`
- Tự động set locale tiếng Việt cho toàn bộ ứng dụng
- Đã đăng ký trong `bootstrap/app.php`

### 4. Cập nhật AddressController
- Thêm `app()->setLocale('vi')` trong các method store() và update()
- Đảm bảo validation messages hiển thị bằng tiếng Việt

### 5. Cập nhật form validation
- Loại bỏ toast notification
- Thêm client-side validation với thông báo tiếng Việt
- Cập nhật cả create.blade.php và edit.blade.php

## Cấu hình .env

Thêm các dòng sau vào file `.env`:

```env
APP_LOCALE=vi
APP_FALLBACK_LOCALE=vi
APP_FAKER_LOCALE=vi_VN
```

## Kết quả

- ✅ Tất cả thông báo lỗi validation hiển thị bằng tiếng Việt
- ✅ Không còn toast notification
- ✅ Client-side validation với thông báo tiếng Việt
- ✅ Server-side validation với thông báo tiếng Việt
- ✅ Locale được set tự động cho toàn bộ ứng dụng

## Ví dụ thông báo lỗi

Thay vì:
- "The receiver name field is required."
- "The receiver phone field is required."

Bây giờ hiển thị:
- "Trường họ và tên là bắt buộc."
- "Trường số điện thoại là bắt buộc."
