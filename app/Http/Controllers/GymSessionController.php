<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GymSession;
use App\Models\Reservation;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;


class GymSessionController extends Controller
{
    public function index()
    {
        $gymSessions = GymSession::with(['reservations.team.members'])
            ->active()
            ->where('status', '!=', 'full')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('gym_sessions.index', compact('gymSessions'));
    }
    public function show(GymSession $gymSession)
    {
        $gymSession->load(['reservations.team.members']);
        
        return view('gym_sessions.show', compact('gymSession'));
    }
    public function mySessions()
    {
        $reservations = Reservation::with(['gymSession', 'team'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('sessions.my', compact('reservations'));
    }
}
