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
        try {
            // Log the incoming request data
            Log::info('Join Request Store Method Called', [
                'user_id' => Auth::id(),
                'reservation_id' => $reservation->id,
                'request_data' => $request->all()
            ]);

        $request->validate([
            'message' => 'required|string|max:500'
        ]);

            // Check if user is authenticated
            if (!Auth::check()) {
                Log::warning('Unauthenticated user tried to create join request');
                return redirect()->route('login')->with('error', 'لطفاً ابتدا وارد حساب کاربری خود شوید.');
            }

            // Check ownership
            if ($reservation->user_id == Auth::id()) {
                Log::warning('User tried to join their own team', [
                    'user_id' => Auth::id(),
                    'reservation_id' => $reservation->id
                ]);
                return back()->with('error', 'نمی‌توانید به تیم خود درخواست دهید!');
            }
            
            // Check existing requests
            $existingRequest = JoinRequest::where('user_id', Auth::id())
                                        ->where('reservation_id', $reservation->id)
                                        ->first();

            if ($existingRequest) {
                Log::info('Existing join request found', [
                    'request_id' => $existingRequest->id,
                    'status' => $existingRequest->status
                ]);

                if ($existingRequest->status === 'pending') {
                    return back()->with('error', 'شما قبلاً یک درخواست الحاق در حال انتظار برای این تیم ارسال کرده‌اید.');
                } elseif ($existingRequest->status === 'approved') {
                    return back()->with('error', 'شما قبلاً به این تیم ملحق شده‌اید.');
                }
            }

            // Create the join request
            $joinRequest = JoinRequest::create([
                'user_id' => Auth::id(),
                'reservation_id' => $reservation->id,
                'status' => 'pending',
                'message' => $request->message
            ]);

            Log::info('Join Request created successfully', [
                'join_request_id' => $joinRequest->id,
                'user_id' => Auth::id(),
                'reservation_id' => $reservation->id
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'درخواست با موفقیت ارسال شد!');

        } catch (\Exception $e) {
            Log::error('Join Request Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'reservation_id' => $reservation->id ?? null
            ]);
            return back()->with('error', 'خطا در سیستم! لطفاً دوباره تلاش کنید.');
        }
    }

    public function create(Reservation $reservation)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'لطفاً ابتدا وارد حساب کاربری خود شوید.');
        }

        // Check ownership
        if ($reservation->user_id === Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'شما نمی‌توانید به تیم خود درخواست الحاق دهید.');
        }
        
        // Check existing requests
        $existingRequest = JoinRequest::where('user_id', Auth::id())
                                        ->where('reservation_id', $reservation->id)
                                        ->first();

        if ($existingRequest) {
            if ($existingRequest->status === 'pending') {
                return redirect()->route('dashboard')->with('error', 'شما قبلاً یک درخواست الحاق در حال انتظار برای این تیم ارسال کرده‌اید.');
            } elseif ($existingRequest->status === 'approved') {
                return redirect()->route('dashboard')->with('error', 'شما قبلاً به این تیم ملحق شده‌اید.');
            }
        }

        return view('join_requests.create', compact('reservation'));
    }
    // تأیید درخواست الحاق
    public function approve($joinRequestId)
    {
        $joinRequest = JoinRequest::findOrFail($joinRequestId);

        // اطمینان از اینکه کاربر لاگین شده مالک رزرو مربوطه است.
        if ($joinRequest->reservation->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'شما مجاز به تأیید این درخواست نیستید.');
        }

        // استفاده از رابطه `gymSession` روی مدل `reservation`
        $gymSession = $joinRequest->reservation->gymSession;

        // بررسی ظرفیت تیم
        if ($gymSession->current_capacity >= $gymSession->max_capacity) {
            return redirect()->back()->with('error', 'ظرفیت تیم تکمیل است.');
        }

        // تأیید درخواست
        $joinRequest->update(['status' => 'approved']);
        $gymSession->increment('current_capacity'); // افزایش ظرفیت فعلی

        // بررسی تکمیل ظرفیت بعد از افزایش
        if ($gymSession->current_capacity >= $gymSession->max_capacity) {
            $gymSession->update(['status' => 'full']);
        }

        return redirect()->back()->with('success', 'درخواست الحاق تأیید شد.');
    }

    // رد درخواست الحاق
    public function reject($joinRequestId)
    {
        $joinRequest = JoinRequest::findOrFail($joinRequestId);

        // اطمینان از اینکه کاربر لاگین شده مالک رزرو مربوطه است.
        if ($joinRequest->reservation->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'شما مجاز به رد این درخواست نیستید.');
        }

        // رد درخواست
        $joinRequest->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'درخواست الحاق رد شد.');
    }
}