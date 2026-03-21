<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GreyyCare | Modern Clinic Appointment System</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background: #f8fafc;
            overflow-x: hidden;
        }

        /* ----- WHITE & BLUE THEME - CLINIC ATMOSPHERE ----- */
        .bg-clinic-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            overflow: hidden;
        }

        .bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(240,248,255,0.96) 100%);
            z-index: 1;
        }

        .bg-slideshow {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.98) contrast(1.02) saturate(0.9);
            transition: opacity 1.8s ease-in-out;
            opacity: 0;
        }

        .bg-slideshow.active {
            opacity: 1;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            width: 100%;
            position: relative;
            z-index: 3;
        }

        /* Navbar - White/Clean with Blue accent */
        .navbar {
            padding: 1rem 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(37, 99, 235, 0.2);
            position: sticky;
            top: 0;
            z-index: 100;
            transition: all 0.3s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .medical-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        }

        .medical-logo:hover {
            transform: rotate(6deg) scale(1.05);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
        }

        .caduceus {
            position: relative;
            width: 22px;
            height: 26px;
        }

        .caduceus-staff {
            position: absolute;
            width: 2px;
            height: 26px;
            background: white;
            left: 10px;
            top: 0;
        }

        .caduceus-wing {
            position: absolute;
            width: 14px;
            height: 6px;
            border: 2px solid white;
            left: 3px;
        }
        .caduceus-wing:first-child { top: 6px; transform: rotate(-30deg); }
        .caduceus-wing:last-child { bottom: 5px; transform: rotate(30deg); }
        .caduceus-snake {
            position: absolute;
            width: 7px;
            height: 7px;
            border: 2px solid white;
            border-radius: 50%;
            left: 7px;
        }
        .caduceus-snake:nth-child(3) { top: 4px; }
        .caduceus-snake:nth-child(4) { top: 11px; }
        .caduceus-snake:nth-child(5) { top: 18px; }

        .logo-text {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0f172a;
        }
        .logo-text span {
            font-weight: 500;
            color: #2563eb;
        }

        /* Buttons - Blue & White scheme */
        .btn {
            padding: 0.6rem 1.4rem;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            border: none;
            cursor: pointer;
        }

        .btn-login {
            background: white;
            color: #2563eb;
            border: 1px solid #2563eb;
        }
        .btn-login:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.25);
        }
        .btn-register, .btn-dashboard {
            background: linear-gradient(95deg, #2563eb, #1e40af);
            color: white;
            border: none;
        }
        .btn-register:hover, .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
            filter: brightness(1.02);
        }
        .btn-primary {
            background: linear-gradient(95deg, #2563eb, #1e40af);
            color: white;
            padding: 0.8rem 2rem;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(37,99,235,0.2);
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.3);
        }
        .btn-secondary {
            background: white;
            border: 1px solid #cbd5e1;
            color: #1e293b;
        }
        .btn-secondary:hover {
            background: #f1f5f9;
            border-color: #2563eb;
            transform: translateY(-2px);
            color: #2563eb;
        }
        .btn-cta {
            background: linear-gradient(95deg, #2563eb, #1e40af);
            padding: 0.9rem 2.2rem;
            font-weight: 700;
            font-size: 1rem;
            color: white;
        }

        /* Hero Section - White/Blue */
        .hero {
            padding: 5rem 0 4rem;
            position: relative;
        }
        .hero-content {
            max-width: 700px;
            margin: 0 auto;
            text-align: center;
        }
        .hero-title {
            font-size: 3.6rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.2rem;
            color: #0f172a;
        }
        .hero-title span {
            display: block;
            font-size: 2rem;
            font-weight: 500;
            color: #2563eb;
        }
        .hero-subtitle {
            font-size: 1.1rem;
            color: #475569;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .hero-buttons {
            display: flex;
            gap: 1.2rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Modern Card Grid - White cards with blue accents */
        .features {
            padding: 5rem 0;
            background: #f8fafc;
        }
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .section-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.3px;
        }
        .section-subtitle {
            color: #475569;
            font-size: 1.1rem;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.8rem;
        }
        .feature-card {
            background: white;
            border-radius: 28px;
            padding: 1.8rem;
            transition: all 0.35s ease;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .feature-card:hover {
            border-color: #2563eb;
            transform: translateY(-8px);
            box-shadow: 0 20px 32px -12px rgba(37, 99, 235, 0.2);
        }
        .feature-icon {
            width: 56px;
            height: 56px;
            background: #eff6ff;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 1.6rem;
            margin-bottom: 1.2rem;
            transition: all 0.3s;
        }
        .feature-card:hover .feature-icon {
            background: #2563eb;
            color: white;
            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.25);
        }
        .feature-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.6rem;
        }
        .feature-description {
            color: #475569;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* Stats Section - Light with blue numbers */
        .stats {
            padding: 3rem 0 5rem;
            background: #ffffff;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.8rem;
        }
        .stat-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 32px;
            text-align: center;
            padding: 2rem 1rem;
            transition: all 0.3s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        .stat-card:hover {
            border-color: #2563eb;
            transform: scale(1.02);
            box-shadow: 0 12px 24px -12px rgba(37,99,235,0.15);
        }
        .stat-number {
            font-size: 2.8rem;
            font-weight: 800;
            color: #2563eb;
            margin-bottom: 0.4rem;
        }
        .stat-label {
            color: #334155;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* CTA section - Blue gradient */
        .cta {
            padding: 4rem 0 6rem;
            background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
        }
        .cta-content {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 48px;
            padding: 3.5rem 2rem;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            transition: all 0.3s;
            box-shadow: 0 8px 28px rgba(0,0,0,0.04);
        }
        .cta-content:hover {
            border-color: #2563eb;
            box-shadow: 0 20px 35px -12px rgba(37, 99, 235, 0.2);
        }
        .cta-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #0f172a;
        }
        .cta-subtitle {
            color: #475569;
            margin-bottom: 2rem;
        }

        /* Footer - Light theme */
        .footer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 3rem 0 2rem;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 2rem;
        }
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            margin-bottom: 1rem;
        }
        .footer-logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(145deg, #2563eb, #1e40af);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }
        .footer-description {
            color: #475569;
            font-size: 0.85rem;
            max-width: 240px;
        }
        .footer-column h4 {
            color: #0f172a;
            font-size: 1rem;
            margin-bottom: 1.2rem;
            font-weight: 600;
        }
        .footer-column ul {
            list-style: none;
        }
        .footer-column li {
            margin-bottom: 0.6rem;
        }
        .footer-column a {
            color: #5b6e8c;
            text-decoration: none;
            transition: 0.2s;
            font-size: 0.9rem;
        }
        .footer-column a:hover {
            color: #2563eb;
            padding-left: 4px;
        }
        .social-links {
            display: flex;
            gap: 0.8rem;
            margin-top: 1rem;
        }
        .social-link {
            width: 34px;
            height: 34px;
            background: #f1f5f9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            transition: 0.25s;
            text-decoration: none;
        }
        .social-link:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-3px);
        }
        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
            margin-top: 2rem;
            color: #64748b;
            font-size: 0.85rem;
        }

        @media (max-width: 1024px) {
            .features-grid, .stats-grid { grid-template-columns: repeat(2,1fr); }
        }
        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .navbar .container { flex-direction: column; gap: 1rem; }
            .footer-grid { grid-template-columns: 1fr; text-align: center; }
            .footer-logo { justify-content: center; }
            .footer-description { margin: 0 auto; }
            .social-links { justify-content: center; }
            .features-grid, .stats-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 540px) {
            .hero-title { font-size: 2rem; }
            .hero-title span { font-size: 1.3rem; }
        }
    </style>
