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
        // Gyms ටේබල් එකට අලුත් විස්තර එකතු කිරීම
        Schema::table('gyms', function (Blueprint $table) {
            if (!Schema::hasColumn('gyms', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('gyms', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('gyms', 'logo_path')) {
                $table->string('logo_path')->nullable();
            }
        });

        // Users (Owner) ටේබල් එකට අලුත් විස්තර එකතු කිරීම
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nic')) {
                $table->string('nic')->nullable();
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gyms_and_users', function (Blueprint $table) {
            //
        });
    }
};
