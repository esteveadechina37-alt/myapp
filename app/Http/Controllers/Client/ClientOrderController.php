<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Plat;
use App\Models\Client;
use App\Models\TableRestaurant;
use App\Models\Facture;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * ClientOrderController
 * Gestion complète du système de commande côté client
 */
class ClientOrderController extends Controller
{
    /**
     * Tableau de bord client - Vue principale
     */
    public function dashboard()
    {
        $user = Auth::user();
        $client = Client::firstOrCreate(
            ['email' => $user->email],
            [
                'nom' => explode(' ', $user->name)[0] ?? $user->name,
                'prenom' => count(explode(' ', $user->name)) > 1 ? implode(' ', array_slice(explode(' ', $user->name), 1)) : ''
            ]
        );

        $activeCommandes = [];
        $recentCommandes = [];
        $factures = [];
        $cartCount = 0;

        if ($client) {
            // Commandes actives (non complétées et non annulées)
            $activeCommandes = Commande::where('client_id', $client->id)
                ->whereIn('statut', ['enregistree', 'en_preparation', 'prete', 'prete_a_emporter', 'prete_a_livrer', 'en_livraison', 'servie'])
                ->with('lignesCommandes.plat', 'table')
                ->orderBy('created_at', 'desc')
                ->get();

            // Commandes récentes
            $recentCommandes = Commande::where('client_id', $client->id)
                ->with('lignesCommandes.plat')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Factures
            $factures = Facture::whereHas('commande', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        }

        // Nombre d'articles dans le panier
        $cart = session()->get('cart', []);
        $cartCount = array_sum($cart);

        return view('client.dashboard', [
            'activeCommandes' => $activeCommandes,
            'recentCommandes' => $recentCommandes,
            'factures' => $factures,
            'cartCount' => $cartCount,
            'user' => $user,
            'client' => $client
        ]);
    }

    /**
     * Afficher le menu avec panier
     */
    public function menu()
    {
        $categories = Categorie::where('est_active', true)
            ->orderBy('ordre_affichage')
            ->with(['plats' => function ($query) {
                $query->where('est_disponible', true)->orderBy('nom');
            }])
            ->get();

        $cart = session()->get('cart', []);
        $cartCount = array_sum($cart);

        return view('client.menu', [
            'categories' => $categories,
            'cartCount' => $cartCount
        ]);
    }

    /**
     * Afficher le panier
     */
    public function viewCart()
    {
        $cart = session()->get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $platId => $quantity) {
            $plat = Plat::find($platId);
            if ($plat) {
                $sousTotal = $plat->prix * $quantity;
                $items[] = [
                    'id' => $plat->id,
                    'nom' => $plat->nom,
                    'description' => $plat->description,
                    'prix' => $plat->prix,
                    'quantite' => $quantity,
                    'image' => $plat->image,
                    'sousTotal' => $sousTotal
                ];
                $total += $sousTotal;
            }
        }

        usort($items, function ($a, $b) {
            return $a['nom'] <=> $b['nom'];
        });

        return view('client.cart', [
            'items' => $items,
            'subTotal' => $total,
            'tva' => $total * 0.196,
            'total' => $total * 1.196,
            'cartCount' => count($cart)
        ]);
    }

