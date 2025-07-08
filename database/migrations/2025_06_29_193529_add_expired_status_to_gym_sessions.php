<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // تغییر نوع enum برای فیلد status (برای دیتابیس‌هایی که از enum پشتیبانی می‌کنند)
        DB::statement("ALTER TABLE gym_sessions MODIFY COLUMN status ENUM('available', 'reserved', 'full', 'expired') DEFAULT 'available'");
        
        // یا برای دیتابیس‌های عمومی:
        Schema::table('gym_sessions', function (Blueprint $table) {
            $table->string('status')->default('available')->comment('available, reserved, full, expired')->change();
        });
    }

    public function down()
    {
        // بازگشت به حالت قبلی
        DB::statement("ALTER TABLE gym_sessions MODIFY COLUMN status ENUM('available', 'reserved', 'full') DEFAULT 'available'");
        
        // یا برای دیتابیس‌های عمومی:
        Schema::table('gym_sessions', function (Blueprint $table) {
            $table->string('status')->default('available')->comment('available, reserved, full')->change();
        });
    }
};