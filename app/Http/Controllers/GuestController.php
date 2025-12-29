<?php

namespace App\Http\Controllers;

use App\Models\Apar;
use App\Models\Apat;
use App\Models\Apab;
use App\Models\FireAlarm;
use App\Models\BoxHydrant;
use App\Models\RumahPompa;
use App\Models\P3k;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        // Cache dashboard statistics for 5 minutes
        $cacheKey = 'guest_dashboard_stats';
        $cacheDuration = 300; // 5 minutes
        
        $stats = cache()->remember($cacheKey, $cacheDuration, function () {
            // Optimize: Use single query with groupBy for each model
            $aparStats = Apar::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');
            
            $apatStats = Apat::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');
            
            $apabStats = Apab::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');
            
            $fireAlarmStats = FireAlarm::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');
            
            $boxHydrantStats = BoxHydrant::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');
            
            $rumahPompaStats = RumahPompa::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');
            
            $p3kStats = P3k::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');
            
            return [
                'apar' => [
                    'total' => $aparStats->sum(),
                    'baik' => $aparStats->get('baik', 0),
                    'isi_ulang' => $aparStats->get('isi ulang', 0),
                    'rusak' => $aparStats->get('rusak', 0),
                ],
                'apat' => [
                    'total' => $apatStats->sum(),
                    'baik' => $apatStats->get('baik', 0),
                    'rusak' => $apatStats->get('rusak', 0),
                ],
                'apab' => [
                    'total' => $apabStats->sum(),
                    'baik' => $apabStats->get('baik', 0),
                    'tidak_baik' => $apabStats->sum() - $apabStats->get('baik', 0),
                ],
                'fireAlarm' => [
                    'total' => $fireAlarmStats->sum(),
                    'baik' => $fireAlarmStats->get('baik', 0),
                    'rusak' => $fireAlarmStats->get('rusak', 0),
                ],
                'boxHydrant' => [
                    'total' => $boxHydrantStats->sum(),
                    'baik' => $boxHydrantStats->get('baik', 0),
                    'rusak' => $boxHydrantStats->get('rusak', 0),
                ],
                'rumahPompa' => [
                    'total' => $rumahPompaStats->sum(),
                    'baik' => $rumahPompaStats->get('baik', 0),
                    'rusak' => $rumahPompaStats->get('rusak', 0),
                ],
                'p3k' => [
                    'total' => $p3kStats->sum(),
                    'baik' => $p3kStats->get('baik', 0),
                    'rusak' => $p3kStats->get('rusak', 0),
                ],
            ];
        });
        
        $aparData = $stats['apar'];
        $apatData = $stats['apat'];
        $apabData = $stats['apab'];
        $fireAlarmData = $stats['fireAlarm'];
        $boxHydrantData = $stats['boxHydrant'];
        $rumahPompaData = $stats['rumahPompa'];
        $p3kData = $stats['p3k'];

        $totalItems = $aparData['total'] + $apatData['total'] + $apabData['total'] + 
                     $fireAlarmData['total'] + $boxHydrantData['total'] + $rumahPompaData['total'] + $p3kData['total'];
        
        $totalEquipment = $totalItems;

        // Trend data (cached)
        $trendData = cache()->remember('guest_trend_data', 300, function () {
            return [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                'datasets' => [
                    'APAR' => [0, 0, 0, 0, 0, \App\Models\KartuApar::count()],
                    'APAT' => [0, 0, 0, 0, 0, \App\Models\KartuApat::count()],
                    'APAB' => [0, 0, 0, 0, 0, \App\Models\KartuApab::count()],
                    'Fire Alarm' => [0, 0, 0, 0, 0, \App\Models\KartuFireAlarm::count()],
                    'Box Hydrant' => [0, 0, 0, 0, 0, \App\Models\KartuBoxHydrant::count()],
                    'Rumah Pompa' => [0, 0, 0, 0, 0, \App\Models\KartuRumahPompa::count()],
                    'P3K' => [0, 0, 0, 0, 0, \App\Models\KartuP3k::count()],
                ]
            ];
        });

        return view('guest.dashboard', compact(
            'aparData', 'apatData', 'apabData', 'fireAlarmData', 
            'boxHydrantData', 'rumahPompaData', 'p3kData', 'totalItems', 'totalEquipment', 'trendData'
        ));
    }

    // APAR
    public function apar()
    {
        // Optimize: Select only needed columns and use eager loading
        $apars = Apar::select('id', 'serial_no', 'barcode', 'location_code', 'type', 'capacity', 'status', 'unit_id', 'qr_url')
            ->with(['unit:id,name', 'kartuApars:id,apar_id,tgl_periksa'])
            ->orderBy('serial_no')
            ->paginate(20);
        return view('guest.apar.index', compact('apars'));
    }

    public function aparRiwayat(Apar $apar)
    {
        // Optimize: Load only needed relationships and columns
        $apar->load(['unit:id,name']);
        
        // Get inspection history with optimized query
        $riwayatInspeksi = $apar->kartuApars()
            ->select('id', 'apar_id', 'tgl_periksa', 'petugas', 'kesimpulan')
            ->orderBy('tgl_periksa', 'desc')
            ->get()
            ->map(function ($kartu) {
                // Remove sensitive fields
                $kartu->makeHidden(['ttd_petugas', 'ttd_penyelia', 'approved_by', 'approved_at', 'rejected_by', 'rejected_at', 'rejection_reason']);
                return $kartu;
            });
            
        return view('guest.apar.riwayat', compact('apar', 'riwayatInspeksi'));
    }

    // APAT
    public function apat()
    {
        // Use eager loading for better performance
        $apats = Apat::with(['unit', 'kartuApats'])
            ->orderBy('serial_no')
            ->paginate(20);
        return view('guest.apat.index', compact('apats'));
    }

    public function apatRiwayat(Apat $apat)
    {
        // Load relationships for the equipment
        $apat->load(['unit', 'kartuApats']);
        
        // Get inspection history ordered by date
        // Filter sensitive data - exclude signatures and approval details
        $riwayatInspeksi = $apat->kartuApats()
            ->orderBy('tgl_periksa', 'desc')
            ->get()
            ->map(function ($kartu) {
                // Remove sensitive fields
                $kartu->makeHidden(['ttd_petugas', 'ttd_penyelia', 'approved_by', 'approved_at', 'rejected_by', 'rejected_at', 'rejection_reason']);
                return $kartu;
            });
            
        return view('guest.apat.riwayat', compact('apat', 'riwayatInspeksi'));
    }

    // P3K
    public function p3k()
    {
        // Use eager loading for better performance
        $p3ks = P3k::with(['unit', 'kartuP3ks'])
            ->orderBy('serial_no')
            ->paginate(20);
        return view('guest.p3k.index', compact('p3ks'));
    }

    public function p3kRiwayat(P3k $p3k)
    {
        // Load relationships for the equipment
        $p3k->load(['unit', 'kartuP3ks']);
        
        // Get inspection history ordered by date
        // Filter sensitive data - exclude signatures and approval details
        $riwayatInspeksi = $p3k->kartuP3ks()
            ->orderBy('tgl_periksa', 'desc')
            ->get()
            ->map(function ($kartu) {
                // Remove sensitive fields
                $kartu->makeHidden(['ttd_petugas', 'ttd_penyelia', 'approved_by', 'approved_at', 'rejected_by', 'rejected_at', 'rejection_reason']);
                return $kartu;
            });
            
        return view('guest.p3k.riwayat', compact('p3k', 'riwayatInspeksi'));
    }

    // APAB
    public function apab()
    {
        // Use eager loading for better performance
        $apabs = Apab::with(['unit', 'kartuApabs'])
            ->orderBy('serial_no')
            ->paginate(20);
        return view('guest.apab.index', compact('apabs'));
    }

    public function apabRiwayat(Apab $apab)
    {
        // Load relationships for the equipment
        $apab->load(['unit', 'kartuApabs']);
        
        // Get inspection history ordered by date
        // Filter sensitive data - exclude signatures and approval details
        $riwayatInspeksi = $apab->kartuApabs()
            ->orderBy('tgl_periksa', 'desc')
            ->get()
            ->map(function ($kartu) {
                // Remove sensitive fields
                $kartu->makeHidden(['ttd_petugas', 'ttd_penyelia', 'approved_by', 'approved_at', 'rejected_by', 'rejected_at', 'rejection_reason']);
                return $kartu;
            });
            
        return view('guest.apab.riwayat', compact('apab', 'riwayatInspeksi'));
    }

    // Fire Alarm
    public function fireAlarm()
    {
        // Use eager loading for better performance
        $fireAlarms = FireAlarm::with(['unit', 'kartuFireAlarms'])
            ->orderBy('serial_no')
            ->paginate(20);
        return view('guest.fire-alarm.index', compact('fireAlarms'));
    }

    public function fireAlarmRiwayat(FireAlarm $fireAlarm)
    {
        // Load relationships for the equipment
        $fireAlarm->load(['unit', 'kartuFireAlarms']);
        
        // Get inspection history ordered by date
        // Filter sensitive data - exclude signatures and approval details
        $riwayatInspeksi = $fireAlarm->kartuFireAlarms()
            ->orderBy('tgl_periksa', 'desc')
            ->get()
            ->map(function ($kartu) {
                // Remove sensitive fields
                $kartu->makeHidden(['ttd_petugas', 'ttd_penyelia', 'approved_by', 'approved_at', 'rejected_by', 'rejected_at', 'rejection_reason']);
                return $kartu;
            });
            
        return view('guest.fire-alarm.riwayat', compact('fireAlarm', 'riwayatInspeksi'));
    }

    // Box Hydrant
    public function boxHydrant()
    {
        // Use eager loading for better performance
        $boxHydrants = BoxHydrant::with(['unit', 'kartuBoxHydrants'])
            ->orderBy('serial_no')
            ->paginate(20);
        return view('guest.box-hydrant.index', compact('boxHydrants'));
    }

    public function boxHydrantRiwayat(BoxHydrant $boxHydrant)
    {
        // Load relationships for the equipment
        $boxHydrant->load(['unit', 'kartuBoxHydrants']);
        
        // Get inspection history ordered by date
        // Filter sensitive data - exclude signatures and approval details
        $riwayatInspeksi = $boxHydrant->kartuBoxHydrants()
            ->orderBy('tgl_periksa', 'desc')
            ->get()
            ->map(function ($kartu) {
                // Remove sensitive fields
                $kartu->makeHidden(['ttd_petugas', 'ttd_penyelia', 'approved_by', 'approved_at', 'rejected_by', 'rejected_at', 'rejection_reason']);
                return $kartu;
            });
            
        return view('guest.box-hydrant.riwayat', compact('boxHydrant', 'riwayatInspeksi'));
    }

    // Rumah Pompa
    public function rumahPompa()
    {
        // Use eager loading for better performance
        $rumahPompas = RumahPompa::with(['unit', 'kartuRumahPompas'])
            ->orderBy('serial_no')
            ->paginate(20);
        return view('guest.rumah-pompa.index', compact('rumahPompas'));
    }

    public function rumahPompaRiwayat(RumahPompa $rumahPompa)
    {
        // Load relationships for the equipment
        $rumahPompa->load(['unit', 'kartuRumahPompas']);
        
        // Get inspection history ordered by date
        // Filter sensitive data - exclude signatures and approval details
        $riwayatInspeksi = $rumahPompa->kartuRumahPompas()
            ->orderBy('tgl_periksa', 'desc')
            ->get()
            ->map(function ($kartu) {
                // Remove sensitive fields
                $kartu->makeHidden(['ttd_petugas', 'ttd_penyelia', 'approved_by', 'approved_at', 'rejected_by', 'rejected_at', 'rejection_reason']);
                return $kartu;
            });
            
        return view('guest.rumah-pompa.riwayat', compact('rumahPompa', 'riwayatInspeksi'));
    }

    // Laporan Keseluruhan
    public function report()
    {
        // Cache report data for 10 minutes
        $cacheKey = 'guest_report_data';
        $cacheDuration = 600; // 10 minutes
        
        $data = cache()->remember($cacheKey, $cacheDuration, function () {
            // Optimize: Select only needed columns
            $apars = Apar::select('id', 'serial_no', 'location_code', 'type', 'status', 'unit_id')
                ->with([
                    'unit:id,name',
                    'kartuApars' => function($q) {
                        $q->select('id', 'apar_id', 'tgl_periksa')
                          ->latest('tgl_periksa')
                          ->limit(1);
                    }
                ])
                ->orderBy('serial_no')
                ->get();

            $apats = Apat::select('id', 'serial_no', 'location_code', 'type', 'status', 'unit_id')
                ->with([
                    'unit:id,name',
                    'kartuApats' => function($q) {
                        $q->select('id', 'apat_id', 'tgl_periksa')
                          ->latest('tgl_periksa')
                          ->limit(1);
                    }
                ])
                ->orderBy('serial_no')
                ->get();

            $p3ks = P3k::select('id', 'serial_no', 'location_code', 'jenis', 'status', 'unit_id')
                ->with([
                    'unit:id,name',
                    'kartuP3ks' => function($q) {
                        $q->select('id', 'p3k_id', 'tgl_periksa')
                          ->latest('tgl_periksa')
                          ->limit(1);
                    }
                ])
                ->orderBy('serial_no')
                ->get();

            $apabs = Apab::select('id', 'serial_no', 'location_code', 'type', 'status', 'unit_id')
                ->with([
                    'unit:id,name',
                    'kartuApabs' => function($q) {
                        $q->select('id', 'apab_id', 'tgl_periksa')
                          ->latest('tgl_periksa')
                          ->limit(1);
                    }
                ])
                ->orderBy('serial_no')
                ->get();

            $fireAlarms = FireAlarm::select('id', 'serial_no', 'location_code', 'type', 'status', 'unit_id')
                ->with([
                    'unit:id,name',
                    'kartuFireAlarms' => function($q) {
                        $q->select('id', 'fire_alarm_id', 'tgl_periksa')
                          ->latest('tgl_periksa')
                          ->limit(1);
                    }
                ])
                ->orderBy('serial_no')
                ->get();

            $boxHydrants = BoxHydrant::select('id', 'serial_no', 'location_code', 'type', 'status', 'unit_id')
                ->with([
                    'unit:id,name',
                    'kartuBoxHydrants' => function($q) {
                        $q->select('id', 'box_hydrant_id', 'tgl_periksa')
                          ->latest('tgl_periksa')
                          ->limit(1);
                    }
                ])
                ->orderBy('serial_no')
                ->get();

            $rumahPompas = RumahPompa::select('id', 'serial_no', 'location_code', 'type', 'status', 'unit_id')
                ->with([
                    'unit:id,name',
                    'kartuRumahPompas' => function($q) {
                        $q->select('id', 'rumah_pompa_id', 'tgl_periksa')
                          ->latest('tgl_periksa')
                          ->limit(1);
                    }
                ])
                ->orderBy('serial_no')
                ->get();

            // Calculate summary
            $summary = [
                'total_equipment' => $apars->count() + $apats->count() + $p3ks->count() + 
                                    $apabs->count() + $fireAlarms->count() + $boxHydrants->count() + 
                                    $rumahPompas->count(),
                'total_baik' => $apars->where('status', 'baik')->count() + 
                               $apats->where('status', 'baik')->count() + 
                               $p3ks->where('status', 'baik')->count() + 
                               $apabs->where('status', 'baik')->count() + 
                               $fireAlarms->where('status', 'baik')->count() + 
                               $boxHydrants->where('status', 'baik')->count() + 
                               $rumahPompas->where('status', 'baik')->count(),
                'total_rusak' => $apars->where('status', 'rusak')->count() + 
                                $apats->where('status', 'rusak')->count() + 
                                $p3ks->where('status', 'rusak')->count() + 
                                $apabs->where('status', '!=', 'baik')->count() + 
                                $fireAlarms->where('status', 'rusak')->count() + 
                                $boxHydrants->where('status', 'rusak')->count() + 
                                $rumahPompas->where('status', 'rusak')->count(),
            ];

            return compact('apars', 'apats', 'p3ks', 'apabs', 'fireAlarms', 'boxHydrants', 'rumahPompas', 'summary');
        });

        return view('guest.report', $data);
    }

}
