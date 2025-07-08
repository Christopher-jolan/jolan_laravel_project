<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\GymSession;
use App\Models\User;

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
        return $this->belongsTo(GymSession::class);
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function joinRequests()
    {
        return $this->hasMany(JoinRequest::class);
    }

    public function getMembersCountAttribute()
    {
        return $this->team ? $this->team->member_count : 1;
    }
}
