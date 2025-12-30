<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Commande;
use Illuminate\Support\Facades\Mail;
use Exception;

class NotificationService
{
    /**
     * Notifier un changement de statut de commande
     */
    public function notifyOrderStatusChange(Commande $commande, string $oldStatus): void
    {
        try {
            $client = $commande->client;
            $user = $client->user ?? null;

            $message = $this->getStatusChangeMessage($commande->statut);
            $title = "Commande #{$commande->numero_commande}";

            // Enregistrer la notification dans la base
            if ($user) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'order_status_change',
                    'title' => $title,
                    'message' => $message,
                    'data' => [
                        'order_id' => $commande->id,
                        'order_number' => $commande->numero_commande,
                        'status' => $commande->statut,
                        'old_status' => $oldStatus,
                    ],
                    'sent_at' => now(),
                ]);

                // Envoyer email
                $this->sendEmail(
                    $client->email,
                    $title,
                    $message,
                    [
                        'commande' => $commande,
                        'status' => $commande->statut,
                    ]
                );
            }
        } catch (Exception $e) {
            \Log::error('Erreur notification changement statut', [
                'order_id' => $commande->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notifier l'annulation d'une commande
     */
    public function notifyOrderCancellation(Commande $commande, ?string $reason = null): void
    {
        try {
            $client = $commande->client;
            $user = $client->user ?? null;

            $title = "Commande #{$commande->numero_commande} - Annulée";
            $message = "Votre commande a été annulée";
            if ($reason) {
                $message .= ": {$reason}";
            }

            if ($user) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'order_cancelled',
                    'title' => $title,
                    'message' => $message,
                    'data' => [
                        'order_id' => $commande->id,
                        'reason' => $reason,
                    ],
                    'sent_at' => now(),
                ]);

                $this->sendEmail($client->email, $title, $message);
            }
        } catch (Exception $e) {
            \Log::error('Erreur notification annulation', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Notifier les employés d'une nouvelle commande
     */
    public function notifyNewOrder(Commande $commande): void
    {
        try {
            $users = User::whereIn('role', ['cuisinier', 'serveur', 'admin'])
                ->where('active', true)
                ->get();

            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'new_order',
                    'title' => "Nouvelle commande #{$commande->numero_commande}",
                    'message' => "Commande de {$commande->client->nom} - {$commande->lignes->count()} articles",
                    'data' => [
                        'order_id' => $commande->id,
                        'order_number' => $commande->numero_commande,
                    ],
                    'sent_at' => now(),
                ]);
            }
        } catch (Exception $e) {
            \Log::error('Erreur notification nouvelle commande', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Notifier du paiement reçu
     */
    public function notifyPaymentReceived(Commande $commande): void
    {
        try {
            $client = $commande->client;
            $user = $client->user ?? null;

            if ($user) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'payment_received',
                    'title' => "Paiement reçu - Commande #{$commande->numero_commande}",
                    'message' => "Merci ! Votre paiement de {$commande->montant_total}€ a été reçu.",
                    'data' => [
                        'order_id' => $commande->id,
                        'amount' => $commande->montant_total,
                    ],
                    'sent_at' => now(),
                ]);

                $this->sendEmail(
                    $client->email,
                    "Paiement reçu",
                    "Votre paiement de {$commande->montant_total}€ a été confirmé."
                );
            }
        } catch (Exception $e) {
            \Log::error('Erreur notification paiement', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Obtenir le message selon le statut
     */
    private function getStatusChangeMessage(string $status): string
    {
        $messages = [
            'enregistree' => 'Votre commande a été enregistrée',
            'en_preparation' => 'Votre commande est en préparation',
            'prete' => 'Votre commande est prête à être servie',
            'servie' => 'Votre commande a été servie',
            'payee' => 'Votre commande a été payée',
            'livree' => 'Votre commande a été livrée',
            'annulee' => 'Votre commande a été annulée',
        ];

        return $messages[$status] ?? 'Votre commande a été mise à jour';
    }

    /**
     * Envoyer un email
     */
    private function sendEmail(string $to, string $subject, string $message, array $data = []): void
    {
        try {
            Mail::send('emails.notification', array_merge(['message' => $message], $data), function ($mail) use ($to, $subject) {
                $mail->to($to)->subject($subject);
            });
        } catch (Exception $e) {
            \Log::error('Erreur envoi email', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Obtenir les notifications non lues d'un utilisateur
     */
    public function getUnreadNotifications(User $user)
    {
        return $user->notifications()
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Obtenir toutes les notifications d'un utilisateur
     */
    public function getUserNotifications(User $user, int $limit = 20)
    {
        return $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead(Notification $notification): void
    {
        $notification->markAsRead();
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead(User $user): void
    {
        $user->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Supprimer une notification
     */
    public function delete(Notification $notification): void
    {
        $notification->delete();
    }
}
