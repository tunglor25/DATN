<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon - TLO Fashion</title>
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
            overflow: hidden;
            position: relative;
        }

        /* Animated gradient background */
        body::before {
            content: '';
            position: fixed;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(78,205,196,0.03), transparent, rgba(255,107,107,0.03), transparent);
            animation: rotateBg 30s linear infinite;
            z-index: 0;
        }
        @keyframes rotateBg {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.12;
            z-index: 0;
            animation: orbFloat 20s ease-in-out infinite;
        }
        .orb-1 { width: 500px; height: 500px; background: #4ecdc4; top: -150px; right: -150px; }
        .orb-2 { width: 400px; height: 400px; background: #ff6b6b; bottom: -100px; left: -100px; animation-delay: -10s; }
        .orb-3 { width: 300px; height: 300px; background: #ffe66d; top: 50%; left: 30%; animation-delay: -5s; }

        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(40px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
        }

        .container {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 40px 24px;
            max-width: 700px;
        }

        /* Rocket animation */
        .rocket {
            font-size: 60px;
            display: inline-block;
            animation: rocketBounce 3s ease-in-out infinite;
            margin-bottom: 20px;
        }
        @keyframes rocketBounce {
            0%, 100% { transform: translateY(0) rotate(-15deg); }
            50% { transform: translateY(-20px) rotate(-15deg); }
        }

        .brand {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 6px;
            color: #4ecdc4;
            margin-bottom: 24px;
            font-weight: 600;
        }

        h1 {
            font-size: clamp(36px, 8vw, 64px);
            font-weight: 700;
            background: linear-gradient(135deg, #fff, #4ecdc4, #ff6b6b);
            background-size: 200% 200%;
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            animation: gradientShift 5s ease infinite;
            margin-bottom: 16px;
            line-height: 1.2;
        }
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .subtitle {
            font-size: 16px;
            color: #888;
            max-width: 500px;
            margin: 0 auto 40px;
            line-height: 1.7;
        }

        /* Countdown */
        .countdown {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
        }
        .countdown-item {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 20px 16px;
            min-width: 85px;
            backdrop-filter: blur(10px);
        }
        .countdown-item .number {
            font-family: 'JetBrains Mono', monospace;
            font-size: 36px;
            font-weight: 700;
            color: #4ecdc4;
            display: block;
            line-height: 1;
            margin-bottom: 8px;
        }
        .countdown-item .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #666;
        }

        /* GIF */
        .gif-container {
            margin-bottom: 36px;
        }
        .gif-container img {
            max-width: 320px;
            width: 100%;
            border-radius: 16px;
            border: 2px solid rgba(78,205,196,0.2);
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        /* Features preview */
        .features {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 36px;
        }
        .feature {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            padding: 16px;
            font-size: 13px;
            color: #aaa;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }
        .feature:hover {
            border-color: rgba(78,205,196,0.3);
            transform: translateY(-2px);
        }
        .feature i { color: #4ecdc4; font-size: 16px; }

        .joke {
            padding: 16px 20px;
            background: rgba(78,205,196,0.04);
            border: 1px solid rgba(78,205,196,0.1);
            border-radius: 14px;
            font-size: 13px;
            color: #888;
            font-style: italic;
            margin-bottom: 32px;
            line-height: 1.7;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            border-radius: 12px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            background: linear-gradient(135deg, #4ecdc4, #44b09e);
            color: #fff;
            box-shadow: 0 4px 20px rgba(78,205,196,0.3);
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(78,205,196,0.4);
        }

        @media (max-width: 768px) {
            .countdown { gap: 12px; }
            .countdown-item { min-width: 70px; padding: 16px 12px; }
            .countdown-item .number { font-size: 28px; }
            .features { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="container">
        <div class="rocket">🚀</div>
        <div class="brand">TLO Fashion</div>
        <h1>Coming Soon</h1>
        <p class="subtitle">
            Chúng tôi đang xây dựng một trang thật đặc biệt. Hãy quay lại sau nhé — bạn sẽ không thất vọng!
        </p>

        <div class="countdown" id="countdown">
            <div class="countdown-item">
                <span class="number" id="days">00</span>
                <span class="label">Ngày</span>
            </div>
            <div class="countdown-item">
                <span class="number" id="hours">00</span>
                <span class="label">Giờ</span>
            </div>
            <div class="countdown-item">
                <span class="number" id="minutes">00</span>
                <span class="label">Phút</span>
            </div>
            <div class="countdown-item">
                <span class="number" id="seconds">00</span>
                <span class="label">Giây</span>
            </div>
        </div>

        <div class="gif-container">
            <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExaGJsajR3YTN3cjFucnRmY2dxNng3M284dTA5aDh3MTZzY282bml5MyZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/3oKIPnAiaMCws8nOsE/giphy.gif" alt="Building...">
        </div>

        <div class="features">
            <div class="feature"><i class="fas fa-tshirt"></i> Bộ sưu tập mới</div>
            <div class="feature"><i class="fas fa-bolt"></i> Trải nghiệm nhanh hơn</div>
            <div class="feature"><i class="fas fa-star"></i> Ưu đãi độc quyền</div>
        </div>

        <div class="joke">
            💡 Mẹo: Bạn có thể nhấn F5 liên tục để trang load nhanh hơn. <br>
            (Chỉ đùa thôi, đừng làm thế — server sẽ khóc đấy 😢)
        </div>

        <a href="{{ url('/') }}" class="btn">
            <i class="fas fa-home"></i> Về Trang Chủ
        </a>
    </div>

    <script>
        // Countdown 30 ngày từ giờ
        const target = new Date();
        target.setDate(target.getDate() + 30);

        function updateCountdown() {
            const now = new Date();
            const diff = target - now;
            if (diff <= 0) return;

            document.getElementById('days').textContent = String(Math.floor(diff / 86400000)).padStart(2, '0');
            document.getElementById('hours').textContent = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
            document.getElementById('minutes').textContent = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            document.getElementById('seconds').textContent = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
</body>
</html>
