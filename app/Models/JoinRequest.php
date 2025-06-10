<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JoinRequest extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'reservation_id',
    'status',
    'message'
];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

        
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
