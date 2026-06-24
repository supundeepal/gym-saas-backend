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
    Schema::create('packages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('gym_id')->constrained('gyms')->onDelete('cascade'); 
        $table->string('name'); // <-- මේ පේළිය තමයි මගහැරිලා තියෙන්නේ
        $table->decimal('price', 8, 2); 
        $table->integer('duration_in_days'); 
        $table->text('description')->nullable(); 
        $table->string('status')->default('active'); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
