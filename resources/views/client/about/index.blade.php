@extends('layouts.app_client')

@section('title', 'Giới thiệu - TLO Fashion')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ===== RESET PARENT CONTAINER ===== */
.about-page-wrapper .main-content,
.about-page-wrapper .wrapper {
    max-width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
}

.about-page {
    overflow-x: hidden;
    font-family: 'Inter', sans-serif;
    --accent: #ff6b6b;
    --accent-dark: #e85d5d;
    --accent-gradient: linear-gradient(135deg, #ff6b6b, #ee5a24);
    --dark: #0f0f0f;
    --dark-light: #1a1a2e;
    --text-primary: #1a1a2e;
    --text-secondary: #64748b;
    --text-light: #94a3b8;
    --surface: #ffffff;
    --surface-alt: #f8fafc;
    --border: #e2e8f0;
}

/* ===== ANIMATIONS ===== */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInLeft {
    from { opacity: 0; transform: translateX(-40px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes fadeInRight {
    from { opacity: 0; transform: translateX(40px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes scaleIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-12px); }
}

@keyframes shimmer {
    0% { background-position: -200% center; }
    100% { background-position: 200% center; }
}

@keyframes countUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-on-scroll {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.animate-on-scroll.visible {
    opacity: 1;
    transform: translateY(0);
}

.animate-delay-1 { transition-delay: 0.1s; }
.animate-delay-2 { transition-delay: 0.2s; }
.animate-delay-3 { transition-delay: 0.3s; }
.animate-delay-4 { transition-delay: 0.4s; }
.animate-delay-5 { transition-delay: 0.5s; }

/* ===== HERO SECTION ===== */
.about-hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    background: var(--dark);
    overflow: hidden;
}

.about-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse at 20% 50%, rgba(255, 107, 107, 0.15) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 20%, rgba(238, 90, 36, 0.1) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 80%, rgba(255, 107, 107, 0.08) 0%, transparent 50%);
    z-index: 1;
}

.about-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    z-index: 1;
}

.hero-inner {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 1320px;
    margin: 0 auto;
    padding: 120px 24px 80px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.hero-text-block {
    color: #fff;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    background: rgba(255, 107, 107, 0.15);
    border: 1px solid rgba(255, 107, 107, 0.3);
    border-radius: 100px;
    color: var(--accent);
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 28px;
    animation: fadeInUp 0.8s ease-out;
}

.hero-badge i {
    font-size: 0.7rem;
}

.hero-main-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 700;
    line-height: 1.15;
    margin-bottom: 24px;
    animation: fadeInUp 0.8s ease-out 0.15s both;
}

.hero-main-title .accent {
    background: var(--accent-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: 1.15rem;
    line-height: 1.75;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 40px;
    max-width: 520px;
    animation: fadeInUp 0.8s ease-out 0.3s both;
}

/* Stats Row */
.hero-stats-row {
    display: flex;
    gap: 40px;
    animation: fadeInUp 0.8s ease-out 0.45s both;
}

.hero-stat {
    position: relative;
    padding-left: 20px;
}

.hero-stat::before {
    content: '';
    position: absolute;
    left: 0;
    top: 4px;
    bottom: 4px;
    width: 3px;
    background: var(--accent-gradient);
    border-radius: 2px;
}

.hero-stat-number {
    font-family: 'Playfair Display', serif;
    font-size: 2.25rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
}

.hero-stat-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.5);
    margin-top: 4px;
}

/* Hero Image Mosaic */
.hero-mosaic {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: 200px 200px;
    gap: 16px;
    animation: scaleIn 1s ease-out 0.3s both;
}

.mosaic-img {
    border-radius: 20px;
    overflow: hidden;
    position: relative;
}

.mosaic-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.mosaic-img:hover img {
    transform: scale(1.08);
}

.mosaic-img.tall {
    grid-row: 1 / 3;
}

.mosaic-img::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 60%, rgba(0,0,0,0.3) 100%);
    pointer-events: none;
}

