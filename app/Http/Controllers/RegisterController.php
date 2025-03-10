<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    // نمایش فرم ثبت‌نام
    public function showRegistrationForm()
    {
        return view('auth.register'); // ویو فرم ثبت‌نام
    }

    // پردازش فرم ثبت‌نام
    public function register(Request $request)
    {
        // اعتبارسنجی داده‌های ورودی
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => User::rules(), // اعتبارسنجی شماره موبایل
            'password' => 'required|string|min:8|confirmed',
        ]);

        // ایجاد کاربر جدید
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        // ریدایرکت به صفحه اصلی با پیام موفقیت
        return redirect()->route('home')->with('success', 'ثبت‌نام شما با موفقیت انجام شد.');
    }
}