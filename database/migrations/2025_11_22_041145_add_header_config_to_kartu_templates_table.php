<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kartu_templates', function (Blueprint $table) {
            $table->text('company_name')->nullable()->after('subtitle');
            $table->text('company_address')->nullable()->after('company_name');
            $table->string('company_phone', 100)->nullable()->after('company_address');
            $table->string('company_fax', 100)->nullable()->after('company_phone');
            $table->string('company_email', 100)->nullable()->after('company_fax');
        });
    }

    public function down(): void
    {
        Schema::table('kartu_templates', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'company_address',
                'company_phone',
                'company_fax',
                'company_email',
            ]);
        });
    }
};
