<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyQRScanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Permettre aux admins d'accéder sans scan
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // Vérifier si l'utilisateur est authentifié
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Vérifier si le QR a été scanné via la session
        $qrScanned = $request->session()->get('qr_scanned');
        
        // Si pas de scan, rediriger vers le dashboard
        if (!$qrScanned) {
            return redirect()->route('client.dashboard')
                ->with('warning', 'Vous devez scanner le code QR avant d\'accéder au menu');
        }

        return $next($request);
    }
}
