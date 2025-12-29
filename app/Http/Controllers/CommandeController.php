<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Plat;
use App\Models\TableRestaurant;
use App\Models\Client;
use App\Models\Facture;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Contrôleur Commande
 * Gère la création, modification et suivi des commandes
 */
class CommandeController extends Controller
{
    /**
     * Créer une nouvelle commande
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_commande' => 'required|in:sur_place,a_emporter,livraison',
            'table_id' => 'nullable|exists:tables_restaurant,id',
            'client_id' => 'nullable|exists:clients,id',
            'plats' => 'required|array',
            'plats.*.plat_id' => 'required|exists:plats,id',
            'plats.*.quantite' => 'required|integer|min:1',
            'plats.*.commentaire' => 'nullable|string',
            'commentaires' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Créer la commande
            $commande = Commande::create([
                'numero' => $this->genererNumeroCommande(),
                'client_id' => $validated['client_id'] ?? null,
                'table_id' => $validated['table_id'] ?? null,
                'utilisateur_id' => Auth::user()->id ?? 1,
                'type_commande' => $validated['type_commande'],
                'statut' => 'en_attente',
                'heure_commande' => now(),
                'commentaires' => $validated['commentaires'] ?? null,
            ]);

            // Ajouter les lignes de commande
            $montantTotalHT = 0;
            $montantTotalTVA = 0;

            foreach ($validated['plats'] as $platData) {
                $plat = Plat::find($platData['plat_id']);
                
                $montantHT = $plat->prix * $platData['quantite'];
                $tvaRate = 20; // Taux TVA par défaut
                $montantTVA = $montantHT * ($tvaRate / 100);

                LigneCommande::create([
                    'commande_id' => $commande->id,
                    'plat_id' => $plat->id,
                    'quantite' => $platData['quantite'],
                    'prix_unitaire_ht' => $plat->prix,
                    'taux_tva' => $tvaRate,
                    'statut' => 'en_attente',
                    'commentaire' => $platData['commentaire'] ?? null,
                ]);

                $montantTotalHT += $montantHT;
                $montantTotalTVA += $montantTVA;
            }

            // Mettre à jour les montants
            $commande->update([
                'montant_total_ht' => $montantTotalHT,
                'montant_tva' => $montantTotalTVA,
                'montant_total_ttc' => $montantTotalHT + $montantTotalTVA,
            ]);

            // Marquer la table comme occupée
            if ($commande->table_id) {
                TableRestaurant::find($commande->table_id)->update(['est_disponible' => false]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Commande créée avec succès',
                'commande_id' => $commande->id,
                'numero_commande' => $commande->numero,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la commande: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Afficher les détails d'une commande
     */
    public function show($id)
    {
        $commande = Commande::with(['lignesCommandes.plat', 'table', 'client', 'utilisateur', 'facture'])
            ->findOrFail($id);

        return view('commandes.show', ['commande' => $commande]);
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,confirmee,en_preparation,prete,servie,payee,livree,annulee',
        ]);

        $commande = Commande::findOrFail($id);
        
        $commande->update([
            'statut' => $request->statut,
        ]);

        // Mettre à jour les timestamps selon le statut
        if ($request->statut === 'en_preparation') {
            $commande->update(['heure_remise_cuisine' => now()]);
        } elseif ($request->statut === 'prete') {
            $commande->update(['heure_prete' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Statut de la commande mis à jour',
        ]);
    }

    /**
     * Générer un numéro de commande unique
     */
    private function genererNumeroCommande()
    {
        $date = now()->format('YmdHis');
        $random = random_int(100, 999);
        return 'CMD-' . $date . '-' . $random;
    }

    /**
     * Afficher les commandes en cours
     */
    public function listCommandes()
    {
        $commandes = Commande::where('statut', '!=', 'archivee')
            ->orderBy('heure_commande', 'desc')
            ->paginate(20);

        return view('commandes.list', ['commandes' => $commandes]);
    }

    /**
     * Annuler une commande
     */
    public function cancel($id)
    {
        $commande = Commande::findOrFail($id);
        
        $commande->update(['statut' => 'annulee']);
        
        // Libérer la table
        if ($commande->table_id) {
            TableRestaurant::find($commande->table_id)->update(['est_disponible' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Commande annulée',
        ]);
    }
}
