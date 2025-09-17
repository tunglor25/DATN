<!-- Font Awesome (Free CDN) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<footer class="client-footer">
    <!-- Upper Section -->
    <div class="footer-upper">
        <div class="container">
            <div class="footer-grid">
                <!-- Logo Section -->
                <div class="footer-section logo-section">
                    <div class="footer-logo">
                        <i class="fas fa-shoe-prints"></i> Tlo Fashion
                    </div>
                    <p class="footer-description">
                        Thời trang chất lượng, phong cách đẳng cấp. Mang đến cho bạn những trải nghiệm mua sắm tuyệt vời
                        nhất.
                    </p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Utility Menu -->
                <div class="footer-section utility-section">
                    <h4 class="footer-title">Menu tiện ích</h4>
                    <ul class="footer-menu">
                        <li><a href="#">Trang chủ</a></li>
                        <li><a href="#">Sản phẩm</a></li>
                        <li><a href="#">Bộ sưu tập</a></li>
                        <li><a href="#">Khuyến mãi</a></li>
                        <li><a href="#">Tin tức</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>

                <!-- Quick Support -->
                <div class="footer-section support-section">
                    <h4 class="footer-title">Hỗ trợ nhanh</h4>
                    <ul class="footer-menu">
                        <li><a href="#"><i class="fas fa-question-circle"></i> Câu hỏi thường gặp</a></li>
                        <li><a href="#"><i class="fas fa-truck"></i> Chính sách vận chuyển</a></li>
                        <li><a href="#"><i class="fas fa-exchange-alt"></i> Chính sách đổi trả</a></li>
                        <li><a href="#"><i class="fas fa-shield-alt"></i> Chính sách bảo mật</a></li>
                        <li><a href="#"><i class="fas fa-credit-card"></i> Hướng dẫn thanh toán</a></li>
                        <li><a href="tel:0344122842"><i class="fas fa-phone-alt"></i> 0344 122 842</a></li>
                    </ul>
                </div>

                <!-- Map Section -->
                <div class="footer-section map-section">
                    <h4 class="footer-title">Cửa hàng của chúng tôi</h4>
                    <div class="footer-map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.096484299841!2d105.7801083154027!3d21.028820893153785!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab86cece9ac1%3A0xa9bc04e04602dd85!2zRlBUIEFwdGVjaCBIw6AgTuG7mWkgLSBI4buHIFRo4buRbmcgxJDDoG8gVOG6oW8gTOG6rXAgVHLDrG5oIFZpw6puIFF14buRYyBU4bq_IChTaW5jZSAxOTk5KQ!5e0!3m2!1svi!2s!4v1627541808784!5m2!1svi!2s"
                            width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy">
                        </iframe>
                        <p class="map-address">
                            <i class="fas fa-map-marker-alt"></i> Số 8, Tôn Thất Thuyết, Mỹ Đình, Hà Nội
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lower Section -->
    <div class="footer-lower">
        <div class="container">
            <div class="copyright">
                &copy; {{ date('Y') }} <strong>Tlo Fashion</strong>. Tất cả quyền được bảo hộ.
            </div>
            <div class="payment-methods fa-icons">
                <i class="fab fa-cc-visa fa-beat" title="Visa"></i>
                <i class="fab fa-cc-mastercard fa-beat" title="MasterCard"></i>
                <i class="fab fa-cc-jcb fa-beat" title="JCB"></i>
                <i class="fab fa-cc-paypal fa-beat" title="PayPal"></i>
            </div>
        </div>
    </div>
</footer>

<style>
    .client-footer {
        background-color: #f8f9fa;
        color: #333;
        font-size: 14px;
        line-height: 1.6;
        border-top: 1px solid #e0e0e0;
    }

    .footer-upper {
        padding: 40px 0;
        background-color: #fff;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
    }

    .footer-section {
        margin-bottom: 20px;
    }

    .footer-logo {
        font-size: 24px;
        font-weight: 700;
        color: #ff6b6b;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footer-logo i {
        font-size: 28px;
    }

    .footer-description {
        margin-bottom: 20px;
        color: #666;
    }

    .footer-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
        position: relative;
        padding-bottom: 10px;
    }

    .footer-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 2px;
        background-color: #ff6b6b;
    }

    .footer-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-menu li {
        margin-bottom: 10px;
    }

    .footer-menu a {
        color: #666;
        text-decoration: none;
        transition: color 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footer-menu a:hover {
        color: #ff6b6b;
    }

    .footer-menu i {
        width: 20px;
        text-align: center;
    }

    .social-links {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .social-links a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background-color: #f0f0f0;
        color: #666;
        border-radius: 50%;
        transition: all 0.3s;
    }

    .social-links a:hover {
        background-color: #ff6b6b;
        color: #fff;
    }

    .map-address {
        margin-top: 10px;
        color: #666;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footer-lower {
        padding: 20px 0;
        background-color: #f8f9fa;
        border-top: 1px solid #e0e0e0;
    }

    .footer-lower .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .copyright {
        color: #666;
    }

    /* Font Awesome icons style for payment */
    .payment-methods.fa-icons i {
        font-size: 28px;
        color: #555;
        margin-right: 12px;
        transition: color 0.3s;
    }

    .payment-methods.fa-icons i:hover {
        color: #ff6b6b;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .footer-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .footer-grid {
            grid-template-columns: 1fr;
        }

        .footer-lower .container {
            flex-direction: column;
            gap: 10px;
            text-align: center;
        }
    }
</style>