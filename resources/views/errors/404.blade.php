<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700|Montserrat:400,700|Poppins:400,700"
        rel="stylesheet">
    <link href="https://db.onlinewebfonts.com/c/09d5772629cb46bc57208ddfd7b77f63?family=Carol+Gothic+W10+Regular"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('build/css/403.css') }}">
    <title>404 - Page Not Found</title>
</head>

<body>
    <div class="luxury-container">
        <div class="velvet-curtain"></div>

        <div class="error-header">
            <h1 class="error-code">404</h1>
            <h2 class="error-title">Trang Không Tồn Tại</h2>
            <p class="error-subtitle">
                Trang bạn đang tìm kiếm có thể đã bị di chuyển, xóa hoặc chưa bao giờ tồn tại.
                <span class="flashing">(Hoặc có thể nó đang trốn bạn?)</span>
            </p>
        </div>

        <div class="security-theater">
            <div class="security-gif" style="background-image: url('https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcjJtY2R5Z2R0dGJ5eXJ2Z2V1bWJ6ZzR6bGZ4eWQ3dGJzNnVqZ3R3biZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/14uQ3cOFteDaU/giphy.gif')"></div>

            <div class="security-details">
                <h3>Khám Phá Không Thành Công</h3>

                <div class="threat-meter">
                    <div class="threat-indicator" style="width: 30%; background: #4CAF50;"></div>
                    <div class="threat-level">MỨC ĐE DỌA THẤP</div>
                </div>

                <ul class="security-list">
                    <li>
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Bạn đang đi lạc vào vùng đất chưa được khám phá</span>
                    </li>
                    <li>
                        <i class="fas fa-compass"></i>
                        <span>Hãy thử sử dụng thanh điều hướng hoặc công cụ tìm kiếm</span>
                    </li>
                    <li>
                        <i class="fas fa-question-circle"></i>
                        <span>Nếu bạn tin đây là lỗi, vui lòng báo cáo với chúng tôi</span>
                    </li>
                </ul>

                <p class="humorous-note">
                    * Đừng lo, chúng tôi không ghi lại địa chỉ IP của bạn... có lẽ thôi.
                </p>
            </div>
        </div>

        <div class="action-buttons">
            <a href="{{ url('/') }}" class="btn-luxury btn-primary">
                <i class="fas fa-home"></i> &nbsp; Trở Về Trang Chủ
            </a>
            <a href="mailto:shoptlowd50@gmail.com" class="btn-luxury btn-secondary">
                <i class="fas fa-envelope"></i> &nbsp; Báo Cáo Lỗi
            </a>
        </div>

        <div class="legal-warning">
            <p>
                <i class="fas fa-exclamation-triangle"></i>
                CẢNH BÁO: Tiếp tục truy cập vào các trang không tồn tại có thể dẫn đến việc bạn phải đối mặt với
                <span class="gothic-text"><a href="https://youtu.be/dQw4w9WgXcQ?si=oHg5SJYRHA0FESXv">màn hình 404</a></span>
                vô tận.
            </p>
        </div>
    </div>
</body>

</html>