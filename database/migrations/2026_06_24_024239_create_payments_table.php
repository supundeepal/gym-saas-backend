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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained('gyms')->onDelete('cascade'); // කුමන ජිම් එකේද?
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // කුමන සාමාජිකයාද?
            $table->decimal('amount', 8, 2); // ගෙවපු මුදල (උදා: 1500.00)
            $table->string('payment_type'); // ගෙවපු හේතුව (උදා: Monthly Fee, Day Pass)
            $table->timestamps(); // ගෙවූ දිනය සහ වේලාව
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
