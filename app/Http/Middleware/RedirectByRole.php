<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectByRole
{
    public function handle(Request $request, Closure $next)
    {
        $u = $request->user();

        if ($u) {
            if (method_exists($u, 'hasRole')) {
                if ($u->hasRole('superadmin')) {
                    return redirect()->route('admin.dashboard');
                }
                if ($u->hasRole('leader')) {
                    return redirect()->route('leader.dashboard');
                }
                if ($u->hasRole('inspector')) {
                    return redirect()->route('inspector.dashboard');
                }
                if ($u->hasRole('petugas')) {
                    return redirect()->route('user.dashboard');
                }
                return redirect()->route('user.dashboard');
            }
            // jika spatie belum aktif, fallback
            return redirect()->route('user.dashboard');
        }

        return $next($request);
    }
}
