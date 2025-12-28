<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KartuApar;
use App\Models\Signature;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        // Get all pending approvals (kartu yang belum di-approve)
        $pendingApprovals = KartuApar::with(['apar', 'user', 'approver'])
            ->whereNull('approved_at')
            ->latest()
            ->paginate(20);

        return view('admin.approvals.index', compact('pendingApprovals'));
    }

    public function show($id)
    {
        $kartu = KartuApar::with(['apar', 'user', 'approver'])->findOrFail($id);
        $signatures = Signature::where('is_active', true)->get();

        return view('admin.approvals.show', compact('kartu', 'signatures'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'signature_id' => 'required|exists:signatures,id',
        ]);

        $kartu = KartuApar::findOrFail($id);
        
        $kartu->update([
            'signature_id' => $request->signature_id,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('admin.approvals.index')
            ->with('success', 'Kartu kendali berhasil di-approve');
    }

    public function reject($id)
    {
        $kartu = KartuApar::findOrFail($id);
        
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
