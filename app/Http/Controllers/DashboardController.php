<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\JoinRequest;
use App\Models\GymSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $data = [
            'reservations' => Reservation::with(['gymSession', 'team'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get(),
            
            'receivedRequests' => JoinRequest::with(['user', 'reservation.gymSession'])
                ->whereHas('reservation', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderBy('created_at', 'desc')
                ->get(),
                
            'sentRequests' => JoinRequest::with(['reservation.gymSession', 'reservation.user'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()
        ];

        return view('dashboard', $data);
    }

    public function handleJoinRequest(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        $joinRequest = JoinRequest::findOrFail($id);
        
        // بررسی اینکه کاربر مالک رزرو است
        if ($joinRequest->reservation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->action === 'approve') {
            // بررسی ظرفیت سانس
            $gymSession = $joinRequest->reservation->gymSession;
            $totalMembers = $joinRequest->reservation->member_count + 1;
            
            if ($totalMembers > $gymSession->max_capacity) {
                return back()->with('error', 'ظرفیت سانس تکمیل است.');
            }

            $joinRequest->update(['status' => 'approved']);
            $joinRequest->reservation->increment('member_count');
            
            // به‌روزرسانی وضعیت سانس
            $gymSession->updateStatus();
            
            return back()->with('success', 'درخواست با موفقیت تأیید شد.');
        } else {
            $joinRequest->update(['status' => 'rejected']);
            return back()->with('success', 'درخواست با موفقیت رد شد.');
        }
    }
}