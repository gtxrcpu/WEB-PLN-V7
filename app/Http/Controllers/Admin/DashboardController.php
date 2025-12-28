<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Apar;
use App\Models\Apat;
use App\Models\FireAlarm;
use App\Models\BoxHydrant;
use App\Models\RumahPompa;
use App\Models\Apab;
use App\Models\P3k;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Total users by role
        $totalUsers = User::count();
        $totalSuperadmin = User::role('superadmin')->count();
        $totalLeader = User::role('leader')->count();
        $totalInspector = User::role('inspector')->count();
        $totalPetugas = User::role('petugas')->count();

        // Total equipment
        $totalApar = Apar::count();
        $totalApat = Apat::count();
        $totalFireAlarm = FireAlarm::count();
        $totalBoxHydrant = BoxHydrant::count();
        $totalRumahPompa = RumahPompa::count();
        $totalApab = Apab::count();
        $totalP3k = P3k::count();
        $totalEquipment = $totalApar + $totalApat + $totalFireAlarm + $totalBoxHydrant + $totalRumahPompa + $totalApab + $totalP3k;

        // APAR status breakdown
        $aparData = [
            'baik' => Apar::where('status', 'baik')->count(),
            'isi_ulang' => Apar::where('status', 'isi ulang')->count(),
            'rusak' => Apar::where('status', 'rusak')->count(),
            'total' => $totalApar
        ];

        // APAT status breakdown
        $apatData = [
            'baik' => Apat::where('status', 'baik')->count(),
            'rusak' => Apat::where('status', 'rusak')->count(),
            'total' => $totalApat
        ];

        // APAB status breakdown
        $apabData = [
            'baik' => Apab::where('status', 'baik')->count(),
            'tidak_baik' => Apab::where('status', 'tidak baik')->count(),
            'total' => $totalApab
        ];

        // Fire Alarm status breakdown
        $fireAlarmData = [
            'baik' => FireAlarm::where('status', 'baik')->count(),
            'rusak' => FireAlarm::where('status', 'rusak')->count(),
            'total' => $totalFireAlarm
        ];

        // Box Hydrant status breakdown
        $boxHydrantData = [
            'baik' => BoxHydrant::where('status', 'baik')->count(),
            'rusak' => BoxHydrant::where('status', 'rusak')->count(),
            'total' => $totalBoxHydrant
        ];

        // Rumah Pompa status breakdown
        $rumahPompaData = [
            'baik' => RumahPompa::where('status', 'baik')->count(),
            'rusak' => RumahPompa::where('status', 'rusak')->count(),
            'total' => $totalRumahPompa
        ];

        // Recent users
        $recentUsers = User::latest()->take(5)->get();

        // Equipment by type
        $equipmentByType = [
            ['name' => 'APAR', 'count' => $totalApar],
            ['name' => 'APAT', 'count' => $totalApat],
            ['name' => 'Fire Alarm', 'count' => $totalFireAlarm],
            ['name' => 'Box Hydrant', 'count' => $totalBoxHydrant],
            ['name' => 'Rumah Pompa', 'count' => $totalRumahPompa],
            ['name' => 'APAB', 'count' => $totalApab],
            ['name' => 'P3K', 'count' => $totalP3k],
        ];

        // Total items for KPI
        $totalBaik = $aparData['baik'] + $apatData['baik'] + $apabData['baik'] + 
                     $fireAlarmData['baik'] + $boxHydrantData['baik'] + $rumahPompaData['baik'];
        $totalRusak = $aparData['rusak'] + $apatData['rusak'] + $apabData['tidak_baik'] + 
                      $fireAlarmData['rusak'] + $boxHydrantData['rusak'] + $rumahPompaData['rusak'];

        // Pending approvals
        $pendingApprovals = \App\Models\KartuApar::whereNull('approved_at')->count();
        $pendingApprovalsList = \App\Models\KartuApar::with(['apar.unit', 'user'])
            ->whereNull('approved_at')
            ->latest()
            ->take(10)
            ->get();

        // Tren inspeksi 12 bulan terakhir (gabungan semua modul)
        $monthlyInspections = [];
        $monthLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabels[] = $date->format('M Y');
            
            // Hitung dari semua tabel kartu
            $countApar = DB::table('kartu_apars')
                ->whereYear('tgl_periksa', $date->year)
                ->whereMonth('tgl_periksa', $date->month)
                ->count();
            
            $countApat = DB::table('kartu_apats')
                ->whereYear('tgl_periksa', $date->year)
                ->whereMonth('tgl_periksa', $date->month)
                ->count();
            
            $countApab = DB::table('kartu_apabs')
                ->whereYear('tgl_periksa', $date->year)
                ->whereMonth('tgl_periksa', $date->month)
                ->count();
            
            $countFireAlarm = DB::table('kartu_fire_alarms')
                ->whereYear('tgl_periksa', $date->year)
                ->whereMonth('tgl_periksa', $date->month)
                ->count();
            
            $countBoxHydrant = DB::table('kartu_box_hydrants')
                ->whereYear('tgl_periksa', $date->year)
                ->whereMonth('tgl_periksa', $date->month)
                ->count();
            
            $countRumahPompa = DB::table('kartu_rumah_pompas')
                ->whereYear('tgl_periksa', $date->year)
                ->whereMonth('tgl_periksa', $date->month)
                ->count();
            
            $countP3k = DB::table('kartu_p3ks')
                ->whereYear('tgl_periksa', $date->year)
                ->whereMonth('tgl_periksa', $date->month)
                ->count();
            
            $totalCount = $countApar + $countApat + $countApab + $countFireAlarm + $countBoxHydrant + $countRumahPompa + $countP3k;
            $monthlyInspections[] = $totalCount;
        }

        $totalItems = $totalEquipment;

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalSuperadmin',
            'totalLeader',
            'totalInspector',
            'totalPetugas',
            'totalEquipment',
            'totalApar',
            'totalApat',
            'totalFireAlarm',
            'totalBoxHydrant',
            'totalRumahPompa',
            'totalApab',
            'totalP3k',
            'aparData',
            'apatData',
            'apabData',
            'fireAlarmData',
            'boxHydrantData',
            'rumahPompaData',
            'recentUsers',
            'equipmentByType',
            'totalItems',
            'totalBaik',
            'totalRusak',
            'monthlyInspections',
            'monthLabels'
        ));
    }
}
