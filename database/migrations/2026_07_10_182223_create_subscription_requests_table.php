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
        // 1. ජිම් එකේ දැනට තියෙන Plan එක සේව් කරගන්න Column එකක් හදනවා
        Schema::table('gyms', function (Blueprint $table) {
            if (!Schema::hasColumn('gyms', 'plan_id')) {
                $table->unsignedBigInteger('plan_id')->nullable()->after('id');
            }
        });

        // 2. අලුත් මාසෙට සල්ලි ගෙවලා ස්ලිප් එක දාන ටේබල් එක
        Schema::create('subscription_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gym_id');
            $table->unsignedBigInteger('plan_id');
            $table->string('slip_path');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_requests');
    }
};
