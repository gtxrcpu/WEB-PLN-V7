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

        $request->session()->regenerate();
        
        // Clear intended URL to prevent redirect to wrong role's page after session timeout
        $request->session()->forget('url.intended');

        $user = $request->user();

        // Redirect by role (spatie/permission)
        if ($user && method_exists($user, 'hasRole')) {
            if ($user->hasRole('superadmin')) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->hasRole('leader')) {
                return redirect()->route('leader.dashboard');
            }
            if ($user->hasRole('inspector')) {
                return redirect()->route('inspector.dashboard');
            }
            if ($user->hasRole('petugas')) {
                return redirect()->route('user.dashboard');
            }
            // Default ke user dashboard
            return redirect()->route('user.dashboard');
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

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
