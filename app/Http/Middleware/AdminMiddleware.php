<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Vérifier que l'utilisateur est admin
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/')->with('error', 'Vous devez être connecté.');
        }

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé. Vous devez être administrateur.');
        }

        return $next($request);
    }
}
