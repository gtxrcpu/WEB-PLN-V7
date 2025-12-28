<?php

namespace App\Http\Controllers;

use App\Models\Apar;
use App\Models\Apat;
use App\Models\Apab;
use App\Models\FireAlarm;
use App\Models\BoxHydrant;
use App\Models\RumahPompa;
use App\Models\KartuKendali;
use App\Models\KartuApat;
use App\Models\KartuApab;
use App\Models\KartuFireAlarm;
use App\Models\KartuBoxHydrant;
use App\Models\KartuRumahPompa;
use Illuminate\Http\Request;

class QuickActionController extends Controller
{
    // Scan QR
    public function scan()
    {
        return view('quick.scan');
    }

    public function searchQR(Request $request)
    {
        // Accept both GET and POST
        $qr = $request->input('qr') ?? $request->query('qr');
        
        // Try to decode JSON format (new format)
        $decoded = json_decode($qr, true);
        if ($decoded && isset($decoded['type']) && isset($decoded['code'])) {
            $type = strtolower($decoded['type']);
            $code = $decoded['code'];
            
            // Search by code based on type
            if ($type === 'apar') {
                $equipment = Apar::where('barcode', $code)->orWhere('serial_no', $code)->first();
                if ($equipment) {
                    return view('quick.scan-result', [
                        'equipment' => $equipment,
                        'type' => 'apar',
                        'typeName' => 'APAR',
                        'qr' => $qr
                    ]);
                }
            } elseif ($type === 'apat') {
                $equipment = Apat::where('barcode', $code)->orWhere('serial_no', $code)->first();
                if ($equipment) {
                    return view('quick.scan-result', [
                        'equipment' => $equipment,
                        'type' => 'apat',
                        'typeName' => 'APAT',
                        'qr' => $qr
                    ]);
                }
            } elseif ($type === 'apab') {
                $equipment = Apab::where('barcode', $code)->orWhere('serial_no', $code)->first();
                if ($equipment) {
                    return view('quick.scan-result', [
                        'equipment' => $equipment,
                        'type' => 'apab',
                        'typeName' => 'APAB',
                        'qr' => $qr
                    ]);
                }
            } elseif ($type === 'fire alarm' || $type === 'fire-alarm') {
                $equipment = FireAlarm::where('barcode', $code)->orWhere('serial_no', $code)->first();
                if ($equipment) {
                    return view('quick.scan-result', [
                        'equipment' => $equipment,
                        'type' => 'fire-alarm',
                        'typeName' => 'Fire Alarm',
                        'qr' => $qr
                    ]);
                }
            } elseif ($type === 'box hydrant' || $type === 'box-hydrant') {
                $equipment = BoxHydrant::where('barcode', $code)->orWhere('serial_no', $code)->first();
                if ($equipment) {
                    return view('quick.scan-result', [
                        'equipment' => $equipment,
                        'type' => 'box-hydrant',
                        'typeName' => 'Box Hydrant',
                        'qr' => $qr
                    ]);
                }
            } elseif ($type === 'rumah pompa' || $type === 'rumah-pompa') {
                $equipment = RumahPompa::where('barcode', $code)->orWhere('serial_no', $code)->first();
                if ($equipment) {
                    return view('quick.scan-result', [
                        'equipment' => $equipment,
                        'type' => 'rumah-pompa',
                        'typeName' => 'Rumah Pompa',
                        'qr' => $qr
                    ]);
                }
            }
        }
        
        // Extract ID from URL if QR contains full URL (backward compatibility)
        // Example: http://127.0.0.1:8000/apar/2/riwayat -> extract "2" and "apar"
        if (preg_match('#/(apar|apat|apab|fire-alarm|box-hydrant|rumah-pompa)/(\d+)#', $qr, $matches)) {
            $module = $matches[1];
            $id = $matches[2];
            
            // Search by ID based on module
            if ($module === 'apar') {
                $equipment = Apar::find($id);
                if ($equipment) {
                    return view('quick.scan-result', [
                        'equipment' => $equipment,
                        'type' => 'apar',
                        'typeName' => 'APAR',
                        'qr' => $qr
                    ]);
                }
            } elseif ($module === 'apat') {
                $equipment = Apat::find($id);
                if ($equipment) {
                    return view('quick.scan-result', [
                        'equipment' => $equipment,
                        'type' => 'apat',
                        'typeName' => 'APAT',
                        'qr' => $qr
                    ]);
                }
            } elseif ($module === 'apab') {
                $equipment = Apab::find($id);
                if ($equipment) {
                    return view('quick.scan-result', [
                        'equipment' => $equipment,
                        'type' => 'apab',
                        'typeName' => 'APAB',
                        'qr' => $qr
                    ]);
                }
            } elseif ($module === 'fire-alarm') {
                $equipment = FireAlarm::find($id);
                if ($equipment) {
                    return view('quick.scan-result', [
                        'equipment' => $equipment,
                        'type' => 'fire-alarm',
                        'typeName' => 'Fire Alarm',
                        'qr' => $qr
                    ]);
                }
            } elseif ($module === 'box-hydrant') {
                $equipment = BoxHydrant::find($id);
                if ($equipment) {
                    return view('quick.scan-result', [
                        'equipment' => $equipment,
                        'type' => 'box-hydrant',
                        'typeName' => 'Box Hydrant',
                        'qr' => $qr
                    ]);
                }
            } elseif ($module === 'rumah-pompa') {
                $equipment = RumahPompa::find($id);
                if ($equipment) {
                    return view('quick.scan-result', [
                        'equipment' => $equipment,
                        'type' => 'rumah-pompa',
                        'typeName' => 'Rumah Pompa',
                        'qr' => $qr
                    ]);
                }
            }
        }
        
        // Fallback: Search by barcode or serial_no in all modules
        $apar = Apar::where('barcode', $qr)->orWhere('serial_no', $qr)->first();
        if ($apar) {
            return view('quick.scan-result', [
                'equipment' => $apar,
                'type' => 'apar',
                'typeName' => 'APAR',
                'qr' => $qr
            ]);
        }

        $apat = Apat::where('barcode', $qr)->orWhere('serial_no', $qr)->first();
        if ($apat) {
            return view('quick.scan-result', [
                'equipment' => $apat,
                'type' => 'apat',
                'typeName' => 'APAT',
                'qr' => $qr
            ]);
        }

        $apab = Apab::where('barcode', $qr)->orWhere('serial_no', $qr)->first();
        if ($apab) {
            return view('quick.scan-result', [
                'equipment' => $apab,
                'type' => 'apab',
                'typeName' => 'APAB',
                'qr' => $qr
            ]);
        }

        $fireAlarm = FireAlarm::where('barcode', $qr)->orWhere('serial_no', $qr)->first();
        if ($fireAlarm) {
            return view('quick.scan-result', [
                'equipment' => $fireAlarm,
                'type' => 'fire-alarm',
                'typeName' => 'Fire Alarm',
                'qr' => $qr
            ]);
        }

        $boxHydrant = BoxHydrant::where('barcode', $qr)->orWhere('serial_no', $qr)->first();
        if ($boxHydrant) {
            return view('quick.scan-result', [
                'equipment' => $boxHydrant,
                'type' => 'box-hydrant',
                'typeName' => 'Box Hydrant',
                'qr' => $qr
            ]);
        }

        $rumahPompa = RumahPompa::where('barcode', $qr)->orWhere('serial_no', $qr)->first();
        if ($rumahPompa) {
            return view('quick.scan-result', [
                'equipment' => $rumahPompa,
                'type' => 'rumah-pompa',
                'typeName' => 'Rumah Pompa',
                'qr' => $qr
            ]);
        }

        return back()->with('error', 'QR Code tidak ditemukan');
    }

