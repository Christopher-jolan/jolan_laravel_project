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
        --primary-color: #1a4b7a;
        --secondary-color: #2c7da0;
        --accent-color: #61a5c2;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --success-color: #2a9d8f;
        --warning-color: #e9c46a;
    }
    
    body {
        font-family: 'B Nazanin', Arial, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ed 100%);
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
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
    }
    
    .nav-btn:hover {
        opacity: 0.9;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
    }
    
    .btn-success {
        background-color: var(--success-color);
    }
    
    .btn-danger {
        background-color: #dc3545;
    }
    
    .btn-info {
        background-color: #17a2b8;
    }
    
    .container {
        flex: 1;
        padding: 20px 15px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* استایل‌های دیگر... */
    
    @media (max-width: 768px) {
        .nav-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .nav-btn {
            width: 100%;
            justify-content: center;
        }
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
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-btn btn-info">
                            <i class="bi bi-shield-lock"></i> پنل ادمین
                        </a>
                    @endif
                    
                    <a href="{{ route('dashboard') }}" class="nav-btn btn-primary">
                        <i class="bi bi-speedometer2"></i> داشبورد
                    </a>
                    
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
        <p>حق کپی رایت © ۲۰۲۳ در اختیار دانشگاه جندی‌شاپور قرار دارد.</p>
    </div>

    <!-- اسکریپت‌ها -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        console.log('Form element:', document.getElementById('joinRequestForm'));
        console.log('Button element:', document.getElementById('submitBtn'));
    </script>
<script>
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

        // مطمئن شوید این کد در انتهای صفحه و قبل از بسته شدن تگ </body> قرار دارد
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('joinRequestForm');
    const btn = document.getElementById('submitBtn');
    
    if (!form || !btn) {
        console.error('خطا: فرم یا دکمه پیدا نشد!');
        console.log('فرم:', form);
        console.log('دکمه:', btn);
        return;
    }
    
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('فرم در حال ارسال...');
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...';
            
            // برای دیباگ
            console.log('وضعیت دکمه:', btn.disabled);
            console.log('محتویات دکمه:', btn.innerHTML);
            
            // ارسال واقعی فرم
            this.submit();
        });
    });
 </script>
 <script>
document.getElementById('joinRequestForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('submitBtn');
    console.log('Before:', btn.disabled, btn.innerHTML);
    
    btn.disabled = true;
    btn.innerHTML = 'در حال ارسال...';
    
    console.log('After:', btn.disabled, btn.innerHTML);
    return true; // اجازه دهید فرم ارسال شود
});
</script>
    
@stack('scripts')
</body>
</html>