<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reservation_id',
        'status',
    ];

    // رابطه با کاربر
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // رابطه با رزرو
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
