@extends('layouts.client')

@section('title', 'Détails Commande #' . $commande->numero)

@section('extra-styles')
<style>
    .order-header {
        background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
    }

    .order-number {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .order-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .info-item {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .info-icon {
        font-size: 1.3rem;
        opacity: 0.8;
    }

    .info-label {
        font-size: 0.85rem;
        opacity: 0.8;
    }

    .info-value {
        font-weight: 600;
        margin-top: 3px;
    }

    .section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .section-title {
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .item-row {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
        align-items: center;
    }

    .item-row:last-child {
        border-bottom: none;
    }

    .item-name {
        font-weight: 600;
        color: #333;
    }

    .item-qty {
        color: #999;
        font-size: 0.9rem;
    }

    .summary {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
    }

    .summary-row.total {
        border-top: 2px solid #e0e0e0;
        padding-top: 15px;
        font-weight: 700;
        font-size: 1.1rem;
        color: #d32f2f;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .status-enregistree { background: #fff3cd; color: #856404; }
    .status-en_preparation { background: #d1ecf1; color: #0c5460; }
    .status-prete { background: #d4edda; color: #155724; }
    .status-servie { background: #c3e6cb; color: #155724; }
    .status-payee { background: #c3e6cb; color: #155724; }

    .timeline {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .timeline-step {
        padding: 15px;
        border: 2px solid #f0f0f0;
        border-radius: 8px;
        text-align: center;
        background: #f9f9f9;
    }

    .timeline-step.completed {
        border-color: #4caf50;
        background: #e8f5e9;
        color: #2e7d32;
    }

    .timeline-step.active {
        border-color: #2196f3;
        background: #e3f2fd;
        color: #1565c0;
    }

    .timeline-step-icon {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .timeline-step-title {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .timeline-step-time {
        font-size: 0.75rem;
        opacity: 0.7;
        margin-top: 5px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-back {
        background: #f0f0f0;
        color: #333;
    }

    .btn-back:hover {
        background: #d32f2f;
        color: white;
    }

    .btn-pay {
        background: #d32f2f;
        color: white;
    }

    .btn-pay:hover {
        background: #b71c1c;
    }

    .btn-cancel {
        background: #ffebee;
        color: #d32f2f;
    }

    .btn-cancel:hover {
        background: #d32f2f;
        color: white;
    }

    @media (max-width: 768px) {
        .order-info-grid {
            grid-template-columns: 1fr;
        }

        .timeline {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="order-header">
        <div class="order-number">
            <i class="fas fa-receipt"></i> Commande #{{ $commande->numero }}
        </div>
        <span class="status-badge status-{{ str_replace(' ', '_', $commande->statut) }}">
            {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
        </span>

        <div class="order-info-grid">
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-calendar"></i></div>
                <div>
                    <div class="info-label">Date de commande</div>
                    <div class="info-value">{{ $commande->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    @if ($commande->type_commande === 'sur_place')
                        <i class="fas fa-chair"></i>
                    @elseif ($commande->type_commande === 'a_emporter')
                        <i class="fas fa-shopping-bag"></i>
                    @else
                        <i class="fas fa-truck"></i>
                    @endif
                </div>
                <div>
                    <div class="info-label">Type</div>
                    <div class="info-value">
                        @if ($commande->type_commande === 'sur_place')
                            Sur place - Table {{ $commande->table->numero ?? 'N/A' }}
                        @elseif ($commande->type_commande === 'a_emporter')
                            À emporter
                        @else
                            Livraison
                        @endif
                    </div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-euro-sign"></i></div>
                <div>
                    <div class="info-label">Montant</div>
                    <div class="info-value">{{ number_format($commande->montant_total_ttc, 0, ',', ' ') }} CFA</div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    @if ($commande->est_payee)
                        <i class="fas fa-check-circle" style="color: #4caf50;"></i>
                    @else
                        <i class="fas fa-clock" style="color: #ffc107;"></i>
                    @endif
                </div>
                <div>
                    <div class="info-label">Paiement</div>
                    <div class="info-value">
                        @if ($commande->est_payee)
                            Payée
                        @else
                            En attente
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Articles -->
            <div class="section">
                <h3 class="section-title">Articles commandés</h3>

                @foreach ($commande->lignesCommandes as $ligne)
                    <div class="item-row">
                        <div>
                            <div class="item-name">{{ $ligne->plat->nom }}</div>
                            <div class="item-qty">Quantité: {{ $ligne->quantite }}</div>
                        </div>
                        <div style="font-weight: 600; color: #d32f2f;">
                            {{ number_format($ligne->quantite * $ligne->prix_unitaire_ht, 0, ',', ' ') }} CFA
                        </div>
                    </div>
                @endforeach

                <div class="summary">
                    <div class="summary-row">
                        <span>Sous-total HT</span>
                        <span>{{ number_format($commande->montant_total_ht, 0, ',', ' ') }} CFA</span>
                    </div>
                    <div class="summary-row">
                        <span>TVA (19.6%)</span>
                        <span>{{ number_format($commande->montant_tva, 0, ',', ' ') }} CFA</span>
                    </div>
                    <div class="summary-row total">
                        <span>TOTAL TTC</span>
                        <span>{{ number_format($commande->montant_total_ttc, 0, ',', ' ') }} CFA</span>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="section">
                <h3 class="section-title">Suivi de la commande</h3>

                <div class="timeline">
                    <div class="timeline-step {{ in_array($commande->statut, ['en_preparation', 'prete', 'servie']) ? 'completed' : 'active' }}">
                        <div class="timeline-step-icon"><i class="fas fa-check"></i></div>
                        <div class="timeline-step-title">Enregistrée</div>
                        <div class="timeline-step-time">{{ $commande->created_at->format('H:i') }}</div>
                    </div>

                    <div class="timeline-step {{ in_array($commande->statut, ['prete', 'servie']) ? 'completed' : (in_array($commande->statut, ['en_preparation']) ? 'active' : '') }}">
                        <div class="timeline-step-icon"><i class="fas fa-utensils"></i></div>
                        <div class="timeline-step-title">Préparation</div>
                        @if ($commande->heure_remise_cuisine)
                            <div class="timeline-step-time">{{ $commande->heure_remise_cuisine->format('H:i') }}</div>
                        @endif
                    </div>

                    <div class="timeline-step {{ in_array($commande->statut, ['prete', 'servie']) ? 'completed' : '' }}">
                        <div class="timeline-step-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="timeline-step-title">Prête</div>
                        @if ($commande->heure_prete)
                            <div class="timeline-step-time">{{ $commande->heure_prete->format('H:i') }}</div>
                        @endif
                    </div>

                    @if ($commande->type_commande === 'livraison')
                        <div class="timeline-step {{ in_array($commande->statut, ['livree']) ? 'completed' : '' }}">
                            <div class="timeline-step-icon"><i class="fas fa-truck"></i></div>
                            <div class="timeline-step-title">Livraison</div>
                            @if ($commande->heure_livraison_demandee)
                                <div class="timeline-step-time">{{ $commande->heure_livraison_demandee->format('H:i') }}</div>
                            @endif
                        </div>
                    @endif

                    <div class="timeline-step {{ $commande->est_payee ? 'completed' : '' }}">
                        <div class="timeline-step-icon"><i class="fas fa-euro-sign"></i></div>
                        <div class="timeline-step-title">Payée</div>
                    </div>
                </div>
            </div>

            @if ($commande->commentaires)
                <div class="section">
                    <h3 class="section-title">Notes</h3>
                    <p>{{ $commande->commentaires }}</p>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="section">
                <h3 class="section-title">Actions</h3>

                <div class="action-buttons">
                    <button class="btn btn-back" onclick="window.print()">
                        <i class="fas fa-print"></i> Imprimer
                    </button>
                    <a href="{{ route('client.order-history') }}" class="btn btn-back">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>

                @if (!$commande->est_payee && in_array($commande->statut, ['prete', 'prete_a_emporter', 'prete_a_livrer', 'servie']))
                    <button class="btn btn-pay w-100 mt-2" onclick="showPaymentModal({{ $commande->id }})">
                        <i class="fas fa-credit-card"></i> Payer maintenant
                    </button>
                @endif

                @if ($commande->est_payee && $commande->facture)
                    <button class="btn btn-pay w-100 mt-2" onclick="window.open('/client/invoice/{{ $commande->facture->id }}/download', '_blank')">
                        <i class="fas fa-file-pdf"></i> Télécharger la Facture
                    </button>
                @endif

                @if (in_array($commande->statut, ['enregistree', 'en_preparation']))
                    <form method="POST" action="{{ route('client.cancel-order', $commande->id) }}" style="margin-top: 15px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-cancel w-100" onclick="return confirm('Annuler cette commande?')">
                            <i class="fas fa-times-circle"></i> Annuler
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Paiement -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="pay('carte')">💳 Carte bancaire</button>
                    <button class="btn btn-outline-primary" onclick="pay('especes')">💵 Espèces</button>
                    <button class="btn btn-outline-primary" onclick="pay('mobile')">📱 Paiement mobile</button>
                    <button class="btn btn-outline-primary" onclick="pay('cheque')">📄 Chèque</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let commandeId = {{ $commande->id }};

    function showPaymentModal(id) {
        commandeId = id;
        new bootstrap.Modal(document.getElementById('paymentModal')).show();
    }

    function pay(method) {
        fetch(`/client/payment/${commandeId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ payment_method: method })
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                alert('Paiement effectué!');
                location.reload();
            } else {
                alert('Erreur: ' + d.message);
            }
        });
    }
</script>
@endsection
