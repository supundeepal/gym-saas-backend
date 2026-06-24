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
    Schema::create('gyms', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // ජිම් එකේ නම
        $table->string('slug')->unique(); // URL එකට පාවිච්චි කරන නම (උදා: power-gym)
        $table->string('phone_number')->nullable(); // දුරකථන අංකය
        $table->text('address')->nullable(); // ලිපිනය
        $table->string('status')->default('active'); // active ද නැද්ද යන්න
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gyms');
    }
};
