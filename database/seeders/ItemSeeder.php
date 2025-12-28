<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil superadmin sebagai owner default
        $superadmin = \App\Models\User::where('email', 'superadmin@pln.co.id')->first();
        
        if (!$superadmin) {
            $this->command->warn('⚠️  Superadmin not found, skipping ItemSeeder');
            return;
        }

        $uid = $superadmin->id;
        $now = now();

        $rows = [
            ['name'=>'APAR Powder 6kg','barcode'=>'APAR-0001','location'=>'Gudang A1','thumbnail_path'=>null,'user_id'=>$uid,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'APAR CO2 3kg','barcode'=>'APAR-0002','location'=>'Lobby Utama','thumbnail_path'=>null,'user_id'=>$uid,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'APAT Ember Pasir','barcode'=>'APAT-0101','location'=>'Workshop Bengkel','thumbnail_path'=>null,'user_id'=>$uid,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Fire Alarm Panel-1','barcode'=>'FAL-1001','location'=>'Ruang Panel Lantai 1','thumbnail_path'=>null,'user_id'=>$uid,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Box Hydrant BH-01','barcode'=>'HYD-2001','location'=>'Koridor Timur L1','thumbnail_path'=>null,'user_id'=>$uid,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'P3K Kit A','barcode'=>'P3K-0001','location'=>'Kantor Admin','thumbnail_path'=>null,'user_id'=>$uid,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'APAR Foam 9kg','barcode'=>'APAR-0003','location'=>'Kantin','thumbnail_path'=>null,'user_id'=>$uid,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Detector Smoke D-12','barcode'=>'FAL-2012','location'=>'Ruang Server','thumbnail_path'=>null,'user_id'=>$uid,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Box Hydrant BH-02','barcode'=>'HYD-2002','location'=>'Koridor Barat L2','thumbnail_path'=>null,'user_id'=>$uid,'created_at'=>$now,'updated_at'=>$now],
            ['name'=>'Kotak P3K Kit B','barcode'=>'P3K-0002','location'=>'Ruang Rapat','thumbnail_path'=>null,'user_id'=>$uid,'created_at'=>$now,'updated_at'=>$now],
        ];

        DB::table('items')->insert($rows);
        
        $this->command->info('✅ Sample items created');
    }
}
