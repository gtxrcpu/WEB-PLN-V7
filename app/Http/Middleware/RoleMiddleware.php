<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            // Clear intended URL to prevent wrong redirect after re-login
            session()->forget('url.intended');
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        if (!$user->hasRole($role)) {
            // Redirect to appropriate dashboard based on user's actual role
            if ($user->hasRole('superadmin')) {
                return redirect()->route('admin.dashboard')
                    ->with('warning', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            if ($user->hasRole('leader')) {
                return redirect()->route('leader.dashboard')
                    ->with('warning', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            if ($user->hasRole('inspector')) {
                return redirect()->route('inspector.dashboard')
                    ->with('warning', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            if ($user->hasRole('petugas')) {
                return redirect()->route('user.dashboard')
                    ->with('warning', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            
            // Fallback: redirect to user dashboard
            return redirect()->route('user.dashboard')
                ->with('warning', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
