# Hướng Dẫn Cài Đặt Dự Án (Cho Developer)

Tài liệu này cung cấp các bước chi tiết để một lập trình viên (Developer) có thể tải dự án này về và cài đặt chạy trên máy cá nhân (Local Environment).

## 📌 Yêu Cầu Hệ Thống (Prerequisites)

Trước khi cài đặt, hãy đảm bảo máy tính của bạn đã được cài đặt sãn các phần mềm sau:

- **PHP** >= 8.2 (Khuyến nghị dùng Laragon hoặc XAMPP).
- **Composer** (Trình quản lý thư viện PHP).
- **Node.js & npm** (Dùng để build Frontend/Vite).
- **MySQL / MariaDB** (Hệ quản trị cơ sở dữ liệu).
- **Git** (Dùng để clone dự án).

---

## 🚀 Các Bước Cài Đặt Chi Tiết

### Bước 1: Tải dự án về máy (Clone project)

Mở Terminal / Git Bash tại thư mục bạn muốn lưu dự án (ví dụ `htdocs` của XAMPP hoặc `www` của Laragon) và chạy lệnh:

```bash
git clone https://github.com/tunglor25/DATN.git
cd DATN-1
```

### Bước 2: Cài đặt thư viện PHP (Composer)

Cài đặt toàn bộ các packages / vendor mà dự án yêu cầu (như Laravel Framework, Socialite, v.v.):

```bash
composer install
```

### Bước 3: Cài đặt thư viện Frontend (NPM)

Dự án sử dụng Vite để biên dịch tài nguyên (CSS, JS). Bạn cần tải xuống thư viện bằng lệnh:

```bash
npm install
```

### Bước 4: Khởi tạo tệp tin cấu hình Môi trường (.env)

Copy file cấu hình mẫu `.env.example` thành file `.env` chạy chính thức:

```bash
cp .env.example .env
```

_(Nếu bạn dùng Windows PowerShell hoặc CMD, bạn có thể copy bằng tay tệp tin và đổi tên lại thành `.env`)_

### Bước 5: Tạo APP_KEY bảo mật

Chạy lệnh sau để Laravel cấp một mã bảo mật ngẫu nhiên vào file `.env` của bạn:

```bash
php artisan key:generate
```

### Bước 6: Cấu hình Cơ sở dữ liệu (Database)

1. Mở phần mềm quản lý Database (như phpMyAdmin, HeidiSQL, DBeaver) và tạo một database mới (ví dụ: `datn_db`).
2. Mở file `.env` bằng code editor và chỉnh sửa các dòng sau cho khớp với máy của bạn:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=datn_db    # Tên database bạn vừa tạo
DB_USERNAME=root       # Mặc định thường là root
DB_PASSWORD=           # Bỏ trống nếu MySQL không có mật khẩu
```

### Bước 7: Tạo bảng và Nhập dữ liệu giả lập (Migrate & Seed)

Chạy lệnh sau để xây dựng cấu trúc các bảng trong Database và chèn (Seed) danh sách tài khoản, sản phẩm, bài viết và cấu hình mẫu (Tất cả Dữ liệu mẫu đã được sao lưu toàn vẹn có sẵn ảnh thật):

```bash
php artisan migrate:fresh --seed
```

_Lưu ý: Lệnh này sẽ **xoá sạch** dữ liệu cũ (nếu có) và khởi tạo lại toàn bộ database từ số không cùng với Seeders._

### Bước 8: Link thư mục Storage (Hình ảnh)

Để tranh bị lỗi không hiển thị hình ảnh tải lên do vấn đề phân quyền public, chạy lệnh:

```bash
php artisan storage:link
```

---

## 🏃 Khởi Chạy Dự Án

Để có thể xem được giao diện chuẩn xác nhất, bạn cần mở **2 cửa sổ Terminal** cùng lúc song song.

**Terminal 1:** Khởi động máy chủ Server PHP (Backend Laravel)

```bash
php artisan serve
```

**Terminal 2:** Khởi động máy chủ Node (Dịch CSS/JS của Frontend Vite)

```bash
npm run dev
```

🌐 **Xem thành quả:**
Mở trình duyệt web và truy cập vào địa chỉ: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🛠️ Một số lệnh hữu ích thường dùng:

- Xóa bộ nhớ đệm (Cache) của cấu hình và Views:
  `php artisan optimize:clear`
- Nếu bạn có thay đổi cấu hình trong file `.env`, luôn nhớ chạy:
  `php artisan config:clear`

## 🔑 Tài khoản Test Mặc định (Tùy chỉnh nếu có)

(Vui lòng cập nhật thông tin tài khoản Admin và Customer mặc định vào đây để dev khác tiện đăng nhập sau khi db:seed).

- **Admin**: admin@example.com / Password: password
- **User**: user@example.com / Password: password
  _(Ghi chú: Thay đổi email trên nếu UserSeeder của bạn tạo ra thông tin khác)._
