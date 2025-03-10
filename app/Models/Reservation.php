<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gym_session_id',
        'team_name',
        'status',
    ];

    // رابطه با کاربر
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // رابطه با سانس
    public function gymSession() // تغییر نام متد
{
    return $this->belongsTo(GymSession::class, 'gym_session_id'); // تغییر نام فیلد
}
}
