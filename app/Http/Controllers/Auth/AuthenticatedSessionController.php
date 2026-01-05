<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login (override Breeze).
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login:
     * - Validasi kredensial sederhana
     * - Auth::attempt dengan opsi remember
     * - Gagal -> toast via session('auth_error') + keep old('email')
     * - Berhasil -> redirect by role (Admin / Inspector / User)
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = (bool) $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->with('auth_error', 'Email atau password salah. Silakan cek lagi (pastikan CapsLock mati).')
                ->withInput($request->only('email', 'remember'));
        }

        // Regenerate session to prevent fixation attacks
        $request->session()->regenerate();
        
        // Clear any old session data that might interfere
        $request->session()->forget('url.intended');
        $request->session()->forget('_previous');

        // Get user (roles already eager loaded via User model $with property)
        $user = $request->user();

        // Redirect by role (spatie/permission) - optimized with cached roles
        if ($user && method_exists($user, 'hasRole')) {
            // Get first role name directly from loaded relationship (no DB query)
            $roleName = $user->roles->first()?->name;
            
            return match($roleName) {
                'superadmin' => redirect()->route('admin.dashboard'),
                'leader' => redirect()->route('leader.dashboard'),
                'inspector' => redirect()->route('inspector.dashboard'),
                'petugas' => redirect()->route('user.dashboard'),
                default => redirect()->route('user.dashboard'),
            };
        }

        // Fallback jika belum ada role: arahkan ke route dashboard generik
        return redirect()->route('dashboard');
    }

    /**
     * Logout dan invalidate session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // Flush all session data before invalidating
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Force redirect to login with fresh session
        return redirect()->route('login')->with('status', 'You have been logged out successfully.');
    }
}
