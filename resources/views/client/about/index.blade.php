@extends('layouts.app_client')

@section('title', 'Giới thiệu - TLO Fashion')

@section('content')
<div class="about-page">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="container">
                <div class="row align-items-center min-vh-100">
                    <div class="col-lg-6">
                        <div class="hero-text">
                            <h1 class="hero-title">
                                <span class="text-gradient">TLO Fashion</span>
                                <br>Nơi Thời Trang Gặp Gỡ Sự Sáng Tạo
                            </h1>
                            <p class="hero-description">
                                Chúng tôi không chỉ bán quần áo, chúng tôi bán phong cách sống. 
                                Mỗi sản phẩm là một tác phẩm nghệ thuật, mỗi bộ trang phục là một câu chuyện.
                            </p>
                            <div class="hero-stats">
                                <div class="stat-item">
                                    <div class="stat-number">10K+</div>
                                    <div class="stat-label">Khách hàng hài lòng</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">500+</div>
                                    <div class="stat-label">Sản phẩm độc đáo</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">5+</div>
                                    <div class="stat-label">Năm kinh nghiệm</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-image">
                            <div class="image-grid">
                                <div class="image-item large">
                                    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Fashion Collection">
                                </div>
                                <div class="image-item small">
                                    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Fashion Style">
                                </div>
                                <div class="image-item small">
                                    <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80" alt="Fashion Design">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Story Section -->
    <section class="story-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="story-image">
                        <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Our Story">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="story-content">
                        <h2 class="section-title">Câu Chuyện Của Chúng Tôi</h2>
                        <p class="story-text">
                            TLO Fashion được thành lập với một tầm nhìn đơn giản: tạo ra những bộ trang phục 
                            không chỉ đẹp mà còn mang lại cảm giác tự tin và thoải mái cho người mặc.
                        </p>
                        <p class="story-text">
                            Từ một cửa hàng nhỏ, chúng tôi đã phát triển thành một thương hiệu thời trang 
                            được yêu thích, với sự cam kết không ngừng về chất lượng và thiết kế độc đáo.
                        </p>
                        <div class="story-features">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>Đam mê thời trang</h4>
                                    <p>Mỗi thiết kế đều được tạo ra với tình yêu và sự tận tâm</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>Chất lượng cao cấp</h4>
                                    <p>Sử dụng những chất liệu tốt nhất cho sản phẩm</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Giá Trị Cốt Lõi</h2>
                <p class="section-subtitle">Những nguyên tắc định hướng mọi quyết định của chúng tôi</p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h3>Sáng tạo</h3>
                        <p>Không ngừng đổi mới và sáng tạo trong thiết kế, mang đến những xu hướng thời trang mới nhất</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h3>Chất lượng</h3>
                        <p>Cam kết cung cấp những sản phẩm chất lượng cao với giá cả hợp lý</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Khách hàng</h3>
                        <p>Đặt sự hài lòng của khách hàng lên hàng đầu trong mọi hoạt động</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3>Bền vững</h3>
                        <p>Hướng đến thời trang bền vững, thân thiện với môi trường</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3>Uy tín</h3>
                        <p>Xây dựng niềm tin với khách hàng thông qua sự minh bạch và trách nhiệm</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3>Đổi mới</h3>
                        <p>Không ngừng học hỏi và cải tiến để mang đến trải nghiệm tốt nhất</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Collections Section -->
    <section class="collections-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Bộ Sưu Tập Độc Đáo</h2>
                <p class="section-subtitle">Khám phá những phong cách thời trang đặc biệt được thiết kế riêng cho bạn</p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="collection-card">
                        <div class="collection-image">
                            <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80" alt="Street Style Collection">
                            <div class="collection-overlay">
                                <div class="collection-badge">Mới</div>
                            </div>
                        </div>
                        <div class="collection-info">
                            <h4>Street Style Collection</h4>
                            <p>Phong cách đường phố hiện đại với những thiết kế độc đáo</p>
                            <div class="collection-features">
                                <span class="feature-tag">Urban</span>
                                <span class="feature-tag">Trendy</span>
                                <span class="feature-tag">Comfort</span>
                            </div>
                            <a href="{{ route('client.products.index') }}" class="collection-btn">
                                <i class="fas fa-arrow-right"></i>
                                Khám phá ngay
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="collection-card">
                        <div class="collection-image">
                            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Elegant Collection">
                            <div class="collection-overlay">
                                <div class="collection-badge">Premium</div>
                            </div>
                        </div>
                        <div class="collection-info">
                            <h4>Elegant Collection</h4>
                            <p>Thanh lịch và sang trọng cho những dịp đặc biệt</p>
                            <div class="collection-features">
                                <span class="feature-tag">Luxury</span>
                                <span class="feature-tag">Elegant</span>
                                <span class="feature-tag">Premium</span>
                            </div>
                            <a href="{{ route('client.products.index') }}" class="collection-btn">
                                <i class="fas fa-arrow-right"></i>
                                Khám phá ngay
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="collection-card">
                        <div class="collection-image">
                            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Sport Collection">
                            <div class="collection-overlay">
                                <div class="collection-badge">Hot</div>
                            </div>
                        </div>
                        <div class="collection-info">
                            <h4>Sport Collection</h4>
                            <p>Năng động và thoải mái cho cuộc sống hiện đại</p>
                            <div class="collection-features">
                                <span class="feature-tag">Active</span>
                                <span class="feature-tag">Comfort</span>
                                <span class="feature-tag">Style</span>
                            </div>
                            <a href="{{ route('client.products.index') }}" class="collection-btn">
                                <i class="fas fa-arrow-right"></i>
                                Khám phá ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content text-center">
                <h2>Sẵn Sàng Khám Phá Bộ Sưu Tập Mới Nhất?</h2>
                <p>Hãy để chúng tôi giúp bạn tìm thấy phong cách hoàn hảo</p>
                <div class="cta-buttons">
                    <a href="{{ route('client.products.index') }}" class="btn btn-primary btn-lg">Xem Sản Phẩm</a>
                    <a href="{{ route('home') }}" class="btn btn-outline-light btn-lg">Về Trang Chủ</a>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* About Page Styles */
