<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\GymSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    // رزرو سانس
    // public function store(Request $request, $sessionId)
    // {
    //     $gymSession = GymSession::findOrFail($gymSessionId); // تغییر نام متغیر

    //     // بررسی ظرفیت سانس
    //     if ($gymSession->current_capacity >= $gymSession->max_capacity) {
    //         return redirect()->back()->with('error', 'ظرفیت سانس تکمیل است.');
    //     }

    // // ایجاد رزرو
    //     Reservation::create([
    //         'user_id' => auth()->id(),
    //         'gym_session_id' => $gymSessionId, // تغییر نام فیلد
    //         'status' => 'pending',
    //     ]);

    // // افزایش تعداد رزروها
    //     $gymSession->increment('current_capacity');

    // // به‌روزرسانی وضعیت سانس
    //     if ($gymSession->current_capacity >= $gymSession->max_capacity) {
    //         $gymSession->update(['status' => 'full']);
    //     } else {
    //         $gymSession->update(['status' => 'reserved']);
    //     }

    public function store(Request $request)
    {
        $request->validate([
            'gym_session_id' => 'required|exists:gym_sessions,id',
            'team_name' => 'required|string|max:255',
            'members' => 'required|array',
            'member_count' => 'required|integer|min:1'
        ]);
    
        $reservation = Reservation::create([
            'user_id' => auth()->id,
            'gym_session_id' => $request->gym_session_id,
            'team_name' => $request->team_name,
            'member_count' => $request->member_count,
            'status' => 'pending'
        ]);
    
        // به‌روزرسانی وضعیت سانس
        $gymSession = GymSession::find($request->gym_session_id);
        $gymSession->updateStatus();
    
        return redirect()->back()->with('success', 'رزرو با موفقیت ثبت شد');
    
        
    }
}
