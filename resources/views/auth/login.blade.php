<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Clinic System') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f0f9ff;
            color: #1e293b;
        }

        /* Light theme (White & Blue) - DEFAULT */
        body {
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f0fa 100%);
            color: #1e293b;
        }

        .login-card {
            background: #ffffff;
            border-color: #e2e8f0;
        }

        .form-input {
            background: #ffffff;
            border-color: #e2e8f0;
            color: #1e293b;
        }

        .form-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .text-muted {
            color: #64748b !important;
        }

        .social-link {
            border-color: #e2e8f0;
            color: #1e293b;
        }

        .social-link:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        /* Main container */
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .auth-container {
            max-width: 1100px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border-radius: 32px;
        }

        /* Left side - Branding (Blue gradient) */
        .auth-brand {
            padding: 3rem;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .auth-brand::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            opacity: 0.6;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        /* Medical Logo - White */
        .medical-logo {
            position: relative;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .medical-logo:hover {
            transform: scale(1.1) rotate(360deg);
            filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.5));
        }

        .caduceus {
            position: relative;
            width: 40px;
            height: 50px;
        }

        .caduceus-staff {
            position: absolute;
            width: 4px;
            height: 50px;
            background: #ffffff;
            left: 18px;
            top: 0;
        }

        .caduceus-wing {
            position: absolute;
            width: 25px;
            height: 12px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            left: 8px;
        }

        .caduceus-wing:first-child {
            top: 10px;
            transform: rotate(-30deg);
        }

        .caduceus-wing:last-child {
            bottom: 10px;
            transform: rotate(30deg);
        }

        .caduceus-snake {
            position: absolute;
            width: 14px;
            height: 14px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            left: 13px;
        }

        .caduceus-snake:nth-child(3) { top: 8px; }
        .caduceus-snake:nth-child(4) { top: 18px; }
        .caduceus-snake:nth-child(5) { top: 28px; }

        .brand-text {
            font-size: 2rem;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            letter-spacing: -0.5px;
            color: #ffffff;
        }

        .brand-text span {
            font-weight: 300;
            color: rgba(255,255,255,0.9);
        }

        .brand-welcome {
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        .brand-welcome h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
        }

        .brand-welcome p {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.85);
            line-height: 1.6;
        }

        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
            position: relative;
            z-index: 1;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 16px;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }

        .feature-item:hover {
            transform: translateX(10px);
            border-color: rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.2);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .feature-item:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            background: #ffffff;
        }

        .feature-text {
            font-size: 0.9rem;
            font-weight: 500;
            color: #ffffff;
        }

        /* Right side - Login Form (White) */
        .auth-form {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-header h3 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-family: 'Poppins', sans-serif;
            color: #0f172a;
        }

        .form-header p {
            color: #64748b;
            font-size: 0.9rem;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid #e2e8f0;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #ffffff;
            color: #1e293b;
            border-radius: 12px;
        }

        .form-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #2563eb;
            font-size: 1rem;
        }

        .input-icon-wrapper .form-input {
            padding-left: 2.75rem;
        }

        /* Checkbox */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border: 1px solid #cbd5e1;
            cursor: pointer;
            background: #ffffff;
            accent-color: #2563eb;
            border-radius: 4px;
        }

        .checkbox-wrapper label {
            font-size: 0.9rem;
            color: #475569;
            cursor: pointer;
        }

        /* Buttons */
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
            filter: brightness(1.02);
        }

        .btn-link {
            background: none;
            border: none;
            color: #2563eb;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .btn-link:hover {
            color: #1e40af;
            text-decoration: underline;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider-text {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        /* Social login */
        .social-login {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            text-decoration: none;
            color: #1e293b;
            transition: all 0.3s ease;
            border-radius: 12px;
            background: #ffffff;
            font-weight: 500;
        }

        .social-link:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.2);
        }

        .social-link i {
            font-size: 1rem;
        }

        .social-link span {
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Footer links */
        .auth-footer {
            margin-top: 2rem;
            text-align: center;
        }

        .auth-footer span {
            color: #64748b;
            font-size: 0.85rem;
        }

        .auth-footer a {
            color: #2563eb;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .auth-footer a:hover {
            color: #1e40af;
            text-decoration: underline;
        }

        /* Session Status */
        .session-status {
            padding: 1rem;
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #166534;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border-radius: 12px;
        }

        /* Error messages */
        .error-message {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-container {
                grid-template-columns: 1fr;
                border-radius: 24px;
            }

            .auth-brand {
                display: none;
            }

            .auth-form {
                padding: 2rem;
            }

            .social-login {
                grid-template-columns: 1fr;
            }
        }

        /* Utility classes */
        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <!-- Left side - Branding (Blue gradient) -->
            <div class="auth-brand">
                <div class="brand-logo">
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
                    <span class="brand-text">Greyy<span>Care</span></span>
                </div>

                <div class="brand-welcome">
                    <h2>Welcome Back!</h2>
                    <p>Access your medical dashboard to manage appointments, view patient records, and streamline your healthcare practice with our modern clinic system.</p>
                </div>

                <div class="brand-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <span class="feature-text">Easy appointment scheduling</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span class="feature-text">Secure & private data</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <span class="feature-text">24/7 access to records</span>
                    </div>
                </div>
            </div>

            <!-- Right side - Login Form (White) -->
            <div class="auth-form">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="session-status">
                        <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <div class="form-header">
                    <h3>Sign In</h3>
                    <p>Enter your credentials to access your account</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">EMAIL ADDRESS</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="username"
                                   class="form-input"
                                   placeholder="your@email.com">
                        </div>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">PASSWORD</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input id="password" 
                                   type="password"
                                   name="password"
                                   required 
                                   autocomplete="current-password"
                                   class="form-input"
                                   placeholder="Enter your password">
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="checkbox-wrapper">
                            <input id="remember_me" type="checkbox" name="remember">
                            <label for="remember_me">Remember me</label>
                        </div>

                        @if (Route::has('password.request'))
                            <button type="button" class="btn-link" onclick="window.location.href='{{ route('password.request') }}'">
                                Forgot password?
                            </button>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn-login">
                        <i class="fas fa-arrow-right-to-bracket" style="margin-right: 0.5rem;"></i>
                        SIGN IN
                    </button>

                    <!-- Divider -->
                    <div class="divider">
                        <span class="divider-line"></span>
                        <span class="divider-text">OR CONTINUE WITH</span>
                        <span class="divider-line"></span>
                    </div>

                    <!-- Social Login -->
                    <div class="social-login">
                        <a href="#" class="social-link">
                            <i class="fab fa-google"></i>
                            <span>Google</span>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-facebook-f"></i>
                            <span>Facebook</span>
                        </a>
                    </div>

                    <!-- Register Link -->
                    @if (Route::has('register'))
                        <div class="auth-footer">
                            <span>Don't have an account? </span>
                            <a href="{{ route('register') }}">Create one now</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</body>
</html>