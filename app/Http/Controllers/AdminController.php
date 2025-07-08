<?php

namespace App\Http\Controllers;

use App\Models\GymSession;
use App\Models\Reservation;
use App\Models\Announcement;
use App\Models\User;
use App\Models\JoinRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('admin');
    // }

    public function dashboard()
    {
        $pendingReservations = Reservation::where('status', 'pending')->get();
        $sessions = GymSession::active()->get();
        $announcements = Announcement::latest()->get();

        return view('admin.dashboard', compact(
            'pendingReservations',
            'sessions',
            'announcements'
        ));
    }

    public function approveReservation($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'approved']);
        
        // به‌روزرسانی وضعیت سانس
        $reservation->gymSession->updateStatus();

        return back()->with('success', 'رزرو با موفقیت تأیید شد');
    }

    public function rejectReservation($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'rejected']);
        return back()->with('success', 'رزرو با موفقیت رد شد');
    }

    public function addSession(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'start_time' => 'required',
        'end_time' => 'required',
        'max_capacity' => 'required|integer|min:1',
        'repeat_weekly' => 'sometimes|boolean'
    ]);

    $date = Carbon::parse($request->date);
    
    GymSession::create([
        'date' => $date->format('Y-m-d'),
        'day_of_week' => $date->format('l'),
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'repeat_weekly' => $request->repeat_weekly ?? false,
        'max_capacity' => $request->max_capacity,
        'status' => 'available'
    ]);

    return back()->with('success', 'سانس جدید با موفقیت ایجاد شد');
}
    public function deleteSession($id)
    {
        $session = GymSession::findOrFail($id);
        $session->delete();
        
        return back()->with('success', 'سانس با موفقیت حذف شد');
    }

    public function addAnnouncement(Request $request)
        {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]);

            Announcement::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'is_active' => Carbon::now()->between($validated['start_time'], $validated['end_time']),
                'created_by' => auth()->id(),
            ]);

            return redirect()->back()->with('success', 'اطلاعیه با موفقیت اضافه شد.');
        }

        public function getAnnouncements()
        {
            return Announcement::active()
                ->orderBy('start_time', 'desc')
                ->get();
        }
    public function deleteAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();
        
        return back()->with('success', 'اطلاعیه با موفقیت حذف شد');
    }
}