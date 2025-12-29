<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KartuApar;
use App\Models\KartuApat;
use App\Models\KartuApab;
use App\Models\KartuFireAlarm;
use App\Models\KartuBoxHydrant;
use App\Models\KartuRumahPompa;
use App\Models\KartuP3k;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ApprovalController extends Controller
{
    public function index()
    {
        // Get all pending approvals from all modules
        $pendingApprovals = collect();
        
        // APAR
        $aparKartu = KartuApar::with(['apar', 'user', 'approver'])
            ->whereNull('approved_at')
            ->get()
            ->map(function ($kartu) {
                $kartu->module = 'APAR';
                $kartu->equipment_name = $kartu->apar->serial_no ?? '-';
                $kartu->route_show = route('admin.approvals.show', ['id' => $kartu->id, 'type' => 'apar']);
                return $kartu;
            });
        
        // APAT
        $apatKartu = KartuApat::with(['apat', 'user', 'approver'])
            ->whereNull('approved_at')
            ->get()
            ->map(function ($kartu) {
                $kartu->module = 'APAT';
                $kartu->equipment_name = $kartu->apat->barcode ?? $kartu->apat->serial_no ?? '-';
                $kartu->route_show = route('admin.approvals.show', ['id' => $kartu->id, 'type' => 'apat']);
                return $kartu;
            });
        
        // APAB
        $apabKartu = KartuApab::with(['apab', 'user', 'approver'])
            ->whereNull('approved_at')
            ->get()
            ->map(function ($kartu) {
                $kartu->module = 'APAB';
                $kartu->equipment_name = $kartu->apab->barcode ?? $kartu->apab->serial_no ?? '-';
                $kartu->route_show = route('admin.approvals.show', ['id' => $kartu->id, 'type' => 'apab']);
                return $kartu;
            });
        
        // Fire Alarm
        $fireAlarmKartu = KartuFireAlarm::with(['fireAlarm', 'user', 'approver'])
            ->whereNull('approved_at')
            ->get()
            ->map(function ($kartu) {
                $kartu->module = 'Fire Alarm';
                $kartu->equipment_name = $kartu->fireAlarm->barcode ?? $kartu->fireAlarm->serial_no ?? '-';
                $kartu->route_show = route('admin.approvals.show', ['id' => $kartu->id, 'type' => 'fire-alarm']);
                return $kartu;
            });
        
        // Box Hydrant
        $boxHydrantKartu = KartuBoxHydrant::with(['boxHydrant', 'user', 'approver'])
            ->whereNull('approved_at')
            ->get()
            ->map(function ($kartu) {
                $kartu->module = 'Box Hydrant';
                $kartu->equipment_name = $kartu->boxHydrant->barcode ?? $kartu->boxHydrant->serial_no ?? '-';
                $kartu->route_show = route('admin.approvals.show', ['id' => $kartu->id, 'type' => 'box-hydrant']);
                return $kartu;
            });
        
        // Rumah Pompa
        $rumahPompaKartu = KartuRumahPompa::with(['rumahPompa', 'user', 'approver'])
            ->whereNull('approved_at')
            ->get()
            ->map(function ($kartu) {
                $kartu->module = 'Rumah Pompa';
                $kartu->equipment_name = $kartu->rumahPompa->barcode ?? $kartu->rumahPompa->serial_no ?? '-';
                $kartu->route_show = route('admin.approvals.show', ['id' => $kartu->id, 'type' => 'rumah-pompa']);
                return $kartu;
            });
        
        // P3K
        $p3kKartu = KartuP3k::with(['p3k', 'user', 'approver'])
            ->whereNull('approved_at')
            ->get()
            ->map(function ($kartu) {
                $kartu->module = 'P3K';
                $kartu->equipment_name = $kartu->p3k->barcode ?? $kartu->p3k->serial_no ?? '-';
                $kartu->route_show = route('admin.approvals.show', ['id' => $kartu->id, 'type' => 'p3k']);
                return $kartu;
            });
        
        // Merge all and sort by created_at
        $pendingApprovals = $aparKartu
            ->concat($apatKartu)
            ->concat($apabKartu)
            ->concat($fireAlarmKartu)
            ->concat($boxHydrantKartu)
            ->concat($rumahPompaKartu)
            ->concat($p3kKartu)
            ->sortByDesc('created_at');

        return view('admin.approvals.index', compact('pendingApprovals'));
    }

    public function show(Request $request, $id)
    {
        $type = $request->query('type', 'apar');
        
        $kartu = match($type) {
            'apar' => KartuApar::with(['apar', 'user', 'approver'])->findOrFail($id),
            'apat' => KartuApat::with(['apat', 'user', 'approver'])->findOrFail($id),
            'apab' => KartuApab::with(['apab', 'user', 'approver'])->findOrFail($id),
            'fire-alarm' => KartuFireAlarm::with(['fireAlarm', 'user', 'approver'])->findOrFail($id),
            'box-hydrant' => KartuBoxHydrant::with(['boxHydrant', 'user', 'approver'])->findOrFail($id),
            'rumah-pompa' => KartuRumahPompa::with(['rumahPompa', 'user', 'approver'])->findOrFail($id),
            'p3k' => KartuP3k::with(['p3k', 'user', 'approver'])->findOrFail($id),
            default => KartuApar::with(['apar', 'user', 'approver'])->findOrFail($id),
        };
        
        $kartu->module_type = $type;
        $signatures = Signature::where('is_active', true)->get();

        return view('admin.approvals.show', compact('kartu', 'signatures', 'type'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'signature_id' => 'required|exists:signatures,id',
            'type' => 'required|string',
        ]);

        $type = $request->input('type');
        
        $kartu = match($type) {
            'apar' => KartuApar::findOrFail($id),
            'apat' => KartuApat::findOrFail($id),
            'apab' => KartuApab::findOrFail($id),
            'fire-alarm' => KartuFireAlarm::findOrFail($id),
            'box-hydrant' => KartuBoxHydrant::findOrFail($id),
            'rumah-pompa' => KartuRumahPompa::findOrFail($id),
            'p3k' => KartuP3k::findOrFail($id),
            default => KartuApar::findOrFail($id),
        };
        
        $kartu->update([
            'signature_id' => $request->signature_id,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('admin.approvals.index')
            ->with('success', 'Kartu kendali berhasil di-approve');
    }

    public function reject(Request $request, $id)
    {
        $type = $request->input('type', 'apar');
        
        $kartu = match($type) {
            'apar' => KartuApar::findOrFail($id),
            'apat' => KartuApat::findOrFail($id),
            'apab' => KartuApab::findOrFail($id),
            'fire-alarm' => KartuFireAlarm::findOrFail($id),
            'box-hydrant' => KartuBoxHydrant::findOrFail($id),
            'rumah-pompa' => KartuRumahPompa::findOrFail($id),
            'p3k' => KartuP3k::findOrFail($id),
            default => KartuApar::findOrFail($id),
        };
        
        $kartu->update([
            'signature_id' => null,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return redirect()
            ->route('admin.approvals.index')
            ->with('success', 'Approval dibatalkan');
    }
}