    // Buat Inspeksi
    public function inspeksi()
    {
        // Get all items for selection
        $apars = Apar::orderBy('serial_no')->get();
        $apats = Apat::orderBy('serial_no')->get();
        $apabs = Apab::orderBy('serial_no')->get();
        $fireAlarms = FireAlarm::orderBy('serial_no')->get();
        $boxHydrants = BoxHydrant::orderBy('serial_no')->get();
        $rumahPompas = RumahPompa::orderBy('serial_no')->get();

        return view('quick.inspeksi', compact('apars', 'apats', 'apabs', 'fireAlarms', 'boxHydrants', 'rumahPompas'));
    }

    // Rekap & Export
    public function rekap()
    {
        // Helper function to safely count table
        $safeCount = function($tableName) {
            try {
                return \DB::table($tableName)->count();
            } catch (\Exception $e) {
                return 0;
            }
        };

        // Get statistics with safe counting
        $stats = [
            'apar' => [
                'total' => Apar::count(),
                'baik' => Apar::where('status', 'baik')->count(),
                'rusak' => Apar::where('status', 'rusak')->count(),
                'inspeksi' => $safeCount('kartu_apars'),
            ],
            'apat' => [
                'total' => Apat::count(),
                'baik' => Apat::where('status', 'baik')->count(),
                'rusak' => Apat::where('status', 'rusak')->count(),
                'inspeksi' => $safeCount('kartu_apats'),
            ],
            'apab' => [
                'total' => Apab::count(),
                'baik' => Apab::where('status', 'baik')->count(),
                'rusak' => Apab::where('status', 'tidak_baik')->count(),
                'inspeksi' => $safeCount('kartu_apabs'),
            ],
            'fire_alarm' => [
                'total' => FireAlarm::count(),
                'baik' => FireAlarm::where('status', 'baik')->count(),
                'rusak' => FireAlarm::where('status', 'rusak')->count(),
                'inspeksi' => $safeCount('kartu_fire_alarms'),
            ],
            'box_hydrant' => [
                'total' => BoxHydrant::count(),
                'baik' => BoxHydrant::where('status', 'baik')->count(),
                'rusak' => BoxHydrant::where('status', 'rusak')->count(),
                'inspeksi' => $safeCount('kartu_box_hydrants'),
            ],
            'rumah_pompa' => [
                'total' => RumahPompa::count(),
                'baik' => RumahPompa::where('status', 'baik')->count(),
                'rusak' => RumahPompa::where('status', 'rusak')->count(),
                'inspeksi' => $safeCount('kartu_rumah_pompas'),
            ],
        ];

        return view('quick.rekap', compact('stats'));
    }

