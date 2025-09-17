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
    {{-- resources/css/403.css --}}
    <link rel="stylesheet" href="{{ asset('build/css/403.css') }}">
    <title>403 - VIP Access Required</title>

</head>

<body>
    <div class="luxury-container">
        <div class="velvet-curtain"></div>

        <div class="error-header">
            <h1 class="error-code">403</h1>
            <h2 class="error-title">Cần Quyền Truy Cập Đặc Biệt</h2>
            <p class="error-subtitle">
                Bạn đã cố gắng truy cập vào khu vực bị hạn chế và hành động này đã được ghi lại, báo cáo đến đội an ninh
                mạng của chúng tôi.
                <span class="flashing">(Chỉ đùa thôi... hoặc cũng có thể là thật?)</span>
            </p>
        </div>

        <div class="security-theater">
            <div class="security-gif"></div>

            <div class="security-details">
                <h3>Vi Phạm Giao Thức Bảo Mật</h3>

                <div class="threat-meter">
                    <div class="threat-indicator"></div>
                    <div class="threat-level">MỨC ĐE DỌA CAO</div>
                </div>

                <ul class="security-list">
                    <li>
                        <i class="fas fa-user-secret"></i>
                        <span>Phát hiện truy cập trái phép từ địa chỉ IP của bạn</span>
                    </li>
                    <li>
                        <i class="fas fa-shield-alt"></i>
                        <span>Hệ thống bảo mật đã được cảnh báo về sự hiện diện của bạn</span>
                    </li>
                    <li>
                        <i class="fas fa-gavel"></i>
                        <span>Người vi phạm sẽ bị bắt gỡ lỗi mã IE6 cũ kỹ</span>
                    </li>
                </ul>

                <p class="humorous-note">
                    * Đừng lo, chúng tôi có thể sẽ không thả Hacker lor ra đâu. Có thể thôi.
                </p>
            </div>
        </div>

        <div class="action-buttons">
            <a href="{{ url('/') }}" class="btn-luxury btn-primary">
                <i class="fas fa-home"></i> &nbsp; Trở Về Trang Chủ
            </a>
            <a href="mailto:shoptlowd50@gmail.com" class="btn-luxury btn-secondary">
                <i class="fas fa-envelope"></i> &nbsp; Xin Cấp Quyền
            </a>
        </div>

        <div class="legal-warning">
            <p>
                <i class="fas fa-exclamation-triangle"></i>
                CẢNH BÁO: Tiếp tục truy cập trái phép có thể dẫn đến bị cấm IP tạm thời,
                nhận email cảnh cáo, hoặc bị ép nghe bài
                <span class="gothic-text"><a href="https://youtu.be/7kO_ALcwNAw?si=eaY6gDXujAClh8vM">TRÌNH</a></span>
                (HTH) liên tục.
            </p>
        </div>

    </div>
</body>

</html>
