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
        $tables = ['apars', 'apats', 'fire_alarms', 'box_hydrants', 'rumah_pompas', 'apabs', 'p3ks'];
        
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('floor_plan_id')->nullable()->constrained()->nullOnDelete();
                $table->decimal('floor_plan_x', 5, 2)->nullable()->comment('X coordinate (0-100%)');
                $table->decimal('floor_plan_y', 5, 2)->nullable()->comment('Y coordinate (0-100%)');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['apars', 'apats', 'fire_alarms', 'box_hydrants', 'rumah_pompas', 'apabs', 'p3ks'];
        
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['floor_plan_id']);
                $table->dropColumn(['floor_plan_id', 'floor_plan_x', 'floor_plan_y']);
            });
        }
    }
};