/* Floating accent decoration */
.hero-float-badge {
    position: absolute;
    bottom: 20px;
    left: 20px;
    z-index: 3;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 14px 20px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    animation: float 4s ease-in-out infinite;
}

.hero-float-badge .badge-icon {
    width: 44px;
    height: 44px;
    background: var(--accent-gradient);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.1rem;
}

.hero-float-badge .badge-text {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.hero-float-badge .badge-text strong {
    display: block;
    color: var(--text-primary);
    font-size: 0.95rem;
}


/* ===== SECTION COMMON ===== */
.about-section {
    padding: 100px 24px;
}

.section-container {
    max-width: 1320px;
    margin: 0 auto;
}

.section-header {
    text-align: center;
    margin-bottom: 64px;
}

.section-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 18px;
    background: rgba(255, 107, 107, 0.08);
    border: 1px solid rgba(255, 107, 107, 0.15);
    border-radius: 100px;
    color: var(--accent);
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 20px;
}

.section-heading {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 4vw, 2.75rem);
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 16px;
    line-height: 1.25;
}

.section-desc {
    font-size: 1.1rem;
    color: var(--text-secondary);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.7;
}


/* ===== STORY SECTION ===== */
.story-section {
    background: var(--surface);
}

.story-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
}

.story-image-wrapper {
    position: relative;
}

.story-main-image {
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 25px 60px rgba(0,0,0,0.1);
    position: relative;
}

.story-main-image img {
    width: 100%;
    height: 520px;
    object-fit: cover;
    display: block;
}

.story-accent-box {
    position: absolute;
    bottom: -30px;
    right: -30px;
    background: var(--accent-gradient);
    color: #fff;
    padding: 28px 32px;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(255, 107, 107, 0.3);
    text-align: center;
    z-index: 2;
}

.story-accent-box .accent-number {
    font-family: 'Playfair Display', serif;
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1;
}

.story-accent-box .accent-text {
    font-size: 0.85rem;
    opacity: 0.9;
    margin-top: 4px;
}

.story-content-block h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 24px;
    line-height: 1.25;
}

.story-content-block h2 .accent {
    color: var(--accent);
}

.story-lead {
    font-size: 1.15rem;
    color: var(--text-secondary);
    line-height: 1.8;
    margin-bottom: 20px;
}

.story-text {
    font-size: 1rem;
    color: var(--text-light);
    line-height: 1.8;
    margin-bottom: 32px;
}

/* Story Feature List */
.story-features-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.story-feature {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px 20px;
    border-radius: 16px;
    background: var(--surface-alt);
    border: 1px solid var(--border);
    transition: all 0.3s ease;
}

.story-feature:hover {
    border-color: var(--accent);
    box-shadow: 0 4px 20px rgba(255, 107, 107, 0.08);
    transform: translateX(4px);
}

.story-feature-icon {
    width: 48px;
    height: 48px;
    background: var(--accent-gradient);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.story-feature h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.story-feature p {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin: 0;
    line-height: 1.5;
}


/* ===== VALUES SECTION ===== */
.values-section {
    background: var(--surface-alt);
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.value-card {
    background: var(--surface);
    border-radius: 20px;
    padding: 36px 28px;
    border: 1px solid var(--border);
    text-align: center;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
}

.value-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--accent-gradient);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.4s ease;
}

.value-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
    border-color: rgba(255, 107, 107, 0.2);
}

.value-card:hover::before {
    transform: scaleX(1);
}

.value-icon-wrap {
    width: 72px;
    height: 72px;
    margin: 0 auto 20px;
    background: rgba(255, 107, 107, 0.08);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
    font-size: 1.6rem;
    transition: all 0.4s ease;
}

.value-card:hover .value-icon-wrap {
    background: var(--accent-gradient);
    color: #fff;
    transform: scale(1.05) rotate(3deg);
    box-shadow: 0 10px 25px rgba(255, 107, 107, 0.25);
}

