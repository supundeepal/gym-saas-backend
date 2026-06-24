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
        Schema::create('workout_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained('gyms')->onDelete('cascade'); // අදාළ ජිම් එක
            $table->foreignId('member_id')->constrained('users')->onDelete('cascade'); // සැලැස්ම හිමි සාමාජිකයා
            $table->string('plan_name'); // සැලැස්මේ නම (උදා: Weight Loss, Muscle Gain)
            $table->text('description'); // ව්‍යායාම විස්තරය (දිනපතා කළ යුතු දේවල්)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_plans');
    }
};
