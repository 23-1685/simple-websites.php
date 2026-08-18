<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧺 Daystar Digital Laundry - Welcome</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #faf8f6;
            overflow-x: hidden;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            padding: 15px 0;
        }

        .navbar-custom .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            color: #0f6c48;
        }

        .navbar-custom .navbar-brand i {
            color: #38ef7d;
            margin-right: 8px;
        }

        .navbar-custom .nav-link {
            font-weight: 600;
            color: #2d2d2d !important;
            margin: 0 10px;
            transition: all 0.3s;
            position: relative;
        }

        .navbar-custom .nav-link:hover {
            color: #0f6c48 !important;
        }

        .navbar-custom .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #11998e, #38ef7d);
            transition: width 0.3s ease;
        }

        .navbar-custom .nav-link:hover::after {
            width: 100%;
        }

        .btn-nav {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            color: white !important;
            padding: 8px 25px !important;
            border-radius: 50px !important;
            font-weight: 600 !important;
            transition: all 0.3s;
        }

        .btn-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(17, 153, 142, 0.35);
            color: white !important;
        }

        .btn-nav-outline {
            border: 2px solid #0f6c48 !important;
            color: #0f6c48 !important;
            padding: 8px 25px !important;
            border-radius: 50px !important;
            font-weight: 600 !important;
            transition: all 0.3s;
        }

        .btn-nav-outline:hover {
            background: #0f6c48 !important;
            color: white !important;
        }

        .hero-section {
            min-height: 85vh;
            display: flex;
            align-items: center;
            padding: 80px 0 60px;
            background: linear-gradient(160deg, #faf8f6 0%, #e8f5e9 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(56, 239, 125, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -5%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(17, 153, 142, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(17, 153, 142, 0.12);
            color: #0f6c48;
            padding: 6px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }

        .hero-title {
            font-weight: 900;
            font-size: 3.8rem;
            line-height: 1.1;
            color: #1a1a2e;
        }

        .hero-title .highlight {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: #5a5a7a;
            margin: 20px 0 30px;
            max-width: 500px;
            line-height: 1.8;
        }

        .hero-cta .btn {
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.05rem;
            transition: all 0.3s;
        }

        .hero-cta .btn-primary-custom {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            border: none;
            color: white;
            box-shadow: 0 8px 30px rgba(17, 153, 142, 0.3);
        }

        .hero-cta .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(17, 153, 142, 0.45);
        }

        .hero-cta .btn-outline-custom {
            border: 2px solid #0f6c48;
            color: #0f6c48;
            background: transparent;
        }

        .hero-cta .btn-outline-custom:hover {
            background: #0f6c48;
            color: white;
            transform: translateY(-3px);
        }

        .brand-image-wrapper {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .brand-card {
            background: white;
            border-radius: 30px;
            padding: 25px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.08);
            text-align: center;
            max-width: 420px;
            width: 100%;
            transition: all 0.4s;
            border: 1px solid rgba(255,255,255,0.6);
        }

        .brand-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 35px 80px rgba(0,0,0,0.12);
        }

        .brand-card .brand-image {
            width: 100%;
            max-height: 280px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 12px;
        }

        .brand-name {
            font-weight: 800;
            font-size: 1.4rem;
            color: #0f6c48;
            letter-spacing: 0.5px;
        }

        .brand-tagline {
            color: #5a5a7a;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .brand-divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #11998e, #38ef7d);
            margin: 8px auto;
            border-radius: 10px;
        }

        .mama-badge {
            display: inline-block;
            background: rgba(255, 193, 7, 0.15);
            color: #b8860b;
            padding: 4px 16px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 8px;
        }

        .mama-badge i {
            color: #ffc107;
        }

        .features-section {
            padding: 80px 0;
            background: white;
        }

        .section-label {
            display: inline-block;
            background: rgba(17, 153, 142, 0.1);
            color: #0f6c48;
            padding: 4px 18px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .section-title {
            font-weight: 800;
            font-size: 2.5rem;
            color: #1a1a2e;
            margin-top: 10px;
        }

        .feature-card {
            background: #faf8f6;
            border-radius: 20px;
            padding: 30px 25px;
            transition: all 0.4s;
            border: 1px solid transparent;
            text-align: center;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            border-color: rgba(17, 153, 142, 0.2);
            box-shadow: 0 15px 40px rgba(0,0,0,0.05);
            background: white;
        }

        .feature-card .feature-icon {
            font-size: 2.8rem;
            display: block;
            margin-bottom: 15px;
        }

        .feature-card h5 {
            font-weight: 700;
            color: #1a1a2e;
        }

        .feature-card p {
            color: #5a5a7a;
            font-size: 0.95rem;
        }

        .how-section {
            padding: 80px 0;
            background: #faf8f6;
        }

        .step-card {
            text-align: center;
            padding: 20px;
        }

        .step-number {
            display: inline-block;
            width: 60px;
            height: 60px;
            line-height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #11998e, #38ef7d);
            color: white;
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .step-card h5 {
            font-weight: 700;
            color: #1a1a2e;
        }

        .step-card p {
            color: #5a5a7a;
            font-size: 0.95rem;
        }

        .social-section {
            padding: 60px 0 80px;
            background: white;
        }

        .social-card {
            background: #faf8f6;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            transition: all 0.4s;
            border: 1px solid transparent;
            height: 100%;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .social-card:hover {
            transform: translateY(-5px);
            border-color: rgba(17, 153, 142, 0.15);
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }

        .social-card .social-icon {
            font-size: 3rem;
            display: block;
            margin-bottom: 12px;
        }

        .social-card .social-name {
            font-weight: 700;
            color: #1a1a2e;
        }

        .social-card .social-handle {
            color: #0f6c48;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .footer {
            background: #1a1a2e;
            color: rgba(255,255,255,0.7);
            padding: 40px 0 20px;
        }

        .footer .brand-footer {
            font-weight: 800;
            font-size: 1.5rem;
            color: white;
        }

        .footer .brand-footer i {
            color: #38ef7d;
        }

        .footer a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: all 0.3s;
        }

        .footer a:hover {
            color: white;
        }

        .footer .social-icons a {
            display: inline-block;
            margin-right: 15px;
            font-size: 1.4rem;
            color: rgba(255,255,255,0.5);
            transition: all 0.3s;
        }

        .footer .social-icons a:hover {
            color: #38ef7d;
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .brand-card {
                margin-top: 30px;
                max-width: 100%;
            }

            .hero-section {
                padding: 60px 0 40px;
                min-height: auto;
            }

            .section-title {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 576px) {

            .hero-title {
                font-size: 2rem;
            }

            .hero-cta .btn {
                padding: 12px 25px;
                font-size: 0.9rem;
                width: 100%;
                margin-bottom: 10px;
            }
        }

    </style>
</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">

    <div class="container">

        <a class="navbar-brand" href="index.php">
            <i class="fas fa-tshirt"></i>
            Daystar Laundry
        </a>

        <button
            class="navbar-toggler border-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#features">
                        Services
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#social">
                        Connect
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link btn-nav-outline mx-2"
                        href="track_order.php"
                    >
                        📦 Track Order
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link btn-nav"
                        href="login.php"
                    >
                        Login
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link btn-nav"
                        href="register.php"
                        style="background: #8ad1b6;"
                    >
                        Register
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>


<!-- HERO SECTION -->

<section class="hero-section" id="home">

    <div class="container">

        <div class="row align-items-center">

            <!-- Left Content -->

            <div class="col-lg-6 hero-content">

                <div class="hero-badge">

                    <i class="fas fa-sparkles me-1"></i>

                    Welcome to Daystar Digital Laundry

                </div>

                <h1 class="hero-title">

                    Your Clothes
                    <br>

                    <span class="highlight">
                        Deserve the Best
                    </span>

                    Care

                </h1>

                <p class="hero-subtitle">

                    Say goodbye to lost socks and wet receipts!

                    <br>

                    Place orders, track your laundry,
                    and get notified—all from your phone.

                </p>

                <div class="hero-cta d-flex flex-wrap gap-3">

                    <a
                        href="register.php"
                        class="btn btn-primary-custom"
                    >

                        <i class="fas fa-user-plus me-2"></i>

                        Get Started

                    </a>

                    <a
                        href="#features"
                        class="btn btn-outline-custom"
                    >

                        <i class="fas fa-info-circle me-2"></i>

                        Learn More

                    </a>

                </div>

                <div class="mt-4">

                    <span class="mama-badge">

                        <i class="fas fa-heart"></i>

                        Meet Mama Fua —
                        We treat your clothes like family!

                    </span>

                </div>

            </div>


            <!-- Right: Professional Brand Banner -->

            <div class="col-lg-6 brand-image-wrapper">

                <div class="brand-card">

                    <!--
                        IMAGE ADDED HERE.
                        Path assumes brand-banner.png is saved inside an "images" folder
                        next to this PHP file, e.g.:
                            index.php
                            images/brand-banner.png
                        If your file lives elsewhere, change the src="" value below.
                    -->
                    <img
                        src="assets/images/brand-banner.png"
                        alt="Step Laundry Solution - Clean. Fresh. Delivered."
                        class="brand-image"
                    >

                    <div class="brand-name">

                        🧺 Step Laundry Solution

                    </div>

                    <div class="brand-divider"></div>

                    <div class="brand-tagline">

                        Clean. Fresh. Delivered. 🚀

                    </div>

                    <div
                        style="
                            margin-top:10px;
                            font-size:0.85rem;
                            color:#5a5a7a;
                        "
                    >

                        <span>⭐</span>

                        Trusted by Daystar Students

                        <span>⭐</span>

                    </div>

                    <div
                        style="
                            margin-top:4px;
                            font-size:0.7rem;
                            color:#0f6c48;
                            font-weight:600;
                        "
                    >

                        #StepLaundrySolution
                        #DaystarLaundry

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- FEATURES -->

<section class="features-section" id="features">

    <div class="container">

        <div class="text-center mb-5">

            <span class="section-label">
                ✨ Why Choose Us
            </span>

            <h2 class="section-title">
                Laundry Made Simple
            </h2>

            <p class="text-muted">
                We bring the laundry to your doorstep—
                digital &amp; delightful!
            </p>

        </div>


        <div class="row g-4">


            <div class="col-md-4">

                <div class="feature-card">

                    <span class="feature-icon">
                        📱
                    </span>

                    <h5>
                        Order Anywhere
                    </h5>

                    <p>
                        Place your laundry order 24/7
                        from your phone or laptop.
                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="feature-card">

                    <span class="feature-icon">
                        📍
                    </span>

                    <h5>
                        Real-Time Tracking
                    </h5>

                    <p>
                        Know exactly where your laundry is—
                        from Pending to Ready for Pickup.
                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="feature-card">

                    <span class="feature-icon">
                        💬
                    </span>

                    <h5>
                        Instant Notifications
                    </h5>

                    <p>
                        Get notified when your laundry is ready.
                        No more guessing.
                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="feature-card">

                    <span class="feature-icon">
                        💰
                    </span>

                    <h5>
                        Flexible Payments
                    </h5>

                    <p>
                        Pay via Cash, Card, or M-Pesa.
                        Multiple options available.
                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="feature-card">

                    <span class="feature-icon">
                        📊
                    </span>

                    <h5>
                        Smart Reports
                    </h5>

                    <p>
                        Track your laundry history
                        and spending easily.
                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="feature-card">

                    <span class="feature-icon">
                        🤝
                    </span>

                    <h5>
                        Trust &amp; Transparency
                    </h5>

                    <p>
                        No lost items, no surprises.
                        We treat your clothes with love.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- HOW IT WORKS -->

<section class="how-section">

    <div class="container">

        <div class="text-center mb-5">

            <span class="section-label">
                🔄 How It Works
            </span>

            <h2 class="section-title">
                Laundry in 3 Easy Steps
            </h2>

            <p class="text-muted">
                Simple, fast, and stress-free!
            </p>

        </div>


        <div class="row g-4">


            <div class="col-md-4">

                <div class="step-card">

                    <div class="step-number">
                        1
                    </div>

                    <h5>
                        📝 Place Order
                    </h5>

                    <p>
                        Log in, select your services,
                        and submit your laundry order online.
                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="step-card">

                    <div class="step-number">
                        2
                    </div>

                    <h5>
                        🧼 We Wash
                    </h5>

                    <p>
                        Drop off your clothes or request pickup.
                        We wash, dry, and iron with care.
                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="step-card">

                    <div class="step-number">
                        3
                    </div>

                    <h5>
                        ✅ Collect &amp; Pay
                    </h5>

                    <p>
                        Get notified when ready.
                        Pick up your fresh clothes and pay securely.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- SOCIAL MEDIA -->

<section class="social-section" id="social">

    <div class="container">

        <div class="text-center mb-5">

            <span class="section-label">
                📱 Connect With Us
            </span>

            <h2 class="section-title">
                Find Us on Social Media
            </h2>

            <p class="text-muted">
                Follow us for updates, tips, and special offers!
            </p>

        </div>


        <div class="row g-4 justify-content-center">


            <div class="col-md-3 col-sm-6">

                <a
                    href="#"
                    target="_blank"
                    class="social-card"
                >

                    <span
                        class="social-icon"
                        style="color:#1877f2;"
                    >
                        📘
                    </span>

                    <div class="social-name">
                        Facebook
                    </div>

                    <span class="social-handle">

                        <i class="fab fa-facebook me-1"></i>

                        @DaystarLaundry

                    </span>

                </a>

            </div>


            <div class="col-md-3 col-sm-6">

                <a
                    href="#"
                    target="_blank"
                    class="social-card"
                >

                    <span
                        class="social-icon"
                        style="color:#e4405f;"
                    >
                        📸
                    </span>

                    <div class="social-name">
                        Instagram
                    </div>

                    <span class="social-handle">

                        <i class="fab fa-instagram me-1"></i>

                        @DaystarLaundry

                    </span>

                </a>

            </div>


            <div class="col-md-3 col-sm-6">

                <a
                    href="#"
                    target="_blank"
                    class="social-card"
                >

                    <span
                        class="social-icon"
                        style="color:#000000;"
                    >
                        🎵
                    </span>

                    <div class="social-name">
                        TikTok
                    </div>

                    <span class="social-handle">

                        <i class="fab fa-tiktok me-1"></i>

                        @DaystarLaundry

                    </span>

                </a>

            </div>


            <div class="col-md-3 col-sm-6">

                <a
                    href="#"
                    target="_blank"
                    class="social-card"
                >

                    <span
                        class="social-icon"
                        style="color:#25d366;"
                    >
                        💬
                    </span>

                    <div class="social-name">
                        WhatsApp
                    </div>

                    <span class="social-handle">

                        <i class="fab fa-whatsapp me-1"></i>

                        +254 712 345 678

                    </span>

                </a>

            </div>

        </div>

    </div>

</section>


<!-- FOOTER -->

<footer class="footer">

    <div class="container">

        <div class="row">


            <div class="col-lg-4 mb-4">

                <div class="brand-footer">

                    <i class="fas fa-tshirt"></i>

                    Daystar Laundry

                </div>

                <p
                    style="
                        margin-top:12px;
                        font-size:0.95rem;
                    "
                >

                    Digital laundry management
                    for the Daystar community.

                    <br>

                    Clean clothes, happy students! 🧺

                </p>

                <div class="social-icons">

                    <a href="#">
                        <i class="fab fa-facebook-f"></i>
                    </a>

                    <a href="#">
                        <i class="fab fa-instagram"></i>
                    </a>

                    <a href="#">
                        <i class="fab fa-tiktok"></i>
                    </a>

                    <a href="#">
                        <i class="fab fa-twitter"></i>
                    </a>

                    <a href="#">
                        <i class="fab fa-youtube"></i>
                    </a>

                </div>

            </div>


            <div class="col-lg-2 col-md-4 mb-4">

                <h6
                    style="
                        color:white;
                        font-weight:700;
                    "
                >
                    Quick Links
                </h6>

                <ul class="list-unstyled">

                    <li>
                        <a href="index.php">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="#features">
                            Services
                        </a>
                    </li>

                    <li>
                        <a href="track_order.php">
                            Track Order
                        </a>
                    </li>

                </ul>

            </div>


            <div class="col-lg-3 col-md-4 mb-4">

                <h6
                    style="
                        color:white;
                        font-weight:700;
                    "
                >
                    Student Resources
                </h6>

                <ul class="list-unstyled">

                    <li>
                        <a href="#">
                            Pricing Guide
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Laundry Tips
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            FAQs
                        </a>
                    </li>

                </ul>

            </div>


            <div class="col-lg-3 col-md-4 mb-4">

                <h6
                    style="
                        color:white;
                        font-weight:700;
                    "
                >
                    Contact
                </h6>

                <ul class="list-unstyled">

                    <li>

                        <i
                            class="fas fa-map-marker-alt me-2"
                            style="color:#38ef7d;"
                        ></i>

                        Daystar Arthi Campus

                    </li>

                    <li>

                        <i
                            class="fas fa-phone me-2"
                            style="color:#38ef7d;"
                        ></i>

                        +254 712 345 678

                    </li>

                    <li>

                        <i
                            class="fas fa-envelope me-2"
                            style="color:#38ef7d;"
                        ></i>

                        info@daystarlaundry.com

                    </li>

                </ul>

            </div>

        </div>


        <hr
            style="
                border-color:rgba(255,255,255,0.1);
            "
        >


        <div class="text-center">

            <p
                style="
                    font-size:0.85rem;
                    margin-bottom:0;
                "
            >

                &copy; 2026

                <strong>
                    Daystar Digital Laundry
                </strong>

                — Built with ❤️ for the Daystar Community

                <br>

                <span
                    style="
                        font-size:0.75rem;
                        opacity:0.6;
                    "
                >


                

            </span>

            </p>

        </div>

    </div>

</footer>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>
</html>