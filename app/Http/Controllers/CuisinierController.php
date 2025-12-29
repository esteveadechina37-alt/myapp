<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\LigneCommande;
use Illuminate\Http\Request;

class CuisinierController extends Controller
{
    /**
     * Cuisinier Dashboard
     */
    public function dashboard()
    {
        $data = [
            'commandesEnPreparation' => Commande::where('statut', 'en_preparation')->count(),
            'commandesPretes' => Commande::where('statut', 'prete')->count(),
            'commandesAujourdhui' => Commande::whereDate('created_at', today())->count(),
            'commandesEnCours' => Commande::where('statut', 'en_preparation')->with('details')->latest()->take(10)->get(),
        ];

        return view('employes.cuisinier.dashboard', $data);
    }

    /**
     * Consulter les commandes à préparer
     */
    public function consulterCommandes()
    {
        $commandes = Commande::where('statut', 'en_preparation')
            ->with('details', 'user')
            ->latest()
            ->paginate(15);

        return view('employes.cuisinier.commandes', ['commandes' => $commandes]);
    }

    /**
     * Marquer commande comme prête
     */
    public function marquerPrete(Commande $commande)
    {
        $commande->update(['statut' => 'prete']);
        return redirect()->back()->with('success', 'Commande marquée comme prête');
    }

    /**
     * Mettre à jour statut détail commande (plat)
     */
    public function updateDetailStatut(LigneCommande $detail, Request $request)
    {
        $request->validate(['statut' => 'required|in:en_preparation,pret,livre']);
        
        $detail->update(['statut' => $request->statut]);
        
        // Vérifier si tous les plats sont prêts
        $commande = $detail->commande;
        $allReady = $commande->details->every(fn($d) => $d->statut === 'pret');
        
        if ($allReady) {
            $commande->update(['statut' => 'prete']);
        }

        return redirect()->back()->with('success', 'Statut mis à jour');
    }
}
