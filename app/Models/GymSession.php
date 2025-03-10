<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymSession extends Model
{
    use HasFactory;

    protected $table = 'gym_sessions'; // نام جدول

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

    // رابطه با رزروها
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
