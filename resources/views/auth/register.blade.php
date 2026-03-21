<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Clinic System') }} - Register</title>

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
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f0fa 100%);
            color: #1e293b;
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
            max-width: 1200px;
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
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 2rem;
            position: relative;
            z-index: 1;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 16px;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }

        .feature-item:hover {
            transform: translateX(5px);
            border-color: rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.2);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 32px;
            height: 32px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 0.9rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .feature-item:hover .feature-icon {
            transform: scale(1.05);
        }

        .feature-text {
            font-size: 0.8rem;
            font-weight: 500;
            color: #ffffff;
        }

        /* Right side - Register Form (White) */
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
            margin-bottom: 1.25rem;
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

        /* Password strength meter */
        .password-strength {
            margin-top: 0.5rem;
            display: flex;
            gap: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            flex: 1;
            background: #e2e8f0;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .strength-bar.active {
            background: #2563eb;
        }

        .strength-bar.weak {
            background: #ef4444;
        }

        .strength-bar.medium {
            background: #f59e0b;
        }

        .strength-bar.strong {
            background: #10b981;
        }

        .password-requirements {
            margin-top: 0.75rem;
            padding: 0.75rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            font-size: 0.75rem;
            border-radius: 12px;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.35rem;
            color: #64748b;
            font-size: 0.7rem;
        }

        .requirement-item i {
            font-size: 0.7rem;
            width: 14px;
            color: #94a3b8;
        }

        .requirement-item.valid i {
            color: #10b981;
        }

        /* Buttons */
        .btn-register {
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

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
            filter: brightness(1.02);
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

        /* Checkbox */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1rem 0;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #2563eb;
            border-radius: 4px;
        }

        .checkbox-wrapper label {
            font-size: 0.85rem;
            color: #475569;
            cursor: pointer;
        }

        .checkbox-wrapper a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .checkbox-wrapper a:hover {
            color: #1e40af;
            text-decoration: underline;
        }

        /* Footer links */
        .auth-footer {
            margin-top: 1.5rem;
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

        /* Error messages */
        .error-message {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }

        /* Success message */
        .success-message {
            color: #10b981;
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

            .brand-features {
                grid-template-columns: 1fr;
            }
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
                    <h2>Join Our Community!</h2>
                    <p>Create your account to access our healthcare platform and enjoy seamless appointment management with modern clinic technology.</p>
                </div>

                <div class="brand-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <span class="feature-text">Connect with top doctors</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <span class="feature-text">Easy appointment scheduling</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                        <span class="feature-text">Access medical records</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <span class="feature-text">Get appointment reminders</span>
                    </div>
                </div>
            </div>

            <!-- Right side - Register Form (White) -->
            <div class="auth-form">
                <div class="form-header">
                    <h3>Create Account</h3>
                    <p>Fill in your details to get started with GreyyCare</p>
                </div>

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">FULL NAME</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input id="name" 
                                   type="text" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="name"
                                   class="form-input"
                                   placeholder="John Doe">
                        </div>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

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
                                   autocomplete="new-password"
                                   class="form-input"
                                   placeholder="Create a strong password">
                        </div>
                        
                        <!-- Password strength indicator -->
                        <div class="password-strength">
                            <div class="strength-bar" id="bar1"></div>
                            <div class="strength-bar" id="bar2"></div>
                            <div class="strength-bar" id="bar3"></div>
                            <div class="strength-bar" id="bar4"></div>
                        </div>

                        <!-- Password requirements -->
                        <div class="password-requirements">
                            <div class="requirement-item" id="req-length">
                                <i class="fas fa-circle"></i>
                                <span>At least 8 characters</span>
                            </div>
                            <div class="requirement-item" id="req-uppercase">
                                <i class="fas fa-circle"></i>
                                <span>At least one uppercase letter</span>
                            </div>
                            <div class="requirement-item" id="req-lowercase">
                                <i class="fas fa-circle"></i>
                                <span>At least one lowercase letter</span>
                            </div>
                            <div class="requirement-item" id="req-number">
                                <i class="fas fa-circle"></i>
                                <span>At least one number</span>
                            </div>
                        </div>
                        
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">CONFIRM PASSWORD</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-check-circle input-icon"></i>
                            <input id="password_confirmation" 
                                   type="password"
                                   name="password_confirmation"
                                   required 
                                   autocomplete="new-password"
                                   class="form-input"
                                   placeholder="Confirm your password">
                        </div>
                        <div id="confirm-message" class="success-message"></div>
                        @error('password_confirmation')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
                    </div>

                    <!-- Register Button -->
                    <button type="submit" class="btn-register" id="registerBtn">
                        <i class="fas fa-user-plus" style="margin-right: 0.5rem;"></i>
                        CREATE ACCOUNT
                    </button>

                    <!-- Divider -->
                    <div class="divider">
                        <span class="divider-line"></span>
                        <span class="divider-text">OR</span>
                        <span class="divider-line"></span>
                    </div>

                    <!-- Login Link -->
                    <div class="auth-footer">
                        <span>Already have an account? </span>
                        <a href="{{ route('login') }}">Sign in here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Password strength checker with real-time validation
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const confirmMessage = document.getElementById('confirm-message');
        const bars = {
            bar1: document.getElementById('bar1'),
            bar2: document.getElementById('bar2'),
            bar3: document.getElementById('bar3'),
            bar4: document.getElementById('bar4')
        };

        // Requirement elements
        const reqLength = document.getElementById('req-length');
        const reqUppercase = document.getElementById('req-uppercase');
        const reqLowercase = document.getElementById('req-lowercase');
        const reqNumber = document.getElementById('req-number');

        function updatePasswordStrength() {
            const value = passwordInput.value;
            
            const hasLower = /[a-z]/.test(value);
            const hasUpper = /[A-Z]/.test(value);
            const hasNumber = /[0-9]/.test(value);
            const isLongEnough = value.length >= 8;
            
            // Calculate strength score (0-4)
            let strength = 0;
            if (isLongEnough) strength++;
            if (hasLower) strength++;
            if (hasUpper) strength++;
            if (hasNumber) strength++;
            
            // Update requirement icons with checkmarks
            reqLength.innerHTML = `<i class="fas fa-${isLongEnough ? 'check-circle' : 'circle'}"></i> <span>At least 8 characters</span>`;
            reqUppercase.innerHTML = `<i class="fas fa-${hasUpper ? 'check-circle' : 'circle'}"></i> <span>At least one uppercase letter</span>`;
            reqLowercase.innerHTML = `<i class="fas fa-${hasLower ? 'check-circle' : 'circle'}"></i> <span>At least one lowercase letter</span>`;
            reqNumber.innerHTML = `<i class="fas fa-${hasNumber ? 'check-circle' : 'circle'}"></i> <span>At least one number</span>`;
            
            if (isLongEnough) reqLength.classList.add('valid');
            else reqLength.classList.remove('valid');
            if (hasUpper) reqUppercase.classList.add('valid');
            else reqUppercase.classList.remove('valid');
            if (hasLower) reqLowercase.classList.add('valid');
            else reqLowercase.classList.remove('valid');
            if (hasNumber) reqNumber.classList.add('valid');
            else reqNumber.classList.remove('valid');
            
            // Update strength bars
            const allBars = [bars.bar1, bars.bar2, bars.bar3, bars.bar4];
            
            allBars.forEach((bar, index) => {
                bar.classList.remove('active', 'weak', 'medium', 'strong');
                if (index < strength) {
                    bar.classList.add('active');
                    if (strength === 1) bar.classList.add('weak');
                    else if (strength === 2) bar.classList.add('medium');
                    else if (strength >= 3) bar.classList.add('strong');
                }
            });
        }

        // Confirm password validation
        function validateConfirmPassword() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;
            
            if (confirm.length === 0) {
                confirmMessage.innerHTML = '';
                return;
            }
            
            if (password === confirm) {
                confirmMessage.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match!';
                confirmMessage.style.color = '#10b981';
            } else {
                confirmMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match';
                confirmMessage.style.color = '#dc2626';
            }
        }

        // Event listeners
        passwordInput.addEventListener('input', function() {
            updatePasswordStrength();
            validateConfirmPassword();
        });
        
        confirmInput.addEventListener('input', validateConfirmPassword);

        // Form validation before submit
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirm = confirmInput.value;
            const terms = document.getElementById('terms').checked;
            
            if (password !== confirm) {
                e.preventDefault();
                confirmMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match!';
                confirmMessage.style.color = '#dc2626';
                return;
            }
            
            if (!terms) {
                e.preventDefault();
                alert('Please agree to the Terms of Service and Privacy Policy');
                return;
            }
            
            // Check password strength
            const hasLower = /[a-z]/.test(password);
            const hasUpper = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const isLongEnough = password.length >= 8;
            
            if (!isLongEnough || !hasLower || !hasUpper || !hasNumber) {
                e.preventDefault();
                alert('Please ensure your password meets all the requirements');
            }
        });

        // Initial check
        updatePasswordStrength();
    </script>
</body>
</html>