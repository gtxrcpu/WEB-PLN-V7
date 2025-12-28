<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\KartuApar;
use App\Models\Signature;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $unitId = $user->unit_id;

        // Get pending approvals untuk unit leader saja
        $pendingApprovals = KartuApar::with(['apar', 'user', 'approver'])
            ->whereHas('apar', function($q) use ($unitId) {
                $q->where('unit_id', $unitId);
            })
            ->whereNull('approved_at')
            ->latest()
            ->paginate(20);

        return view('leader.approvals.index', compact('pendingApprovals'));
    }

    public function show($id)
    {
        $user = auth()->user();
        $kartu = KartuApar::with(['apar', 'user', 'approver'])->findOrFail($id);
        
        // Pastikan kartu ini dari unit leader
        if ($kartu->apar->unit_id !== $user->unit_id) {
            abort(403, 'Unauthorized action.');
        }

        $signatures = Signature::where('is_active', true)->get();

        return view('leader.approvals.show', compact('kartu', 'signatures'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'signature_id' => 'required|exists:signatures,id',
        ]);

        $user = auth()->user();
        $kartu = KartuApar::findOrFail($id);
        
        // Pastikan kartu ini dari unit leader
        if ($kartu->apar->unit_id !== $user->unit_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $kartu->update([
            'signature_id' => $request->signature_id,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('leader.approvals.index')
            ->with('success', 'Kartu kendali berhasil di-approve');
    }

    public function reject($id)
    {
        $user = auth()->user();
        $kartu = KartuApar::findOrFail($id);
        
        // Pastikan kartu ini dari unit leader
        if ($kartu->apar->unit_id !== $user->unit_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $kartu->update([
            'signature_id' => null,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return redirect()
            ->route('leader.approvals.index')
            ->with('success', 'Approval dibatalkan');
    }
}
