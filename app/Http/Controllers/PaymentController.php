<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Payment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class PaymentController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Afficher le formulaire de paiement
     */
    public function show(Commande $commande)
    {
        $this->authorize('view', $commande);

        return view('payment.form', [
            'commande' => $commande,
            'montant' => $commande->montant_total,
            'numero_commande' => $commande->numero_commande,
        ]);
    }

    /**
     * Traiter le paiement
     */
    public function process(Request $request, Commande $commande)
    {
        $this->authorize('view', $commande);

        $validated = $request->validate([
            'payment_method' => 'required|in:card,cash,transfer,check',
            'card_number' => 'required_if:payment_method,card|digits:16',
            'card_holder' => 'required_if:payment_method,card|string',
            'expiry_month' => 'required_if:payment_method,card|digits:2',
            'expiry_year' => 'required_if:payment_method,card|digits:4',
            'cvv' => 'required_if:payment_method,card|digits:3',
            'notes' => 'nullable|string',
        ]);

        try {
            return DB::transaction(function () use ($validated, $commande) {
                // Valider le paiement
                $paymentValid = $this->validatePayment($validated, $commande);

                if (!$paymentValid) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Paiement refusé. Vérifiez vos informations.',
                    ], 422);
                }

                // Enregistrer le paiement
                $payment = Payment::create([
                    'commande_id' => $commande->id,
                    'montant' => $commande->montant_total,
                    'methode' => $validated['payment_method'],
                    'statut' => 'complete',
                    'reference_transaction' => $this->generateTransactionReference(),
                    'date_paiement' => now(),
                    'notes' => $validated['notes'] ?? null,
                ]);

                // Mettre à jour la commande
                $commande->update([
                    'statut' => 'payee',
                    'heure_paiement' => now(),
                ]);

                // Notifier le client
                $this->notificationService->notifyPaymentReceived($commande);

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement traité avec succès',
                    'transaction_ref' => $payment->reference_transaction,
                    'redirect' => route('client.order-detail', $commande->id),
                ]);
            });
        } catch (Exception $e) {
            \Log::error('Erreur traitement paiement', [
                'order_id' => $commande->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement du paiement',
            ], 500);
        }
    }

    /**
     * Valider les informations de paiement
     */
    private function validatePayment(array $data, Commande $commande): bool
    {
        if ($data['payment_method'] === 'card') {
            // Validation basique de la carte
            if (!$this->validateCardNumber($data['card_number'])) {
                return false;
            }

            // Vérifier l'expiration
            if (!$this->validateCardExpiry($data['expiry_month'], $data['expiry_year'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Valider le numéro de carte (algorithme de Luhn)
     */
    private function validateCardNumber(string $cardNumber): bool
    {
        $cardNumber = preg_replace('/[^0-9]/', '', $cardNumber);
        
        if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
            return false;
        }

        $sum = 0;
        $parity = strlen($cardNumber) % 2;

        for ($i = 0; $i < strlen($cardNumber); $i++) {
            $digit = (int)$cardNumber[$i];

            if ($i % 2 === $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
        }

        return ($sum % 10) === 0;
    }

    /**
     * Valider l'expiration de la carte
     */
    private function validateCardExpiry(string $month, string $year): bool
    {
        $currentYear = (int)date('Y');
        $currentMonth = (int)date('m');
        $expiryYear = (int)$year;
        $expiryMonth = (int)$month;

        if ($expiryYear < $currentYear) {
            return false;
        }

        if ($expiryYear === $currentYear && $expiryMonth < $currentMonth) {
            return false;
        }

        return true;
    }

    /**
     * Générer une référence de transaction
     */
    private function generateTransactionReference(): string
    {
        return 'TXN-' . date('YmdHis') . '-' . random_int(10000, 99999);
    }

    /**
     * Afficher l'historique des paiements
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        $payments = Payment::join('commandes', 'payments.commande_id', '=', 'commandes.id')
            ->join('clients', 'commandes.client_id', '=', 'clients.id')
            ->join('users', 'clients.email', '=', 'users.email')
            ->where('users.id', $user->id)
            ->select('payments.*', 'commandes.numero_commande')
            ->orderBy('payments.date_paiement', 'desc')
            ->paginate(10);

        return view('payment.history', compact('payments'));
    }

    /**
     * Télécharger reçu de paiement
     */
    public function receipt(Payment $payment)
    {
        $this->authorize('view', $payment);

        return response()->json([
            'transaction_ref' => $payment->reference_transaction,
            'montant' => $payment->montant,
            'methode' => $payment->methode,
            'date' => $payment->date_paiement->format('d/m/Y H:i'),
            'statut' => $payment->statut,
        ]);
    }

    /**
     * Remboursement partiel (Admin)
     */
    public function refund(Request $request, Payment $payment)
    {
        $this->authorize('admin');

        $validated = $request->validate([
            'montant' => 'required|numeric|min:0.01|max:' . $payment->montant,
            'raison' => 'required|string',
        ]);

        try {
            return DB::transaction(function () use ($validated, $payment) {
                // Créer un paiement inversé (remboursement)
                Payment::create([
                    'commande_id' => $payment->commande_id,
                    'montant' => -$validated['montant'],
                    'methode' => $payment->methode,
                    'statut' => 'refunded',
                    'reference_transaction' => 'REFUND-' . $payment->reference_transaction,
                    'date_paiement' => now(),
                    'notes' => 'Remboursement: ' . $validated['raison'],
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Remboursement traité',
                ]);
            });
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur remboursement',
            ], 500);
        }
    }
}
