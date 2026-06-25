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
            // RFID card number එක සේව් කරන්න (මේක හිස්ව තියෙන්න පුළුවන්, හැබැයි කාටවත් මාරු වෙන්න බෑ)
            $table->string('rfid_number')->nullable()->unique()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rfid_number');
        });
    }
};
