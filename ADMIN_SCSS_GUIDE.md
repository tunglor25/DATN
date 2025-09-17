# 🎨 Hướng dẫn sử dụng SCSS CDN cho Admin Panel

## 📋 Tổng quan

File `resources/css/admin-custom.scss` chứa toàn bộ styling custom cho admin panel với bảng màu hiện đại và các component đẹp mắt.

## 🚀 Cách sử dụng

### **Phương pháp 1: Sử dụng CDN đã compile**

1. **Copy nội dung SCSS** từ `resources/css/admin-custom.scss`
2. **Paste vào các công cụ online:**
   - [SassMeister](https://www.sassmeister.com/)
   - [CodePen](https://codepen.io/)
   - [JSFiddle](https://jsfiddle.net/)

3. **Lấy CSS đã compile** và thêm vào layout admin:

```html
<!-- Trong resources/views/layouts/app.blade.php -->
<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Admin CSS -->
    <style>
        /* Paste CSS đã compile từ SCSS vào đây */
    </style>
</head>
```

### **Phương pháp 2: Sử dụng Vite (Khuyến nghị)**

1. **Cài đặt SASS:**
```bash
npm install sass
```

2. **Cập nhật vite.config.js:**
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin-custom.scss', // Thêm dòng này
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
```

3. **Import trong layout admin:**
```html
<!-- Trong resources/views/layouts/app.blade.php -->
<head>
    @vite(['resources/css/admin-custom.scss'])
</head>
```

### **Phương pháp 3: Sử dụng Laravel Mix (Nếu có)**

1. **Cài đặt dependencies:**
```bash
npm install sass sass-loader
```

2. **Cập nhật webpack.mix.js:**
```javascript
const mix = require('laravel-mix');

mix.sass('resources/css/admin-custom.scss', 'public/css')
   .version();
```

3. **Import trong layout:**
```html
<link href="{{ mix('css/admin-custom.css') }}" rel="stylesheet">
```

## 🎨 Bảng màu

### **Primary Colors:**
- **Primary**: `#667eea` (Xanh dương tím)
- **Secondary**: `#f093fb` (Hồng tím)
- **Success**: `#48bb78` (Xanh lá)
- **Warning**: `#ed8936` (Cam)
- **Danger**: `#f56565` (Đỏ)
- **Info**: `#4299e1` (Xanh dương)

### **Gradients:**
- **Primary Gradient**: `linear-gradient(135deg, #667eea 0%, #f093fb 100%)`
- **Success Gradient**: `linear-gradient(135deg, #48bb78 0%, #38a169 100%)`
- **Danger Gradient**: `linear-gradient(135deg, #f56565 0%, #e53e3e 100%)`

## 🧩 Components có sẵn

### **1. Sidebar**
```html
<div class="sidebar">
    <div class="sidebar-header">
        <h3>TLO Admin</h3>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link active" href="#"><i class="fas fa-dashboard"></i>Dashboard</a>
    </nav>
</div>
```

### **2. Stats Cards**
```html
<div class="stats-card">
    <div class="stats-icon bg-primary">
        <i class="fas fa-shopping-cart"></i>
    </div>
    <div class="stats-number">1,234</div>
    <div class="stats-label">Đơn hàng</div>
</div>
```

### **3. Custom Buttons**
```html
<button class="btn btn-primary">Primary Button</button>
<button class="btn btn-outline-primary">Outline Button</button>
```

### **4. Custom Tables**
```html
<table class="table">
    <thead>
        <tr>
            <th>Header</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Content</td>
        </tr>
    </tbody>
</table>
```

### **5. Custom Cards**
```html
<div class="card">
    <div class="card-header">
        <h5>Card Title</h5>
    </div>
    <div class="card-body">
        Card content
    </div>
</div>
```

## 🎭 Animation Classes

### **Fade In Up:**
```html
<div class="fade-in-up">Content with animation</div>
```

### **Slide In Right:**
```html
<div class="slide-in-right">Content with animation</div>
```

## 🎯 Utility Classes

### **Text Gradient:**
```html
<h1 class="text-gradient-primary">Gradient Text</h1>
```

### **Border Gradient:**
```html
<div class="border-gradient-primary">Gradient Border</div>
```

### **Custom Shadow:**
```html
<div class="shadow-custom">Custom Shadow</div>
```

## 📱 Responsive Design

SCSS đã bao gồm responsive design cho:
- **Mobile**: < 768px
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px

## 🌙 Dark Mode

Hỗ trợ dark mode tự động với `@media (prefers-color-scheme: dark)`.

## 🔧 Tùy chỉnh

### **Thay đổi màu sắc:**
```scss
// Trong admin-custom.scss
$primary-color: #your-color;
$secondary-color: #your-color;
```

### **Thay đổi font:**
```scss
body {
    font-family: 'Your Font', sans-serif;
}
```

### **Thay đổi border radius:**
```scss
.card {
    border-radius: 1rem; // Thay đổi giá trị này
}
```

## 📁 File Structure

```
resources/
├── css/
│   ├── app.css
│   └── admin-custom.scss    # SCSS cho admin
├── views/
│   └── layouts/
│       └── app.blade.php    # Layout admin
└── admin-demo.html          # Demo file
```

## 🚀 Deployment

### **Development:**
```bash
npm run dev
```

### **Production:**
```bash
npm run build
```

## 📞 Hỗ trợ

Nếu gặp vấn đề:
1. Kiểm tra console browser
2. Đảm bảo đã cài đặt SASS
3. Kiểm tra đường dẫn file
4. Clear cache: `php artisan cache:clear`

## 🎨 Preview

Mở file `admin-demo.html` trong browser để xem preview của admin panel với styling mới.

---

**Lưu ý:** Đảm bảo backup file gốc trước khi thay đổi để có thể rollback nếu cần.
