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
        Schema::table('gyms', function (Blueprint $table) {
            // ජිම් එකේ SMS Balance එක සේව් කරන්න (Default රුපියල් 0.00 යි)
            $table->decimal('sms_balance', 10, 2)->default(0.00)->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->dropColumn('sms_balance');
        });
    }
};
