<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('gym_sessions', function (Blueprint $table) {
        $table->id();
        $table->date('date'); // تاریخ سانس
        $table->string('day_of_week'); // روز هفته
        $table->time('start_time'); // زمان شروع
        $table->time('end_time'); // زمان پایان
        $table->boolean('repeat_weekly')->default(true); // آیا سانس هفتگی تکرار می‌شود؟
        $table->integer('max_capacity'); // حداکثر ظرفیت سانس
        $table->integer('current_capacity')->default(0); // تعداد فعلی رزروها
        $table->enum('status', ['available', 'reserved', 'full'])->default('available'); // وضعیت سانس
        $table->timestamps();
    });
    }
  /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_sessions');
    }
};
