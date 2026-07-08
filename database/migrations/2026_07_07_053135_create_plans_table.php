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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Basic, Pro, Premium වගේ නම්
            $table->string('slug')->unique(); // URL වලට
            $table->decimal('price', 10, 2)->default(0.00); // මාසික/වාර්ෂික ගාණ 
            $table->string('billing_cycle')->default('monthly'); // monthly ද yearly ද
            $table->integer('max_members')->default(0); // උපරිම Members ලා ගාණ (0 = Unlimited)
            $table->integer('max_staff')->default(0); // උපරිම Trainers/Staff ගාණ
            $table->json('features')->nullable(); // Features ලිස්ට් එක සේව් කරන්න
            $table->boolean('is_active')->default(true); // Active ද නැද්ද
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
