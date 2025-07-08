<?php

namespace App\Http\Controllers;

use App\Models\GymSession;
use App\Models\Announcement;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    
    public function index()
    {
        $activeAnnouncements = Announcement::active()->get();
        $gymSessions = GymSession::active()
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('home', compact('gymSessions', 'activeAnnouncements'));
    }
    
}
