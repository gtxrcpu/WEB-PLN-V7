<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kartu_p3k_pemakaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('p3k_id')->constrained('p3ks')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Detail pemakaian
            $table->string('item_digunakan');
            $table->integer('jumlah')->default(1);
            $table->text('keperluan')->nullable();
            $table->string('nama_pengguna')->nullable();
            
            // Kesimpulan & meta
            $table->string('kesimpulan');
            $table->date('tgl_pemakaian');
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
        Schema::dropIfExists('kartu_p3k_pemakaian');
    }
};
