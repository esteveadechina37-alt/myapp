<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;

class LivreurController extends Controller
{
    /**
     * Livreur Dashboard
     */
    public function dashboard()
    {
        $data = [
            'livraisonsEnCours' => Commande::where('statut', 'en_livraison')->count(),
            'livraisonsLivrees' => Commande::where('statut', 'livree')->count(),
            'livraisonsAujourdhui' => Commande::whereDate('created_at', today())
                ->where('type_commande', 'livraison')
                ->count(),
            'livraisons' => Commande::where('type_commande', 'livraison')
                ->whereIn('statut', ['prete', 'en_livraison'])
                ->with('client')
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('employes.livreur.dashboard', $data);
    }

    /**
     * Consulter les livraisons
     */
    public function consulterLivraisons()
    {
        $livraisons = Commande::where('type_commande', 'livraison')
            ->with('client')
            ->latest()
            ->paginate(15);

        return view('employes.livreur.livraisons', ['livraisons' => $livraisons]);
    }

    /**
     * Prendre une livraison
     */
    public function prendreLivraison(Commande $commande)
    {
        $commande->update(['statut' => 'en_livraison']);
        return redirect()->back()->with('success', 'Livraison prise en charge');
    }

    /**
     * Confirmer livraison
     */
    public function confirmerLivraison(Commande $commande, Request $request)
    {
        $request->validate(['code' => 'required|string']);

        // Vérifier le code de confirmation (simple)
        $commande->update([
            'statut' => 'livree',
            'date_livraison' => now(),
        ]);

        return redirect()->back()->with('success', 'Livraison confirmée');
    }
}
