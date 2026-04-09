<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Lỗi Máy Chủ</title>
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

        /* Fire particles */
        .particle {
            position: fixed;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        .particle:nth-child(1) { left: 10%; animation: rise 4s linear infinite; background: #ff6b6b; }
        .particle:nth-child(2) { left: 25%; animation: rise 3s linear infinite 0.5s; background: #ffa502; }
        .particle:nth-child(3) { left: 40%; animation: rise 5s linear infinite 1s; background: #ff6348; }
        .particle:nth-child(4) { left: 55%; animation: rise 3.5s linear infinite 1.5s; background: #ff4757; }
        .particle:nth-child(5) { left: 70%; animation: rise 4.5s linear infinite 2s; background: #ffa502; }
        .particle:nth-child(6) { left: 85%; animation: rise 3s linear infinite 0.8s; background: #ff6b6b; }
        .particle:nth-child(7) { left: 50%; animation: rise 4s linear infinite 2.5s; background: #ff6348; }
        .particle:nth-child(8) { left: 15%; animation: rise 5s linear infinite 1.2s; background: #ff4757; }

        @keyframes rise {
            0% { bottom: -10px; opacity: 1; transform: translateX(0) scale(1); }
            50% { opacity: 0.8; transform: translateX(20px) scale(1.5); }
            100% { bottom: 110vh; opacity: 0; transform: translateX(-10px) scale(0); }
        }

        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.1;
            z-index: 0;
        }
        .orb-1 { width: 500px; height: 500px; background: #ff6348; top: 30%; left: 20%; animation: orbFloat 10s ease-in-out infinite; }
        .orb-2 { width: 400px; height: 400px; background: #ffa502; bottom: 0; right: 10%; animation: orbFloat 12s ease-in-out infinite reverse; }

        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(40px, -30px); }
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
            padding: 60px 24px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Burning text */
        .error-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: clamp(80px, 18vw, 180px);
            font-weight: 700;
            line-height: 1;
            color: transparent;
            background: linear-gradient(180deg, #ffa502, #ff6348, #e74c3c);
            background-clip: text;
            -webkit-background-clip: text;
            animation: burnText 2s ease-in-out infinite alternate;
            margin-bottom: 8px;
        }
        @keyframes burnText {
            0% { filter: brightness(1) drop-shadow(0 0 10px rgba(255,99,72,0.3)); }
            100% { filter: brightness(1.2) drop-shadow(0 0 30px rgba(255,99,72,0.6)); }
        }

        .crash-icon {
            font-size: 60px;
            margin-bottom: 16px;
            animation: wobble 3s ease-in-out infinite;
        }
        @keyframes wobble {
            0%, 100% { transform: rotate(0); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
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
            max-width: 550px;
            line-height: 1.7;
            margin-bottom: 40px;
        }

        .card {
            width: 100%;
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,99,72,0.15);
            border-radius: 20px;
            backdrop-filter: blur(20px);
            padding: 36px;
            margin-bottom: 32px;
        }

        .terminal {
            background: #0c0c0c;
            border: 1px solid #222;
            border-radius: 12px;
            padding: 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            margin-bottom: 24px;
            overflow: hidden;
        }
        .terminal-header {
            display: flex;
            gap: 6px;
            margin-bottom: 16px;
        }
        .terminal-dot {
            width: 12px; height: 12px;
            border-radius: 50%;
        }
        .terminal-dot:nth-child(1) { background: #ff5f56; }
        .terminal-dot:nth-child(2) { background: #ffbd2e; }
        .terminal-dot:nth-child(3) { background: #27c93f; }

        .terminal-line {
            color: #27c93f;
            margin-bottom: 6px;
            opacity: 0;
            animation: typeLine 0.3s forwards;
        }
        .terminal-line:nth-child(2) { animation-delay: 0.3s; }
        .terminal-line:nth-child(3) { animation-delay: 0.6s; }
        .terminal-line:nth-child(4) { animation-delay: 0.9s; color: #ffa502; }
        .terminal-line:nth-child(5) { animation-delay: 1.2s; color: #ff6348; }
        .terminal-line:nth-child(6) { animation-delay: 1.5s; color: #ff4757; }
        .terminal-line:nth-child(7) { animation-delay: 1.8s; color: #888; font-style: italic; }

        @keyframes typeLine {
            0% { opacity: 0; transform: translateX(-10px); }
            100% { opacity: 1; transform: translateX(0); }
        }

        .gif-container {
            text-align: center;
            margin-bottom: 24px;
        }
        .gif-container img {
            max-width: 360px;
            width: 100%;
            border-radius: 12px;
            border: 2px solid rgba(255,99,72,0.2);
        }

        .joke {
            padding: 14px 18px;
            background: rgba(255,99,72,0.06);
            border-left: 3px solid #ff6348;
            border-radius: 0 10px 10px 0;
            font-size: 13px;
            color: #999;
            font-style: italic;
            text-align: center;
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
            background: linear-gradient(135deg, #ff6348, #e74c3c);
            color: #fff;
            box-shadow: 0 4px 20px rgba(255,99,72,0.3);
        }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(255,99,72,0.4); }
        .btn-ghost {
            background: rgba(255,255,255,0.05);
            color: #ccc;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.1); transform: translateY(-3px); color: #fff; }

        @media (max-width: 768px) {
            .card { padding: 24px; }
            .buttons { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="container">
        <div class="crash-icon">🔥</div>
        <div class="error-code">500</div>
        <h1 class="error-title">Máy Chủ Gặp Sự Cố</h1>
        <p class="error-subtitle">
            Có gì đó đã sai nghiêm trọng ở phía máy chủ. Đội ngũ của chúng tôi đã được thông báo và đang khẩn trương khắc phục.
        </p>

        <div class="card">
            <div class="terminal">
                <div class="terminal-header">
                    <div class="terminal-dot"></div>
                    <div class="terminal-dot"></div>
                    <div class="terminal-dot"></div>
                </div>
                <div class="terminal-line">$ php artisan serve</div>
                <div class="terminal-line">[INFO] Server running on http://127.0.0.1:8000</div>
                <div class="terminal-line">[REQUEST] GET /your-page HTTP/1.1</div>
                <div class="terminal-line">[WARNING] Something doesn't look right...</div>
                <div class="terminal-line">[ERROR] 500 Internal Server Error</div>
                <div class="terminal-line">[FATAL] Server đang... nghỉ giải lao ☕</div>
                <div class="terminal-line"># Đừng lo, dev đã được đánh thức dậy rồi 😅</div>
            </div>

            <div class="gif-container">
                <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExN3g2MTd0Z3Z1bHBmcDR5dW9rMnFiempwZmpqd25uYnA2bm1pcXBnaSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/HUkOv6BNWc1HO/giphy.gif" alt="Server on fire">
            </div>

            <div class="joke">
                * Máy chủ nói: "Tôi cần nghỉ phép!" — Lập trình viên nói: "Thế deploy lên production lúc 5h chiều thứ 6 là sai rồi." 💀
            </div>
        </div>

        <div class="buttons">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Trở Về Trang Chủ
            </a>
            <a href="javascript:location.reload()" class="btn btn-ghost">
                <i class="fas fa-redo"></i> Thử Lại
            </a>
        </div>
    </div>
</body>
</html>
