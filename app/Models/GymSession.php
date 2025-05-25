<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymSession extends Model
{
    use HasFactory;

    protected $table = 'gym_sessions';

    protected $fillable = [
        'date',
        'day_of_week',
        'start_time',
        'end_time',
        'repeat_weekly',
        'max_capacity',
        'current_capacity',
        'status',
    ];

    public function updateStatus()
{
    $totalReserved = $this->reservations()->sum('member_count');
    
    if ($totalReserved >= $this->max_capacity) {
        $this->status = 'full';
    } elseif ($totalReserved > 0) {
        $this->status = 'reserved';
    } else {
        $this->status = 'available';
    }
    
    $this->reserved_count = $totalReserved;
    $this->save();
}

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
