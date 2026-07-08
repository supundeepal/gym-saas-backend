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
        Schema::create('sms_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // උදා: "1000 SMS"
            $table->integer('sms_count'); // කොච්චරක් යවන්න පුළුවන්ද (උදා: 1000)
            $table->decimal('price', 10, 2); // ගාණ (උදා: 1000.00)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_packages');
    }
};
