<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Phiên Hết Hạn</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Space Grotesk', sans-serif;
            background: #0a0a0f;
            color: #e0e0e0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.1;
            z-index: 0;
        }
        .orb-1 { width: 400px; height: 400px; background: #a29bfe; top: -100px; right: -100px; animation: orbFloat 12s ease-in-out infinite; }
        .orb-2 { width: 300px; height: 300px; background: #6c5ce7; bottom: -50px; left: -50px; animation: orbFloat 15s ease-in-out infinite reverse; }

        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, -20px); }
        }

        .container {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 40px 24px;
            max-width: 600px;
        }

        /* Hourglass animation */
        .hourglass {
            font-size: 64px;
            animation: flipHourglass 2s ease-in-out infinite;
            display: inline-block;
            margin-bottom: 24px;
        }
        @keyframes flipHourglass {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(180deg); }
        }

        .error-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: clamp(80px, 18vw, 140px);
            font-weight: 700;
            color: transparent;
            background: linear-gradient(135deg, #a29bfe, #6c5ce7);
            background-clip: text;
            -webkit-background-clip: text;
            line-height: 1;
            margin-bottom: 12px;
        }

        .error-title {
            font-size: clamp(22px, 4vw, 32px);
            font-weight: 700;
            color: #fff;
            margin-bottom: 16px;
        }

        .error-subtitle {
            font-size: 15px;
            color: #888;
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(162,155,254,0.15);
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 32px;
            text-align: left;
        }
        .card h3 {
            color: #a29bfe;
            font-size: 16px;
            margin-bottom: 16px;
        }
        .card ul {
            list-style: none;
            padding: 0;
        }
        .card ul li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            font-size: 14px;
            color: #bbb;
        }
        .card ul li:last-child { border-bottom: none; }
        .card ul li i { color: #a29bfe; min-width: 18px; margin-top: 3px; }

        .joke {
            padding: 14px 18px;
            background: rgba(162,155,254,0.06);
            border-left: 3px solid #a29bfe;
            border-radius: 0 10px 10px 0;
            font-size: 13px;
            color: #999;
            font-style: italic;
            margin-top: 16px;
        }

        .gif-container {
            margin-bottom: 24px;
        }
        .gif-container img {
            max-width: 280px;
            width: 100%;
            border-radius: 12px;
            border: 2px solid rgba(162,155,254,0.2);
        }

        .buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
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
            transition: all 0.3s;
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #a29bfe, #6c5ce7);
            color: #fff;
            box-shadow: 0 4px 20px rgba(108,92,231,0.3);
        }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(108,92,231,0.4); }
        .btn-ghost {
            background: rgba(255,255,255,0.05);
            color: #ccc;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.1); transform: translateY(-3px); color: #fff; }

        @media (max-width: 768px) {
            .buttons { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="container">
        <div class="hourglass">⏳</div>
        <div class="error-code">419</div>
        <h1 class="error-title">Phiên Đã Hết Hạn</h1>
        <p class="error-subtitle">
            Phiên làm việc của bạn đã hết hạn do không hoạt động quá lâu. Vui lòng làm mới trang hoặc đăng nhập lại.
        </p>

        <div class="gif-container">
            <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExdWNpbjJ5c2g2ZjJhOGNxMGJlbXNhNHhraWk5a2h4MHBkOXRlZXMyNyZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/l2JehQ2GitHGdVG9Y/giphy.gif" alt="Timeout GIF">
        </div>

        <div class="card">
            <h3><i class="fas fa-clock me-2"></i> Tại sao lại xảy ra?</h3>
            <ul>
                <li><i class="fas fa-coffee"></i> <span>Bạn có thể đã đi pha cà phê quá lâu ☕</span></li>
                <li><i class="fas fa-bed"></i> <span>Hoặc ngủ quên trước màn hình (chúng tôi hiểu mà)</span></li>
                <li><i class="fas fa-sync-alt"></i> <span>CSRF token đã hết hạn — chỉ cần F5 là xong!</span></li>
            </ul>
            <div class="joke">
                * Mẹo: Đừng mở tab rồi quên. Tab cũng có cảm xúc, nó sẽ buồn nếu bị bỏ rơi quá lâu. 🥲
            </div>
        </div>

        <div class="buttons">
            <a href="javascript:location.reload()" class="btn btn-primary">
                <i class="fas fa-redo"></i> Làm Mới Trang
            </a>
            <a href="{{ url('/') }}" class="btn btn-ghost">
                <i class="fas fa-home"></i> Trang Chủ
            </a>
        </div>
    </div>
</body>
</html>
