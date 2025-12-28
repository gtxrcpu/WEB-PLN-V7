<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KartuTemplate;

class KartuTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'module' => 'apar',
                'title' => 'KARTU KENDALI',
                'subtitle' => 'ALAT PEMADAM API RINGAN (APAR)',
                'company_name' => 'PT PLN (Persero)',
                'company_address' => 'Jl. Trunojoyo No. 135, Surabaya',
                'company_phone' => 'Telp: (031) 1234567',
                'company_fax' => 'Fax: (031) 7654321',
                'company_email' => 'info@pln.co.id',
                'header_fields' => [
                    ['label' => 'No. Dokumen', 'value' => 'APAR-001'],
                    ['label' => 'Revisi', 'value' => '00'],
                    ['label' => 'Tanggal', 'value' => date('d-m-Y')],
                    ['label' => 'Halaman', 'value' => '1 dari 1'],
                ],
                'inspection_fields' => [
                    ['label' => 'Pressure Gauge', 'type' => 'checkbox'],
                    ['label' => 'Pin & Segel', 'type' => 'checkbox'],
                    ['label' => 'Selang', 'type' => 'checkbox'],
                    ['label' => 'Tabung', 'type' => 'checkbox'],
                    ['label' => 'Label', 'type' => 'checkbox'],
                    ['label' => 'Kondisi Fisik', 'type' => 'checkbox'],
                ],
                'footer_fields' => [
                    ['label' => 'Lokasi', 'value' => 'Surabaya'],
                    ['label' => 'Label Pimpinan', 'value' => 'Team Leader K3L & KAM'],
                ],
                'is_active' => true,
            ],
            [
                'module' => 'apat',
                'title' => 'KARTU KENDALI',
                'subtitle' => 'ALAT PEMADAM API TRADISIONAL (APAT)',
                'company_name' => 'PT PLN (Persero)',
                'company_address' => 'Jl. Trunojoyo No. 135, Surabaya',
                'company_phone' => 'Telp: (031) 1234567',
                'company_fax' => 'Fax: (031) 7654321',
                'company_email' => 'info@pln.co.id',
                'header_fields' => [
                    ['label' => 'No. Dokumen', 'value' => 'APAT-001'],
                    ['label' => 'Revisi', 'value' => '00'],
                    ['label' => 'Tanggal', 'value' => date('d-m-Y')],
                    ['label' => 'Halaman', 'value' => '1 dari 1'],
                ],
                'inspection_fields' => [
                    ['label' => 'Kondisi Fisik', 'type' => 'checkbox'],
                    ['label' => 'Drum', 'type' => 'checkbox'],
                    ['label' => 'Aduk Pasir', 'type' => 'checkbox'],
                    ['label' => 'Sekop', 'type' => 'checkbox'],
                    ['label' => 'Fire Blanket', 'type' => 'checkbox'],
                    ['label' => 'Ember', 'type' => 'checkbox'],
                ],
                'footer_fields' => [
                    ['label' => 'Lokasi', 'value' => 'Surabaya'],
                    ['label' => 'Label Pimpinan', 'value' => 'Team Leader K3L & KAM'],
                ],
                'is_active' => true,
            ],
            [
                'module' => 'apab',
                'title' => 'KARTU KENDALI',
                'subtitle' => 'ALAT PEMADAM API BERAT (APAB)',
                'company_name' => 'PT PLN (Persero)',
                'company_address' => 'Jl. Trunojoyo No. 135, Surabaya',
                'company_phone' => 'Telp: (031) 1234567',
                'company_fax' => 'Fax: (031) 7654321',
                'company_email' => 'info@pln.co.id',
                'header_fields' => [
                    ['label' => 'No. Dokumen', 'value' => 'APAB-001'],
                    ['label' => 'Revisi', 'value' => '00'],
                    ['label' => 'Tanggal', 'value' => date('d-m-Y')],
                    ['label' => 'Halaman', 'value' => '1 dari 1'],
                ],
                'inspection_fields' => [
                    ['label' => 'Pressure Gauge', 'type' => 'checkbox'],
                    ['label' => 'Pin & Segel', 'type' => 'checkbox'],
                    ['label' => 'Selang', 'type' => 'checkbox'],
                    ['label' => 'Tabung', 'type' => 'checkbox'],
                    ['label' => 'Nozzle', 'type' => 'checkbox'],
                    ['label' => 'Kondisi Fisik', 'type' => 'checkbox'],
                ],
                'footer_fields' => [
                    ['label' => 'Lokasi', 'value' => 'Surabaya'],
                    ['label' => 'Label Pimpinan', 'value' => 'Team Leader K3L & KAM'],
                ],
                'is_active' => true,
            ],
            [
                'module' => 'fire-alarm',
                'title' => 'KARTU KENDALI',
                'subtitle' => 'FIRE ALARM SYSTEM',
                'company_name' => 'PT PLN (Persero)',
                'company_address' => 'Jl. Trunojoyo No. 135, Surabaya',
                'company_phone' => 'Telp: (031) 1234567',
                'company_fax' => 'Fax: (031) 7654321',
                'company_email' => 'info@pln.co.id',
                'header_fields' => [
                    ['label' => 'No. Dokumen', 'value' => 'FA-001'],
                    ['label' => 'Revisi', 'value' => '00'],
                    ['label' => 'Tanggal', 'value' => date('d-m-Y')],
                    ['label' => 'Halaman', 'value' => '1 dari 1'],
                ],
                'inspection_fields' => [
                    ['label' => 'Panel Alarm', 'type' => 'checkbox'],
                    ['label' => 'Detector', 'type' => 'checkbox'],
                    ['label' => 'Bell/Sirine', 'type' => 'checkbox'],
                    ['label' => 'Manual Call Point', 'type' => 'checkbox'],
                    ['label' => 'Kabel & Instalasi', 'type' => 'checkbox'],
                    ['label' => 'Kondisi Fisik', 'type' => 'checkbox'],
                ],
                'footer_fields' => [
                    ['label' => 'Lokasi', 'value' => 'Surabaya'],
                    ['label' => 'Label Pimpinan', 'value' => 'Team Leader K3L & KAM'],
                ],
                'is_active' => true,
            ],
            [
                'module' => 'box-hydrant',
                'title' => 'KARTU KENDALI',
                'subtitle' => 'BOX HYDRANT',
                'company_name' => 'PT PLN (Persero)',
                'company_address' => 'Jl. Trunojoyo No. 135, Surabaya',
                'company_phone' => 'Telp: (031) 1234567',
                'company_fax' => 'Fax: (031) 7654321',
                'company_email' => 'info@pln.co.id',
                'header_fields' => [
                    ['label' => 'No. Dokumen', 'value' => 'BH-001'],
                    ['label' => 'Revisi', 'value' => '00'],
                    ['label' => 'Tanggal', 'value' => date('d-m-Y')],
                    ['label' => 'Halaman', 'value' => '1 dari 1'],
                ],
                'inspection_fields' => [
                    ['label' => 'Pilar Hydrant', 'type' => 'checkbox'],
                    ['label' => 'Box Hydrant', 'type' => 'checkbox'],
                    ['label' => 'Hose/Selang', 'type' => 'checkbox'],
                    ['label' => 'Nozzle', 'type' => 'checkbox'],
                    ['label' => 'Coupling', 'type' => 'checkbox'],
                    ['label' => 'Kondisi Fisik', 'type' => 'checkbox'],
                ],
                'footer_fields' => [
                    ['label' => 'Lokasi', 'value' => 'Surabaya'],
                    ['label' => 'Label Pimpinan', 'value' => 'Team Leader K3L & KAM'],
                ],
                'is_active' => true,
            ],
            [
                'module' => 'rumah-pompa',
                'title' => 'KARTU KENDALI',
                'subtitle' => 'RUMAH POMPA HYDRANT',
                'company_name' => 'PT PLN (Persero)',
                'company_address' => 'Jl. Trunojoyo No. 135, Surabaya',
                'company_phone' => 'Telp: (031) 1234567',
                'company_fax' => 'Fax: (031) 7654321',
                'company_email' => 'info@pln.co.id',
                'header_fields' => [
                    ['label' => 'No. Dokumen', 'value' => 'RP-001'],
                    ['label' => 'Revisi', 'value' => '00'],
                    ['label' => 'Tanggal', 'value' => date('d-m-Y')],
                    ['label' => 'Halaman', 'value' => '1 dari 1'],
                ],
                'inspection_fields' => [
                    // SECTION A: PEMIPAAN DAN VALVE
                    ['section' => 'A', 'section_title' => 'PEMIPAAN DAN VALVE', 'label' => 'Tidak ada kebocoran di pipa suction dan discharge pompa Electric, Diesel, dan Jockey (Tekanan Stanby di 8 Bar)', 'type' => 'checkbox'],
                    ['section' => 'A', 'section_title' => '', 'label' => 'Semua valve pada pipa suction dan discharge pompa Electric, Diesel, dan Jockey dalam kondisi Buka/Open.', 'type' => 'checkbox'],
                    
                    // SECTION B: MESIN DIESEL
                    ['section' => 'B', 'section_title' => 'MESIN DIESEL', 'label' => 'Cek Level oli mesin (tambah bila kurang) Ganti Oli mesin setiap 6 bulan sekali)', 'type' => 'checkbox'],
                    ['section' => 'B', 'section_title' => '', 'label' => 'Cek level BBM mesin (tambah bila kurang)', 'type' => 'checkbox'],
                    ['section' => 'B', 'section_title' => '', 'label' => 'Cek level air radiator (tambah bila kurang)', 'type' => 'checkbox'],
                    ['section' => 'B', 'section_title' => '', 'label' => 'Cek tegangan battery', 'type' => 'checkbox'],
                    ['section' => 'B', 'section_title' => '', 'label' => 'Pastikan filter oli dalam kondisi baik', 'type' => 'checkbox'],
                    ['section' => 'B', 'section_title' => '', 'label' => 'Pastikan filter bbm dan clarifier dalam kondisi baik', 'type' => 'checkbox'],
                    ['section' => 'B', 'section_title' => '', 'label' => 'Pastikan filter udara dalam kondisi baik', 'type' => 'checkbox'],
                    ['section' => 'B', 'section_title' => '', 'label' => 'Pastikan kelancaran vanbell', 'type' => 'checkbox'],
                    ['section' => 'B', 'section_title' => '', 'label' => 'Pastikan terminal kabel battery dalam kondisi baik', 'type' => 'checkbox'],
                    ['section' => 'B', 'section_title' => '', 'label' => 'Pastikan panel kontrol mesin dalam kondisi baik', 'type' => 'checkbox'],
                    ['section' => 'B', 'section_title' => '', 'label' => 'Hidupkan mesin selama ± 20 menit untuk pemanasan', 'type' => 'checkbox'],
                    ['section' => 'B', 'section_title' => '', 'label' => 'Cek semua indikator pada display berfungsi dengan baik', 'type' => 'checkbox'],
                    ['section' => 'B', 'section_title' => '', 'label' => 'Matikan mesin / stop engine setelah selesai', 'type' => 'checkbox'],
                    ['section' => 'B', 'section_title' => '', 'label' => 'Pastikan kondisi mesin dan sekitarnya bersih dan aman', 'type' => 'checkbox'],
                    ['section' => 'B', 'section_title' => '', 'label' => 'Pastikan ruangan mesin dan sekitarnya bersih dan aman', 'type' => 'checkbox'],
                    
                    // SECTION C: PANEL DAN POMPA
                    ['section' => 'C', 'section_title' => 'PANEL DAN POMPA', 'label' => 'Cek Fisik Panel Elektric Pump dan Indikator-indikatornya', 'type' => 'checkbox'],
                    ['section' => 'C', 'section_title' => '', 'label' => 'Cek Fisik Panel Jacky Pump dan Indikator-indikatornya', 'type' => 'checkbox'],
                    ['section' => 'C', 'section_title' => '', 'label' => 'Cek posisi selector pada pose Automatic pada panel kontrol (Electric, Jockey, dan Diesel).', 'type' => 'checkbox'],
                    ['section' => 'C', 'section_title' => '', 'label' => 'Cek Fungsi Pompa dengan membuka pilor (minimal 3 orang): - Jacky akan menyala pada tekanan 7 Bar - Electric Pump akan menyala pada tekanan 6 Bar', 'type' => 'checkbox'],
                ],
                'table_header' => 'KONDISI OKTOBER MINGGU 2',
                'footer_fields' => [
                    ['label' => 'Lokasi', 'value' => 'Surabaya'],
                    ['label' => 'Label Pimpinan', 'value' => 'Team Leader K3L & KAM'],
                ],
                'is_active' => true,
            ],
            [
                'module' => 'p3k',
                'title' => 'KARTU KENDALI',
                'subtitle' => 'KOTAK P3K',
                'company_name' => 'PT PLN (Persero)',
                'company_address' => 'Jl. Trunojoyo No. 135, Surabaya',
                'company_phone' => 'Telp: (031) 1234567',
                'company_fax' => 'Fax: (031) 7654321',
                'company_email' => 'info@pln.co.id',
                'header_fields' => [
                    ['label' => 'No. Dokumen', 'value' => 'P3K-001'],
                    ['label' => 'Revisi', 'value' => '00'],
                    ['label' => 'Tanggal', 'value' => date('d-m-Y')],
                    ['label' => 'Halaman', 'value' => '1 dari 1'],
                ],
                'inspection_fields' => [
                    ['label' => 'Kotak P3K', 'type' => 'checkbox'],
                    ['label' => 'Plester', 'type' => 'checkbox'],
                    ['label' => 'Perban', 'type' => 'checkbox'],
                    ['label' => 'Kasa Steril', 'type' => 'checkbox'],
                    ['label' => 'Antiseptik', 'type' => 'checkbox'],
                    ['label' => 'Gunting', 'type' => 'checkbox'],
                    ['label' => 'Sarung Tangan', 'type' => 'checkbox'],
                    ['label' => 'Masker', 'type' => 'checkbox'],
                ],
                'footer_fields' => [
                    ['label' => 'Lokasi', 'value' => 'Surabaya'],
                    ['label' => 'Label Pimpinan', 'value' => 'Team Leader K3L & KAM'],
                ],
                'is_active' => true,
            ],
            // P3K Pemeriksaan - Checklist kondisi kotak P3K
            [
                'module' => 'p3k-pemeriksaan',
                'title' => 'KARTU PEMERIKSAAN',
                'subtitle' => 'KOTAK P3K',
                'company_name' => 'PT PLN (Persero)',
                'company_address' => 'Jl. Trunojoyo No. 135, Surabaya',
                'company_phone' => 'Telp: (031) 1234567',
                'company_fax' => 'Fax: (031) 7654321',
                'company_email' => 'info@pln.co.id',
                'header_fields' => [
                    ['label' => 'No. Dokumen', 'value' => 'P3K-PEM-001'],
                    ['label' => 'Revisi', 'value' => '00'],
                    ['label' => 'Tanggal', 'value' => date('d-m-Y')],
                    ['label' => 'Halaman', 'value' => '1 dari 1'],
                ],
                'table_header' => 'CHECKLIST PEMERIKSAAN KOTAK P3K',
                'inspection_fields' => [
                    ['label' => 'Kotak P3K dalam kondisi baik dan bersih', 'type' => 'checkbox'],
                    ['label' => 'Semua item tersedia lengkap', 'type' => 'checkbox'],
                    ['label' => 'Tidak ada item yang kadaluarsa', 'type' => 'checkbox'],
                    ['label' => 'Obat-obatan tersimpan dengan baik', 'type' => 'checkbox'],
                    ['label' => 'Label dan instruksi terbaca jelas', 'type' => 'checkbox'],
                    ['label' => 'Kotak mudah diakses', 'type' => 'checkbox'],
                    ['label' => 'Lokasi penempatan sesuai standar', 'type' => 'checkbox'],
                ],
                'footer_fields' => [
                    ['label' => 'Lokasi', 'value' => 'Surabaya'],
                    ['label' => 'Label Pimpinan', 'value' => 'Team Leader K3L & KAM'],
                ],
                'is_active' => true,
            ],
            // P3K Pemakaian - Catatan penggunaan obat/alat
            [
                'module' => 'p3k-pemakaian',
                'title' => 'KARTU PEMAKAIAN',
                'subtitle' => 'KOTAK P3K',
                'company_name' => 'PT PLN (Persero)',
                'company_address' => 'Jl. Trunojoyo No. 135, Surabaya',
                'company_phone' => 'Telp: (031) 1234567',
                'company_fax' => 'Fax: (031) 7654321',
                'company_email' => 'info@pln.co.id',
                'header_fields' => [
                    ['label' => 'No. Dokumen', 'value' => 'P3K-PAK-001'],
                    ['label' => 'Revisi', 'value' => '00'],
                    ['label' => 'Tanggal', 'value' => date('d-m-Y')],
                    ['label' => 'Halaman', 'value' => '1 dari 1'],
                ],
                'table_header' => 'CATATAN PEMAKAIAN OBAT/ALAT P3K',
                'inspection_fields' => [
                    ['label' => 'Plester', 'type' => 'select'],
                    ['label' => 'Perban', 'type' => 'select'],
                    ['label' => 'Kasa Steril', 'type' => 'select'],
                    ['label' => 'Antiseptik', 'type' => 'select'],
                    ['label' => 'Sarung Tangan', 'type' => 'select'],
                    ['label' => 'Masker', 'type' => 'select'],
                    ['label' => 'Alkohol 70%', 'type' => 'select'],
                    ['label' => 'Betadine', 'type' => 'select'],
                    ['label' => 'Obat Luka', 'type' => 'select'],
                    ['label' => 'Kapas', 'type' => 'select'],
                ],
                'footer_fields' => [
                    ['label' => 'Lokasi', 'value' => 'Surabaya'],
                    ['label' => 'Label Pimpinan', 'value' => 'Team Leader K3L & KAM'],
                ],
                'is_active' => true,
            ],
            // P3K Stock - Kartu kendali stock
            [
                'module' => 'p3k-stock',
                'title' => 'KARTU KENDALI STOCK',
                'subtitle' => 'KOTAK P3K',
                'company_name' => 'PT PLN (Persero)',
                'company_address' => 'Jl. Trunojoyo No. 135, Surabaya',
                'company_phone' => 'Telp: (031) 1234567',
                'company_fax' => 'Fax: (031) 7654321',
                'company_email' => 'info@pln.co.id',
                'header_fields' => [
                    ['label' => 'No. Dokumen', 'value' => 'P3K-STK-001'],
                    ['label' => 'Revisi', 'value' => '00'],
                    ['label' => 'Tanggal', 'value' => date('d-m-Y')],
                    ['label' => 'Halaman', 'value' => '1 dari 1'],
                ],
                'table_header' => 'PEMERIKSAAN STOCK P3K',
                'inspection_fields' => [
                    ['label' => 'Kotak P3K', 'type' => 'checkbox'],
                    ['label' => 'Plester', 'type' => 'checkbox'],
                    ['label' => 'Perban', 'type' => 'checkbox'],
                    ['label' => 'Kasa Steril', 'type' => 'checkbox'],
                    ['label' => 'Antiseptik', 'type' => 'checkbox'],
                    ['label' => 'Gunting', 'type' => 'checkbox'],
                    ['label' => 'Sarung Tangan', 'type' => 'checkbox'],
                    ['label' => 'Masker', 'type' => 'checkbox'],
                    ['label' => 'Alkohol 70%', 'type' => 'checkbox'],
                    ['label' => 'Betadine', 'type' => 'checkbox'],
                    ['label' => 'Obat Luka', 'type' => 'checkbox'],
                    ['label' => 'Kapas', 'type' => 'checkbox'],
                ],
                'footer_fields' => [
                    ['label' => 'Lokasi', 'value' => 'Surabaya'],
                    ['label' => 'Label Pimpinan', 'value' => 'Team Leader K3L & KAM'],
                ],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $templateData) {
            KartuTemplate::updateOrCreate(
                ['module' => $templateData['module']],
                $templateData
            );
        }

        $this->command->info('✅ Kartu templates created successfully for all modules!');
    }
}
