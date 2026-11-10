<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    // Durasi timeout inaktivitas (dalam menit)
    protected int $timeoutMinutes = 30;

    public function handle(Request $request, Closure $next)
    {
        // 1. Cek apakah sudah login
        if (!Auth::guard('admin')->check()) {
            // Jika request AJAX, kembalikan response 401
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Cek inaktivitas
        $lastActivity = session('admin_last_activity');
        $timeout      = $this->timeoutMinutes * 60;

        if ($lastActivity !== null && (time() - $lastActivity) > $timeout) {
            // Paksa logout
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', "Sesi Anda berakhir karena tidak aktif selama {$this->timeoutMinutes} menit. Silakan login kembali.");
        }

        // 3. Perbarui waktu aktivitas terakhir
        session(['admin_last_activity' => time()]);

        return $next($request);
    }
}