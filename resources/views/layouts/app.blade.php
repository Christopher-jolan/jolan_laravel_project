<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">   
    <title>@yield('title') | سیستم نوبت‌دهی سالن ورزشی</title>
    
    <!-- استایل‌های مشترک -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
    :root {
        /* تم لایت (پیش‌فرض) */
        --primary-color: #102542; /* Dark Blue */
        --secondary-color: #f87060; /* Desert Sand */
        --accent-color: #cdd7d6; /* Light Gray */
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --success-color: #2a9d8f;
        --warning-color: #e9c46a;
        --card-bg: #ffffff;
        --text-color: #333333;
        --body-bg: #f5f5f5;
        --text-light: #f8f9fa;
        --text-muted: #6c757d;
    }

    [data-theme="dark"] {
        /* تم دارک - بهبود یافته */
        --primary-color: #928dab; /* Bora */
        --secondary-color: #1f1c2c; /* Skyline */
        --accent-color: #3a3846;
        --light-color: #2d2b3a;
        --dark-color: #ffffff; /* متن سفید خالص */
        --success-color: #28a745;
        --warning-color: #ffc107;
        --card-bg: #2d2b3a;
        --text-color: #ffffff; /* متن سفید خالص */
        --body-bg: #1a1824;
        --text-light: #ffffff;
        --text-muted: #b0b0b0; /* خاکستری روشن برای متن‌های کم اهمیت */
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
    
    .header {
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 20px 15px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .navbar-container {
        background-color: var(--primary-color);
        padding: 10px 15px;
    }
    
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .nav-buttons {
        display: flex;
        gap: 10px;
    }
    
    .nav-btn {
        color: white;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        padding: 8px 12px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    .nav-btn:hover {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-success {
        background-color: var(--success-color);
        border-color: var(--success-color);
    }
    
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    .btn-info {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }
    
    .btn-warning {
        background-color: var(--warning-color);
        border-color: var(--warning-color);
    }
    
    .container {
        flex: 1;
        padding: 20px 15px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .card {
        background-color: var(--card-bg);
        border: none;
        border-radius: 10px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 20px;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
    }
    
    .card-header {
        border-radius: 10px 10px 0 0 !important;
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        color: white;
    }
    
    .footer {
        background-color: var(--primary-color);
        color: white;
        padding: 20px 0;
        text-align: center;
        margin-top: auto;
    }
    
    .footer-links a {
        color: white;
        text-decoration: none;
        margin: 0 10px;
    }
    
    .footer-links a:hover {
        text-decoration: underline;
    }
    
    /* دکمه تغییر تم */
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
    
    .theme-switcher:hover {
        transform: scale(1.1);
    }

    [data-theme="dark"] p,
    [data-theme="dark"] .text-dark,
    [data-theme="dark"] .text-muted,
    [data-theme="dark"] .text-body {
        color: var(--text-color) !important;
    }

    [data-theme="dark"] .text-muted {
        color: var(--text-muted) !important;
    }

    p {
        color: var(--text-color);
        transition: color 0.3s ease;
    }
    
    /* دکمه بازگشت */
    .back-to-home {
        position: fixed;
        bottom: 80px;
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
        text-decoration: none;
    }
    
    .back-to-home:hover {
        transform: scale(1.1);
        color: white;
    }
    
    @media (max-width: 768px) {
        .back-to-home {
            bottom: 70px;
            left: 10px;
            width: 40px;
            height: 40px;
        }
    }

    [data-theme="dark"] label,
    [data-theme="dark"] h4,
    [data-theme="dark"] h5,
    [data-theme="dark"] h6 {
        color: #fff !important;
    }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- هدر مشترک -->
    <div class="header">
        <h1>سامانه نوبت‌دهی سالن ورزشی دانشگاه جندی‌شاپور</h1>
    </div>
    
    <!-- نوار نویگیشن -->
    <div class="navbar-container">
        <div class="navbar">
            <div class="nav-buttons">
                @auth
                    @if(auth()->user()->role === 'admin' && !request()->is('admin*'))
                        <a href="{{ route('admin.dashboard') }}" class="nav-btn btn-info">
                            <i class="bi bi-shield-lock"></i> پنل ادمین
                        </a>
                    @endif
                    
                    @if(!request()->is('dashboard*'))
                        <a href="{{ route('dashboard') }}" class="nav-btn btn-primary">
                            <i class="bi bi-speedometer2"></i> داشبورد
                        </a>
                    @endif
                    
                    <a href="#" class="nav-btn btn-danger" onclick="confirmLogout(event)">
                        <i class="bi bi-box-arrow-left"></i> خروج
                    </a>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-btn btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i> ورود
                    </a>
                    <a href="{{ route('register') }}" class="nav-btn btn-success">
                        <i class="bi bi-person-plus"></i> ثبت‌نام
                    </a>
                @endauth
            </div>
        </div>
    </div>
    
    <!-- محتوای صفحه -->
    <div class="container">
        @yield('content')
        @yield('scripts')
    </div>
    
    <!-- فوتر مشترک -->
    <div class="footer">
        <div class="footer-links">
            <a href="https://t.me/mmdd_jl"><i class="bi bi-telegram"></i> ارتباط با ما (تلگرام)</a> |
            <a href="#"><i class="bi bi-info-circle"></i> درباره ما</a> |
            <a href="#"><i class="bi bi-question-circle"></i> راهنما</a> |
            <a href="#"><i class="bi bi-shield-lock"></i> حریم خصوصی</a>
        </div>
        <p style="color: white !important;">حق کپی رایت © ۲۰۲۳ در اختیار دانشگاه جندی‌شاپور قرار دارد.</p>
    </div>

    <!-- دکمه بازگشت به خانه -->
    <a href="{{ route('home') }}" class="back-to-home" title="بازگشت به صفحه اصلی">
        <i class="bi bi-house-door"></i>
    </a>

    <!-- دکمه تغییر تم -->
    <button class="theme-switcher" id="themeSwitcher">
        <i class="bi bi-moon-stars" id="themeIcon"></i>
    </button>

    <!-- اسکریپت‌ها -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // تغییر تم
        const themeSwitcher = document.getElementById('themeSwitcher');
        const themeIcon = document.getElementById('themeIcon');
        const body = document.body;
        
        // بررسی تم ذخیره شده در localStorage
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

        // تایید خروج
        function confirmLogout(e) {
            e.preventDefault();
            Swal.fire({
                title: 'آیا مطمئن هستید؟',
                text: "می‌خواهید از حساب کاربری خود خارج شوید؟",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله، خارج شوم',
                cancelButtonText: 'انصراف',
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>