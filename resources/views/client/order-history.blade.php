@extends('layouts.client')

@section('title', 'Historique des Commandes')

@section('extra-styles')
<style>
    body {
        font-size: 13px !important;
    }

    .header {
        margin-bottom: 25px;
    }

    .header h1 {
        font-weight: 700;
        margin-bottom: 6px;
        font-size: 1.4rem;
    }

    .header p {
        font-size: 0.8rem;
    }

    .order-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-left: 4px solid #d32f2f;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.12);
    }

    .order-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .order-number {\n        font-weight: 700;\n        color: #333;\n        font-size: 0.95rem;\n    }\n\n    .order-status {\n        padding: 4px 10px;\n        border-radius: 20px;\n        font-size: 0.75rem;\n        font-weight: 600;\n    }

    .status-enregistree { background: #fff3cd; color: #856404; }
    .status-en_preparation { background: #d1ecf1; color: #0c5460; }
    .status-prete { background: #d4edda; color: #155724; }
    .status-servie { background: #c3e6cb; color: #155724; }
    .status-payee { background: #c3e6cb; color: #155724; }
    .status-annulee { background: #f8d7da; color: #721c24; }

    .order-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
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
        font-weight: 700;
        color: #d32f2f;
        font-size: 0.95rem;
    }

    .order-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .btn-view {
        background: #f0f0f0;
        color: #333;
    }

    .btn-view:hover {
        background: #d32f2f;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
    }

    .empty-icon {
        font-size: 3rem;
        color: #ccc;
        margin-bottom: 15px;
    }

    .empty-message {
        color: #999;
        font-size: 1.1rem;
        margin-bottom: 20px;
    }

    .pagination {
        margin-top: 30px;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .order-card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .order-info {
            grid-template-columns: 1fr;
        }

        .order-actions {
            width: 100%;
        }

        .btn-action {
            flex: 1;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="header">
        <h1><i class="fas fa-history"></i> Historique des Commandes</h1>
        <p class="text-muted">Toutes vos commandes passées</p>
    </div>

    @if ($commandes->count() > 0)
        @foreach ($commandes as $commande)
            <div class="order-card">
                <div class="order-card-header">
                    <div>
                        <div class="order-number">Commande #{{ $commande->numero }}</div>
                        <div class="text-muted small">{{ $commande->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="order-status status-{{ str_replace(' ', '_', $commande->statut) }}">
                        {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                    </div>
                    <div class="order-amount">{{ number_format($commande->montant_total_ttc, 0, ',', ' ') }} CFA</div>
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
                                Sur place
                            @elseif ($commande->type_commande === 'a_emporter')
                                À emporter
                            @else
                                Livraison
                            @endif
                        </span>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-utensils"></i></div>
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
                        <span>{{ $commande->est_payee ? 'Payée' : 'Non payée' }}</span>
                    </div>
                </div>

                <div class="order-actions">
                    <a href="{{ route('client.order-detail', $commande->id) }}" class="btn-action btn-view">
                        <i class="fas fa-eye"></i> Voir les détails
                    </a>
                    @if (in_array($commande->statut, ['enregistree', 'en_preparation']))
                        <form method="POST" action="{{ route('client.cancel-order', $commande->id) }}" style="flex: 1;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action" style="background: #ffebee; color: #d32f2f; width: 100%;" onclick="return confirm('Sûr?')">
                                <i class="fas fa-times"></i> Annuler
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach

        {{ $commandes->links() }}
    @else
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-inbox"></i></div>
            <h3 class="empty-message">Aucune commande</h3>
            <p class="text-muted mb-4">Vous n'avez pas encore passé de commande</p>
            <a href="{{ route('client.menu') }}" class="btn" style="background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white; border: none; padding: 12px 30px;">
                <i class="fas fa-utensils"></i> Passer une commande
            </a>
        </div>
    @endif
</div>
@endsection
