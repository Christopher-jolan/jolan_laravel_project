<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم نوبت‌دهی سالن ورزشی</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        @font-face {
            font-family: 'Vazir';
            src: url('https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/Vazir.woff2') format('woff2');
        }
        
        body {
            font-family: 'Vazir', Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ed 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .time-slot {
            transition: all 0.3s ease;
        }
        
        .time-slot:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .animated-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(45deg, #1a4b7a, #2c7da0, #61a5c2, #2a9d8f);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .wave-shape {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }
        
        .wave-shape svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 150px;
        }
        
        .wave-shape .shape-fill {
            fill: #FFFFFF;
        }
    </style>
</head>
<body class="text-gray-800">
    <!-- Animated Background -->
    <div class="animated-bg"></div>
    
    <!-- Header -->
    <header class="text-white py-6 relative overflow-hidden">
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">سامانه نوبت‌دهی سالن ورزشی</h1>
            <p class="text-lg opacity-90">دانشگاه صنعتی جندی‌شاپور</p>
            
            <!-- Decorative elements -->
            <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center opacity-10">
                <i class="bi bi-trophy text-9xl"></i>
            </div>
        </div>
        
        <!-- Wave shape divider -->
        <div class="wave-shape">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-blue-900 to-blue-700 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center py-3">
                <div class="flex items-center mb-4 md:mb-0">
                    <i class="bi bi-calendar2-check text-2xl ml-2"></i>
                    <span class="font-bold text-lg">نوبت‌دهی آنلاین</span>
                </div>
                
                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4 space-x-reverse w-full md:w-auto">
                    <a href="#" class="flex items-center justify-center px-4 py-2 rounded-lg bg-blue-800 hover:bg-blue-600 transition-all">
                        <i class="bi bi-speedometer2 ml-2"></i>
                        داشبورد
                    </a>
                    <a href="#" class="flex items-center justify-center px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-600 transition-all">
                        <i class="bi bi-calendar2-plus ml-2"></i>
                        رزرو جدید
                    </a>
                    <a href="#" class="flex items-center justify-center px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-500 transition-all">
                        <i class="bi bi-clock-history ml-2"></i>
                        نوبت‌های من
                    </a>
                    <a href="#" onclick="confirmLogout(event)" class="flex items-center justify-center px-4 py-2 rounded-lg bg-rose-700 hover:bg-rose-600 transition-all">
                        <i class="bi bi-box-arrow-left ml-2"></i>
                        خروج
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Sidebar -->
            <div class="lg:col-span-1">
                <div class="glass-card p-6 mb-6">
                    <h3 class="text-xl font-bold mb-4 text-blue-800 border-b pb-2">پروفایل کاربری</h3>
                    <div class="flex flex-col items-center text-center">
                        <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center mb-4 overflow-hidden">
                            <i class="bi bi-person-circle text-5xl text-blue-600"></i>
                        </div>
                        <h4 class="font-bold">محمد رضایی</h4>
                        <p class="text-sm text-gray-600 mb-4">کاربر ویژه</p>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 75%"></div>
                        </div>
                        <p class="text-xs text-gray-500">امتیاز شما: ۷۵ از ۱۰۰</p>
                    </div>
                </div>
                
                <div class="glass-card p-6">
                    <h3 class="text-xl font-bold mb-4 text-blue-800 border-b pb-2">دسترسی سریع</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 transition-all">
                                <i class="bi bi-credit-card-2-front text-blue-600 ml-2"></i>
                                پرداخت‌های من
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 transition-all">
                                <i class="bi bi-gear text-blue-600 ml-2"></i>
                                تنظیمات حساب
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 transition-all">
                                <i class="bi bi-question-circle text-blue-600 ml-2"></i>
                                راهنمای سیستم
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 transition-all">
                                <i class="bi bi-telephone text-blue-600 ml-2"></i>
                                پشتیبانی
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content Area -->
            <div class="lg:col-span-2">
                <div class="glass-card p-6 mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-blue-800">رزرو سالن ورزشی</h2>
                        <div class="relative">
                            <select class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded-lg leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>سالن والیبال</option>
                                <option>سالن بسکتبال</option>
                                <option>سالن بدنسازی</option>
                                <option>استخر</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-2 text-gray-700">
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">انتخاب تاریخ</h3>
                            <div class="flex space-x-2 space-x-reverse">
                                <button class="p-2 rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                                <button class="p-2 rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-7 gap-2 text-center">
                            <div class="py-2 font-semibold text-gray-500">ش</div>
                            <div class="py-2 font-semibold text-gray-500">ی</div>
                            <div class="py-2 font-semibold text-gray-500">د</div>
                            <div class="py-2 font-semibold text-gray-500">س</div>
                            <div class="py-2 font-semibold text-gray-500">چ</div>
                            <div class="py-2 font-semibold text-gray-500">پ</div>
                            <div class="py-2 font-semibold text-gray-500">ج</div>
                            
                            <!-- Calendar days -->
                            <div class="py-2 text-gray-400">29</div>
                            <div class="py-2 text-gray-400">30</div>
                            <div class="py-2 text-gray-400">31</div>
                            <div class="py-2 rounded-lg bg-blue-100 font-semibold">1</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">2</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">3</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">4</div>
                            
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">5</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">6</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">7</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">8</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">9</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">10</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">11</div>
                            
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">12</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">13</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">14</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">15</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">16</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">17</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">18</div>
                            
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">19</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">20</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">21</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">22</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">23</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">24</div>
                            <div class="py-2 hover:bg-gray-100 rounded-lg cursor-pointer">25</div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-4">زمان‌های قابل رزرو</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <!-- Time slots -->
                            <div class="time-slot p-3 rounded-lg border border-gray-200 bg-white text-center cursor-pointer hover:border-blue-300 hover:shadow-md">
                                <div class="text-sm text-gray-500">صبح</div>
                                <div class="font-bold">۸:۰۰ - ۱۰:۰۰</div>
                                <div class="text-xs text-green-600 mt-1">خالی</div>
                            </div>
                            <div class="time-slot p-3 rounded-lg border border-gray-200 bg-white text-center cursor-pointer hover:border-blue-300 hover:shadow-md">
                                <div class="text-sm text-gray-500">صبح</div>
                                <div class="font-bold">۱۰:۰۰ - ۱۲:۰۰</div>
                                <div class="text-xs text-green-600 mt-1">خالی</div>
                            </div>
                            <div class="time-slot p-3 rounded-lg border border-gray-200 bg-white text-center cursor-pointer hover:border-blue-300 hover:shadow-md">
                                <div class="text-sm text-gray-500">ظهر</div>
                                <div class="font-bold">۱۲:۰۰ - ۱۴:۰۰</div>
                                <div class="text-xs text-red-600 mt-1">رزرو شده</div>
                            </div>
                            <div class="time-slot p-3 rounded-lg border border-gray-200 bg-white text-center cursor-pointer hover:border-blue-300 hover:shadow-md">
                                <div class="text-sm text-gray-500">ظهر</div>
                                <div class="font-bold">۱۴:۰۰ - ۱۶:۰۰</div>
                                <div class="text-xs text-green-600 mt-1">خالی</div>
                            </div>
                            <div class="time-slot p-3 rounded-lg border border-gray-200 bg-white text-center cursor-pointer hover:border-blue-300 hover:shadow-md">
                                <div class="text-sm text-gray-500">عصر</div>
                                <div class="font-bold">۱۶:۰۰ - ۱۸:۰۰</div>
                                <div class="text-xs text-green-600 mt-1">خالی</div>
                            </div>
                            <div class="time-slot p-3 rounded-lg border border-gray-200 bg-white text-center cursor-pointer hover:border-blue-300 hover:shadow-md">
                                <div class="text-sm text-gray-500">عصر</div>
                                <div class="font-bold">۱۸:۰۰ - ۲۰:۰۰</div>
                                <div class="text-xs text-amber-600 mt-1">در انتظار تایید</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="glass-card p-6">
                    <h3 class="text-xl font-bold mb-4 text-blue-800 border-b pb-2">اطلاعات رزرو</h3>
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                    نام و نام خانوادگی
                                </label>
                                <input class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="name" type="text" placeholder="نام کامل">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="student-id">
                                    شماره دانشجویی
                                </label>
                                <input class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="student-id" type="text" placeholder="شماره دانشجویی">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="purpose">
                                هدف از رزرو
                            </label>
                            <select class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded-lg leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="purpose">
                                <option>تمرین تیمی</option>
                                <option>مسابقه دوستانه</option>
                                <option>مسابقه رسمی</option>
                                <option>سایر موارد</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">
                                توضیحات (اختیاری)
                            </label>
                            <textarea class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="notes" rows="3" placeholder="توضیحات مورد نیاز"></textarea>
                        </div>
                        
                        <div class="flex justify-end">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg flex items-center transition-all">
                                <i class="bi bi-check2-circle ml-2"></i>
                                تایید و پرداخت
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-lg font-bold mb-4 border-b pb-2 border-gray-700">درباره ما</h4>
                    <p class="text-gray-400 text-sm">سامانه نوبت‌دهی سالن‌های ورزشی دانشگاه جندی‌شاپور با هدف تسهیل دسترسی دانشجویان و کارکنان به امکانات ورزشی دانشگاه طراحی شده است.</p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4 border-b pb-2 border-gray-700">لینک‌های مفید</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">قوانین و مقررات</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">سوالات متداول</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">راهنمای استفاده</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">تماس با ما</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4 border-b pb-2 border-gray-700">تماس با ما</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li class="flex items-center">
                            <i class="bi bi-geo-alt ml-2"></i>
                            اهواز، دانشگاه جندی‌شاپور
                        </li>
                        <li class="flex items-center">
                            <i class="bi bi-telephone ml-2"></i>
                            ۰۶۱-۳۳۳۳۳۳۳
                        </li>
                        <li class="flex items-center">
                            <i class="bi bi-envelope ml-2"></i>
                            sport@jundishapur.ac.ir
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4 border-b pb-2 border-gray-700">شبکه‌های اجتماعی</h4>
                    <div class="flex space-x-4 space-x-reverse">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <i class="bi bi-telegram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-blue-400 transition-colors">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center hover:bg-red-600 transition-colors">
                            <i class="bi bi-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-sm text-gray-400">
                <p>کلیه حقوق این سامانه متعلق به دانشگاه صنعتی جندی‌شاپور می‌باشد. © ۱۴۰۲</p>
            </div>
        </div>
    </footer>

    <!-- Logout Modal (hidden by default) -->
    <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">خروج از حساب کاربری</h3>
                <button onclick="closeLogoutModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <p class="text-gray-600 mb-6">آیا مطمئن هستید که می‌خواهید از حساب کاربری خود خارج شوید؟</p>
            <div class="flex justify-end space-x-4 space-x-reverse">
                <button onclick="closeLogoutModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                    انصراف
                </button>
                <button onclick="performLogout()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    بله، خارج شوم
                </button>
            </div>
        </div>
    </div>

    <script>
        // Logout functions
        function confirmLogout(e) {
            e.preventDefault();
            document.getElementById('logoutModal').classList.remove('hidden');
        }
        
        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
        }
        
        function performLogout() {
            // Here you would typically make an AJAX call to logout
            alert('شما با موفقیت خارج شدید.');
            closeLogoutModal();
            // Then redirect to login page
            // window.location.href = '/login';
        }
        
        // Time slot selection
        document.querySelectorAll('.time-slot').forEach(slot => {
            slot.addEventListener('click', function() {
                // Remove active class from all slots
                document.querySelectorAll('.time-slot').forEach(s => {
                    s.classList.remove('border-blue-500', 'bg-blue-50');
                });
                
                // Add active class to clicked slot
                this.classList.add('border-blue-500', 'bg-blue-50');
            });
        });
        
        // Calendar day selection
        document.querySelectorAll('.calendar-grid div:not(.text-gray-400)').forEach(day => {
            day.addEventListener('click', function() {
                // Remove active class from all days
                document.querySelectorAll('.calendar-grid div').forEach(d => {
                    d.classList.remove('bg-blue-100', 'font-semibold');
                });
                
                // Add active class to clicked day
                this.classList.add('bg-blue-100', 'font-semibold');
            });
        });
    </script>
</body>
</html>