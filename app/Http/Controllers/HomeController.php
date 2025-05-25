<?php

namespace App\Http\Controllers;

use App\Models\GymSession; // import مدل GymSession
use Illuminate\Http\Request;


class HomeController extends Controller
{
    
    public function index()
    {
        // دریافت سانس‌ها از دیتابیس
        // $gymSessions = GymSession::all(); // تغییر نام متغیر
        $gymSessions = GymSession::with(['reservations.user'])->get();
        // $gymSessions = [
        //     (object) [
        //         'id' => 1,
        //         'date' => 'یکشنبه ۲۰ اسفند',
        //         'start_time' => '۲۱:۰۰',
        //         'end_time' => '۲۲:۳۰',
        //         'status' => 'available',
        //     ],
        //     (object) [
        //         'id' => 2,
        //         'date' => 'سه‌شنبه ۲۲ اسفند',
        //         'start_time' => '۱۹:۳۰',
        //         'end_time' => '۲۱:۰۰',
        //         'status' => 'available',
        //     ],
        //     (object) [
        //         'id' => 3,
        //         'date' => 'سه‌شنبه ۲۲ اسفند',
        //         'start_time' => '۲۲:۳۰',
        //         'end_time' => '۰۰:۰۰',
        //         'status' => 'available',
        //     ],
        // ];

        // نمایش صفحه اصلی
        return view('home', compact('gymSessions')); // تغییر نام متغیر
    }
    
}
