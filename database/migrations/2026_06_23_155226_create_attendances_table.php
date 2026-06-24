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
            $table->foreignId('gym_id')->constrained('gyms')->onDelete('cascade'); // කුමන ජිම් එකේද?
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // කුමන සාමාජිකයාද?
            $table->timestamp('check_in_time'); // ආපු වෙලාව
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
