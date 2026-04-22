<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    @php
        $settingsData = \App\Models\Setting::pluck('value', 'key')->toArray();
        $companyName = $settingsData['company_name'] ?? 'TechPOS';
        $appLogoSetting = \App\Models\Setting::where('key', 'app_logo')->first();
        $logoUrl = $appLogoSetting && $appLogoSetting->hasMedia('logo')
            ? asset($appLogoSetting->getFirstMediaUrl('logo'))
            : asset('adminPanel/assets/images/logo_sm.png');
    @endphp
    <title>Log In | {{ $companyName }} - ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ $companyName }} Restaurant Management System - Login" name="description" />
    <link rel="shortcut icon" href="{{ $logoUrl }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --brand-primary: #1e293b;
            --brand-secondary: #0f172a;
            --dark-bg: #0f172a;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(255, 255, 255, 0.5);
            --text-main: #1e293b;
            --text-muted: #64748b;
            --radius: 20px;
            --shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--dark-bg);
            overflow: hidden;
            position: relative;
        }

        /* Premium Backdrop - NO ANIMATION */
        .bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(15, 23, 42, 0.5) 100%),
                url('{{ asset('adminPanel/assets/images/login_bg_premium.png') }}');
            background-size: cover;
            background-position: center;
            z-index: -1;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1000px;
            padding: 20px;
            display: flex;
            justify-content: center;
            z-index: 10;
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            display: flex;
            width: 100%;
            min-height: 580px;
            overflow: hidden;
        }

        /* Form Side */
        .login-content {
            flex: 1.2;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Branding Side */
        .login-side {
            flex: 1;
            background: var(--brand-secondary);
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
            position: relative;
        }

        @media (max-width: 850px) {
            .login-side {
                display: none;
            }

            .login-card {
                max-width: 480px;
            }
        }

        .logo-box {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .logo-img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .logo-text {
            margin-left: 12px;
        }

        .logo-text h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.5px;
            line-height: 1;
        }

        .logo-text p {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-title {
            margin-bottom: 30px;
        }

        .header-title h2 {
            font-size: 30px;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 5px;
        }

        .header-title p {
            color: var(--text-muted);
            font-size: 15px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 14px 15px 14px 45px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            color: var(--text-main);
            outline: none;
        }

        .form-control:focus {
            border-color: var(--brand-primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-muted);
            font-size: 16px;
            background: none;
            border: none;
            z-index: 5;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .remember-checkbox {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 14px;
            color: var(--text-muted);
        }

        .remember-checkbox input {
            width: 16px;
            height: 16px;
            margin-right: 8px;
        }

        .forgot-link {
            color: var(--text-main);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: var(--brand-secondary);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login:hover {
            opacity: 0.95;
        }

        .side-content h3 {
            font-size: 32px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .side-content p {
            font-size: 16px;
            opacity: 0.8;
            margin-bottom: 30px;
            font-weight: 300;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.08);
            padding: 15px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .feature-card i {
            font-size: 18px;
            margin-bottom: 6px;
            display: block;
        }

        .feature-card span {
            font-size: 12px;
            font-weight: 500;
        }

        .skywaves-branding {
            margin-top: 40px;
            text-align: center;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding-top: 25px;
        }

        .skywaves-branding p {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .social-row {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .social-icon {
            width: 32px;
            height: 32px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-secondary);
            text-decoration: none;
            font-size: 14px;
        }

        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>

<body>
    <div class="bg-overlay"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-content">
                <div class="logo-box">
                    <img src="{{ $logoUrl }}" class="logo-img" alt="Logo">
                    <div class="logo-text">
                        <h1>{{ $companyName }}</h1>
                        <p>TechPOS Restaurant Management System</p>
                    </div>
                </div>

                <div class="header-title">
                    <h2>Sign In</h2>
                    <p>Enter details to access control panel.</p>
                </div>

                @if ($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-block">
                        <label class="form-label">Email Address</label>
                        <div class="input-group-custom">
                            <input type="email" name="email" class="form-control" placeholder="admin@system.com"
                                required value="{{ old('email') }}" autofocus>
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <div class="input-block">
                        <label class="form-label">Password</label>
                        <div class="input-group-custom">
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="••••••••" required>
                            <i class="fas fa-lock"></i>
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-footer">
                        <label class="remember-checkbox">
                            <input type="checkbox" name="remember">
                            Remember me
                        </label>
                        <!-- @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                        @endif -->
                    </div>

                    <button type="submit" class="btn-login">
                        <span>Sign In</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="skywaves-branding">
                    <p>Powered by Skywaves</p>
                    <div class="social-row">
                        <a href="https://www.facebook.com/skywavesit.tech" target="_blank" class="social-icon"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="https://www.linkedin.com/company/skywaves-network-it-solution" target="_blank"
                            class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.instagram.com/skywaves_it_solution/" target="_blank" class="social-icon"><i
                                class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            <div class="login-side">
                <div class="side-content">
                    <h3>Smart Solution for Business Growth.</h3>
                    <p>Streamlined management for modern restaurant operations. Efficiency starts here.</p>

                    <div class="feature-grid">
                        <div class="feature-card">
                            <i class="fas fa-utensils"></i>
                            <span>Menu Control</span>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-chart-pie"></i>
                            <span>Analytics</span>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-users"></i>
                            <span>Staff Control</span>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-boxes"></i>
                            <span>Inventory</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#togglePassword').on('click', function () {
                const input = $('#password');
                const icon = $(this).find('i');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
</body>

</html>