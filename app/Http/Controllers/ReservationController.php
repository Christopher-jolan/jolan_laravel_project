<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationController extends Controller
{
    // رزرو سانس
    public function store(Request $request, $sessionId)
    {
        $gymSession = GymSession::findOrFail($gymSessionId); // تغییر نام متغیر

    // بررسی ظرفیت سانس
    if ($gymSession->current_capacity >= $gymSession->max_capacity) {
        return redirect()->back()->with('error', 'ظرفیت سانس تکمیل است.');
    }

    // ایجاد رزرو
    Reservation::create([
        'user_id' => auth()->id(),
        'gym_session_id' => $gymSessionId, // تغییر نام فیلد
        'status' => 'pending',
    ]);

    // افزایش تعداد رزروها
    $gymSession->increment('current_capacity');

    // به‌روزرسانی وضعیت سانس
    if ($gymSession->current_capacity >= $gymSession->max_capacity) {
        $gymSession->update(['status' => 'full']);
    } else {
        $gymSession->update(['status' => 'reserved']);
    }

    return redirect()->back()->with('success', 'سانس با موفقیت رزرو شد.');
    }
}
