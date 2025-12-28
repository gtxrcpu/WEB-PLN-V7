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
        Schema::create('apar_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Nama setting
            $table->text('value')->nullable(); // Nilai setting
            $table->string('type')->default('text'); // text, number, json
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('apar_settings')->insert([
            [
                'key' => 'kode_format',
                'value' => 'APAR-{UNIT}-{YYYY}-{NNNN}',
                'type' => 'text',
                'description' => 'Format kode APAR. Variabel: {UNIT}, {YYYY}, {MM}, {NNNN}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'kode_counter',
                'value' => '1',
                'type' => 'number',
                'description' => 'Counter untuk nomor urut APAR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apar_settings');
    }
};
