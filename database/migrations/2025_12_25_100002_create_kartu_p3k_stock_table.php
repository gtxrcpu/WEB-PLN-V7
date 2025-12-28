<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kartu_p3k_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('p3k_id')->constrained('p3ks')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Stock items (JSON untuk fleksibilitas)
            $table->json('stock_items')->nullable();
            
            // Kesimpulan & meta
            $table->string('kesimpulan');
            $table->date('tgl_periksa');
            $table->string('petugas');
            $table->text('catatan')->nullable();
            
            // Approval
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('signature_id')->nullable()->constrained('signatures')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kartu_p3k_stock');
    }
};
