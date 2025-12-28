<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $unitId = $user->unit_id;

        // Hanya tampilkan user di unit leader
        $users = User::where('unit_id', $unitId)
            ->with('roles')
            ->paginate(20);

        return view('leader.users.index', compact('users'));
    }

    public function create()
    {
        return view('leader.users.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'position' => 'required|in:petugas,leader',
        ]);

        $newUser = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'unit_id' => $user->unit_id, // Otomatis set ke unit leader
            'position' => $validated['position'],
        ]);

        // Assign role
        $role = $validated['position'] === 'leader' ? 'leader' : 'petugas';
        $newUser->assignRole($role);

        return redirect()
            ->route('leader.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        // Pastikan user ini dari unit leader
        if ($user->unit_id !== auth()->user()->unit_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('leader.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Pastikan user ini dari unit leader
        if ($user->unit_id !== auth()->user()->unit_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'position' => 'required|in:petugas,leader',
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'position' => $validated['position'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        // Update role
        $user->syncRoles([$validated['position'] === 'leader' ? 'leader' : 'petugas']);

        return redirect()
            ->route('leader.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        // Pastikan user ini dari unit leader
        if ($user->unit_id !== auth()->user()->unit_id) {
            abort(403, 'Unauthorized action.');
        }

        // Tidak bisa hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        $user->delete();

        return redirect()
            ->route('leader.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