.value-card h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 12px;
}

.value-card p {
    font-size: 0.92rem;
    color: var(--text-secondary);
    line-height: 1.65;
    margin: 0;
}


/* ===== MILESTONE/TIMELINE SECTION ===== */
.milestone-section {
    background: var(--dark);
    color: #fff;
    position: relative;
    overflow: hidden;
}

.milestone-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse at 30% 0%, rgba(255, 107, 107, 0.1) 0%, transparent 50%),
        radial-gradient(ellipse at 70% 100%, rgba(238, 90, 36, 0.08) 0%, transparent 50%);
}

.milestone-section .section-heading {
    color: #fff;
}

.milestone-section .section-desc {
    color: rgba(255, 255, 255, 0.6);
}

.timeline {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, transparent, rgba(255,107,107,0.4), transparent);
    transform: translateX(-50%);
}

.timeline-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 48px;
    position: relative;
}

.timeline-item:nth-child(odd) {
    flex-direction: row;
    text-align: right;
}

.timeline-item:nth-child(even) {
    flex-direction: row-reverse;
    text-align: left;
}

.timeline-content {
    width: calc(50% - 40px);
    padding: 28px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 18px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.timeline-content:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 107, 107, 0.3);
    transform: translateY(-4px);
}

.timeline-dot {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    width: 18px;
    height: 18px;
    background: var(--accent-gradient);
    border-radius: 50%;
    border: 3px solid var(--dark);
    box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.2);
    z-index: 2;
}

.timeline-year {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--accent);
    margin-bottom: 8px;
}

.timeline-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #fff;
    margin-bottom: 8px;
}

.timeline-desc {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.55);
    line-height: 1.6;
    margin: 0;
}


/* ===== COLLECTIONS SECTION ===== */
.collections-section {
    background: var(--surface);
}

.collections-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}

.collection-card {
    border-radius: 24px;
    overflow: hidden;
    background: var(--surface);
    border: 1px solid var(--border);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
}

.collection-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.1);
    border-color: transparent;
}

.collection-img-wrap {
    position: relative;
    height: 300px;
    overflow: hidden;
}

.collection-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.collection-card:hover .collection-img-wrap img {
    transform: scale(1.08);
}

.collection-label {
    position: absolute;
    top: 16px;
    left: 16px;
    padding: 6px 16px;
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(8px);
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--accent);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.collection-body {
    padding: 28px;
}

.collection-body h4 {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 10px;
}

.collection-body p {
    font-size: 0.92rem;
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: 20px;
}

.collection-tags {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.collection-tag {
    padding: 5px 14px;
    background: var(--surface-alt);
    border: 1px solid var(--border);
    border-radius: 100px;
    font-size: 0.78rem;
    font-weight: 500;
    color: var(--text-secondary);
    transition: all 0.3s ease;
}

.collection-card:hover .collection-tag {
    background: rgba(255, 107, 107, 0.08);
    border-color: rgba(255, 107, 107, 0.2);
    color: var(--accent);
}

.collection-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.92rem;
    font-weight: 600;
    color: var(--accent);
    text-decoration: none;
    transition: all 0.3s ease;
}

.collection-link i {
    font-size: 0.8rem;
    transition: transform 0.3s ease;
}

.collection-link:hover {
    color: var(--accent-dark);
}

.collection-link:hover i {
    transform: translateX(5px);
}


/* ===== CTA SECTION ===== */
.about-cta {
    background: var(--surface-alt);
    position: relative;
    overflow: hidden;
}

.cta-card {
    position: relative;
    background: var(--dark);
    border-radius: 32px;
    padding: 80px 60px;
    text-align: center;
    overflow: hidden;
}

.cta-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse at 30% 50%, rgba(255, 107, 107, 0.15) 0%, transparent 50%),
        radial-gradient(ellipse at 70% 50%, rgba(238, 90, 36, 0.1) 0%, transparent 50%);
}

