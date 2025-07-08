<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود - سیستم نوبت‌دهی سالن ورزشی</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #102542; /* Dark Blue */
            --secondary-color: #f87060; /* Desert Sand */
            --accent-color: #cdd7d6; 
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #2a9d8f;
            --card-bg: #ffffff;
            --text-color: #333333;
            --body-bg: #f5f5f5;
        }

        [data-theme="dark"] {
            --primary-color: #102542;
            --secondary-color: #f87060;
            --accent-color: #3a3846;
            --light-color: #2d2b3a;
            --dark-color: #e0e0e0;
            --success-color: #28a745;
            --card-bg: #2d2b3a;
            --text-color: #e0e0e0;
            --body-bg: #1a1824;
        }
        
        body {
            font-family: 'B Nazanin', Arial, sans-serif;
            background-color: var(--body-bg);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        
        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            padding: 20px;
        }
        
        .auth-card {
            width: 100%;
            max-width: 500px;
            background-color: var(--card-bg);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .auth-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .auth-body {
            padding: 30px;
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            transition: all 0.3s;
            background-color: var(--card-bg);
            color: var(--text-color);
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(248, 112, 96, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 12px;
            font-weight: bold;
            width: 100%;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .auth-footer {
            text-align: center;
            padding: 15px;
            background-color: var(--light-color);
            border-top: 1px solid var(--accent-color);
        }
        
        .auth-footer a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: bold;
        }
        
        .input-group-text {
            background-color: var(--accent-color);
            border: 1px solid #ddd;
            color: var(--text-color);
        }
        
        .password-toggle {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-muted);
        }
        
        .validation-error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: -10px;
            margin-bottom: 15px;
            display: block;
        }
        
        .login-method-tabs {
            margin-bottom: 20px;
            border-bottom: 1px solid var(--accent-color);
        }
        
        .login-method-tabs .nav-link {
            color: var(--text-color);
            font-weight: bold;
            border: none;
            padding: 10px 20px;
        }
        
        .login-method-tabs .nav-link.active {
            color: var(--secondary-color);
            background-color: transparent;
            border-bottom: 3px solid var(--secondary-color);
        }
        
        .login-method-tabs .nav-link:hover:not(.active) {
            color: var(--secondary-color);
        }
        
        .theme-switcher {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        
        .back-to-home {
            position: fixed;
            bottom: 80px;
            left: 20px;
            z-index: 1000;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }
        
        @media (max-width: 576px) {
            .auth-card {
                border-radius: 0;
            }
            
            .theme-switcher, .back-to-home {
                bottom: 10px;
                left: 10px;
                width: 40px;
                height: 40px;
            }
            
            .back-to-home {
                bottom: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2><i class="bi bi-box-arrow-in-right"></i> ورود به سامانه</h2>
                <p class="mb-0">برای رزرو سانس ورزشی لطفاً وارد شوید</p>
            </div>
            
            <div class="auth-body">
                <ul class="nav nav-tabs login-method-tabs" id="loginMethodTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="email-tab" data-bs-toggle="tab" data-bs-target="#email-tab-pane" type="button" role="tab">
                            <i class="bi bi-envelope"></i> ورود با ایمیل
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="phone-tab" data-bs-toggle="tab" data-bs-target="#phone-tab-pane" type="button" role="tab">
                            <i class="bi bi-phone"></i> ورود با تلفن
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="loginMethodTabsContent">
                    <div class="tab-pane fade show active" id="email-tab-pane" role="tabpanel">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="login_method" value="email">
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">آدرس ایمیل</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <span class="validation-error">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">رمز عبور</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                <i class="bi bi-eye-slash password-toggle" onclick="togglePassword('password')"></i>
                                @error('password')
                                    <span class="validation-error">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">مرا به خاطر بسپار</label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="bi bi-box-arrow-in-right"></i> ورود
                            </button>
                        </form>
                    </div>
                    
                    <div class="tab-pane fade" id="phone-tab-pane" role="tabpanel">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="login_method" value="phone">
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">شماره تلفن</label>
                                <div class="input-group">
                                    <span class="input-group-text">+98</span>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                </div>
                                @error('phone')
                                    <span class="validation-error">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">رمز عبور</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password_phone" name="password" required>
                                <i class="bi bi-eye-slash password-toggle" onclick="togglePassword('password_phone')"></i>
                                @error('password')
                                    <span class="validation-error">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember_phone" name="remember">
                                <label class="form-check-label" for="remember_phone">مرا به خاطر بسپار</label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="bi bi-box-arrow-in-right"></i> ورود
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="auth-footer">
                <p>حساب کاربری ندارید؟ <a href="{{ route('register') }}">ثبت‌نام کنید</a></p>
                <p><a href="{{ route('password.request') }}">رمز عبور خود را فراموش کرده‌اید؟</a></p>
            </div>
        </div>
    </div>

    <!-- دکمه بازگشت به خانه -->
    <a href="{{ route('home') }}" class="back-to-home" title="بازگشت به صفحه اصلی">
        <i class="bi bi-house-door"></i>
    </a>

    <!-- دکمه تغییر تم -->
    <button class="theme-switcher" id="themeSwitcher">
        <i class="bi bi-moon-stars" id="themeIcon"></i>
    </button>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }
        
        // Switch to phone tab if there's phone validation error
        @if($errors->has('phone'))
            document.addEventListener('DOMContentLoaded', function() {
                const phoneTab = new bootstrap.Tab(document.getElementById('phone-tab'));
                phoneTab.show();
            });
        @endif

        // تغییر تم
        const themeSwitcher = document.getElementById('themeSwitcher');
        const themeIcon = document.getElementById('themeIcon');
        const body = document.body;
        
        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') {
            body.setAttribute('data-theme', 'dark');
            themeIcon.className = 'bi bi-sun';
        }
        
        themeSwitcher.addEventListener('click', () => {
            if (body.getAttribute('data-theme') === 'dark') {
                body.removeAttribute('data-theme');
                themeIcon.className = 'bi bi-moon-stars';
                localStorage.setItem('theme', 'light');
            } else {
                body.setAttribute('data-theme', 'dark');
                themeIcon.className = 'bi bi-sun';
                localStorage.setItem('theme', 'dark');
            }
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>