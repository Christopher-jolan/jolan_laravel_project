<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
        'gym_session_id',
        'team_name',
        'member_count',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function getMembersCountAttribute()
    {
        // اگر تیم دارد تعداد اعضای تیم را برگردان
        // اگر تیم ندارد (رزرو انفرادی) عدد 1 را برگردان
        return $this->team ? $this->team->member_count : 1;
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class, 'team_id', 'team_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function gymSession()
{
    return $this->belongsTo(GymSession::class, 'gym_session_id');
}
}
