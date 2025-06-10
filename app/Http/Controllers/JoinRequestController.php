<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\JoinRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; 

class JoinRequestController extends Controller
{
    // ارسال درخواست الحاق
    public function store(Request $request, Reservation $reservation)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        // دیباگ: نمایش داده‌های ورودی
        \Log::debug('Join Request Data:', [
            'user_id' => auth()->id(),
            'reservation_id' => $reservation->id,
            'message' => $request->message
        ]);

        try {
            // بررسی مالکیت
            if ($reservation->user_id == auth()->id()) {
                return back()->with('error', 'نمی‌توانید به تیم خود درخواست دهید!');
            }

            // ایجاد درخواست
            $joinRequest = JoinRequest::create([
                'user_id' => auth()->id(),
                'reservation_id' => $reservation->id,
                'status' => 'pending',
                'message' => $request->message
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'درخواست با موفقیت ارسال شد!');

        } catch (\Exception $e) {
            \Log::error('Join Request Error: '.$e->getMessage());
            return back()->with('error', 'خطا در سیستم!');
        }
    }

    public function create(Reservation $reservation)
    {
        // بررسی اینکه کاربر مالک رزرو نیست
        if ($reservation->user_id === auth()->id()) {
            abort(403, 'شما نمی‌توانید به تیم خود درخواست الحاق دهید.');
        }

        return view('join_requests.create', compact('reservation'));
    }
    // تأیید درخواست الحاق
    public function approve($joinRequestId)
    {
        $joinRequest = JoinRequest::findOrFail($joinRequestId);

        // بررسی ظرفیت تیم
        if ($joinRequest->reservation->gymSession->current_capacity >= $joinRequest->reservation->gymSession->max_capacity) {
            return redirect()->back()->with('error', 'ظرفیت تیم تکمیل است.');
        }

        // تأیید درخواست
        $joinRequest->update(['status' => 'approved']);
        $joinRequest->reservation->gymSession->increment('current_capacity');

        // بررسی تکمیل ظرفیت
        if ($joinRequest->reservation->gymSession->current_capacity >= $joinRequest->reservation->gymSession->max_capacity) {
            $joinRequest->reservation->gymSession->update(['status' => 'full']);
        }

        return redirect()->back()->with('success', 'درخواست الحاق تأیید شد.');
    }

    // رد درخواست الحاق
    public function reject($joinRequestId)
    {
        $joinRequest = JoinRequest::findOrFail($joinRequestId);

        // رد درخواست
        $joinRequest->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'درخواست الحاق رد شد.');
    }
}
