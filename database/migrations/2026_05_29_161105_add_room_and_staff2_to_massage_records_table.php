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
        Schema::table('massage_records', function (Blueprint $table) {
            $table->integer('room_number')->nullable()->after('id');
            $table->foreignId('staff_id_2')->nullable()->after('staff_id')->constrained('staff')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('massage_records', function (Blueprint $table) {
            $table->dropForeign(['staff_id_2']);
            $table->dropColumn(['room_number', 'staff_id_2']);
        });
    }
};
