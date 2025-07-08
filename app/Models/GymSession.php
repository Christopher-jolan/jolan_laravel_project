<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'status', // ['available', 'reserved', 'full', 'expired']
    ];

    public static function handleWeeklyRepeats()
    {
        $now = Carbon::now();
        $today = $now->format('Y-m-d');
        
        $expiredSessions = self::where('repeat_weekly', true)
            ->where(function($query) use ($today, $now) {
                $query->where('date', '<', $today)
                    ->orWhere(function($q) use ($today, $now) {
                        $q->where('date', $today)
                            ->where('end_time', '<', $now->format('H:i:s'));
                    });
            })
            ->where('status', '!=', 'expired')
            ->get();

        foreach ($expiredSessions as $session) {
            // ایجاد سانس جدید برای هفته بعد
            $newDate = Carbon::parse($session->date)->addWeek();
            
            self::create([
                'date' => $newDate->format('Y-m-d'),
                'day_of_week' => $newDate->format('l'),
                'start_time' => $session->start_time,
                'end_time' => $session->end_time,
                'repeat_weekly' => true,
                'max_capacity' => $session->max_capacity,
                'status' => 'available'
            ]);
            
            // تغییر وضعیت سانس قدیمی به expired به جای حذف
            $session->update(['status' => 'expired']);
        }
    }

    public function updateStatus()
    {
        if ($this->reserved_count >= $this->max_capacity) {
            $this->status = 'full';
        } elseif ($this->reserved_count > 0) {
            $this->status = 'reserved';
        } else {
            $this->status = 'available';
        }
        $this->save();
    }

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'expired')
                    ->where('date', '>=', now()->format('Y-m-d'));
    }


    public function reservations()
    {
        return $this->hasMany(Reservation::class)->with('joinRequests');
    }
}
