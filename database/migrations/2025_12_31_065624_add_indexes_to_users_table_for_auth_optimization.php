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
        Schema::table('users', function (Blueprint $table) {
            // Check and add index on email for faster authentication lookup
            if (!$this->hasIndex('users', 'users_email_index')) {
                $table->index('email', 'users_email_index');
            }
            
            // Check and add index on unit_id for faster joins
            if (!$this->hasIndex('users', 'users_unit_id_index')) {
                $table->index('unit_id', 'users_unit_id_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if ($this->hasIndex('users', 'users_email_index')) {
                $table->dropIndex('users_email_index');
            }
            if ($this->hasIndex('users', 'users_unit_id_index')) {
                $table->dropIndex('users_unit_id_index');
            }
        });
    }

    /**
     * Check if index exists
     */
    private function hasIndex($table, $index)
    {
        $driver = \DB::getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite: Query sqlite_master table
            $indexes = \DB::select("SELECT name FROM sqlite_master WHERE type='index' AND name=?", [$index]);
            return !empty($indexes);
        } else {
            // MySQL/MariaDB: Use SHOW INDEX
            $indexes = \DB::select("SHOW INDEX FROM {$table} WHERE Key_name = '{$index}'");
            return !empty($indexes);
        }
    }
};
