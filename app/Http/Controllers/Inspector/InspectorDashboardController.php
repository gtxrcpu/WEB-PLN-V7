<?php

namespace App\Http\Controllers\Inspector;

use App\Http\Controllers\Controller;
use App\Models\Apar;
use App\Models\Apat;
use App\Models\Apab;
use App\Models\FireAlarm;
use App\Models\BoxHydrant;
use App\Models\RumahPompa;
use App\Models\P3k;
use Illuminate\Http\Request;

class InspectorDashboardController extends Controller
{
    public function index()
    {
        // Get all equipment data (not filtered by unit - Inspector can see all)
        $aparData = [
            'total' => Apar::count(),
            'baik' => Apar::where('status', 'baik')->count(),
            'isi_ulang' => Apar::where('status', 'isi ulang')->count(),
            'rusak' => Apar::where('status', 'rusak')->count(),
        ];

        $apatData = [
            'total' => Apat::count(),
            'baik' => Apat::where('status', 'baik')->count(),
            'rusak' => Apat::where('status', 'rusak')->count(),
        ];

        $apabData = [
            'total' => Apab::count(),
            'baik' => Apab::where('status', 'baik')->count(),
            'tidak_baik' => Apab::where('status', '!=', 'baik')->count(),
        ];

        $fireAlarmData = [
            'total' => FireAlarm::count(),
            'baik' => FireAlarm::where('status', 'baik')->count(),
            'rusak' => FireAlarm::where('status', 'rusak')->count(),
        ];

        $boxHydrantData = [
            'total' => BoxHydrant::count(),
            'baik' => BoxHydrant::where('status', 'baik')->count(),
            'rusak' => BoxHydrant::where('status', 'rusak')->count(),
        ];

        $rumahPompaData = [
            'total' => RumahPompa::count(),
            'baik' => RumahPompa::where('status', 'baik')->count(),
            'rusak' => RumahPompa::where('status', 'rusak')->count(),
        ];

        $totalItems = $aparData['total'] + $apatData['total'] + $apabData['total'] + 
                     $fireAlarmData['total'] + $boxHydrantData['total'] + $rumahPompaData['total'];
        
        $totalEquipment = $totalItems; // Alias for compatibility

        // Trend data (last 6 months)
        $trendData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            'datasets' => [
                'APAR' => [0, 0, 0, 0, 0, \App\Models\KartuApar::count()],
                'APAT' => [0, 0, 0, 0, 0, \App\Models\KartuApat::count()],
                'APAB' => [0, 0, 0, 0, 0, \App\Models\KartuApab::count()],
                'Fire Alarm' => [0, 0, 0, 0, 0, \App\Models\KartuFireAlarm::count()],
                'Box Hydrant' => [0, 0, 0, 0, 0, \App\Models\KartuBoxHydrant::count()],
                'Rumah Pompa' => [0, 0, 0, 0, 0, \App\Models\KartuRumahPompa::count()],
            ]
        ];

        return view('inspector.dashboard', compact(
            'aparData', 'apatData', 'apabData', 'fireAlarmData', 
            'boxHydrantData', 'rumahPompaData', 'totalItems', 'totalEquipment', 'trendData'
        ));
    }

    // APAR
    public function apar()
    {
        $apars = Apar::orderBy('serial_no')->get();
        return view('inspector.apar.index', compact('apars'));
    }

    public function aparRiwayat(Apar $apar)
    {
        $riwayatInspeksi = $apar->kartuApars()->orderBy('tgl_periksa', 'desc')->get();
        return view('inspector.apar.riwayat', compact('apar', 'riwayatInspeksi'));
    }

    // APAT
    public function apat()
    {
        $apats = Apat::orderBy('serial_no')->get();
        return view('inspector.apat.index', compact('apats'));
    }

    public function apatRiwayat(Apat $apat)
    {
        $riwayatInspeksi = $apat->kartuApats()->orderBy('tgl_periksa', 'desc')->get();
        return view('inspector.apat.riwayat', compact('apat', 'riwayatInspeksi'));
    }

    // P3K
    public function p3k()
    {
        $p3ks = P3k::orderBy('serial_no')->get();
        return view('inspector.p3k.index', compact('p3ks'));
    }

    public function p3kRiwayat(P3k $p3k)
    {
        $riwayatInspeksi = $p3k->kartuP3ks()->orderBy('tgl_periksa', 'desc')->get();
        return view('inspector.p3k.riwayat', compact('p3k', 'riwayatInspeksi'));
    }

    // APAB
    public function apab()
    {
        $apabs = Apab::orderBy('serial_no')->get();
        return view('inspector.apab.index', compact('apabs'));
    }

    public function apabRiwayat(Apab $apab)
    {
        $riwayatInspeksi = $apab->kartuInspeksi()->orderBy('tgl_periksa', 'desc')->get();
        return view('inspector.apab.riwayat', compact('apab', 'riwayatInspeksi'));
    }

    // Fire Alarm
    public function fireAlarm()
    {
        $fireAlarms = FireAlarm::orderBy('serial_no')->get();
        return view('inspector.fire-alarm.index', compact('fireAlarms'));
    }

    public function fireAlarmRiwayat(FireAlarm $fireAlarm)
    {
        $riwayatInspeksi = $fireAlarm->kartuInspeksi()->orderBy('tgl_periksa', 'desc')->get();
        return view('inspector.fire-alarm.riwayat', compact('fireAlarm', 'riwayatInspeksi'));
    }

    // Box Hydrant
    public function boxHydrant()
    {
        $boxHydrants = BoxHydrant::orderBy('serial_no')->get();
        return view('inspector.box-hydrant.index', compact('boxHydrants'));
    }

    public function boxHydrantRiwayat(BoxHydrant $boxHydrant)
    {
        $riwayatInspeksi = $boxHydrant->kartuInspeksi()->orderBy('tgl_periksa', 'desc')->get();
        return view('inspector.box-hydrant.riwayat', compact('boxHydrant', 'riwayatInspeksi'));
    }

    // Rumah Pompa
    public function rumahPompa()
    {
        $rumahPompas = RumahPompa::orderBy('serial_no')->get();
        return view('inspector.rumah-pompa.index', compact('rumahPompas'));
    }

    public function rumahPompaRiwayat(RumahPompa $rumahPompa)
    {
        $riwayatInspeksi = $rumahPompa->kartuInspeksi()->orderBy('tgl_periksa', 'desc')->get();
        return view('inspector.rumah-pompa.riwayat', compact('rumahPompa', 'riwayatInspeksi'));
    }
}
