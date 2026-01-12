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

        // Check if user has unit_id (for filtering by unit)
        $unitId = $user->unit_id ?? null;

        // Stats untuk leader (with or without unit filtering)
        if ($unitId) {
            $stats = [
                'total_equipment' => Apar::where('unit_id', $unitId)->count(),
                'pending_approvals' => KartuApar::whereHas('apar', function ($q) use ($unitId) {
                    $q->where('unit_id', $unitId);
                })->whereNull('approved_at')->count(),
                'approved_this_month' => KartuApar::whereHas('apar', function ($q) use ($unitId) {
                    $q->where('unit_id', $unitId);
                })->whereNotNull('approved_at')
                    ->whereMonth('approved_at', now()->month)
                    ->count(),
                'total_users' => \App\Models\User::where('unit_id', $unitId)->count(),
            ];
        } else {
            $stats = [
                'total_equipment' => Apar::count(),
                'pending_approvals' => KartuApar::whereNull('approved_at')->count(),
                'approved_this_month' => KartuApar::whereNotNull('approved_at')
                    ->whereMonth('approved_at', now()->month)
                    ->count(),
                'total_users' => \App\Models\User::count(),
            ];
        }

        // Equipment data by module (with unit filtering if applicable)
        if ($unitId) {
            $totalApar = Apar::where('unit_id', $unitId)->count();
            $totalApat = Apat::where('unit_id', $unitId)->count();
            $totalApab = Apab::where('unit_id', $unitId)->count();
            $totalFireAlarm = FireAlarm::where('unit_id', $unitId)->count();
            $totalBoxHydrant = BoxHydrant::where('unit_id', $unitId)->count();
            $totalRumahPompa = RumahPompa::where('unit_id', $unitId)->count();

            // Status breakdowns
            $aparData = [
                'baik' => Apar::where('unit_id', $unitId)->where('status', 'baik')->count(),
                'isi_ulang' => Apar::where('unit_id', $unitId)->where('status', 'isi ulang')->count(),
                'rusak' => Apar::where('unit_id', $unitId)->where('status', 'rusak')->count(),
                'total' => $totalApar
            ];
            $apatData = [
                'baik' => Apat::where('unit_id', $unitId)->where('status', 'baik')->count(),
                'rusak' => Apat::where('unit_id', $unitId)->where('status', 'rusak')->count(),
                'total' => $totalApat
            ];
            $apabData = [
                'baik' => Apab::where('unit_id', $unitId)->where('status', 'baik')->count(),
                'tidak_baik' => Apab::where('unit_id', $unitId)->where('status', 'tidak baik')->count(),
                'total' => $totalApab
            ];
            $fireAlarmData = [
                'baik' => FireAlarm::where('unit_id', $unitId)->where('status', 'baik')->count(),
                'rusak' => FireAlarm::where('unit_id', $unitId)->where('status', 'rusak')->count(),
                'total' => $totalFireAlarm
            ];
            $boxHydrantData = [
                'baik' => BoxHydrant::where('unit_id', $unitId)->where('status', 'baik')->count(),
                'rusak' => BoxHydrant::where('unit_id', $unitId)->where('status', 'rusak')->count(),
                'total' => $totalBoxHydrant
            ];
            $rumahPompaData = [
                'baik' => RumahPompa::where('unit_id', $unitId)->where('status', 'baik')->count(),
                'rusak' => RumahPompa::where('unit_id', $unitId)->where('status', 'rusak')->count(),
                'total' => $totalRumahPompa
            ];
        } else {
            $totalApar = Apar::count();
            $totalApat = Apat::count();
            $totalApab = Apab::count();
            $totalFireAlarm = FireAlarm::count();
            $totalBoxHydrant = BoxHydrant::count();
            $totalRumahPompa = RumahPompa::count();

            // Status breakdowns (all units)
            $aparData = [
                'baik' => Apar::where('status', 'baik')->count(),
                'isi_ulang' => Apar::where('status', 'isi ulang')->count(),
                'rusak' => Apar::where('status', 'rusak')->count(),
                'total' => $totalApar
            ];
            $apatData = [
                'baik' => Apat::where('status', 'baik')->count(),
                'rusak' => Apat::where('status', 'rusak')->count(),
                'total' => $totalApat
            ];
            $apabData = [
                'baik' => Apab::where('status', 'baik')->count(),
                'tidak_baik' => Apab::where('status', 'tidak baik')->count(),
                'total' => $totalApab
            ];
            $fireAlarmData = [
                'baik' => FireAlarm::where('status', 'baik')->count(),
                'rusak' => FireAlarm::where('status', 'rusak')->count(),
                'total' => $totalFireAlarm
            ];
            $boxHydrantData = [
                'baik' => BoxHydrant::where('status', 'baik')->count(),
                'rusak' => BoxHydrant::where('status', 'rusak')->count(),
                'total' => $totalBoxHydrant
            ];
            $rumahPompaData = [
                'baik' => RumahPompa::where('status', 'baik')->count(),
                'rusak' => RumahPompa::where('status', 'rusak')->count(),
                'total' => $totalRumahPompa
            ];
        }

        $totalItems = $totalApar + $totalApat + $totalApab + $totalFireAlarm + $totalBoxHydrant + $totalRumahPompa;

        // Tren inspeksi 6 bulan terakhir
        $trendData = $this->getInspectionTrend($unitId);

        // Recent pending approvals
        if ($unitId) {
            $pendingApprovals = KartuApar::with(['apar', 'user'])
                ->whereHas('apar', function ($q) use ($unitId) {
                    $q->where('unit_id', $unitId);
                })
                ->whereNull('approved_at')
                ->latest()
                ->take(10)
                ->get();
        } else {
            $pendingApprovals = KartuApar::with(['apar', 'user'])
                ->whereNull('approved_at')
                ->latest()
                ->take(10)
                ->get();
        }

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

    /**
     * Get inspection trend data for the last 6 months
     */
    private function getInspectionTrend($unitId = null)
    {
        $months = [];
        $data = [
            'APAR' => [],
            'APAT' => [],
            'APAB' => [],
            'Fire Alarm' => [],
            'Box Hydrant' => [],
            'Rumah Pompa' => []
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $year = $date->year;
            $month = $date->month;

            // Count inspections for each module
            if ($unitId) {
                // With unit filtering
                try {
                    $data['APAR'][] = DB::table('kartu_apars')
                        ->join('apars', 'kartu_apars.apar_id', '=', 'apars.id')
                        ->where('apars.unit_id', $unitId)
                        ->whereYear('kartu_apars.tgl_periksa', $year)
                        ->whereMonth('kartu_apars.tgl_periksa', $month)
                        ->count();
                } catch (\Exception $e) {
                    $data['APAR'][] = 0;
                }

                try {
                    $data['APAT'][] = DB::table('kartu_apats')
                        ->join('apats', 'kartu_apats.apat_id', '=', 'apats.id')
                        ->where('apats.unit_id', $unitId)
                        ->whereYear('kartu_apats.tgl_periksa', $year)
                        ->whereMonth('kartu_apats.tgl_periksa', $month)
                        ->count();
                } catch (\Exception $e) {
                    $data['APAT'][] = 0;
                }

                try {
                    $data['APAB'][] = DB::table('kartu_apabs')
                        ->join('apabs', 'kartu_apabs.apab_id', '=', 'apabs.id')
                        ->where('apabs.unit_id', $unitId)
                        ->whereYear('kartu_apabs.tgl_periksa', $year)
                        ->whereMonth('kartu_apabs.tgl_periksa', $month)
                        ->count();
                } catch (\Exception $e) {
                    $data['APAB'][] = 0;
                }

                try {
                    $data['Fire Alarm'][] = DB::table('kartu_fire_alarms')
                        ->join('fire_alarms', 'kartu_fire_alarms.fire_alarm_id', '=', 'fire_alarms.id')
                        ->where('fire_alarms.unit_id', $unitId)
                        ->whereYear('kartu_fire_alarms.tgl_periksa', $year)
                        ->whereMonth('kartu_fire_alarms.tgl_periksa', $month)
                        ->count();
                } catch (\Exception $e) {
                    $data['Fire Alarm'][] = 0;
                }

                try {
                    $data['Box Hydrant'][] = DB::table('kartu_box_hydrants')
                        ->join('box_hydrants', 'kartu_box_hydrants.box_hydrant_id', '=', 'box_hydrants.id')
                        ->where('box_hydrants.unit_id', $unitId)
                        ->whereYear('kartu_box_hydrants.tgl_periksa', $year)
                        ->whereMonth('kartu_box_hydrants.tgl_periksa', $month)
                        ->count();
                } catch (\Exception $e) {
                    $data['Box Hydrant'][] = 0;
                }

                try {
                    $data['Rumah Pompa'][] = DB::table('kartu_rumah_pompas')
                        ->join('rumah_pompas', 'kartu_rumah_pompas.rumah_pompa_id', '=', 'rumah_pompas.id')
                        ->where('rumah_pompas.unit_id', $unitId)
                        ->whereYear('kartu_rumah_pompas.tgl_periksa', $year)
                        ->whereMonth('kartu_rumah_pompas.tgl_periksa', $month)
                        ->count();
                } catch (\Exception $e) {
                    $data['Rumah Pompa'][] = 0;
                }
            } else {
                // Without unit filtering (all units)
                try {
                    $data['APAR'][] = DB::table('kartu_apars')
                        ->whereYear('tgl_periksa', $year)
                        ->whereMonth('tgl_periksa', $month)
                        ->count();
                } catch (\Exception $e) {
                    $data['APAR'][] = 0;
                }

                try {
                    $data['APAT'][] = DB::table('kartu_apats')
                        ->whereYear('tgl_periksa', $year)
                        ->whereMonth('tgl_periksa', $month)
                        ->count();
                } catch (\Exception $e) {
                    $data['APAT'][] = 0;
                }

                try {
                    $data['APAB'][] = DB::table('kartu_apabs')
                        ->whereYear('tgl_periksa', $year)
                        ->whereMonth('tgl_periksa', $month)
                        ->count();
                } catch (\Exception $e) {
                    $data['APAB'][] = 0;
                }

                try {
                    $data['Fire Alarm'][] = DB::table('kartu_fire_alarms')
                        ->whereYear('tgl_periksa', $year)
                        ->whereMonth('tgl_periksa', $month)
                        ->count();
                } catch (\Exception $e) {
                    $data['Fire Alarm'][] = 0;
                }

                try {
                    $data['Box Hydrant'][] = DB::table('kartu_box_hydrants')
                        ->whereYear('tgl_periksa', $year)
                        ->whereMonth('tgl_periksa', $month)
                        ->count();
                } catch (\Exception $e) {
                    $data['Box Hydrant'][] = 0;
                }

                try {
                    $data['Rumah Pompa'][] = DB::table('kartu_rumah_pompas')
                        ->whereYear('tgl_periksa', $year)
                        ->whereMonth('tgl_periksa', $month)
                        ->count();
                } catch (\Exception $e) {
                    $data['Rumah Pompa'][] = 0;
                }
            }
        }

        return [
            'labels' => $months,
            'datasets' => $data
        ];
    }
}