</head>
<body>

<!-- BACKGROUND CLINIC IMAGES SLIDESHOW (Light overlay for white theme) -->
<div class="bg-clinic-wrapper">
    <div class="bg-overlay"></div>
    <!-- Professional clinic/medical imagery with bright & clean atmosphere -->
    <img class="bg-slideshow active" src="https://images.pexels.com/photos/4021775/pexels-photo-4021775.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="Modern clinic interior bright">
    <img class="bg-slideshow" src="https://images.pexels.com/photos/3845749/pexels-photo-3845749.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="Doctor consulting patient">
    <img class="bg-slideshow" src="https://images.pexels.com/photos/6646917/pexels-photo-6646917.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="Hospital reception clean">
    <img class="bg-slideshow" src="https://images.pexels.com/photos/5215024/pexels-photo-5215024.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="Medical team smiling">
</div>

<!-- Navbar - White & Blue -->
<nav class="navbar">
    <div class="container">
        <div class="logo">
            <div class="medical-logo">
                <div class="caduceus">
                    <div class="caduceus-staff"></div>
                    <div class="caduceus-wing"></div>
                    <div class="caduceus-snake"></div>
                    <div class="caduceus-snake"></div>
                    <div class="caduceus-snake"></div>
                    <div class="caduceus-wing"></div>
                </div>
            </div>
            <div class="logo-text">Greyy<span>Care</span></div>
        </div>
        <div class="nav-buttons">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-dashboard"><i class="fas fa-stethoscope"></i> Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-login"><i class="fas fa-sign-in-alt"></i> Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-register"><i class="fas fa-user-md"></i> Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>

