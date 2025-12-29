<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Facture;
use Illuminate\Http\Request;

class GerantController extends Controller
{
    /**
     * Gérant Dashboard - Gestion menu, stocks, tables, paiements
     */
    public function dashboard()
    {
        $data = [
            'totalCommandes' => Commande::count(),
            'commandesPendantes' => Commande::where('statut', 'en_preparation')->count(),
            'totalVentes' => Commande::where('est_payee', true)->sum('montant_total_ttc'),
            'recentCommandes' => Commande::with('client')->latest()->take(10)->get(),
            'revenus' => $this->getRevenusStats(),
        ];

        return view('employes.gerant.dashboard', $data);
    }

    /**
     * Gérer le menu - Afficher les plats
     */
    public function gererMenu()
    {
        $plats = \App\Models\Plat::with('categorie')->paginate(15);
        return view('employes.gerant.menu', ['plats' => $plats]);
    }

    /**
     * Gérer les stocks
     */
    public function gererStocks()
    {
        $stocks = \App\Models\Ingredient::paginate(15);
        return view('employes.gerant.stocks', ['stocks' => $stocks]);
    }

    /**
     * Consulter statistiques
     */
    public function statistiques()
    {
        $stats = [
            'totalCommandes' => Commande::count(),
            'commandesLivrees' => Commande::where('statut', 'livrée')->count(),
            'totalClients' => \App\Models\User::where('role', 'client')->count(),
            'totalVentes' => Commande::where('est_payee', true)->sum('montant_total_ttc'),
            'top5Plats' => \App\Models\Plat::withCount('lignesCommandes')->orderBy('lignes_commandes_count', 'desc')->take(5)->get(),
            'revenueByDay' => $this->getRevenueByDay(),
            'ordersByType' => $this->getOrdersByType(),
            'ordersByHour' => $this->getOrdersByHour(),
            'clientsByDay' => $this->getClientsByDay(),
        ];

        return view('employes.gerant.statistiques', $stats);
    }

    /**
     * Encaisser paiement
     */
    public function encaisserPaiement()
    {
        $commandesNonPayees = Commande::where('est_payee', false)->paginate(15);
        return view('employes.gerant.paiements', ['commandes' => $commandesNonPayees]);
    }

    /**
     * Marquer comme payée
     */
    public function markAsPaid(Request $request, Commande $commande)
    {
        $request->validate(['moyen_paiement' => 'required|in:especes,carte,cheque,virement']);

        $commande->update([
            'est_payee' => true,
            'moyen_paiement' => $request->moyen_paiement,
        ]);

        return redirect()->route('gerant.paiements')->with('success', 'Paiement traité');
    }

    /**
     * Obtenir les stats de revenus
     */
    private function getRevenusStats()
    {
        return [
            'today' => Commande::whereDate('created_at', today())->where('est_payee', true)->sum('montant_total_ttc'),
            'week' => Commande::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('est_payee', true)->sum('montant_total_ttc'),
            'month' => Commande::whereMonth('created_at', now()->month)->where('est_payee', true)->sum('montant_total_ttc'),
        ];
    }

    /**
     * Obtenir le revenu par jour (7 derniers jours)
     */
    private function getRevenueByDay()
    {
        $days = [];
        $revenues = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayName = now()->subDays($i)->format('D');
            $revenue = Commande::whereDate('created_at', $date)->where('est_payee', true)->sum('montant_total_ttc');
            
            $days[] = $dayName;
            $revenues[] = (float)$revenue;
        }
        
        return [
            'labels' => $days,
            'data' => $revenues,
        ];
    }

    /**
     * Obtenir les commandes par type
     */
    private function getOrdersByType()
    {
        $types = ['sur_place', 'a_emporter', 'livraison'];
        $data = [];
        
        foreach ($types as $type) {
            $count = Commande::where('type_commande', $type)->count();
            $data[] = $count;
        }
        
        return [
            'labels' => ['Sur Place', 'À Emporter', 'Livraison'],
            'data' => $data,
        ];
    }

    /**
     * Obtenir les commandes par heure
     */
    private function getOrdersByHour()
    {
        $hours = [];
        $data = [];
        
        for ($h = 9; $h < 17; $h++) {
            $count = Commande::whereRaw("HOUR(created_at) = $h")->count();
            $hours[] = $h . 'h';
            $data[] = $count;
        }
        
        return [
            'labels' => $hours,
            'data' => $data,
        ];
    }

    /**
     * Obtenir les clients par jour de la semaine
     */
    private function getClientsByDay()
    {
        $days = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $data = [];
        
        for ($i = 1; $i <= 7; $i++) {
            $count = Commande::whereRaw("DAYOFWEEK(created_at) = $i")->count();
            $data[] = $count;
        }
        
        return [
            'labels' => $days,
            'data' => $data,
        ];
    }
}
