<?php

namespace App\Jobs;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class UpdateAnnouncementsStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $now = Carbon::now();

        
        Announcement::where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->update(['is_active' => true]);

        
        Announcement::where('end_time', '<', $now)
            ->update(['is_active' => false]);
    }
}
