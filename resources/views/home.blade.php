<!-- <!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحه اصلی - سیستم نوبت‌دهی سالن ورزشی</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'B Nazanin', Arial, sans-serif;
        }
        .session-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .session-card.reserved {
            background-color: #f8d7da;
        }
        .session-card.available {
            background-color: #d4edda;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">سانس‌های سالن ورزشی</h1>
        @foreach ($gymSessions as $session)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $session->date }} - {{ $session->start_time }} تا {{ $session->end_time }}</h5>
                    <p class="card-text">وضعیت: {{ $session->status }}</p>
                    @auth
                        @if ($session->status === 'available')
                            <form action="{{ route('reservations.store', $session->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">رزرو سانس</button>
                            </form>
                        @endif
                    @else
                        <p>برای رزرو سانس، لطفاً وارد شوید.</p>
                    @endauth
                </div>
            </div>
        @endforeach
    </div>
</body>
</html> -->

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحه اصلی - سیستم نوبت‌دهی سالن ورزشی</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'B Nazanin', Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007bff;
            padding: 10px 20px;
        }
        .navbar a {
            color: white;
            margin-left: 15px;
            text-decoration: none;
        }
        .navbar a:hover {
            color: #f8f9fa;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            display: flex;
            margin-top: 20px;
        }
        .sidebar {
            width: 25%;
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 8px;
            margin-left: 20px;
        }
        .main-content {
            width: 75%;
        }
        .card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .card-text {
            font-size: 1rem;
            color: #555;
        }
        .footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 20px;
        }
        .footer a {
            color: white;
            text-decoration: none;
        }
        .footer a:hover {
            color: #f8f9fa;
        }
    </style>
</head>
<body>
    <!-- نوار نویگیشن -->
    <div class="navbar">
        <a href="#" class="btn btn-success">تکمیل ظرفیت</a>
        <a href="{{ route('register') }}" class="btn btn-primary">ثبت‌نام</a>
        <a href="{{ route('login') }}" class="btn btn-light">ورود</a>
    </div>

    <!-- هدر -->
    <div class="header">
        <h1>به سایت نوبت‌دهی سالن ورزشی دانشگاه جندی‌شاپور خوش آمدید</h1>
    </div>

    <!-- محتوای اصلی -->
    <div class="container">
        <!-- سانس‌ها -->
        <div class="main-content">
            @foreach ($gymSessions as $session)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $session->date }} - {{ $session->start_time }} تا {{ $session->end_time }}</h5>
                        <p class="card-text">وضعیت: {{ $session->status }}</p>
                        @auth
                            @if ($session->status === 'available')
                                <form action="{{ route('reservations.store', $session->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">رزرو سانس</button>
                                </form>
                            @endif
                        @else
                            <p>برای رزرو سانس، لطفاً <a href="{{ route('register') }}">ثبت‌نام</a> کنید.</p>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <!-- نوار اطلاعیه -->
        <div class="sidebar">
            <h3>اطلاعیه‌ها</h3>
            <p>سالن ورزشی در تاریخ ۲۰ اسفند به دلیل تعمیرات تعطیل می‌باشد.</p>
        </div>
    </div>

    <!-- فوتر -->
    <div class="footer">
        <p>
            <a href="https://t.me/mmdd_jl">ارتباط با ما (تلگرام)</a> |
            <a href="#">درباره ما</a>
        </p>
        <p>حق کپی رایت © ۲۰۲۳ در اختیار این توسعه‌دهنده قرار دارد.</p>
    </div>
</body>
</html>