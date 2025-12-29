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
        // Add indexes for better query performance
        Schema::table('apars', function (Blueprint $table) {
            $table->index('status');
            $table->index('serial_no');
        });

        Schema::table('apats', function (Blueprint $table) {
            $table->index('status');
            $table->index('serial_no');
        });

        Schema::table('p3ks', function (Blueprint $table) {
            $table->index('status');
            $table->index('serial_no');
        });

        Schema::table('apabs', function (Blueprint $table) {
            $table->index('status');
            $table->index('serial_no');
        });

        Schema::table('fire_alarms', function (Blueprint $table) {
            $table->index('status');
            $table->index('serial_no');
        });

        Schema::table('box_hydrants', function (Blueprint $table) {
            $table->index('status');
            $table->index('serial_no');
        });

        Schema::table('rumah_pompas', function (Blueprint $table) {
            $table->index('status');
            $table->index('serial_no');
        });

        // Add indexes for kartu tables
        Schema::table('kartu_apars', function (Blueprint $table) {
            $table->index(['apar_id', 'tgl_periksa']);
        });

        Schema::table('kartu_apats', function (Blueprint $table) {
            $table->index(['apat_id', 'tgl_periksa']);
        });

        Schema::table('kartu_p3ks', function (Blueprint $table) {
            $table->index(['p3k_id', 'tgl_periksa']);
        });

        Schema::table('kartu_apabs', function (Blueprint $table) {
            $table->index(['apab_id', 'tgl_periksa']);
        });

        Schema::table('kartu_fire_alarms', function (Blueprint $table) {
            $table->index(['fire_alarm_id', 'tgl_periksa']);
        });

        Schema::table('kartu_box_hydrants', function (Blueprint $table) {
            $table->index(['box_hydrant_id', 'tgl_periksa']);
        });

        Schema::table('kartu_rumah_pompas', function (Blueprint $table) {
            $table->index(['rumah_pompa_id', 'tgl_periksa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes
        Schema::table('apars', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['serial_no']);
        });

        Schema::table('apats', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['serial_no']);
        });

        Schema::table('p3ks', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['serial_no']);
        });

        Schema::table('apabs', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['serial_no']);
        });

        Schema::table('fire_alarms', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['serial_no']);
        });

        Schema::table('box_hydrants', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['serial_no']);
        });

        Schema::table('rumah_pompas', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['serial_no']);
        });

        Schema::table('kartu_apars', function (Blueprint $table) {
            $table->dropIndex(['apar_id', 'tgl_periksa']);
        });

        Schema::table('kartu_apats', function (Blueprint $table) {
            $table->dropIndex(['apat_id', 'tgl_periksa']);
        });

        Schema::table('kartu_p3ks', function (Blueprint $table) {
            $table->dropIndex(['p3k_id', 'tgl_periksa']);
        });

        Schema::table('kartu_apabs', function (Blueprint $table) {
            $table->dropIndex(['apab_id', 'tgl_periksa']);
        });

        Schema::table('kartu_fire_alarms', function (Blueprint $table) {
            $table->dropIndex(['fire_alarm_id', 'tgl_periksa']);
        });

        Schema::table('kartu_box_hydrants', function (Blueprint $table) {
            $table->dropIndex(['box_hydrant_id', 'tgl_periksa']);
        });

        Schema::table('kartu_rumah_pompas', function (Blueprint $table) {
            $table->dropIndex(['rumah_pompa_id', 'tgl_periksa']);
        });
    }
};
