<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Commande;
use Illuminate\Http\Request;
use PDF;

/**
 * Contrôleur Facture
 * Gère la génération et le téléchargement des factures PDF
 */
class FactureController extends Controller
{
    /**
     * Générer une facture
     */
    public function generer($commandeId)
    {
        $commande = Commande::with(['lignesCommandes.plat', 'client', 'facture'])
            ->findOrFail($commandeId);

        // Vérifier si la facture existe déjà
        if ($commande->facture) {
            return $this->telecharger($commande->facture->id);
        }

        // Créer la facture
        $facture = Facture::create([
            'commande_id' => $commande->id,
            'numero' => $this->genererNumeroFacture(),
            'montant_ht' => $commande->montant_total_ht,
            'montant_tva' => $commande->montant_tva,
            'montant_ttc' => $commande->montant_total_ttc,
            'date_emission' => now(),
            'statut' => 'emise',
        ]);

        // Marquer la commande comme payée
        $commande->update([
            'est_payee' => true,
            'statut' => 'payee',
        ]);

        return $this->telecharger($facture->id);
    }

    /**
     * Télécharger la facture en PDF
     */
    public function telecharger($factureId)
    {
        $facture = Facture::with(['commande.lignesCommandes.plat', 'commande.client'])
            ->findOrFail($factureId);

        $donnees = [
            'facture' => $facture,
            'commande' => $facture->commande,
        ];

        // Génération du PDF
        $pdf = PDF::loadView('factures.template', $donnees);
        
        return $pdf->download('facture-' . $facture->numero . '.pdf');
    }

    /**
     * Afficher la facture en HTML
     */
    public function show($id)
    {
        $facture = Facture::with(['commande.lignesCommandes.plat', 'commande.client'])
            ->findOrFail($id);

        return view('factures.show', [
            'facture' => $facture,
            'commande' => $facture->commande,
        ]);
    }

    /**
     * Lister les factures
     */
    public function index()
    {
        $factures = Facture::with('commande')
            ->orderBy('date_emission', 'desc')
            ->paginate(20);

        return view('factures.list', ['factures' => $factures]);
    }

    /**
     * Générer un numéro de facture unique
     */
    private function genererNumeroFacture()
    {
        $date = now()->format('Ymd');
        $lastFacture = Facture::where('numero', 'LIKE', "FACT-{$date}-%")
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastFacture) {
            $numero = 1;
        } else {
            $lastNumero = intval(substr($lastFacture->numero, -4));
            $numero = $lastNumero + 1;
        }

        return 'FACT-' . $date . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}
