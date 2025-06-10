<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'leader_id',
        'member_count',
        'description'
    ];

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
