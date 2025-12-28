<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\KartuApar;
use App\Models\Apar;
use App\Models\Apat;
use App\Models\Apab;
use App\Models\FireAlarm;
use App\Models\BoxHydrant;
use App\Models\RumahPompa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $unitId = $user->unit_id;

        // Stats untuk unit leader
        $stats = [
            'total_equipment' => Apar::where('unit_id', $unitId)->count(),
            'pending_approvals' => KartuApar::whereHas('apar', function($q) use ($unitId) {
                $q->where('unit_id', $unitId);
            })->whereNull('approved_at')->count(),
            'approved_this_month' => KartuApar::whereHas('apar', function($q) use ($unitId) {
                $q->where('unit_id', $unitId);
            })->whereNotNull('approved_at')
              ->whereMonth('approved_at', now()->month)
              ->count(),
            'total_users' => \App\Models\User::where('unit_id', $unitId)->count(),
        ];

        // Equipment data by module for this unit
        $totalApar = Apar::where('unit_id', $unitId)->count();
        $totalApat = Apat::where('unit_id', $unitId)->count();
        $totalApab = Apab::where('unit_id', $unitId)->count();
        $totalFireAlarm = FireAlarm::where('unit_id', $unitId)->count();
        $totalBoxHydrant = BoxHydrant::where('unit_id', $unitId)->count();
        $totalRumahPompa = RumahPompa::where('unit_id', $unitId)->count();
        
        $totalItems = $totalApar + $totalApat + $totalApab + $totalFireAlarm + $totalBoxHydrant + $totalRumahPompa;

        // APAR status breakdown
        $aparData = [
            'baik' => Apar::where('unit_id', $unitId)->where('status', 'baik')->count(),
            'isi_ulang' => Apar::where('unit_id', $unitId)->where('status', 'isi ulang')->count(),
            'rusak' => Apar::where('unit_id', $unitId)->where('status', 'rusak')->count(),
            'total' => $totalApar
        ];

        // APAT status breakdown
        $apatData = [
            'baik' => Apat::where('unit_id', $unitId)->where('status', 'baik')->count(),
            'rusak' => Apat::where('unit_id', $unitId)->where('status', 'rusak')->count(),
            'total' => $totalApat
        ];

        // APAB status breakdown
        $apabData = [
            'baik' => Apab::where('unit_id', $unitId)->where('status', 'baik')->count(),
            'tidak_baik' => Apab::where('unit_id', $unitId)->where('status', 'tidak baik')->count(),
            'total' => $totalApab
        ];

        // Fire Alarm status breakdown
        $fireAlarmData = [
            'baik' => FireAlarm::where('unit_id', $unitId)->where('status', 'baik')->count(),
            'rusak' => FireAlarm::where('unit_id', $unitId)->where('status', 'rusak')->count(),
            'total' => $totalFireAlarm
        ];

        // Box Hydrant status breakdown
        $boxHydrantData = [
            'baik' => BoxHydrant::where('unit_id', $unitId)->where('status', 'baik')->count(),
            'rusak' => BoxHydrant::where('unit_id', $unitId)->where('status', 'rusak')->count(),
            'total' => $totalBoxHydrant
        ];

        // Rumah Pompa status breakdown
        $rumahPompaData = [
            'baik' => RumahPompa::where('unit_id', $unitId)->where('status', 'baik')->count(),
            'rusak' => RumahPompa::where('unit_id', $unitId)->where('status', 'rusak')->count(),
            'total' => $totalRumahPompa
        ];

        // Tren inspeksi 6 bulan terakhir untuk unit ini
        $trendData = [
            'labels' => [],
            'datasets' => [
                'APAR' => [],
                'APAT' => [],
                'APAB' => [],
                'Fire Alarm' => [],
                'Box Hydrant' => [],
                'Rumah Pompa' => []
            ]
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $trendData['labels'][] = $date->format('M Y');
            
            // Count inspections for each module in this unit
            $trendData['datasets']['APAR'][] = DB::table('kartu_apars')
                ->join('apars', 'kartu_apars.apar_id', '=', 'apars.id')
                ->where('apars.unit_id', $unitId)
                ->whereYear('kartu_apars.tgl_periksa', $date->year)
                ->whereMonth('kartu_apars.tgl_periksa', $date->month)
                ->count();
            
            $trendData['datasets']['APAT'][] = DB::table('kartu_apats')
                ->join('apats', 'kartu_apats.apat_id', '=', 'apats.id')
                ->where('apats.unit_id', $unitId)
                ->whereYear('kartu_apats.tgl_periksa', $date->year)
                ->whereMonth('kartu_apats.tgl_periksa', $date->month)
                ->count();
            
            $trendData['datasets']['APAB'][] = DB::table('kartu_apabs')
                ->join('apabs', 'kartu_apabs.apab_id', '=', 'apabs.id')
                ->where('apabs.unit_id', $unitId)
                ->whereYear('kartu_apabs.tgl_periksa', $date->year)
                ->whereMonth('kartu_apabs.tgl_periksa', $date->month)
                ->count();
            
            $trendData['datasets']['Fire Alarm'][] = DB::table('kartu_fire_alarms')
                ->join('fire_alarms', 'kartu_fire_alarms.fire_alarm_id', '=', 'fire_alarms.id')
                ->where('fire_alarms.unit_id', $unitId)
                ->whereYear('kartu_fire_alarms.tgl_periksa', $date->year)
                ->whereMonth('kartu_fire_alarms.tgl_periksa', $date->month)
                ->count();
            
            $trendData['datasets']['Box Hydrant'][] = DB::table('kartu_box_hydrants')
                ->join('box_hydrants', 'kartu_box_hydrants.box_hydrant_id', '=', 'box_hydrants.id')
                ->where('box_hydrants.unit_id', $unitId)
                ->whereYear('kartu_box_hydrants.tgl_periksa', $date->year)
                ->whereMonth('kartu_box_hydrants.tgl_periksa', $date->month)
                ->count();
            
            $trendData['datasets']['Rumah Pompa'][] = DB::table('kartu_rumah_pompas')
                ->join('rumah_pompas', 'kartu_rumah_pompas.rumah_pompa_id', '=', 'rumah_pompas.id')
                ->where('rumah_pompas.unit_id', $unitId)
                ->whereYear('kartu_rumah_pompas.tgl_periksa', $date->year)
                ->whereMonth('kartu_rumah_pompas.tgl_periksa', $date->month)
                ->count();
        }

        // Recent pending approvals
        $pendingApprovals = KartuApar::with(['apar', 'user'])
            ->whereHas('apar', function($q) use ($unitId) {
                $q->where('unit_id', $unitId);
            })
            ->whereNull('approved_at')
            ->latest()
            ->take(10)
            ->get();

        return view('leader.dashboard', compact(
            'stats', 
            'pendingApprovals',
            'totalItems',
            'aparData',
            'apatData',
            'apabData',
            'fireAlarmData',
            'boxHydrantData',
            'rumahPompaData',
            'trendData'
        ));
    }
}