    public function exportExcel(Request $request)
    {
        $module = $request->input('module', 'all');
        $type = $request->input('type', 'equipment'); // 'equipment' or 'kartu'
        $format = $request->input('format', 'excel'); // excel or pdf
        
        return \Excel::download(new \App\Exports\RekapExport($module, $type), 
            "rekap_{$type}_{$module}_" . date('Y-m-d') . ".xlsx");
    }

    public function exportPdf(Request $request)
    {
        $module = $request->input('module', 'all');
        $type = $request->input('type', 'equipment'); // 'equipment' or 'kartu'
        
        // Collect data
        $data = $type === 'kartu' 
            ? $this->collectKartuExportData($module) 
            : $this->collectExportData($module);
        
        $pdf = \PDF::loadView('exports.rekap-pdf', [
            'data' => $data,
            'module' => $module,
            'type' => $type,
            'date' => date('d/m/Y')
        ]);
        
        return $pdf->download("rekap_{$type}_{$module}_" . date('Y-m-d') . ".pdf");
    }

    private function collectExportData($module)
    {
        $data = [];

        if ($module === 'all' || $module === 'apar') {
            $items = Apar::all();
            foreach ($items as $item) {
                $data[] = [
                    'modul' => 'APAR',
                    'serial_no' => $item->serial_no,
                    'barcode' => $item->barcode,
                    'lokasi' => $item->location_code ?? '-',
                    'status' => $item->status ?? '-',
                    'kapasitas' => $item->capacity ?? '-',
                ];
            }
        }

        if ($module === 'all' || $module === 'apat') {
            $items = Apat::all();
            foreach ($items as $item) {
                $data[] = [
                    'modul' => 'APAT',
                    'serial_no' => $item->serial_no,
                    'barcode' => $item->barcode,
                    'lokasi' => $item->location_code ?? '-',
                    'status' => $item->status ?? '-',
                    'kapasitas' => $item->capacity ?? '-',
                ];
            }
        }

        if ($module === 'all' || $module === 'apab') {
            $items = Apab::all();
            foreach ($items as $item) {
                $data[] = [
                    'modul' => 'APAB',
                    'serial_no' => $item->serial_no,
                    'barcode' => $item->barcode,
                    'lokasi' => $item->location_code ?? '-',
                    'status' => $item->status ?? '-',
                    'kapasitas' => $item->capacity ?? '-',
                ];
            }
        }

        if ($module === 'all' || $module === 'fire_alarm') {
            $items = FireAlarm::all();
            foreach ($items as $item) {
                $data[] = [
                    'modul' => 'Fire Alarm',
                    'serial_no' => $item->serial_no,
                    'barcode' => $item->barcode,
                    'lokasi' => $item->location_code ?? '-',
                    'status' => $item->status ?? '-',
                    'kapasitas' => '-',
                ];
            }
        }

        if ($module === 'all' || $module === 'box_hydrant') {
            $items = BoxHydrant::all();
            foreach ($items as $item) {
                $data[] = [
                    'modul' => 'Box Hydrant',
                    'serial_no' => $item->serial_no,
                    'barcode' => $item->barcode,
                    'lokasi' => $item->location_code ?? '-',
                    'status' => $item->status ?? '-',
                    'kapasitas' => '-',
                ];
            }
        }

        if ($module === 'all' || $module === 'rumah_pompa') {
            $items = RumahPompa::all();
            foreach ($items as $item) {
                $data[] = [
                    'modul' => 'Rumah Pompa',
                    'serial_no' => $item->serial_no,
                    'barcode' => $item->barcode,
                    'lokasi' => $item->location_code ?? '-',
                    'status' => $item->status ?? '-',
                    'kapasitas' => '-',
                ];
            }
        }

        return $data;
    }

