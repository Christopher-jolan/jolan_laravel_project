<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GymSession;
use App\Models\Reservation;


class GymSessionController extends Controller
{
    public function index()
    {
        $gymSessions = GymSession::with(['reservations.team.members'])
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
}