    /**
     * Ajouter un article au panier (AJAX)
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

        $quantity = max(1, intval($request->input('quantity', 1)));
        $cart = session()->get('cart', []);

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
            'cartTotal' => $this->calculateTotal($cart)
        ]);
    }

    /**
     * Mettre à jour la quantité
     */
    public function updateCart(Request $request, $platId)
    {
        $quantity = intval($request->input('quantity', 1));
        $cart = session()->get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$platId]);
        } else {
            $cart[$platId] = $quantity;
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'cartCount' => array_sum($cart),
            'cartTotal' => $this->calculateTotal($cart)
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
            'cartCount' => array_sum($cart)
        ]);
    }

    /**
     * Vider le panier
     */
    public function clearCart()
    {
        session()->forget('cart');
        return response()->json(['success' => true]);
    }

    /**
     * Afficher le formulaire de commande
     */
    public function checkoutForm()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('client.menu')->with('error', 'Votre panier est vide');
        }

        $items = [];
        $subTotal = 0;

        foreach ($cart as $platId => $quantity) {
            $plat = Plat::find($platId);
            if ($plat) {
                $sousTotal = $plat->prix * $quantity;
                $items[] = [
                    'id' => $plat->id,
                    'nom' => $plat->nom,
                    'prix' => $plat->prix,
                    'quantite' => $quantity,
                    'sousTotal' => $sousTotal
                ];
                $subTotal += $sousTotal;
            }
        }

        $tva = $subTotal * 0.196;
        $total = $subTotal + $tva;

        $tables = TableRestaurant::where('est_disponible', true)
            ->orderBy('numero')
            ->get();

        return view('client.checkout', [
            'items' => $items,
            'subTotal' => $subTotal,
            'tva' => $tva,
            'total' => $total,
            'tables' => $tables,
            'user' => Auth::user()
        ]);
    }

    /**
     * Créer une nouvelle commande
     */
    public function storeCommande(Request $request)
    {
        $validated = $request->validate([
            'type_commande' => 'required|in:sur_place,a_emporter,livraison',
            'table_id' => 'nullable|exists:tables_restaurant,id',
            'adresse_livraison' => 'nullable|string|max:500',
            'commentaires' => 'nullable|string|max:500'
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Votre panier est vide');
        }

        // Validation type de commande
        if ($validated['type_commande'] === 'sur_place' && !$validated['table_id']) {
            return back()->withErrors(['table_id' => 'Sélectionnez une table pour une commande sur place']);
        }

        if ($validated['type_commande'] === 'livraison' && !$validated['adresse_livraison']) {
            return back()->withErrors(['adresse_livraison' => 'Entrez une adresse de livraison']);
        }

        try {
            $user = Auth::user();
            
            // Obtenir ou créer le client
            $client = Client::firstOrCreate(
                ['email' => $user->email],
                [
                    'nom' => explode(' ', $user->name)[0] ?? $user->name,
                    'prenom' => count(explode(' ', $user->name)) > 1 ? implode(' ', array_slice(explode(' ', $user->name), 1)) : '',
                    'telephone' => $user->phone ?? null
                ]
            );

            // Calculer montants
            $montantHT = 0;
            foreach ($cart as $platId => $quantity) {
                $plat = Plat::find($platId);
                if ($plat) {
                    $montantHT += $plat->prix * $quantity;
                }
            }

            $montantTVA = $montantHT * 0.196;
            $montantTTC = $montantHT + $montantTVA;
            $numero = $this->generateOrderNumber();

            // Créer la commande - Directement en préparation pour le cuisinier
            $commande = Commande::create([
                'numero' => $numero,
                'client_id' => $client->id,
                'table_id' => $validated['type_commande'] === 'sur_place' ? $validated['table_id'] : null,
                'type_commande' => $validated['type_commande'],
                'montant_total_ht' => $montantHT,
                'montant_tva' => $montantTVA,
                'montant_total_ttc' => $montantTTC,
                'statut' => 'en_preparation',
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
                        'statut' => 'en_attente'
                    ]);
                }
            }

            // Marquer la table comme occupée
            if ($validated['type_commande'] === 'sur_place' && $validated['table_id']) {
                TableRestaurant::find($validated['table_id'])->update(['est_disponible' => false]);
            }

            // Vider le panier
            session()->forget('cart');

            return redirect()->route('client.order-detail', $commande->id)
                ->with('success', 'Commande créée! Numéro: ' . $numero);

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les détails d'une commande
     */
    public function orderDetail($id)
    {
        $commande = Commande::with('lignesCommandes.plat', 'client', 'table')->findOrFail($id);
        $user = Auth::user();

        if ($commande->client->email !== $user->email) {
            abort(403);
        }

        return view('client.order-detail', ['commande' => $commande]);
    }

    /**
     * Afficher l'historique des commandes
     */
    public function orderHistory()
    {
        $user = Auth::user();
        $client = Client::firstOrCreate(
            ['email' => $user->email],
            [
                'nom' => explode(' ', $user->name)[0] ?? $user->name,
                'prenom' => count(explode(' ', $user->name)) > 1 ? implode(' ', array_slice(explode(' ', $user->name), 1)) : ''
            ]
        );

        if (!$client) {
            return view('client.order-history', ['commandes' => collect([])]);
        }

        $commandes = Commande::where('client_id', $client->id)
            ->with('lignesCommandes.plat')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.order-history', ['commandes' => $commandes]);
    }

    /**
     * Annuler une commande
     */
    public function cancelOrder($id)
    {
        $commande = Commande::findOrFail($id);
        $user = Auth::user();

        if ($commande->client->email !== $user->email) {
            abort(403);
        }

        if (!in_array($commande->statut, ['enregistree', 'en_preparation'])) {
            return back()->with('error', 'Cette commande ne peut pas être annulée');
        }

        // Libérer la table
        if ($commande->table_id) {
            TableRestaurant::find($commande->table_id)->update(['est_disponible' => true]);
        }

        $commande->update(['statut' => 'annulee']);

        return back()->with('success', 'Commande annulée');
    }

    /**
     * Traiter un paiement
     */
    public function processPayment(Request $request, $commandeId)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:carte,especes,mobile,cheque'
        ]);

        $commande = Commande::findOrFail($commandeId);
        $user = Auth::user();

        if ($commande->client->email !== $user->email) {
            return response()->json(['success' => false, 'message' => 'Non autorisé'], 403);
        }

        if ($commande->est_payee) {
            return response()->json(['success' => false, 'message' => 'Déjà payée'], 400);
        }

        try {
            $commande->update([
                'est_payee' => true,
                'moyen_paiement' => $validated['payment_method']
            ]);

            // Créer la facture
            Facture::firstOrCreate(
                ['commande_id' => $commande->id],
                [
                    'montant_ttc' => $commande->montant_total_ttc,
                    'est_payee' => true,
                    'date_paiement' => Carbon::now()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Paiement effectué',
                'commande_id' => $commande->id
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Voir les factures
     */
    public function invoices()
    {
        $user = Auth::user();
        $client = Client::firstOrCreate(
            ['email' => $user->email],
            [
                'nom' => explode(' ', $user->name)[0] ?? $user->name,
                'prenom' => count(explode(' ', $user->name)) > 1 ? implode(' ', array_slice(explode(' ', $user->name), 1)) : ''
            ]
        );

        if (!$client) {
            return view('client.invoices', ['factures' => collect([])]);
        }

        $factures = Facture::whereHas('commande', function ($query) use ($client) {
            $query->where('client_id', $client->id);
        })
        ->with('commande')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('client.invoices', ['factures' => $factures]);
    }

    /**
     * Télécharger une facture
     */
    public function downloadInvoice($id)
    {
        $facture = Facture::with('commande.lignesCommandes.plat', 'commande.client', 'commande.table')->findOrFail($id);
        $user = Auth::user();
        $client = Client::where('email', $user->email)->first();

        if (!$client || $facture->commande->client_id !== $client->id) {
            abort(403);
        }

        // Retourner la vue HTML pour l'impression/téléchargement PDF
        return view('client.facture-pdf', ['facture' => $facture]);
    }

    /**
     * Calculer le total du panier
     */
    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $platId => $quantity) {
            $plat = Plat::find($platId);
            if ($plat) {
                $total += $plat->prix * $quantity;
            }
        }
        return round($total * 1.196, 2);
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
            'image' => $plat->image,
            'categorie' => $plat->categorie->nom ?? null
        ]);
    }

    /**
     * Rechercher des plats
     */
    public function searchPlats(Request $request)
    {
        $search = $request->input('search', '');

        $plats = Plat::where('est_disponible', true)
            ->where(function ($query) use ($search) {
                $query->where('nom', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->with('categorie')
            ->limit(10)
            ->get();

        return response()->json([
            'plats' => $plats->map(function ($plat) {
                return [
                    'id' => $plat->id,
                    'nom' => $plat->nom,
                    'prix' => $plat->prix,
                    'categorie' => $plat->categorie->nom,
                    'image' => $plat->image
                ];
            })
        ]);
    }
}