.cta-card > * {
    position: relative;
    z-index: 2;
}

.cta-card h2 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 3.5vw, 2.5rem);
    font-weight: 700;
    color: #fff;
    margin-bottom: 16px;
    line-height: 1.3;
}

.cta-card p {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 36px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.cta-buttons {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}

.cta-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 36px;
    background: var(--accent-gradient);
    color: #fff;
    border: none;
    border-radius: 14px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
}

.cta-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(255, 107, 107, 0.4);
    color: #fff;
}

.cta-btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 36px;
    background: transparent;
    color: #fff;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 14px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.cta-btn-outline:hover {
    border-color: rgba(255, 255, 255, 0.5);
    background: rgba(255, 255, 255, 0.05);
    transform: translateY(-3px);
    color: #fff;
}


/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .hero-inner {
        grid-template-columns: 1fr;
        gap: 40px;
        padding: 100px 24px 60px;
    }

    .hero-mosaic {
        max-width: 500px;
        margin: 0 auto;
    }

    .story-grid {
        grid-template-columns: 1fr;
        gap: 48px;
    }

    .story-accent-box {
        bottom: -20px;
        right: 20px;
    }

    .values-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .collections-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .timeline::before {
        left: 24px;
    }

    .timeline-item,
    .timeline-item:nth-child(odd),
    .timeline-item:nth-child(even) {
        flex-direction: row;
        text-align: left;
        padding-left: 56px;
    }

    .timeline-content {
        width: 100%;
    }

    .timeline-dot {
        left: 24px;
    }
}

@media (max-width: 768px) {
    .about-section {
        padding: 64px 16px;
    }

    .hero-stats-row {
        gap: 24px;
        flex-wrap: wrap;
    }

    .hero-stat-number {
        font-size: 1.75rem;
    }

    .hero-mosaic {
        grid-template-rows: 160px 160px;
    }

    .values-grid {
        grid-template-columns: 1fr;
    }

    .collections-grid {
        grid-template-columns: 1fr;
        max-width: 440px;
        margin: 0 auto;
    }

    .cta-card {
        padding: 48px 24px;
        border-radius: 24px;
    }

    .story-main-image img {
        height: 360px;
    }

    .story-content-block h2 {
        font-size: 2rem;
    }

    .section-header {
        margin-bottom: 40px;
    }
}

@media (max-width: 480px) {
    .hero-mosaic {
        grid-template-columns: 1fr;
        grid-template-rows: 200px 140px 140px;
    }

    .mosaic-img.tall {
        grid-row: auto;
    }

    .hero-stats-row {
        flex-direction: column;
        gap: 16px;
    }

    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }

    .cta-btn-primary,
    .cta-btn-outline {
        width: 100%;
        justify-content: center;
        max-width: 300px;
    }
}
</style>
@endsection

