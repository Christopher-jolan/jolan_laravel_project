<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // کاربری که رزرو کرده
            $table->foreignId('gym_session_id')->constrained()->onDelete('cascade'); // سانس مربوطه
            $table->string('team_name')->nullable(); // نام تیم (اختیاری)
            $table->enum('status', ['pending', 'approved'])->default('pending'); // وضعیت رزرو
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
