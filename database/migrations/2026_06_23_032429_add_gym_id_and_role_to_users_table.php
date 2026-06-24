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
    Schema::table('users', function (Blueprint $table) {
        // gym_id එකතු කිරීම (Super Admin ට ජිම් එකක් නැති නිසා nullable() දානවා)
        $table->foreignId('gym_id')->nullable()->constrained('gyms')->onDelete('cascade');
        
        // පද්ධතියේ තනතුර (super_admin, gym_admin, staff, member)
        $table->string('role')->default('member');
        
        // දුරකථන අංකය
        $table->string('phone')->nullable();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Table එකෙන් මේවා අයින් කරන විදිහ
        $table->dropForeign(['gym_id']);
        $table->dropColumn(['gym_id', 'role', 'phone']);
    });
}
};