    private function collectKartuExportData($module)
    {
        $data = [];

        if ($module === 'all' || $module === 'apar') {
            $kartus = \App\Models\KartuApar::with(['apar', 'user', 'approver'])->get();
            foreach ($kartus as $kartu) {
                $data[] = [
                    'modul' => 'APAR',
                    'serial_no' => $kartu->apar->serial_no ?? '-',
                    'tgl_periksa' => $kartu->tgl_periksa ? $kartu->tgl_periksa->format('d/m/Y') : '-',
                    'kesimpulan' => $kartu->kesimpulan ?? '-',
                    'dibuat_oleh' => $kartu->user->name ?? 'User Deleted',
                    'tgl_dibuat' => $kartu->created_at ? $kartu->created_at->format('d/m/Y H:i') : '-',
                    'status' => $kartu->isApproved() ? 'Approved' : 'Pending',
                    'approved_oleh' => $kartu->approver->name ?? '-',
                    'tgl_approval' => $kartu->approved_at ? $kartu->approved_at->format('d/m/Y H:i') : '-',
                ];
            }
        }

        if ($module === 'all' || $module === 'apat') {
            $kartus = KartuApat::with(['apat', 'user', 'approver'])->get();
            foreach ($kartus as $kartu) {
                $data[] = [
                    'modul' => 'APAT',
                    'serial_no' => $kartu->apat->serial_no ?? '-',
                    'tgl_periksa' => $kartu->tgl_periksa ? $kartu->tgl_periksa->format('d/m/Y') : '-',
                    'kesimpulan' => $kartu->kesimpulan ?? '-',
                    'dibuat_oleh' => $kartu->user->name ?? 'User Deleted',
                    'tgl_dibuat' => $kartu->created_at ? $kartu->created_at->format('d/m/Y H:i') : '-',
                    'status' => $kartu->isApproved() ? 'Approved' : 'Pending',
                    'approved_oleh' => $kartu->approver->name ?? '-',
                    'tgl_approval' => $kartu->approved_at ? $kartu->approved_at->format('d/m/Y H:i') : '-',
                ];
            }
        }

        if ($module === 'all' || $module === 'apab') {
            $kartus = KartuApab::with(['apab', 'user', 'approver'])->get();
            foreach ($kartus as $kartu) {
                $data[] = [
                    'modul' => 'APAB',
                    'serial_no' => $kartu->apab->serial_no ?? '-',
                    'tgl_periksa' => $kartu->tgl_periksa ? $kartu->tgl_periksa->format('d/m/Y') : '-',
                    'kesimpulan' => $kartu->kesimpulan ?? '-',
                    'dibuat_oleh' => $kartu->user->name ?? 'User Deleted',
                    'tgl_dibuat' => $kartu->created_at ? $kartu->created_at->format('d/m/Y H:i') : '-',
                    'status' => $kartu->isApproved() ? 'Approved' : 'Pending',
                    'approved_oleh' => $kartu->approver->name ?? '-',
                    'tgl_approval' => $kartu->approved_at ? $kartu->approved_at->format('d/m/Y H:i') : '-',
                ];
            }
        }

        if ($module === 'all' || $module === 'fire_alarm') {
            $kartus = KartuFireAlarm::with(['fireAlarm', 'user', 'approver'])->get();
            foreach ($kartus as $kartu) {
                $data[] = [
                    'modul' => 'Fire Alarm',
                    'serial_no' => $kartu->fireAlarm->serial_no ?? '-',
                    'tgl_periksa' => $kartu->tgl_periksa ? $kartu->tgl_periksa->format('d/m/Y') : '-',
                    'kesimpulan' => $kartu->kesimpulan ?? '-',
                    'dibuat_oleh' => $kartu->user->name ?? 'User Deleted',
                    'tgl_dibuat' => $kartu->created_at ? $kartu->created_at->format('d/m/Y H:i') : '-',
                    'status' => $kartu->isApproved() ? 'Approved' : 'Pending',
                    'approved_oleh' => $kartu->approver->name ?? '-',
                    'tgl_approval' => $kartu->approved_at ? $kartu->approved_at->format('d/m/Y H:i') : '-',
                ];
            }
        }

        if ($module === 'all' || $module === 'box_hydrant') {
            $kartus = KartuBoxHydrant::with(['boxHydrant', 'user', 'approver'])->get();
            foreach ($kartus as $kartu) {
                $data[] = [
                    'modul' => 'Box Hydrant',
                    'serial_no' => $kartu->boxHydrant->serial_no ?? '-',
                    'tgl_periksa' => $kartu->tgl_periksa ? $kartu->tgl_periksa->format('d/m/Y') : '-',
                    'kesimpulan' => $kartu->kesimpulan ?? '-',
                    'dibuat_oleh' => $kartu->user->name ?? 'User Deleted',
                    'tgl_dibuat' => $kartu->created_at ? $kartu->created_at->format('d/m/Y H:i') : '-',
                    'status' => $kartu->isApproved() ? 'Approved' : 'Pending',
                    'approved_oleh' => $kartu->approver->name ?? '-',
                    'tgl_approval' => $kartu->approved_at ? $kartu->approved_at->format('d/m/Y H:i') : '-',
                ];
            }
        }

        if ($module === 'all' || $module === 'rumah_pompa') {
            $kartus = KartuRumahPompa::with(['rumahPompa', 'user', 'approver'])->get();
            foreach ($kartus as $kartu) {
                $data[] = [
                    'modul' => 'Rumah Pompa',
                    'serial_no' => $kartu->rumahPompa->serial_no ?? '-',
                    'tgl_periksa' => $kartu->tgl_periksa ? $kartu->tgl_periksa->format('d/m/Y') : '-',
                    'kesimpulan' => $kartu->kesimpulan ?? '-',
                    'dibuat_oleh' => $kartu->user->name ?? 'User Deleted',
                    'tgl_dibuat' => $kartu->created_at ? $kartu->created_at->format('d/m/Y H:i') : '-',
                    'status' => $kartu->isApproved() ? 'Approved' : 'Pending',
                    'approved_oleh' => $kartu->approver->name ?? '-',
                    'tgl_approval' => $kartu->approved_at ? $kartu->approved_at->format('d/m/Y H:i') : '-',
                ];
            }
        }

        return $data;
    }
}