.about-page {
    overflow-x: hidden;
}

/* Animation keyframes */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

@keyframes glow {
    0%, 100% {
        box-shadow: 0 0 20px rgba(255, 68, 68, 0.3);
    }
    50% {
        box-shadow: 0 0 30px rgba(255, 68, 68, 0.6);
    }
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
    color: white;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    animation: fadeInUp 1s ease-out;
}

.text-gradient {
    background: linear-gradient(45deg, #ff4444, #ff8c00);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-description {
    font-size: 1.25rem;
    line-height: 1.6;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-stats {
    display: flex;
    gap: 2rem;
    margin-top: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #ff4444;
    margin-bottom: 0.5rem;
    animation: pulse 2s infinite;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

/* Hero Image Grid */
.hero-image {
    position: relative;
}

.image-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    grid-template-rows: 1fr 1fr;
    gap: 1rem;
    height: 500px;
}

.image-item {
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    transition: transform 0.3s ease;
}

.image-item:hover {
    transform: scale(1.05);
    box-shadow: 0 15px 30px rgba(255, 68, 68, 0.3);
}

.image-item.large {
    grid-row: 1 / 3;
}

.image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Story Section */
.story-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #ffffff 0%, #f8f8f8 100%);
}

.story-image {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.story-image img {
    width: 100%;
    height: 500px;
    object-fit: cover;
}

.story-content {
    padding: 2rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #333;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 60px;
    height: 4px;
    background: linear-gradient(135deg, #ff4444, #ff8c00);
    border-radius: 2px;
}

.story-text {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #666;
    margin-bottom: 1.5rem;
}

.story-features {
    margin-top: 2rem;
}

.feature-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.feature-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #ff4444, #ff8c00);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.feature-text h4 {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.feature-text p {
    color: #666;
    line-height: 1.6;
}

/* Values Section */
.values-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    color: white;
}

.values-section .section-title {
    color: white;
}

.values-section .section-title::after {
    left: 50%;
    transform: translateX(-50%);
}

.section-subtitle {
    font-size: 1.2rem;
    color: #cccccc;
    margin-bottom: 3rem;
}

.value-card {
    text-align: center;
    padding: 2rem;
    border-radius: 20px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f8f8 100%);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    border: 2px solid transparent;
    position: relative;
}

.value-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 50px rgba(255, 68, 68, 0.3);
    border-color: #ff4444;
}

.value-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #ff4444, #ff8c00);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
    box-shadow: 0 10px 20px rgba(255, 68, 68, 0.3);
    animation: glow 3s infinite;
}

.value-card h3 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #1a1a1a;
}

.value-card p {
    color: #555;
    line-height: 1.6;
}

/* Collections Section */
.collections-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #ffffff 0%, #f8f8f8 100%);
}

.collection-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 2px solid transparent;
    height: 100%;
}

.collection-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 50px rgba(255, 68, 68, 0.3);
    border-color: #ff4444;
}

.collection-image {
    height: 280px;
    overflow: hidden;
    position: relative;
}

.collection-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.collection-card:hover .collection-image img {
    transform: scale(1.1);
}

.collection-overlay {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 2;
}

.collection-badge {
    background: linear-gradient(135deg, #ff4444, #ff8c00);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
    animation: pulse 2s infinite;
}

.collection-info {
    padding: 2rem;
    text-align: center;
}

.collection-info h4 {
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #1a1a1a;
}

.collection-info p {
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.collection-features {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.feature-tag {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    color: #1a1a1a;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.collection-card:hover .feature-tag {
    background: linear-gradient(135deg, #ff4444, #ff8c00);
    color: white;
    border-color: #ff4444;
    transform: translateY(-2px);
}

.collection-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #ff4444, #ff8c00);
    color: white;
    padding: 12px 24px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.collection-btn:hover {
    background: linear-gradient(135deg, #ff3333, #ff7a00);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(255, 68, 68, 0.4);
    color: white;
}

.collection-btn i {
    transition: transform 0.3s ease;
}

.collection-btn:hover i {
    transform: translateX(5px);
}

/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
    color: white;
    padding: 100px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ff4444" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.cta-content {
    position: relative;
    z-index: 2;
}

.cta-content h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-content p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn-primary {
    background: linear-gradient(135deg, #ff4444, #ff8c00);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #ff3333, #ff7a00);
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(255, 68, 68, 0.4);
}

.btn-outline-light {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-outline-light:hover {
    background: white;
    color: #1a1a1a;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(255, 255, 255, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .image-grid {
        height: 300px;
    }
    
    .story-content {
        padding: 1rem;
        margin-top: 2rem;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .hero-description {
        font-size: 1rem;
    }
    
    .story-text {
        font-size: 1rem;
    }
}
</style>
@endsection
