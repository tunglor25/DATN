<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Trang Không Tồn Tại</title>
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

        /* Animated background grid */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image:
                linear-gradient(rgba(255,107,107,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,107,107,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridMove 20s linear infinite;
            z-index: 0;
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(60px, 60px); }
        }

        /* Floating orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            z-index: 0;
            animation: orbFloat 15s ease-in-out infinite;
        }
        .orb-1 { width: 400px; height: 400px; background: #ff6b6b; top: -100px; right: -100px; animation-delay: 0s; }
        .orb-2 { width: 300px; height: 300px; background: #4ecdc4; bottom: -50px; left: -50px; animation-delay: -5s; }
        .orb-3 { width: 200px; height: 200px; background: #ffe66d; top: 50%; left: 50%; animation-delay: -10s; }

        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -30px) scale(1.1); }
            50% { transform: translate(-20px, 20px) scale(0.9); }
            75% { transform: translate(20px, 10px) scale(1.05); }
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

        /* Glitch error code */
        .error-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: clamp(100px, 20vw, 200px);
            font-weight: 700;
            line-height: 1;
            position: relative;
            color: transparent;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24, #ff6b6b);
            background-clip: text;
            -webkit-background-clip: text;
            animation: glitchText 3s infinite;
            margin-bottom: 8px;
        }

        .error-code::before,
        .error-code::after {
            content: '404';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }
        .error-code::before {
            animation: glitchBefore 3s infinite;
            clip-path: polygon(0 0, 100% 0, 100% 35%, 0 35%);
        }
        .error-code::after {
            animation: glitchAfter 3s infinite;
            clip-path: polygon(0 65%, 100% 65%, 100% 100%, 0 100%);
        }

        @keyframes glitchText {
            0%, 90%, 100% { transform: none; }
            92% { transform: skew(2deg); }
            94% { transform: skew(-1deg); }
            96% { transform: skew(3deg) translateX(2px); }
            98% { transform: skew(-2deg) translateX(-2px); }
        }
        @keyframes glitchBefore {
            0%, 90%, 100% { transform: none; }
            92% { transform: translateX(-4px); }
            94% { transform: translateX(4px); }
            96% { transform: translateX(-2px); }
        }
        @keyframes glitchAfter {
            0%, 90%, 100% { transform: none; }
            92% { transform: translateX(4px); }
            94% { transform: translateX(-4px); }
            96% { transform: translateX(2px); }
        }

        .error-title {
            font-size: clamp(24px, 4vw, 38px);
            font-weight: 700;
            background: linear-gradient(135deg, #f8f8f8, #aaa);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 12px;
            text-align: center;
        }

        .error-subtitle {
            font-size: 16px;
            color: #888;
            text-align: center;
            max-width: 550px;
            line-height: 1.7;
            margin-bottom: 40px;
        }
        .error-subtitle .flash {
            color: #ff6b6b;
            animation: flash 2s ease-in-out infinite;
        }
        @keyframes flash {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Main card */
        .card {
            width: 100%;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 20px;
            backdrop-filter: blur(20px);
            overflow: hidden;
            margin-bottom: 32px;
        }

        .card-inner {
            display: flex;
            gap: 0;
        }

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
            display: block;
        }
        .gif-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
            color: #ffe66d;
            font-size: 11px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 20px;
            letter-spacing: 2px;
            text-transform: uppercase;
            border: 1px solid rgba(255,230,109,0.3);
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
            background: #4ecdc4;
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.5); opacity: 0.5; }
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
            color: #ff6b6b;
            font-size: 16px;
            margin-top: 3px;
            min-width: 20px;
            text-align: center;
        }

        .joke {
            margin-top: 20px;
            padding: 14px 18px;
            background: rgba(255,107,107,0.06);
            border-left: 3px solid #ff6b6b;
            border-radius: 0 10px 10px 0;
            font-size: 13px;
            color: #999;
            font-style: italic;
        }

        /* Threat meter */
        .meter {
            margin-bottom: 20px;
        }
        .meter-label {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
        }
        .meter-label span:first-child { color: #888; }
        .meter-label span:last-child { color: #4ecdc4; font-weight: 700; }
        .meter-bar {
            height: 4px;
            background: rgba(255,255,255,0.06);
            border-radius: 4px;
            overflow: hidden;
        }
        .meter-fill {
            height: 100%;
            width: 30%;
            background: linear-gradient(90deg, #4ecdc4, #44b09e);
            border-radius: 4px;
            animation: meterPulse 3s ease-in-out infinite;
        }
        @keyframes meterPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        /* Buttons */
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
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: #fff;
            box-shadow: 0 4px 20px rgba(255,107,107,0.3);
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(255,107,107,0.4);
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

        /* Warning footer */
        .warning {
            max-width: 700px;
            text-align: center;
            padding: 20px 28px;
            background: rgba(255,107,107,0.04);
            border: 1px solid rgba(255,107,107,0.1);
            border-radius: 14px;
            font-size: 13px;
            color: #777;
            line-height: 1.7;
        }
        .warning i { color: #ff6b6b; margin-right: 6px; }
        .warning a {
            color: #ff6b6b;
            text-decoration: none;
            font-weight: 700;
            border-bottom: 1px dashed #ff6b6b;
            transition: 0.2s;
        }
        .warning a:hover { color: #fff; border-color: #fff; }

        /* Responsive */
        @media (max-width: 768px) {
            .card-inner { flex-direction: column; }
            .gif-section { width: 100%; min-height: 220px; }
            .details-section { padding: 24px; }
            .buttons { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
            .container { padding: 40px 16px; }
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="container">
        <div class="error-code">404</div>
        <h1 class="error-title">Trang Không Tồn Tại</h1>
        <p class="error-subtitle">
            Trang bạn đang tìm kiếm có thể đã bị di chuyển, xóa hoặc chưa bao giờ tồn tại.
            <span class="flash">(Hoặc có thể nó đang trốn bạn?)</span>
        </p>

        <div class="card">
            <div class="card-inner">
                <div class="gif-section">
                    <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcjJtY2R5Z2R0dGJ5eXJ2Z2V1bWJ6ZzR6bGZ4eWQ3dGJzNnVqZ3R3biZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/14uQ3cOFteDaU/giphy.gif" alt="404 GIF">
                    <div class="gif-badge">🔍 Lost Zone</div>
                </div>
                <div class="details-section">
                    <h3><span class="dot"></span> Khám Phá Không Thành Công</h3>

                    <div class="meter">
                        <div class="meter-label">
                            <span>Mức nguy hiểm</span>
                            <span>Thấp</span>
                        </div>
                        <div class="meter-bar">
                            <div class="meter-fill"></div>
                        </div>
                    </div>

                    <ul class="info-list">
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

                    <div class="joke">
                        * Đừng lo, chúng tôi không ghi lại địa chỉ IP của bạn... có lẽ thôi. 🤫
                    </div>
                </div>
            </div>
        </div>

        <div class="buttons">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Trở Về Trang Chủ
            </a>
            <a href="mailto:shoptlowd50@gmail.com" class="btn btn-ghost">
                <i class="fas fa-envelope"></i> Báo Cáo Lỗi
            </a>
        </div>

        <div class="warning">
            <i class="fas fa-exclamation-triangle"></i>
            CẢNH BÁO: Tiếp tục truy cập vào các trang không tồn tại có thể dẫn đến việc bạn phải đối mặt với
            <a href="https://youtu.be/dQw4w9WgXcQ?si=oHg5SJYRHA0FESXv">màn hình 404</a> vô tận.
        </div>
    </div>
</body>
</html>