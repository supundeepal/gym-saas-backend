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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // පැමිණි සාමාජිකයා
            $table->foreignId('gym_id')->constrained('gyms')->onDelete('cascade'); // අදාළ ජිම් එක
            $table->date('date'); // පැමිණි දිනය
            $table->time('check_in_time'); // ඇතුළු වූ වෙලාව
            $table->time('check_out_time')->nullable(); // පිටවූ වෙලාව (මුලින්ම මේක හිස්ව තියෙන්නේ)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
