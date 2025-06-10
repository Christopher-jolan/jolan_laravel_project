<?php
namespace App\Observers;

use App\Models\Reservation;
use App\Models\GymSession;

class GymSessionObserver
{
    public function creating(GymSession $gymSession)
    {
        if (empty($gymSession->day_of_week)) {
            $gymSession->day_of_week = jdate($gymSession->date)->format('l');
        }
    }
}