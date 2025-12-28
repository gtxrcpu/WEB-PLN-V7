<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 0) Lepas index unik jika sudah terpasang (supaya tidak mengganggu backfill)
        if ($this->indexExists('apars', 'apars_serial_no_unique')) {
            Schema::table('apars', function (Blueprint $t) {
                $t->dropUnique('apars_serial_no_unique');
            });
        }

        // 1) Bersihkan semua serial_no -> NULL (hapus duplikat lama)
        DB::table('apars')->update(['serial_no' => DB::raw('NULL')]);

        // 2) Backfill serial_no unik: SN-A1-000001, 000002, ...
        DB::beginTransaction();
        try {
            $i = 0;
            // chunkById agar hemat memori
            DB::table('apars')->orderBy('id')->chunkById(1000, function ($rows) use (&$i) {
                foreach ($rows as $r) {
                    $i++;
                    $sn = 'SN-A1-'.str_pad((string)$i, 6, '0', STR_PAD_LEFT);
                    DB::table('apars')->where('id', $r->id)->update(['serial_no' => $sn]);
                }
            });
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        // 3) Pasang lagi unique index
        Schema::table('apars', function (Blueprint $t) {
            $t->unique('serial_no', 'apars_serial_no_unique');
        });
    }

    public function down(): void
    {
        // Lepas index unik (nilai serial_no dibiarkan apa adanya)
        if ($this->indexExists('apars', 'apars_serial_no_unique')) {
            Schema::table('apars', function (Blueprint $t) {
                $t->dropUnique('apars_serial_no_unique');
            });
        }
    }

    /** Cek apakah index ada tanpa Doctrine DBAL */
    private function indexExists(string $table, string $index): bool
    {
        $driver = DB::getDriverName();
        
        // For SQLite, use a different approach
        if ($driver === 'sqlite') {
            try {
                $indexes = DB::select("PRAGMA index_list({$table})");
                foreach ($indexes as $idx) {
                    if ($idx->name === $index) {
                        return true;
                    }
                }
                return false;
            } catch (\Exception $e) {
                return false;
            }
        }
        
        // For MySQL
        $db = DB::getDatabaseName();
        $count = DB::table('information_schema.statistics')
            ->where('table_schema', $db)
            ->where('table_name', $table)
            ->where('index_name', $index)
            ->count();
        return $count > 0;
    }
};
