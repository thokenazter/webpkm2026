<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->isApproved()) {
            // Store user info before logout
            $userName = Auth::user()->name;
            
            // Force logout using session
            $request->session()->flush();
            $request->session()->regenerate();
            
            return redirect()->route('approval.pending')
                ->with('message', "Halo {$userName}, akun Anda belum disetujui oleh administrator.");
        }

        return $next($request);
    }
}
