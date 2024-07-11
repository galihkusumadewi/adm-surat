<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckJabatan
{
    public function handle(Request $request, Closure $next, ...$jabatans)
    {
        $user = Auth::user();

        if (!$user) {
            Log::info('Redirecting to login: No user authenticated');
            return redirect('/login');
        }

        if (!$user->id_jabatan) {
            Log::warning('User without jabatan tried to access: ' . $user->id);
            abort(403, 'Unauthorized action.');
        }

        $allowed = false;

        foreach ($jabatans as $jabatanId) {
            if ($user->id_jabatan == $jabatanId) {
                $allowed = true;
                $request->attributes->set('role', $jabatanId);
                break;
            }
        }

        if (!$allowed) {
            Log::warning('User with jabatan ' . $user->id_jabatan . ' tried to access: ' . $request->url());
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
