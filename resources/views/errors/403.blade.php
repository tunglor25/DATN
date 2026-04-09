<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Truy Cập Bị Từ Chối</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background: #0a0a0f;
            color: #e0e0e0;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Scan line effect */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(255,0,0,0.01) 2px,
                rgba(255,0,0,0.01) 4px
            );
            pointer-events: none;
            z-index: 100;
            animation: scanlines 0.1s linear infinite;
        }
        @keyframes scanlines {
            0% { transform: translateY(0); }
            100% { transform: translateY(4px); }
        }

        /* Red alert pulse */
        body::after {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            border: 2px solid transparent;
            z-index: 99;
            animation: alertBorder 4s ease-in-out infinite;
            pointer-events: none;
        }
        @keyframes alertBorder {
            0%, 100% { border-color: transparent; }
            50% { border-color: rgba(255,50,50,0.15); }
        }

        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.12;
            z-index: 0;
            animation: orbFloat 15s ease-in-out infinite;
        }
        .orb-1 { width: 500px; height: 500px; background: #e74c3c; top: -150px; left: -100px; }
        .orb-2 { width: 300px; height: 300px; background: #c0392b; bottom: -100px; right: -50px; animation-delay: -7s; }

        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, -20px); }
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 1000px;
            margin: 0 auto;
            padding: 60px 24px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Lock icon animation */
        .lock-icon {
            width: 80px; height: 80px;
            background: rgba(231,76,60,0.1);
            border: 2px solid rgba(231,76,60,0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            animation: lockShake 5s ease-in-out infinite;
        }
        .lock-icon i {
            font-size: 32px;
            color: #e74c3c;
        }
        @keyframes lockShake {
            0%, 85%, 100% { transform: rotate(0); }
            87% { transform: rotate(-8deg); }
            89% { transform: rotate(8deg); }
            91% { transform: rotate(-5deg); }
            93% { transform: rotate(5deg); }
            95% { transform: rotate(0); }
        }

        .error-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: clamp(80px, 18vw, 160px);
            font-weight: 700;
            line-height: 1;
            color: transparent;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            background-clip: text;
            -webkit-background-clip: text;
            margin-bottom: 8px;
            text-shadow: 0 0 40px rgba(231,76,60,0.2);
            animation: errorPulse 3s ease-in-out infinite;
        }
        @keyframes errorPulse {
            0%, 100% { filter: brightness(1); }
            50% { filter: brightness(1.3); }
        }

        .error-title {
            font-size: clamp(22px, 4vw, 36px);
            font-weight: 700;
            color: #fff;
            margin-bottom: 12px;
            text-align: center;
        }

        .error-subtitle {
            font-size: 15px;
            color: #888;
            text-align: center;
            max-width: 600px;
            line-height: 1.7;
            margin-bottom: 40px;
        }
        .error-subtitle .flash {
            color: #e74c3c;
            animation: flash 2s ease-in-out infinite;
        }
        @keyframes flash {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Main card */
        .card {
            width: 100%;
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(231,76,60,0.15);
            border-radius: 20px;
            backdrop-filter: blur(20px);
            overflow: hidden;
            margin-bottom: 32px;
        }
        .card-inner { display: flex; }

        .gif-section {
            width: 380px;
            min-height: 300px;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }
        .gif-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .gif-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(231,76,60,0.8);
            backdrop-filter: blur(10px);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 20px;
            letter-spacing: 2px;
            text-transform: uppercase;
            animation: badgePulse 2s ease-in-out infinite;
        }
        @keyframes badgePulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(231,76,60,0.4); }
            50% { box-shadow: 0 0 0 8px rgba(231,76,60,0); }
        }

        .details-section {
            flex: 1;
            padding: 36px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .details-section h3 {
            font-size: 20px;
            color: #fff;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .details-section h3 .dot {
            width: 8px; height: 8px;
            background: #e74c3c;
            border-radius: 50%;
            animation: pulse 1s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(231,76,60,0.4); }
            50% { transform: scale(1.3); box-shadow: 0 0 0 6px rgba(231,76,60,0); }
        }

        /* Threat meter */
        .meter { margin-bottom: 20px; }
        .meter-label {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
        }
        .meter-label span:first-child { color: #888; }
        .meter-label span:last-child { color: #e74c3c; font-weight: 700; }
        .meter-bar {
            height: 4px;
            background: rgba(255,255,255,0.06);
            border-radius: 4px;
            overflow: hidden;
        }
        .meter-fill {
            height: 100%;
            width: 85%;
            background: linear-gradient(90deg, #e74c3c, #ff6b6b);
            border-radius: 4px;
            animation: meterPulse 2s ease-in-out infinite;
        }
        @keyframes meterPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .info-list {
            list-style: none;
            padding: 0;
        }
        .info-list li {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            font-size: 14px;
            color: #bbb;
            line-height: 1.6;
        }
        .info-list li:last-child { border-bottom: none; }
        .info-list li i {
            color: #e74c3c;
            font-size: 16px;
            margin-top: 3px;
            min-width: 20px;
            text-align: center;
        }

        .joke {
            margin-top: 20px;
            padding: 14px 18px;
            background: rgba(231,76,60,0.06);
            border-left: 3px solid #e74c3c;
            border-radius: 0 10px 10px 0;
            font-size: 13px;
            color: #999;
            font-style: italic;
        }

        .buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 32px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 28px;
            border-radius: 12px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: #fff;
            box-shadow: 0 4px 20px rgba(231,76,60,0.3);
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(231,76,60,0.4);
        }
        .btn-ghost {
            background: rgba(255,255,255,0.05);
            color: #ccc;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .btn-ghost:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-3px);
            color: #fff;
        }

        .warning {
            max-width: 700px;
            text-align: center;
            padding: 20px 28px;
            background: rgba(231,76,60,0.04);
            border: 1px solid rgba(231,76,60,0.1);
            border-radius: 14px;
            font-size: 13px;
            color: #777;
            line-height: 1.7;
        }
        .warning i { color: #e74c3c; margin-right: 6px; }
        .warning a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: 700;
            border-bottom: 1px dashed #e74c3c;
            transition: 0.2s;
        }
        .warning a:hover { color: #fff; border-color: #fff; }

        @media (max-width: 768px) {
            .card-inner { flex-direction: column; }
            .gif-section { width: 100%; min-height: 220px; }
            .details-section { padding: 24px; }
            .buttons { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="container">
        <div class="lock-icon">
            <i class="fas fa-lock"></i>
        </div>
        <div class="error-code">403</div>
        <h1 class="error-title">Cần Quyền Truy Cập Đặc Biệt</h1>
        <p class="error-subtitle">
            Bạn đã cố gắng truy cập vào khu vực bị hạn chế và hành động này đã được ghi lại, báo cáo đến đội an ninh mạng của chúng tôi.
            <span class="flash">(Chỉ đùa thôi... hoặc cũng có thể là thật?)</span>
        </p>

        <div class="card">
            <div class="card-inner">
                <div class="gif-section">
                    <img src="https://media2.giphy.com/media/v1.Y2lkPTc5MGI3NjExeDd5cWVtZjk0bXRqdjJpc29zOTJhZGszOTBmNDNzdWdsN29hczF1ZSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/3o6MbmTd7iUDK5AiHu/giphy.gif" alt="403 Security GIF">
                    <div class="gif-badge">🚨 Restricted</div>
                </div>
                <div class="details-section">
                    <h3><span class="dot"></span> Vi Phạm Giao Thức Bảo Mật</h3>

                    <div class="meter">
                        <div class="meter-label">
                            <span>Mức đe dọa</span>
                            <span>Cao</span>
                        </div>
                        <div class="meter-bar">
                            <div class="meter-fill"></div>
                        </div>
                    </div>

                    <ul class="info-list">
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

                    <div class="joke">
                        * Đừng lo, chúng tôi có thể sẽ không thả Hacker lor ra đâu. Có thể thôi. 😈
                    </div>
                </div>
            </div>
        </div>

        <div class="buttons">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Trở Về Trang Chủ
            </a>
            <a href="mailto:shoptlowd50@gmail.com" class="btn btn-ghost">
                <i class="fas fa-envelope"></i> Xin Cấp Quyền
            </a>
        </div>

        <div class="warning">
            <i class="fas fa-exclamation-triangle"></i>
            CẢNH BÁO: Tiếp tục truy cập trái phép có thể dẫn đến bị cấm IP tạm thời, nhận email cảnh cáo, hoặc bị ép nghe bài
            <a href="https://youtu.be/7kO_ALcwNAw?si=eaY6gDXujAClh8vM">TRÌNH</a> (HTH) liên tục.
        </div>
    </div>
</body>
</html>
