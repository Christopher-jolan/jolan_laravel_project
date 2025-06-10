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
        'status',
    ];

    public static function handleWeeklyRepeats()
    {
        $now = Carbon::now();
        $today = $now->format('Y-m-d');
        
        // پیدا کردن سانس‌های تکرارشونده که تاریخشان گذشته
        $expiredSessions = self::where('repeat_weekly', true)
            ->whereDate('date', '<', $today)
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
            
            // حذف سانس قدیمی
            $session->delete();
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


    public function reservations()
    {
        return $this->hasMany(Reservation::class)->with('joinRequests');
    }
}
