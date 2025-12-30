<?php

namespace App\Services;

use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Facture;
use App\Models\Plat;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Créer une nouvelle commande
     */
    public function createOrder(array $data): Commande
    {
        return DB::transaction(function () use ($data) {
            $client = $this->getOrCreateClient($data);

            $commande = Commande::create([
                'client_id' => $client->id,
                'numero_commande' => $this->generateOrderNumber(),
                'type_commande' => $data['type_commande'] ?? 'sur_place',
                'table_id' => $data['table_id'] ?? null,
                'statut' => 'enregistree',
                'montant_total' => 0,
                'heure_commande' => now(),
            ]);

            $total = 0;
            foreach ($data['items'] as $item) {
                $plat = Plat::findOrFail($item['plat_id']);
                
                $ligne = LigneCommande::create([
                    'commande_id' => $commande->id,
                    'plat_id' => $plat->id,
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $plat->prix,
                    'sous_total' => $plat->prix * $item['quantite'],
                ]);

                $total += $ligne->sous_total;
            }

            $commande->update(['montant_total' => $total]);

            return $commande;
        });
    }

    /**
     * Générer numéro de commande unique
     */
    public function generateOrderNumber(): string
    {
        $date = Carbon::now()->format('YmdHis');
        $random = random_int(10000, 99999);
        return 'CMD-' . $date . '-' . $random;
    }

    /**
     * Mettre à jour le statut de la commande
     */
    public function updateOrderStatus(Commande $commande, string $newStatus): void
    {
        $validStatuses = ['enregistree', 'en_preparation', 'prete', 'servie', 'payee', 'livree', 'annulee', 'archivee'];
        
        if (!in_array($newStatus, $validStatuses)) {
            throw new \InvalidArgumentException("Statut invalide: {$newStatus}");
        }

        $oldStatus = $commande->statut;
        
        $commande->update([
            'statut' => $newStatus,
            'heure_prete' => $newStatus === 'prete' ? now() : $commande->heure_prete,
            'heure_servie' => $newStatus === 'servie' ? now() : $commande->heure_servie,
            'heure_livree' => $newStatus === 'livree' ? now() : $commande->heure_livree,
        ]);

        // Notifier du changement
        event(new \App\Events\OrderStatusChanged($commande, $oldStatus));
    }

    /**
     * Annuler une commande
     */
    public function cancelOrder(Commande $commande, ?string $reason = null): void
    {
        if (!in_array($commande->statut, ['enregistree', 'en_preparation'])) {
            throw new \InvalidArgumentException("Impossible d'annuler une commande avec le statut: {$commande->statut}");
        }

        DB::transaction(function () use ($commande, $reason) {
            $commande->update([
                'statut' => 'annulee',
                'motif_annulation' => $reason,
                'heure_annulation' => now(),
            ]);

            event(new \App\Events\OrderCancelled($commande));
        });
    }

    /**
     * Obtenir les commandes par statut
     */
    public function getOrdersByStatus(string $status, int $limit = 50)
    {
        return Commande::where('statut', $status)
            ->with('client', 'lignes', 'lignes.plat')
            ->orderBy('heure_commande', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir le temps estimé de préparation
     */
    public function getEstimatedTime(Commande $commande): int
    {
        $timePerItem = 3; // minutes par plat
        $totalItems = $commande->lignes->sum('quantite');
        return ceil($totalItems * $timePerItem);
    }

    /**
     * Générer une facture
     */
    public function generateInvoice(Commande $commande): Facture
    {
        return DB::transaction(function () use ($commande) {
            $facture = Facture::create([
                'commande_id' => $commande->id,
                'numero_facture' => $this->generateInvoiceNumber(),
                'montant_total' => $commande->montant_total,
                'statut_paiement' => 'en_attente',
                'date_facture' => now(),
            ]);

            return $facture;
        });
    }

    /**
     * Générer numéro de facture
     */
    public function generateInvoiceNumber(): string
    {
        $date = Carbon::now()->format('Ym');
        $lastInvoice = Facture::where('numero_facture', 'like', 'FAC-' . $date . '%')
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastInvoice) {
            $parts = explode('-', $lastInvoice->numero_facture);
            $sequence = ((int)$parts[2] ?? 0) + 1;
        }

        return 'FAC-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Obtenir les commandes du jour
     */
    public function getTodayOrders()
    {
        return Commande::whereDate('heure_commande', today())
            ->with('client', 'lignes')
            ->orderBy('heure_commande', 'desc')
            ->get();
    }

    /**
     * Obtenir les statistiques de commandes
     */
    public function getStatistics(string $period = 'day'): array
    {
        $query = Commande::query();

        if ($period === 'day') {
            $query->whereDate('heure_commande', today());
        } elseif ($period === 'week') {
            $query->where('heure_commande', '>=', now()->subWeek());
        } elseif ($period === 'month') {
            $query->where('heure_commande', '>=', now()->subMonth());
        }

        $commandes = $query->get();

        return [
            'total_commandes' => $commandes->count(),
            'montant_total' => $commandes->sum('montant_total'),
            'commandes_payees' => $commandes->where('statut', 'payee')->count(),
            'commandes_annulees' => $commandes->where('statut', 'annulee')->count(),
            'montant_moyen' => $commandes->count() > 0 ? $commandes->sum('montant_total') / $commandes->count() : 0,
            'temps_moyen_preparation' => $this->getAveragePreparationTime($commandes),
        ];
    }

    /**
     * Obtenir le temps moyen de préparation
     */
    private function getAveragePreparationTime($commandes): ?int
    {
        $times = $commandes
            ->filter(fn($c) => $c->heure_prete && $c->heure_commande)
            ->map(fn($c) => $c->heure_prete->diffInMinutes($c->heure_commande))
            ->toArray();

        return count($times) > 0 ? (int)array_sum($times) / count($times) : null;
    }

    /**
     * Obtenir ou créer un client
     */
    private function getOrCreateClient(array $data): Client
    {
        if (isset($data['client_id'])) {
            return Client::findOrFail($data['client_id']);
        }

        // Créer un client anonyme
        return Client::create([
            'nom' => $data['nom'] ?? 'Client',
            'prenom' => $data['prenom'] ?? '',
            'email' => $data['email'] ?? 'client-' . uniqid() . '@restaurant.local',
            'telephone' => $data['telephone'] ?? null,
        ]);
    }

    /**
     * Vérifier si la commande peut être modifiée
     */
    public function canModify(Commande $commande): bool
    {
        return in_array($commande->statut, ['enregistree', 'en_preparation']);
    }

    /**
     * Vérifier si la commande peut être annulée
     */
    public function canCancel(Commande $commande): bool
    {
        return in_array($commande->statut, ['enregistree', 'en_preparation']);
    }
}
