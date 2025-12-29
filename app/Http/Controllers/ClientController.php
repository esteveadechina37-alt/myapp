<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Facture;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display the client dashboard
     */
    public function dashboard()
    {
        $userId = auth()->id();

        // Récupérer les commandes en cours (non finalisées)
        $activeCommands = Commande::where('client_id', $userId)
            ->whereIn('statut', ['enregistree', 'en_preparation', 'prete', 'prete_a_emporter', 'prete_a_livrer', 'en_livraison', 'servie'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Récupérer les commandes récentes de l'utilisateur
        $recentCommands = Commande::where('client_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Récupérer les factures de l'utilisateur via la relation avec les commandes
        $invoices = Facture::whereHas('commande', function($query) use ($userId) {
                $query->where('client_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Récupérer les commandes non payées
        $unpaidCommands = Commande::where('client_id', $userId)
            ->where('est_payee', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.dashboard', [
            'activeCommands' => $activeCommands,
            'recentCommands' => $recentCommands,
            'invoices' => $invoices,
            'unpaidCommands' => $unpaidCommands
        ]);
    }

    /**
     * Mark QR as scanned
     */
    public function markQRScanned(Request $request)
    {
        // Stocker le QR scan dans la session
        session(['qr_scanned' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'QR scanné avec succès'
        ]);
    }

    /**
     * Request bill (addition)
     */
    public function requestBill(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string',
            'message' => 'nullable|string|max:500'
        ]);

        // Ajouter la logique pour envoyer la demande au serveur
        // Pour l'instant, on retourne une réponse de succès
        return response()->json([
            'success' => true,
            'message' => 'Demande d\'addition envoyée'
        ]);
    }

    /**
     * Process payment for a command
     */
    public function processPayment(Request $request, $commandeId)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,card,mobile,check'
        ]);

        $commande = Commande::findOrFail($commandeId);

        // Vérifier que la commande appartient à l'utilisateur
        if ($commande->client_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        // Vérifier que la commande n'est pas déjà payée
        if ($commande->est_payee) {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande a déjà été payée'
            ], 400);
        }

        // Marquer la commande comme payée
        $commande->update([
            'est_payee' => true,
            'moyen_paiement' => $request->payment_method,
            'statut' => 'payee'
        ]);

        // Créer/mettre à jour la facture
        $facture = Facture::where('commande_id', $commande->id)->first();
        if ($facture) {
            $facture->update([
                'est_payee' => true,
                'date_paiement' => now()
            ]);
        } else {
            // Créer une nouvelle facture si elle n'existe pas
            Facture::create([
                'commande_id' => $commande->id,
                'montant_ht' => $commande->montant_total_ht,
                'montant_tva' => $commande->montant_tva,
                'montant_ttc' => $commande->montant_total_ttc,
                'est_payee' => true,
                'date_paiement' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Paiement traité avec succès'
        ]);
    }
}
