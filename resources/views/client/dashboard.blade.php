@extends('layouts.client')

@section('title', 'Tableau de Bord Client')

@section('extra-styles')
<style>
    body {
        font-size: 13px !important;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
        color: white;
        padding: 30px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
    }

    .welcome-message {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .action-card {
        background: white;
        border-radius: 10px;
        padding: 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-left: 4px solid #d32f2f;
        cursor: pointer;
    }

    .action-card:hover {
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }

    .action-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: white;
        margin-bottom: 12px;
    }

    .action-icon.menu {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E72 100%);
    }

    .action-icon.orders {
        background: linear-gradient(135deg, #4ECDC4 0%, #44A08D 100%);
    }

    .action-icon.cart {
        background: linear-gradient(135deg, #F38181 0%, #AA96DA 100%);
    }

    .action-icon.invoices {
        background: linear-gradient(135deg, #FCBAD3 0%, #A6C0FE 100%);
    }

    .action-title {
        font-weight: 700;
        color: #333;
        margin-bottom: 6px;
        font-size: 0.95rem;
    }

    .action-description {
        color: #999;
        font-size: 0.8rem;
    }

    .action-badge {
        background: #d32f2f;
        color: white;
        border-radius: 12px;
        padding: 3px 10px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 8px;
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 18px;
        padding-bottom: 8px;
        border-bottom: 3px solid #d32f2f;
        display: inline-block;
    }

    .section-container {
        margin-bottom: 30px;
    }

    .order-card {
        background: white;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-left: 4px solid #d32f2f;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.12);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .order-number {
        font-weight: 700;
        color: #333;
        font-size: 0.95rem;
    }

    .order-status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-enregistree {
        background: #fff3cd;
        color: #856404;
    }

    .status-en_preparation {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-prete {
        background: #d4edda;
        color: #155724;
    }

    .status-servie {
        background: #c3e6cb;
        color: #155724;
    }

    .status-payee {
        background: #c3e6cb;
        color: #155724;
    }

    .order-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 10px;
        margin-bottom: 12px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #666;
        font-size: 0.8rem;
    }

    .info-icon {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d32f2f;
        flex-shrink: 0;
        font-size: 0.85rem;
    }

    .order-amount {
        font-size: 1.1rem;
        font-weight: 700;
        color: #d32f2f;
    }

    .timeline {
        display: flex;
        gap: 8px;
        margin-top: 10px;
        overflow-x: auto;
        padding-bottom: 8px;
    }

    .timeline-step {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        padding: 6px 10px;
        background: #f0f0f0;
        border-radius: 20px;
        font-size: 0.75rem;
    }

    .timeline-step.completed {
        background: #d4edda;
        color: #155724;
    }

    .timeline-step.active {
        background: #d1ecf1;
        color: #0c5460;
    }

    .timeline-step i {
        font-size: 0.8rem;
    }

    .empty-state {
        background: white;
        border-radius: 10px;
        padding: 50px 20px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .empty-icon {
        font-size: 2.5rem;
        color: #ccc;
        margin-bottom: 12px;
    }

    .empty-message {
        color: #999;
        font-size: 0.95rem;
        margin-bottom: 15px;
    }

    .btn-primary-gradient {
        background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }

    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(211, 47, 47, 0.4);
    }

    .card-action-buttons {
        display: flex;
        gap: 8px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .btn-small {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-view {
        background: #f0f0f0;
        color: #333;
        flex: 1;
    }

    .btn-view:hover {
        background: #d32f2f;
        color: white;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .stat-number {
        font-size: 1.7rem;
        font-weight: 700;
        color: #d32f2f;
    }

    .stat-label {
        color: #999;
        font-size: 0.8rem;
        margin-top: 8px;
    }

    @media (max-width: 768px) {
        .quick-actions {
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        }

        .dashboard-header {
            padding: 20px 15px;
        }

        .welcome-message {
            font-size: 1.2rem;
        }

        .action-card {
            padding: 12px;
        }

        .action-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="dashboard-header">
        <div class="welcome-message">
            Bienvenue, {{ $user->name }}! 👋
        </div>
        <p class="mb-0">Gérez vos commandes et suivez leur progression en temps réel</p>
    </div>

    <!-- Actions rapides -->
    <div class="quick-actions">
        <a href="{{ route('client.menu') }}" class="action-card">
            <div class="action-icon menu">
                <i class="fas fa-utensils"></i>
            </div>
            <div class="action-title">Menu</div>
            <div class="action-description">Parcourir nos plats</div>
        </a>

        <a href="{{ route('client.view-cart') }}" class="action-card">
            <div class="action-icon cart">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="action-title">Panier</div>
            <div class="action-description">Voir vos articles</div>
            @if ($cartCount > 0)
                <span class="action-badge">{{ $cartCount }} article(s)</span>
            @endif
        </a>

        <a href="{{ route('client.order-history') }}" class="action-card">
            <div class="action-icon orders">
                <i class="fas fa-history"></i>
            </div>
            <div class="action-title">Commandes</div>
            <div class="action-description">Voir l'historique</div>
        </a>

        <a href="{{ route('client.invoices') }}" class="action-card">
            <div class="action-icon invoices">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="action-title">Factures</div>
            <div class="action-description">Voir mes factures</div>
        </a>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $activeCommandes->count() }}</div>
            <div class="stat-label">Commandes actives</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $recentCommandes->count() }}</div>
            <div class="stat-label">Commandes totales</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $factures->count() }}</div>
            <div class="stat-label">Factures</div>
        </div>
    </div>

    <!-- Commandes actives -->
    @if ($activeCommandes->count() > 0)
        <div class="section-container">
            <h2 class="section-title">
                <i class="fas fa-spinner" style="color: #1976d2; margin-right: 10px;"></i> Commandes actives
            </h2>
            
            @foreach ($activeCommandes as $commande)
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-number">
                            #{{ $commande->numero }}
                        </div>
                        <div class="order-status status-{{ str_replace(' ', '_', $commande->statut) }}">
                            {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                        </div>
                        <div class="order-amount">
                            {{ number_format($commande->montant_total_ttc, 0, ',', ' ') }} CFA
                        </div>
                    </div>

                    <div class="order-info">
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
                            <span>
                                @if ($commande->type_commande === 'sur_place')
                                    Sur place - Table {{ $commande->table->numero ?? 'N/A' }}
                                @elseif ($commande->type_commande === 'a_emporter')
                                    À emporter
                                @else
                                    Livraison
                                @endif
                            </span>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <span>{{ $commande->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <span>{{ $commande->lignesCommandes->count() }} article(s)</span>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                @if ($commande->est_payee)
                                    <i class="fas fa-check" style="color: #4caf50;"></i>
                                @else
                                    <i class="fas fa-clock" style="color: #ffc107;"></i>
                                @endif
                            </div>
                            <span>
                                @if ($commande->est_payee)
                                    Payée
                                @else
                                    Non payée
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="timeline">
                        <div class="timeline-step {{ in_array($commande->statut, ['en_preparation', 'prete', 'servie']) ? 'completed' : 'active' }}">
                            <i class="fas fa-check"></i> Enregistrée
                        </div>
                        <div class="timeline-step {{ in_array($commande->statut, ['prete', 'servie']) ? 'completed' : (in_array($commande->statut, ['en_preparation']) ? 'active' : '') }}">
                            <i class="fas fa-utensils"></i> Préparation
                        </div>
                        <div class="timeline-step {{ in_array($commande->statut, ['prete', 'prete_a_emporter', 'prete_a_livrer', 'servie']) ? 'completed' : '' }}">
                            <i class="fas fa-check-circle"></i> Prête
                        </div>
                        @if ($commande->type_commande === 'livraison')
                            <div class="timeline-step {{ in_array($commande->statut, ['livree']) ? 'completed' : (in_array($commande->statut, ['en_livraison']) ? 'active' : '') }}">
                                <i class="fas fa-truck"></i> Livraison
                            </div>
                        @endif
                        <div class="timeline-step {{ $commande->est_payee ? 'completed' : '' }}">
                            <i class="fas fa-euro-sign"></i> Payée
                        </div>
                    </div>

                    <div class="card-action-buttons">
                        <a href="{{ route('client.order-detail', $commande->id) }}" class="btn-small btn-view">
                            <i class="fas fa-eye"></i> Voir détails
                        </a>
                        @if (!$commande->est_payee && in_array($commande->statut, ['prete', 'prete_a_emporter', 'prete_a_livrer', 'servie']))
                            <button class="btn-small" style="background: #d32f2f; color: white; flex: 1;" onclick="showPaymentModal({{ $commande->id }})">
                                <i class="fas fa-credit-card"></i> Payer
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Commandes récentes -->
    @if ($recentCommandes->count() > 0 && $activeCommandes->count() === 0)
        <div class="section-container">
            <h2 class="section-title">
                <i class="fas fa-history" style="color: #1976d2; margin-right: 10px;"></i> Commandes récentes
            </h2>
            
            @foreach ($recentCommandes->take(5) as $commande)
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-number">#{{ $commande->numero }}</div>
                        <div class="order-amount">{{ number_format($commande->montant_total_ttc, 0, ',', ' ') }} CFA</div>
                    </div>
                    <div class="order-info">
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-calendar"></i></div>
                            <span>{{ $commande->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-utensils"></i></div>
                            <span>{{ $commande->lignesCommandes->count() }} article(s)</span>
                        </div>
                    </div>
                    <div class="card-action-buttons">
                        <a href="{{ route('client.order-detail', $commande->id) }}" class="btn-small btn-view">
                            <i class="fas fa-eye"></i> Voir
                        </a>
                    </div>
                </div>
            @endforeach

            <a href="{{ route('client.order-history') }}" class="btn-primary-gradient" style="margin-top: 20px;">
                <i class="fas fa-arrow-right"></i> Voir tout l'historique
            </a>
        </div>
    @else
        @if ($activeCommandes->count() === 0 && $recentCommandes->count() === 0)
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <div class="empty-message">Aucune commande pour le moment</div>
                <p class="text-muted mb-4">Commencez par parcourir notre menu et passer une commande</p>
                <a href="{{ route('client.menu') }}" class="btn-primary-gradient">
                    <i class="fas fa-utensils"></i> Voir le menu
                </a>
            </div>
        @endif
    @endif
</div>

<!-- Modal de paiement -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Procéder au paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    <div class="form-group mb-3">
                        <label class="form-label">Méthode de paiement</label>
                        <div class="d-grid gap-2">
                            <label class="btn btn-outline-primary" style="text-align: left;">
                                <input type="radio" name="payment_method" value="carte"> 💳 Carte bancaire
                            </label>
                            <label class="btn btn-outline-primary" style="text-align: left;">
                                <input type="radio" name="payment_method" value="especes"> 💵 Espèces
                            </label>
                            <label class="btn btn-outline-primary" style="text-align: left;">
                                <input type="radio" name="payment_method" value="mobile"> 📱 Paiement mobile
                            </label>
                            <label class="btn btn-outline-primary" style="text-align: left;">
                                <input type="radio" name="payment_method" value="cheque"> 📄 Chèque
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" onclick="confirmPayment()">Confirmer le paiement</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentCommandeId = null;

    function showPaymentModal(commandeId) {
        currentCommandeId = commandeId;
        new bootstrap.Modal(document.getElementById('paymentModal')).show();
    }

    function confirmPayment() {
        const method = document.querySelector('input[name="payment_method"]:checked');
        
        if (!method) {
            alert('Veuillez sélectionner une méthode de paiement');
            return;
        }

        fetch(`/client/payment/${currentCommandeId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                payment_method: method.value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Paiement effectué avec succès!');
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
</script>
@endsection
