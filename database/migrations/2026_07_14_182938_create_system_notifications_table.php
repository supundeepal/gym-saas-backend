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
        Schema::create('system_notifications', function (Blueprint $table) {
            $table->id();
            // user_id එක null නම් ඒක Super Admin ට යන එකක්. ID එකක් තිබ්බොත් ඒ අදාළ Gym Owner ට යන එකක්.
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->string('target_role'); // 'super_admin' හෝ 'owner' කියලා සේව් වෙනවා
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false); // මැසේජ් එක කියෙව්වද නැද්ද කියලා බලන්න
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_notifications');
    }
};
