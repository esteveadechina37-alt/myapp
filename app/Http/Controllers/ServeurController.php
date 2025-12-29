<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Categorie;
use App\Models\LigneCommande;
use Illuminate\Http\Request;

class ServeurController extends Controller
{
    /**
     * Serveur Dashboard
     */
    public function dashboard()
    {
        $data = [
            'commandesEnCours' => Commande::where('statut', 'en_preparation')->count(),
            'commandesPretesAServir' => Commande::where('statut', 'prete')->count(),
            'totalCommandesAujourdhui' => Commande::whereDate('created_at', today())->count(),
            'commandesRecentes' => Commande::latest()->take(10)->get(),
        ];

        return view('employes.serveur.dashboard', $data);
    }

    /**
     * Prendre commande en salle
     */
    public function prendreCommande()
    {
        $tables = \App\Models\TableRestaurant::all();
        $plats = \App\Models\Plat::with('categorie')->get();
        $categories = Categorie::all();
        return view('employes.serveur.prendre-commande', [
            'tables' => $tables,
            'plats' => $plats,
            'categories' => $categories
        ]);
    }

    /**
     * Store commande en salle
     */
    public function storeCommande(Request $request)
    {
        try {
            $validated = $request->validate([
                'table_id' => 'required|exists:tables_restaurant,id',
                'plats' => 'required|array',
                'plats.*.plat_id' => 'required|exists:plats,id',
                'plats.*.quantite' => 'required|integer|min:1',
            ]);

            // Créer la commande
            $commande = Commande::create([
                'numero' => 'CMD-' . now()->format('YmdHis'),
                'table_id' => $validated['table_id'],
                'utilisateur_id' => auth()->id(),
                'type_commande' => 'sur_place',
                'statut' => 'en_preparation',
                'heure_commande' => now(),
                'est_payee' => false,
            ]);

            // Ajouter les lignes de commande
            $montantTotalHT = 0;
            $montantTotalTVA = 0;

            foreach ($validated['plats'] as $platData) {
                $plat = \App\Models\Plat::find($platData['plat_id']);
                
                $montantHT = $plat->prix * $platData['quantite'];
                $montantTVA = $montantHT * 0.2; // 20% TVA

                LigneCommande::create([
                    'commande_id' => $commande->id,
                    'plat_id' => $plat->id,
                    'quantite' => $platData['quantite'],
                    'prix_unitaire_ht' => $plat->prix,
                    'taux_tva' => 20,
                    'statut' => 'en_attente',
                ]);

                $montantTotalHT += $montantHT;
                $montantTotalTVA += $montantTVA;
            }

            // Mettre à jour les totaux de la commande
            $commande->update([
                'montant_total_ht' => $montantTotalHT,
                'montant_tva' => $montantTotalTVA,
                'montant_total_ttc' => $montantTotalHT + $montantTotalTVA,
            ]);

            // Marquer la table comme occupée
            \App\Models\TableRestaurant::find($validated['table_id'])->update(['est_disponible' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Commande créée avec succès',
                'commande_id' => $commande->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Consulter les commandes
     */
    public function consulterCommandes()
    {
        $commandes = Commande::with('client', 'details')->latest()->paginate(15);
        return view('employes.serveur.commandes', ['commandes' => $commandes]);
    }

    /**
     * Servir une commande
     */
    public function servir(Commande $commande)
    {
        $commande->update(['statut' => 'servie']);
        return redirect()->back()->with('success', 'Commande marquée comme servie');
    }

    /**
     * Attribuer une table
     */
    public function attribuerTable(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables_restaurant,id',
            'commande_id' => 'required|exists:commandes,id'
        ]);

        $commande = Commande::find($request->commande_id);
        $commande->update(['table_id' => $request->table_id]);

        return redirect()->back()->with('success', 'Table attribuée');
    }
}
