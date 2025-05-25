
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت‌نام - سیستم نوبت‌دهی سالن ورزشی</title>
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
        }
        
        body {
            font-family: 'B Nazanin', Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ed 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: url('{{ asset("images/stage-lighting-background-3d.jpg") }}');
            background-size: 100vw 46vw;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
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
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
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
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(97, 165, 194, 0.25);
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
            background-color: #f8f9fa;
            border-top: 1px solid #eee;
        }
        
        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: bold;
        }
        
        .input-group-text {
            background-color: #e9ecef;
            border: 1px solid #ddd;
        }
        
        .form-floating label {
            right: auto;
            left: 0;
            padding: 0.375rem 0.75rem;
        }
        
        .password-toggle {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
        
        .validation-error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: -10px;
            margin-bottom: 15px;
            display: block;
        }
        
        @media (max-width: 576px) {
            .auth-card {
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2><i class="bi bi-person-plus"></i> ثبت‌نام در سامانه</h2>
                <p class="mb-0">برای رزرو سانس ورزشی لطفاً ثبت‌نام کنید</p>
            </div>
            
            <div class="auth-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">نام کامل</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">ایمیل</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
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
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        <i class="bi bi-eye-slash password-toggle" onclick="togglePassword('password')"></i>
                        @error('password')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3 position-relative">
                        <label for="password_confirmation" class="form-label">تکرار رمز عبور</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        <i class="bi bi-eye-slash password-toggle" onclick="togglePassword('password_confirmation')"></i>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">با <a href="#">قوانین و مقررات</a> موافقم</label>
                        @error('terms')
                            <span class="validation-error">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="bi bi-person-plus"></i> ثبت‌نام
                    </button>
                </form>
            </div>
            
            <div class="auth-footer">
                <p>قبلاً ثبت‌نام کرده‌اید؟ <a href="{{ route('login') }}">وارد شوید</a></p>
            </div>
        </div>
    </div>

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
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>