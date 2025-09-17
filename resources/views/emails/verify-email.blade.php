<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực tài khoản TLO Fashion</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap');
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: linear-gradient(120deg, #f6f7fb 60%, #fff 100%);
            margin: 0;
            padding: 0;
            color: #23272f;
        }
        .container {
            max-width: 470px;
            margin: 38px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 36px 0 rgba(17, 17, 17, 0.14), 0 1.5px 6px 0 rgba(230,57,70,0.06);
            overflow: hidden;
            border: 1px solid #f1f1f1;
        }
        .header {
            background: #23272f;
            padding: 34px 28px 18px 28px;
            text-align: left;
        }
        .header-title {
            color: #fff;
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -.5px;
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }
        .content {
            padding: 32px 30px 22px 30px;
        }
        h1 {
            color: #e63946;
            font-size: 23px;
            font-weight: 700;
            margin: 0 0 19px 0;
            letter-spacing: -1px;
        }
        p {
            margin: 0 0 17px 0;
            font-size: 15.3px;
            color: #333;
        }
        .button-container {
            margin: 35px 0 30px 0;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 13px 38px;
            background: linear-gradient(90deg, #e63946 0%, #c1121f 100%);
            color: #fff !important;
            text-decoration: none;
            border-radius: 7px;
            font-weight: 700;
            font-size: 17px;
            letter-spacing: .2px;
            box-shadow: 0 2px 8px rgba(230,57,70,0.07);
            transition: background 0.18s, box-shadow 0.18s, transform 0.13s;
        }
        .button:hover {
            background: linear-gradient(90deg, #c1121f 0%, #e63946 100%);
            box-shadow: 0 4px 18px rgba(230,57,70,0.13);
            transform: translateY(-1.5px) scale(1.025);
        }
        .highlight {
            background: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 14px 20px;
            margin: 26px 0 22px 0;
            font-size: 14.2px;
            color: #7a5d00;
            border-radius: 7px;
        }
        .signature {
            border-top: 1px solid #f0f0f0;
            padding-top: 18px;
            margin-top: 30px;
        }
        .signature p {
            margin-bottom: 9px;
            font-size: 14px;
        }
        .footer {
            background: #f7f7f7;
            padding: 16px 8px;
            text-align: center;
            font-size: 13.2px;
            color: #888;
            border-top: 1px solid #ececec;
        }
        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
                box-shadow: none;
                border: none;
            }
            .content {
                padding: 18px 7vw 18px 7vw;
            }
            .header {
                padding: 26px 7vw 15px 7vw;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">Xác thực tài khoản của bạn</div>
        </div>
        <div class="content">
            <h1>Xin chào {{ $name }},</h1>
            <p>
                Cảm ơn bạn đã đăng ký tài khoản tại <strong>TLO Fashion</strong> – nơi trải nghiệm thời trang hiện đại và cá tính.
            </p>
            <p>
                Để hoàn tất quá trình đăng ký, vui lòng xác thực địa chỉ email bằng cách nhấn vào nút bên dưới:
            </p>
            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="button">XÁC THỰC EMAIL</a>
            </div>
            <div class="highlight">
                <strong>Lưu ý:</strong> Liên kết xác thực chỉ có hiệu lực trong 24 giờ.<br>
                Nếu liên kết hết hạn, bạn có thể yêu cầu gửi lại email xác thực từ trang đăng nhập.
            </div>
            <p>
                Nếu bạn không thực hiện đăng ký tài khoản này, vui lòng bỏ qua email này hoặc liên hệ với chúng tôi để được hỗ trợ kịp thời.
            </p>
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