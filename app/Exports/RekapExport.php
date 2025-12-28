<?php

namespace App\Exports;

use App\Models\Apar;
use App\Models\Apat;
use App\Models\Apab;
use App\Models\FireAlarm;
use App\Models\BoxHydrant;
use App\Models\RumahPompa;
use App\Models\KartuApar;
use App\Models\KartuApat;
use App\Models\KartuApab;
use App\Models\KartuFireAlarm;
use App\Models\KartuBoxHydrant;
use App\Models\KartuRumahPompa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $module;
    protected $type;

    public function __construct($module = 'all', $type = 'equipment')
    {
        $this->module = $module;
        $this->type = $type; // 'equipment' or 'kartu'
    }

    public function collection()
    {
        $data = collect();

        if ($this->type === 'kartu') {
            return $this->collectKartuData();
        }

        // Original equipment export
        if ($this->module === 'all' || $this->module === 'apar') {
            $items = Apar::all();
            foreach ($items as $item) {
                $data->push([
                    'APAR',
                    $item->serial_no,
                    $item->barcode,
                    $item->location_code ?? '-',
                    $item->status ?? '-',
                    $item->capacity ?? '-',
                ]);
            }
        }

        if ($this->module === 'all' || $this->module === 'apat') {
            $items = Apat::all();
            foreach ($items as $item) {
                $data->push([
                    'APAT',
                    $item->serial_no,
                    $item->barcode,
                    $item->location_code ?? '-',
                    $item->status ?? '-',
                    $item->capacity ?? '-',
                ]);
            }
        }

        if ($this->module === 'all' || $this->module === 'apab') {
            $items = Apab::all();
            foreach ($items as $item) {
                $data->push([
                    'APAB',
                    $item->serial_no,
                    $item->barcode,
                    $item->location_code ?? '-',
                    $item->status ?? '-',
                    $item->capacity ?? '-',
                ]);
            }
        }

        if ($this->module === 'all' || $this->module === 'fire_alarm') {
            $items = FireAlarm::all();
            foreach ($items as $item) {
                $data->push([
                    'Fire Alarm',
                    $item->serial_no,
                    $item->barcode,
                    $item->location_code ?? '-',
                    $item->status ?? '-',
                    '-',
                ]);
            }
        }

        if ($this->module === 'all' || $this->module === 'box_hydrant') {
            $items = BoxHydrant::all();
            foreach ($items as $item) {
                $data->push([
                    'Box Hydrant',
                    $item->serial_no,
                    $item->barcode,
                    $item->location_code ?? '-',
                    $item->status ?? '-',
                    '-',
                ]);
            }
        }

        if ($this->module === 'all' || $this->module === 'rumah_pompa') {
            $items = RumahPompa::all();
            foreach ($items as $item) {
                $data->push([
                    'Rumah Pompa',
                    $item->serial_no,
                    $item->barcode,
                    $item->location_code ?? '-',
                    $item->status ?? '-',
                    '-',
                ]);
            }
        }

        return $data;
    }

    protected function collectKartuData()
    {
        $data = collect();

        if ($this->module === 'all' || $this->module === 'apar') {
            $kartus = KartuApar::with(['apar', 'user', 'approver'])->get();
            foreach ($kartus as $kartu) {
                $data->push([
                    'APAR',
                    $kartu->apar->serial_no ?? '-',
                    $kartu->tgl_periksa ? $kartu->tgl_periksa->format('d/m/Y') : '-',
                    $kartu->kesimpulan ?? '-',
                    $kartu->user->name ?? 'User Deleted',
                    $kartu->created_at ? $kartu->created_at->format('d/m/Y H:i') : '-',
                    $kartu->isApproved() ? 'Approved' : 'Pending',
                    $kartu->approver->name ?? '-',
                    $kartu->approved_at ? $kartu->approved_at->format('d/m/Y H:i') : '-',
                ]);
            }
        }

        if ($this->module === 'all' || $this->module === 'apat') {
            $kartus = KartuApat::with(['apat', 'user', 'approver'])->get();
            foreach ($kartus as $kartu) {
                $data->push([
                    'APAT',
                    $kartu->apat->serial_no ?? '-',
                    $kartu->tgl_periksa ? $kartu->tgl_periksa->format('d/m/Y') : '-',
                    $kartu->kesimpulan ?? '-',
                    $kartu->user->name ?? 'User Deleted',
                    $kartu->created_at ? $kartu->created_at->format('d/m/Y H:i') : '-',
                    $kartu->isApproved() ? 'Approved' : 'Pending',
                    $kartu->approver->name ?? '-',
                    $kartu->approved_at ? $kartu->approved_at->format('d/m/Y H:i') : '-',
                ]);
            }
        }

        if ($this->module === 'all' || $this->module === 'apab') {
            $kartus = KartuApab::with(['apab', 'user', 'approver'])->get();
            foreach ($kartus as $kartu) {
                $data->push([
                    'APAB',
                    $kartu->apab->serial_no ?? '-',
                    $kartu->tgl_periksa ? $kartu->tgl_periksa->format('d/m/Y') : '-',
                    $kartu->kesimpulan ?? '-',
                    $kartu->user->name ?? 'User Deleted',
                    $kartu->created_at ? $kartu->created_at->format('d/m/Y H:i') : '-',
                    $kartu->isApproved() ? 'Approved' : 'Pending',
                    $kartu->approver->name ?? '-',
                    $kartu->approved_at ? $kartu->approved_at->format('d/m/Y H:i') : '-',
                ]);
            }
        }

        if ($this->module === 'all' || $this->module === 'fire_alarm') {
            $kartus = KartuFireAlarm::with(['fireAlarm', 'user', 'approver'])->get();
            foreach ($kartus as $kartu) {
                $data->push([
                    'Fire Alarm',
                    $kartu->fireAlarm->serial_no ?? '-',
                    $kartu->tgl_periksa ? $kartu->tgl_periksa->format('d/m/Y') : '-',
                    $kartu->kesimpulan ?? '-',
                    $kartu->user->name ?? 'User Deleted',
                    $kartu->created_at ? $kartu->created_at->format('d/m/Y H:i') : '-',
                    $kartu->isApproved() ? 'Approved' : 'Pending',
                    $kartu->approver->name ?? '-',
                    $kartu->approved_at ? $kartu->approved_at->format('d/m/Y H:i') : '-',
                ]);
            }
        }

        if ($this->module === 'all' || $this->module === 'box_hydrant') {
            $kartus = KartuBoxHydrant::with(['boxHydrant', 'user', 'approver'])->get();
            foreach ($kartus as $kartu) {
                $data->push([
                    'Box Hydrant',
                    $kartu->boxHydrant->serial_no ?? '-',
                    $kartu->tgl_periksa ? $kartu->tgl_periksa->format('d/m/Y') : '-',
                    $kartu->kesimpulan ?? '-',
                    $kartu->user->name ?? 'User Deleted',
                    $kartu->created_at ? $kartu->created_at->format('d/m/Y H:i') : '-',
                    $kartu->isApproved() ? 'Approved' : 'Pending',
                    $kartu->approver->name ?? '-',
                    $kartu->approved_at ? $kartu->approved_at->format('d/m/Y H:i') : '-',
                ]);
            }
        }

        if ($this->module === 'all' || $this->module === 'rumah_pompa') {
            $kartus = KartuRumahPompa::with(['rumahPompa', 'user', 'approver'])->get();
            foreach ($kartus as $kartu) {
                $data->push([
                    'Rumah Pompa',
                    $kartu->rumahPompa->serial_no ?? '-',
                    $kartu->tgl_periksa ? $kartu->tgl_periksa->format('d/m/Y') : '-',
                    $kartu->kesimpulan ?? '-',
                    $kartu->user->name ?? 'User Deleted',
                    $kartu->created_at ? $kartu->created_at->format('d/m/Y H:i') : '-',
                    $kartu->isApproved() ? 'Approved' : 'Pending',
                    $kartu->approver->name ?? '-',
                    $kartu->approved_at ? $kartu->approved_at->format('d/m/Y H:i') : '-',
                ]);
            }
        }

        return $data;
    }

    public function headings(): array
    {
        if ($this->type === 'kartu') {
            return [
                'Modul',
                'Serial No',
                'Tanggal Periksa',
                'Kesimpulan',
                'Dibuat Oleh',
                'Tanggal Dibuat',
                'Status Approval',
                'Di-approve Oleh',
                'Tanggal Approval',
            ];
        }

        return [
            'Modul',
            'Serial No',
            'Barcode',
            'Lokasi',
            'Status',
            'Kapasitas',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        if ($this->type === 'kartu') {
            return 'Rekap Kartu Kendali';
        }
        return 'Rekap Peralatan';
    }
}
