<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Plat;
use App\Models\Client;
use App\Models\TableRestaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * OrderController
 * Gestion complète du processus de commande pour les clients
 */
class OrderController extends Controller
{
    /**
     * Afficher le panier
     */
    public function cart(Request $request)
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $items = [];

        // Récupérer les détails des plats du panier
        foreach ($cart as $platId => $quantity) {
            $plat = Plat::find($platId);
            if ($plat) {
                $items[] = [
                    'id' => $plat->id,
                    'nom' => $plat->nom,
                    'description' => $plat->description,
                    'prix' => $plat->prix,
                    'quantite' => $quantity,
                    'image' => $plat->image,
                    'sousTotal' => $plat->prix * $quantity
                ];
                $total += $plat->prix * $quantity;
            }
        }

        return view('client.cart', [
            'items' => $items,
            'total' => $total,
            'cartCount' => count($cart)
        ]);
    }

    /**
     * Ajouter un plat au panier
     */
    public function addToCart(Request $request, $platId)
    {
        $plat = Plat::findOrFail($platId);

        if (!$plat->est_disponible) {
            return response()->json([
                'success' => false,
                'message' => 'Ce plat n\'est pas disponible'
            ], 400);
        }

        $quantity = $request->input('quantity', 1);
        if ($quantity < 1) {
            $quantity = 1;
        }

        $cart = session()->get('cart', []);
        
        // Ajouter ou augmenter la quantité
        if (isset($cart[$platId])) {
            $cart[$platId] += $quantity;
        } else {
            $cart[$platId] = $quantity;
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => $plat->nom . ' ajouté au panier',
            'cartCount' => array_sum($cart),
            'total' => $this->calculateCartTotal($cart)
        ]);
    }

    /**
     * Mettre à jour la quantité d'un article
     */
    public function updateCart(Request $request, $platId)
    {
        $quantity = $request->input('quantity', 1);
        $cart = session()->get('cart', []);

        if ($quantity <= 0) {
            // Supprimer l'article
            unset($cart[$platId]);
        } else {
            $cart[$platId] = $quantity;
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Panier mis à jour',
            'cartCount' => array_sum($cart),
            'total' => $this->calculateCartTotal($cart)
        ]);
    }

    /**
     * Supprimer un article du panier
     */
    public function removeFromCart($platId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$platId]);
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Article supprimé du panier',
            'cartCount' => array_sum($cart),
            'total' => $this->calculateCartTotal($cart)
        ]);
    }

    /**
     * Vider le panier
     */
    public function clearCart()
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Panier vidé'
        ]);
    }

    /**
     * Afficher le formulaire de commande
     */
    public function checkoutForm(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('menu.client')->with('error', 'Votre panier est vide');
        }

        // Récupérer les plats du panier
        $items = [];
        $total = 0;
        foreach ($cart as $platId => $quantity) {
            $plat = Plat::find($platId);
            if ($plat) {
                $items[] = [
                    'id' => $plat->id,
                    'nom' => $plat->nom,
                    'prix' => $plat->prix,
                    'quantite' => $quantity,
                    'sousTotal' => $plat->prix * $quantity
                ];
                $total += $plat->prix * $quantity;
            }
        }

        // Récupérer les tables disponibles
        $tables = TableRestaurant::where('est_disponible', true)->get();

        // Calcul de la TVA (19.6% par défaut)
        $tva = $total * 0.196;
        $totalTTC = $total + $tva;

        return view('client.checkout', [
            'items' => $items,
            'sousTotal' => $total,
            'tva' => $tva,
            'total' => $totalTTC,
            'tables' => $tables,
            'user' => Auth::user()
        ]);
    }

    /**
     * Créer une nouvelle commande
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'type_commande' => 'required|in:sur_place,a_emporter,livraison',
            'table_id' => 'nullable|integer|exists:tables_restaurant,id',
            'commentaires' => 'nullable|string|max:500',
            'adresse_livraison' => 'nullable|string|max:500'
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Votre panier est vide');
        }

        // Validation type_commande vs table
        if ($validated['type_commande'] === 'sur_place' && !$validated['table_id']) {
            return back()->withErrors(['table_id' => 'Veuillez sélectionner une table']);
        }

        try {
            // Récupérer l'utilisateur authentifié
            $user = Auth::user();
            
            // Créer ou récupérer le client
            $client = Client::where('user_id', $user->id)->first();
            if (!$client) {
                $client = Client::create([
                    'user_id' => $user->id,
                    'nom' => $user->name,
                    'email' => $user->email,
                    'telephone' => $user->phone ?? null
                ]);
            }

            // Générer le numéro de commande unique
            $numero = $this->generateOrderNumber();

            // Calculer les montants
            $montantHT = 0;
            foreach ($cart as $platId => $quantity) {
                $plat = Plat::find($platId);
                if ($plat) {
                    $montantHT += $plat->prix * $quantity;
                }
            }

            $montantTVA = $montantHT * 0.196; // TVA 19.6%
            $montantTTC = $montantHT + $montantTVA;

            // Créer la commande
            $commande = Commande::create([
                'numero' => $numero,
                'client_id' => $client->id,
                'table_id' => $validated['type_commande'] === 'sur_place' ? $validated['table_id'] : null,
                'type_commande' => $validated['type_commande'],
                'montant_total_ht' => $montantHT,
                'montant_tva' => $montantTVA,
                'montant_total_ttc' => $montantTTC,
                'statut' => 'enregistree',
                'heure_commande' => Carbon::now(),
                'est_payee' => false,
                'commentaires' => $validated['commentaires'] ?? null
            ]);

            // Créer les lignes de commande
            foreach ($cart as $platId => $quantity) {
                $plat = Plat::find($platId);
                if ($plat) {
                    LigneCommande::create([
                        'commande_id' => $commande->id,
                        'plat_id' => $platId,
                        'quantite' => $quantity,
                        'prix_unitaire_ht' => $plat->prix,
                        'taux_tva' => 19.6,
                        'statut' => 'en_attente',
                        'commentaire' => ''
                    ]);
                }
            }

            // Marquer la table comme occupée si sur_place
            if ($validated['type_commande'] === 'sur_place' && $validated['table_id']) {
                $table = TableRestaurant::find($validated['table_id']);
                if ($table) {
                    $table->update(['est_disponible' => false]);
                }
            }

            // Vider le panier
            session()->forget('cart');

            return redirect()->route('commandes.show', $commande->id)
                ->with('success', 'Votre commande a été créée avec succès! Numéro: ' . $numero);

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la création de la commande: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les détails d'une commande
     */
    public function show($id)
    {
        $commande = Commande::with('lignesCommandes.plat')->findOrFail($id);

        // Vérifier que l'utilisateur est autorisé à voir cette commande
        $user = Auth::user();
        $client = $commande->client;
        
        if ($client->user_id !== $user->id) {
            abort(403, 'Non autorisé');
        }

        return view('client.order-detail', [
            'commande' => $commande,
            'lignes' => $commande->lignesCommandes
        ]);
    }

    /**
     * Afficher la liste des commandes de l'utilisateur
     */
    public function list()
    {
        $user = Auth::user();
        $client = Client::where('user_id', $user->id)->first();

        if (!$client) {
            return view('client.orders-list', ['commandes' => []]);
        }

        $commandes = Commande::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.orders-list', [
            'commandes' => $commandes
        ]);
    }

    /**
     * Annuler une commande
     */
    public function cancel($id)
    {
        $commande = Commande::findOrFail($id);
        $user = Auth::user();
        $client = $commande->client;

        if ($client->user_id !== $user->id) {
            abort(403, 'Non autorisé');
        }

        // Vérifier que la commande peut être annulée
        $statusAnnulables = ['enregistree', 'en_preparation'];
        if (!in_array($commande->statut, $statusAnnulables)) {
            return back()->with('error', 'Cette commande ne peut pas être annulée');
        }

        // Marquer la table comme disponible
        if ($commande->table_id) {
            $table = TableRestaurant::find($commande->table_id);
            if ($table) {
                $table->update(['est_disponible' => true]);
            }
        }

        // Annuler la commande
        $commande->update(['statut' => 'annulee']);

        return back()->with('success', 'Commande annulée avec succès');
    }

    /**
     * Obtenir les détails d'un plat (API)
     */
    public function getPlatDetails($platId)
    {
        $plat = Plat::with('categorie')->findOrFail($platId);

        return response()->json([
            'id' => $plat->id,
            'nom' => $plat->nom,
            'description' => $plat->description,
            'prix' => $plat->prix,
            'est_disponible' => $plat->est_disponible,
            'categorie' => $plat->categorie->nom,
            'image' => $plat->image,
            'temps_preparation' => $plat->temps_preparation
        ]);
    }

    /**
     * Calculer le total du panier
     */
    private function calculateCartTotal($cart)
    {
        $total = 0;
        foreach ($cart as $platId => $quantity) {
            $plat = Plat::find($platId);
            if ($plat) {
                $total += $plat->prix * $quantity;
            }
        }
        return round($total, 2);
    }

    /**
     * Générer un numéro de commande unique
     */
    private function generateOrderNumber()
    {
        $date = Carbon::now()->format('YmdHis');
        $random = rand(1000, 9999);
        return 'CMD-' . $date . '-' . $random;
    }
}
