<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah unit_id ke semua tabel equipment
        $tables = [
            'apars',
            'apats',
            'apabs',
            'fire_alarms',
            'box_hydrants',
            'rumah_pompas',
            'p3ks',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('unit_id')->nullable()->after('user_id')->constrained('units')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'apars',
            'apats',
            'apabs',
            'fire_alarms',
            'box_hydrants',
            'rumah_pompas',
            'p3ks',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['unit_id']);
                $table->dropColumn('unit_id');
            });
        }
    }
};
