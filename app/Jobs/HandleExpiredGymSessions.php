<?php

namespace App\Jobs;

use App\Models\GymSession;
use App\Models\Reservation;
use App\Models\JoinRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HandleExpiredGymSessions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = Carbon::now();
        Log::info('HandleExpiredGymSessions started', ['now' => $now->toDateTimeString()]);

        // پیدا کردن سانس‌هایی که تکرار هفتگی دارند و تاریخ+ساعت پایانشان گذشته است
        $expiredSessions = GymSession::where('repeat_weekly', true)
            ->whereRaw("CONCAT(date, ' ', end_time) < ?", [$now->format('Y-m-d H:i:s')])
            ->get();

        Log::info('Expired sessions found', ['count' => $expiredSessions->count()]);

        foreach ($expiredSessions as $session) {
            DB::beginTransaction();
            try {
                Log::info('Processing expired session', [
                    'id' => $session->id,
                    'date' => $session->date,
                    'start_time' => $session->start_time,
                    'end_time' => $session->end_time,
                ]);

                // ایجاد سانس جدید برای هفته بعد
                $newDate = Carbon::parse($session->date)->addWeek();
                $newSession = new GymSession();
                $newSession->date = $newDate->format('Y-m-d');
                $newSession->day_of_week = $newDate->format('l');
                $newSession->start_time = $session->start_time;
                $newSession->end_time = $session->end_time;
                $newSession->repeat_weekly = true;
                $newSession->max_capacity = $session->max_capacity;
                $newSession->current_capacity = 0;
                $newSession->status = 'available';
                $newSession->save();

                Log::info('Created new session for next week', [
                    'new_session_id' => $newSession->id,
                    'date' => $newSession->date
                ]);

                // حذف رزروها و درخواست‌های الحاق مرتبط با این سانس
                foreach ($session->reservations as $reservation) {
                    $joinRequests = JoinRequest::where('reservation_id', $reservation->id)->get();
                    foreach ($joinRequests as $jr) {
                        $jr->delete();
                    }
                    $reservation->delete();
                }

                // حذف سانس قدیمی
                $session->delete();
                Log::info('Old session deleted', ['id' => $session->id]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error in HandleExpiredGymSessions', [
                    'session_id' => $session->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
        Log::info('HandleExpiredGymSessions finished');
    }
}
