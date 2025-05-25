<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\JoinRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class JoinRequestController extends Controller
{
    // ارسال درخواست الحاق
    public function store(Request $request, $reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        // بررسی ظرفیت تیم
        if ($reservation->gymSession->current_capacity >= $reservation->gymSession->max_capacity) {
            return redirect()->back()->with('error', 'ظرفیت تیم تکمیل است.');
        }

        // ایجاد درخواست الحاق
        JoinRequest::create([
            'user_id' => auth()->id,
            'reservation_id' => $reservationId,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'درخواست الحاق شما ارسال شد.');
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
