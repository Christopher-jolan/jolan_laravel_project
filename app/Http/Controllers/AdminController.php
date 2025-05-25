<?php

namespace App\Http\Controllers;

use App\Models\GymSession;
use App\Models\Reservation;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // رزروهای در انتظار تأیید
        $pendingReservations = Reservation::where('status', 'pending')
            ->with(['user', 'gymSession'])
            ->get();

        // لیست تمام سانس‌ها
        $sessions = GymSession::all();

        // اطلاعیه‌ها
        // $announcements = Announcement::all();

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
        return back()->with('success', 'رزرو تأیید شد');
    }

    public function rejectReservation($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'rejected']);
        return back()->with('success', 'رزرو رد شد');
    }

    public function addSession(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'max_capacity' => 'required|integer|min:1'
        ]);

        GymSession::create($request->all());
        return back()->with('success', 'سانس جدید اضافه شد');
    }

    public function deleteSession($id)
    {
        GymSession::destroy($id);
        return back()->with('success', 'سانس حذف شد');
    }

    public function addAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required'
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'created_by' => auth()->user()->id,
        ]);

        return back()->with('success', 'اطلاعیه اضافه شد');
    }

    public function deleteAnnouncement($id)
    {
        // Announcement::destroy($id);
        return back()->with('success', 'اطلاعیه حذف شد');
    }
}