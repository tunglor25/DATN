<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - TLO Fashion</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #1a1a1a;
            padding: 30px 20px;
            text-align: center;
        }

        .header img {
            max-width: 180px;
            height: auto;
        }

        .content {
            padding: 30px;
        }

        h1 {
            color: #1a1a1a;
            font-size: 24px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 20px;
            font-size: 15px;
        }

        .button-container {
            margin: 30px 0;
            text-align: center;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #e63946;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #c1121f;
        }

        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #666666;
        }

        .signature {
            margin-top: 30px;
            border-top: 1px solid #eeeeee;
            padding-top: 20px;
        }

        .highlight {
            background-color: #fff8e1;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
            font-size: 14px;
        }

        .social-links {
            margin-top: 20px;
        }

        .social-links a {
            display: inline-block;
            margin: 0 10px;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="content">
            <h1>Xin chào {{ $name }},</h1>

            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn tại <strong>TLO Fashion</strong>.</p>

            <p>Vui lòng nhấp vào nút bên dưới để đặt lại mật khẩu của bạn:</p>

            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="button">ĐẶT LẠI MẬT KHẨU</a>
            </div>

            <div class="highlight">
                <strong>Lưu ý quan trọng:</strong> Liên kết đặt lại mật khẩu chỉ có hiệu lực trong vòng 60 phút. Nếu bạn
                không thực hiện trong thời gian này, vui lòng yêu cầu gửi lại email.
            </div>

            <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này. Tài khoản của bạn sẽ vẫn an toàn và
                không có thay đổi nào được thực hiện.</p>

            <div class="signature">
                <p>Trân trọng,</p>
                <p><strong>Đội ngũ TLO Fashion</strong></p>
                <p>Hotline: 0344122842</p>
                <p>Email: {{ $supportEmail }}</p>
            </div>
        </div>


        <div class="footer">
            © {{ date('Y') }} TLO Fashion. All rights reserved.<br>
            Địa chỉ: Số 8, Tôn Thất Thuyết, Mỹ Đình, Hà Nội
        </div>
    </div>
</body>

</html>