@section('content')
<div class="about-page">

    <!-- ===== HERO ===== -->
    <section class="about-hero">
        <div class="hero-inner">
            <div class="hero-text-block">
                <div class="hero-badge">
                    <i class="fas fa-circle"></i> Về chúng tôi
                </div>
                <h1 class="hero-main-title">
                    Nơi <span class="accent">Phong Cách</span><br>
                    Gặp Gỡ Đam Mê
                </h1>
                <p class="hero-subtitle">
                    TLO Fashion không chỉ là thời trang – chúng tôi kiến tạo phong cách sống. 
                    Mỗi sản phẩm là một câu chuyện, mỗi thiết kế là một tác phẩm nghệ thuật 
                    được chắp bút từ đam mê và sự sáng tạo.
                </p>
                <div class="hero-stats-row">
                    <div class="hero-stat">
                        <div class="hero-stat-number">10K+</div>
                        <div class="hero-stat-label">Khách hàng tin tưởng</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">500+</div>
                        <div class="hero-stat-label">Sản phẩm độc quyền</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">5+</div>
                        <div class="hero-stat-label">Năm kinh nghiệm</div>
                    </div>
                </div>
            </div>
            <div class="hero-mosaic">
                <div class="mosaic-img tall">
                    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=800&q=80" alt="TLO Fashion Store">
                    <div class="hero-float-badge">
                        <div class="badge-icon"><i class="fas fa-award"></i></div>
                        <div class="badge-text">
                            <strong>Top Thương Hiệu</strong>
                            Được yêu thích 2024
                        </div>
                    </div>
                </div>
                <div class="mosaic-img">
                    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=600&q=80" alt="Fashion Style">
                </div>
                <div class="mosaic-img">
                    <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=600&q=80" alt="Fashion Design">
                </div>
            </div>
        </div>
    </section>

    <!-- ===== STORY ===== -->
    <section class="about-section story-section">
        <div class="section-container">
            <div class="story-grid">
                <div class="story-image-wrapper animate-on-scroll">
                    <div class="story-main-image">
                        <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?auto=format&fit=crop&w=900&q=80" alt="Câu chuyện TLO Fashion">
                    </div>
                    <div class="story-accent-box">
                        <div class="accent-number">5+</div>
                        <div class="accent-text">Năm phát triển</div>
                    </div>
                </div>
                <div class="story-content-block">
                    <div class="section-tag animate-on-scroll">
                        <i class="fas fa-bookmark"></i> CÂU CHUYỆN CỦA CHÚNG TÔI
                    </div>
                    <h2 class="animate-on-scroll animate-delay-1">
                        Từ đam mê đến <span class="accent">thương hiệu</span> được yêu thích
                    </h2>
                    <p class="story-lead animate-on-scroll animate-delay-2">
                        TLO Fashion được sinh ra từ niềm đam mê cháy bỏng với thời trang — với mong muốn 
                        mang đến những bộ trang phục không chỉ đẹp, mà còn kể được câu chuyện của người mặc.
                    </p>
                    <p class="story-text animate-on-scroll animate-delay-3">
                        Từ một cửa hàng nhỏ, chúng tôi đã phát triển thành một thương hiệu được hàng nghìn 
                        khách hàng tin tưởng. Mỗi sản phẩm đều được tuyển chọn kỹ lưỡng về chất liệu, 
                        thiết kế tinh tế và mức giá hợp lý.
                    </p>
                    <div class="story-features-list animate-on-scroll animate-delay-4">
                        <div class="story-feature">
                            <div class="story-feature-icon"><i class="fas fa-heart"></i></div>
                            <div>
                                <h4>Đam mê thời trang</h4>
                                <p>Mỗi thiết kế đều được tạo ra với tình yêu và sự tận tâm tuyệt đối</p>
                            </div>
                        </div>
                        <div class="story-feature">
                            <div class="story-feature-icon"><i class="fas fa-gem"></i></div>
                            <div>
                                <h4>Chất lượng cao cấp</h4>
                                <p>Cam kết sử dụng những chất liệu tốt nhất, bền đẹp theo thời gian</p>
                            </div>
                        </div>
                        <div class="story-feature">
                            <div class="story-feature-icon"><i class="fas fa-shipping-fast"></i></div>
                            <div>
                                <h4>Giao hàng nhanh chóng</h4>
                                <p>Vận chuyển toàn quốc, đảm bảo sản phẩm đến tay bạn nhanh nhất</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== VALUES ===== -->
    <section class="about-section values-section">
        <div class="section-container">
            <div class="section-header">
                <div class="section-tag animate-on-scroll"><i class="fas fa-star"></i> GIÁ TRỊ CỐT LÕI</div>
                <h2 class="section-heading animate-on-scroll animate-delay-1">Những nguyên tắc định hướng</h2>
                <p class="section-desc animate-on-scroll animate-delay-2">Kim chỉ nam trong mọi quyết định và hoạt động của TLO Fashion</p>
            </div>
            <div class="values-grid">
                <div class="value-card animate-on-scroll animate-delay-1">
                    <div class="value-icon-wrap"><i class="fas fa-palette"></i></div>
                    <h3>Sáng tạo</h3>
                    <p>Không ngừng đổi mới trong thiết kế, mang đến những xu hướng thời trang mới nhất và độc đáo nhất</p>
                </div>
                <div class="value-card animate-on-scroll animate-delay-2">
                    <div class="value-icon-wrap"><i class="fas fa-award"></i></div>
                    <h3>Chất lượng</h3>
                    <p>Cam kết cung cấp sản phẩm chất lượng cao với mức giá hợp lý, xứng đáng với mỗi đồng bạn bỏ ra</p>
                </div>
                <div class="value-card animate-on-scroll animate-delay-3">
                    <div class="value-icon-wrap"><i class="fas fa-users"></i></div>
                    <h3>Khách hàng</h3>
                    <p>Đặt sự hài lòng của khách hàng lên hàng đầu, lắng nghe và phục vụ bằng cả trái tim</p>
                </div>
                <div class="value-card animate-on-scroll animate-delay-1">
                    <div class="value-icon-wrap"><i class="fas fa-leaf"></i></div>
                    <h3>Bền vững</h3>
                    <p>Hướng đến thời trang bền vững, thân thiện với môi trường và có trách nhiệm xã hội</p>
                </div>
                <div class="value-card animate-on-scroll animate-delay-2">
                    <div class="value-icon-wrap"><i class="fas fa-handshake"></i></div>
                    <h3>Uy tín</h3>
                    <p>Xây dựng niềm tin vững chắc thông qua sự minh bạch, trung thực và trách nhiệm</p>
                </div>
                <div class="value-card animate-on-scroll animate-delay-3">
                    <div class="value-icon-wrap"><i class="fas fa-rocket"></i></div>
                    <h3>Đổi mới</h3>
                    <p>Không ngừng học hỏi, cải tiến và ứng dụng công nghệ để mang đến trải nghiệm tốt nhất</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== MILESTONES ===== -->
    <section class="about-section milestone-section">
        <div class="section-container">
            <div class="section-header">
                <div class="section-tag animate-on-scroll" style="border-color: rgba(255,107,107,0.3); background: rgba(255,107,107,0.1);">
                    <i class="fas fa-flag"></i> HÀNH TRÌNH
                </div>
                <h2 class="section-heading animate-on-scroll animate-delay-1">Dấu mốc phát triển</h2>
                <p class="section-desc animate-on-scroll animate-delay-2">Những cột mốc quan trọng trên hành trình xây dựng thương hiệu TLO Fashion</p>
            </div>
            <div class="timeline">
                <div class="timeline-item animate-on-scroll">
                    <div class="timeline-content">
                        <div class="timeline-year">2019</div>
                        <div class="timeline-title">Khởi đầu hành trình</div>
                        <p class="timeline-desc">Cửa hàng TLO Fashion đầu tiên được thành lập tại Hà Nội với tầm nhìn mang thời trang chất lượng đến mọi người</p>
                    </div>
                    <div class="timeline-dot"></div>
                </div>
                <div class="timeline-item animate-on-scroll animate-delay-1">
                    <div class="timeline-content">
                        <div class="timeline-year">2020</div>
                        <div class="timeline-title">Mở rộng trực tuyến</div>
                        <p class="timeline-desc">Ra mắt website thương mại điện tử, mở rộng khả năng phục vụ khách hàng trên toàn quốc</p>
                    </div>
                    <div class="timeline-dot"></div>
                </div>
                <div class="timeline-item animate-on-scroll animate-delay-2">
                    <div class="timeline-content">
                        <div class="timeline-year">2022</div>
                        <div class="timeline-title">5.000 khách hàng</div>
                        <p class="timeline-desc">Đạt mốc 5.000 khách hàng thân thiết, khẳng định vị thế trên thị trường thời trang Việt Nam</p>
                    </div>
                    <div class="timeline-dot"></div>
                </div>
                <div class="timeline-item animate-on-scroll animate-delay-3">
                    <div class="timeline-content">
                        <div class="timeline-year">2024</div>
                        <div class="timeline-title">10.000+ khách hàng tin tưởng</div>
                        <p class="timeline-desc">Tiếp tục phát triển mạnh mẽ với hơn 500 sản phẩm độc quyền và mạng lưới khách hàng trên toàn quốc</p>
                    </div>
                    <div class="timeline-dot"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== COLLECTIONS ===== -->
    <section class="about-section collections-section">
        <div class="section-container">
            <div class="section-header">
                <div class="section-tag animate-on-scroll"><i class="fas fa-fire"></i> BỘ SƯU TẬP</div>
                <h2 class="section-heading animate-on-scroll animate-delay-1">Khám phá phong cách của bạn</h2>
                <p class="section-desc animate-on-scroll animate-delay-2">Những bộ sưu tập được thiết kế riêng, phù hợp với mọi cá tính và phong cách</p>
            </div>
            <div class="collections-grid">
                <div class="collection-card animate-on-scroll animate-delay-1">
                    <div class="collection-img-wrap">
                        <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=800&q=80" alt="Street Style">
                        <span class="collection-label">Mới</span>
                    </div>
                    <div class="collection-body">
                        <h4>Street Style</h4>
                        <p>Phong cách đường phố hiện đại, tự do thể hiện cá tính riêng của bạn</p>
                        <div class="collection-tags">
                            <span class="collection-tag">Urban</span>
                            <span class="collection-tag">Trendy</span>
                            <span class="collection-tag">Comfort</span>
                        </div>
                        <a href="{{ route('client.products.index') }}" class="collection-link">
                            Khám phá ngay <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="collection-card animate-on-scroll animate-delay-2">
                    <div class="collection-img-wrap">
                        <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=800&q=80" alt="Elegant">
                        <span class="collection-label">Premium</span>
                    </div>
                    <div class="collection-body">
                        <h4>Elegant Collection</h4>
                        <p>Thanh lịch và sang trọng, hoàn hảo cho những dịp đặc biệt trong cuộc sống</p>
                        <div class="collection-tags">
                            <span class="collection-tag">Luxury</span>
                            <span class="collection-tag">Elegant</span>
                            <span class="collection-tag">Classic</span>
                        </div>
                        <a href="{{ route('client.products.index') }}" class="collection-link">
                            Khám phá ngay <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="collection-card animate-on-scroll animate-delay-3">
                    <div class="collection-img-wrap">
                        <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=800&q=80" alt="Sport">
                        <span class="collection-label">Hot</span>
                    </div>
                    <div class="collection-body">
                        <h4>Active & Sport</h4>
                        <p>Năng động và thoải mái, đồng hành cùng bạn trong mọi hoạt động thường ngày</p>
                        <div class="collection-tags">
                            <span class="collection-tag">Active</span>
                            <span class="collection-tag">Sport</span>
                            <span class="collection-tag">Casual</span>
                        </div>
                        <a href="{{ route('client.products.index') }}" class="collection-link">
                            Khám phá ngay <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section class="about-section about-cta">
        <div class="section-container">
            <div class="cta-card animate-on-scroll">
                <h2>Sẵn sàng khám phá phong cách<br>của riêng bạn?</h2>
                <p>Hãy để TLO Fashion đồng hành cùng bạn trên hành trình tìm kiếm phong cách hoàn hảo</p>
                <div class="cta-buttons">
                    <a href="{{ route('client.products.index') }}" class="cta-btn-primary">
                        <i class="fas fa-shopping-bag"></i> Xem Sản Phẩm
                    </a>
                    <a href="{{ route('home') }}" class="cta-btn-outline">
                        <i class="fas fa-home"></i> Về Trang Chủ
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@section('scripts')
<script>
// Scroll Animation Observer
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15,
        rootMargin: '0px 0px -40px 0px'
    });

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endsection