<!-- Hero section with clean white/blue messaging -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">
                GreyyCare
                <span>Precision. Compassion. Innovation.</span>
            </h1>
            <p class="hero-subtitle">
                Seamless digital clinic experience — book instant appointments, access virtual care, and manage health records with state-of-the-art technology.
            </p>
            <div class="hero-buttons">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary"><i class="fas fa-calendar-plus"></i> Book Appointment</a>
                    <a href="#features" class="btn btn-secondary"><i class="fas fa-hospital-user"></i> Explore Services</a>
                @else
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary"><i class="fas fa-chalkboard-user"></i> My Dashboard</a>
                @endguest
            </div>
        </div>
    </div>
</section>

<!-- Features Section - White cards, blue icons -->
<section id="features" class="features">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Smart Clinic Ecosystem</h2>
            <p class="section-subtitle">Empowering patients & providers with modern tools</p>
        </div>
        <div class="features-grid">
            <div class="feature-card"><div class="feature-icon"><i class="fas fa-calendar-alt"></i></div><h3 class="feature-title">Smart Scheduling</h3><p class="feature-description">Real-time availability, automated conflict resolution, and instant confirmations.</p></div>
            <div class="feature-card"><div class="feature-icon"><i class="fas fa-video"></i></div><h3 class="feature-title">Telemedicine Hub</h3><p class="feature-description">Virtual consultations with HD video, prescriptions & follow-ups.</p></div>
            <div class="feature-card"><div class="feature-icon"><i class="fas fa-notes-medical"></i></div><h3 class="feature-title">EHR Integration</h3><p class="feature-description">Secure electronic health records accessible anytime, anywhere.</p></div>
            <div class="feature-card"><div class="feature-icon"><i class="fas fa-bell"></i></div><h3 class="feature-title">AI Reminders</h3><p class="feature-description">Smart notifications via SMS/email reduce no-shows by 40%.</p></div>
        </div>
    </div>
</section>

<!-- Stats Section - Light background, blue numbers -->
<section class="stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card"><div class="stat-number">22K+</div><div class="stat-label">Active Patients</div></div>
            <div class="stat-card"><div class="stat-number">120+</div><div class="stat-label">Specialists</div></div>
            <div class="stat-card"><div class="stat-number">58K+</div><div class="stat-label">Yearly Visits</div></div>
            <div class="stat-card"><div class="stat-number">4.9★</div><div class="stat-label">Patient Rating</div></div>
        </div>
    </div>
</section>

<!-- CTA Section for guests - Light blue gradient -->
@guest
<section class="cta">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">Your journey to better health starts here</h2>
            <p class="cta-subtitle">Join 22,000+ patients who trust GreyyCare for modern, compassionate clinic management.</p>
            <a href="{{ route('register') }}" class="btn btn-cta"><i class="fas fa-heartbeat"></i> Get Started Free <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>
@endguest

<!-- Footer - Light theme, blue accents -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-logo"><div class="footer-logo-icon"><i class="fas fa-hand-holding-heart"></i></div><span class="footer-logo-text" style="font-weight:700; color:#0f172a;">GreyyCare</span></div>
                <p class="footer-description">Redefining clinic appointment systems with empathy & innovation. Your health partner.</p>
                <div class="social-links"><a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a><a href="#" class="social-link"><i class="fab fa-twitter"></i></a><a href="#" class="social-link"><i class="fab fa-instagram"></i></a><a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a></div>
            </div>
            <div class="footer-column"><h4>Resources</h4><ul><li><a href="#">Find a Doctor</a></li><li><a href="#">Health Blog</a></li><li><a href="#">Patient Portal</a></li><li><a href="#">Insurance</a></li></ul></div>
            <div class="footer-column"><h4>Support</h4><ul><li><a href="#">Help Center</a></li><li><a href="#">FAQs</a></li><li><a href="#">Live Chat</a></li><li><a href="#">Feedback</a></li></ul></div>
            <div class="footer-column"><h4>Legal</h4><ul><li><a href="#">Privacy Policy</a></li><li><a href="#">Terms of Use</a></li><li><a href="#">HIPAA Compliance</a></li><li><a href="#">Security</a></li></ul></div>
        </div>
        <div class="footer-bottom"><p>&copy; {{ date('Y') }} GreyyCare — Modern Clinic Appointment System. All rights reserved.</p></div>
    </div>
</footer>

<script>
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if(targetId === "#") return;
            const targetElem = document.querySelector(targetId);
            if(targetElem) {
                targetElem.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Background slideshow for clinic imagery
    let currentSlide = 0;
    const slides = document.querySelectorAll('.bg-slideshow');
    if(slides.length > 1) {
        setInterval(() => {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }, 5500);
    }

    // subtle parallax effect for depth
    const bgOverlay = document.querySelector('.bg-overlay');
    if(bgOverlay) {
        window.addEventListener('mousemove', (e) => {
            let x = (window.innerWidth / 2 - e.pageX) / 60;
            let y = (window.innerHeight / 2 - e.pageY) / 60;
            bgOverlay.style.transform = `translate(${x * 0.2}px, ${y * 0.15}px)`;
        });
    }
</script>
</body>
</html>