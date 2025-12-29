@extends('layouts.client')

@section('title', 'Mes Factures')

@section('extra-styles')
<style>
    .header {
        margin-bottom: 30px;
    }

    .header h1 {
        font-weight: 700;
        margin-bottom: 10px;
    }

    .invoice-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-left: 5px solid #d32f2f;
        transition: all 0.3s ease;
    }

    .invoice-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .invoice-number {
        font-weight: 700;
        color: #333;
        font-size: 1.1rem;
    }

    .invoice-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        background: #c3e6cb;
        color: #155724;
    }

    .invoice-amount {
        font-weight: 700;
        color: #d32f2f;
        font-size: 1.1rem;
    }

    .invoice-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #666;
        font-size: 0.9rem;
    }

    .info-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d32f2f;
        flex-shrink: 0;
    }

    .invoice-actions {
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

    .btn-download {
        background: #d32f2f;
        color: white;
    }

    .btn-download:hover {
        background: #b71c1c;
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
        .invoice-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .invoice-info {
            grid-template-columns: 1fr;
        }

        .invoice-actions {
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
        <h1><i class="fas fa-file-invoice"></i> Mes Factures</h1>
        <p class="text-muted">Toutes vos factures et documents financiers</p>
    </div>

    @if ($factures->count() > 0)
        @foreach ($factures as $facture)
            <div class="invoice-card">
                <div class="invoice-header">
                    <div>
                        <div class="invoice-number">Facture #{{ $facture->commande->numero }}</div>
                        <div class="text-muted small">{{ $facture->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="invoice-status">
                        <i class="fas fa-check-circle"></i> Payée
                    </div>
                    <div class="invoice-amount">{{ number_format($facture->montant_ttc, 0, ',', ' ') }} CFA</div>
                </div>

                <div class="invoice-info">
                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-receipt"></i></div>
                        <span>Commande #{{ $facture->commande->numero }}</span>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-calendar"></i></div>
                        <span>{{ $facture->date_paiement ? $facture->date_paiement->format('d/m/Y') : 'N/A' }}</span>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-euro-sign"></i></div>
                        <span>{{ number_format($facture->montant_ttc, 0, ',', ' ') }} CFA</span>
                    </div>
                </div>

                <div class="invoice-actions">
                    <a href="{{ route('client.order-detail', $facture->commande->id) }}" class="btn-action btn-view">
                        <i class="fas fa-eye"></i> Voir la commande
                    </a>
                    <button class="btn-action btn-download" onclick="downloadInvoice({{ $facture->id }})">
                        <i class="fas fa-download"></i> Télécharger
                    </button>
                </div>
            </div>
        @endforeach

        {{ $factures->links() }}
    @else
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-file-invoice"></i></div>
            <h3 class="empty-message">Aucune facture</h3>
            <p class="text-muted mb-4">Vous n'avez pas encore de facture. Passez une commande et payez-la!</p>
            <a href="{{ route('client.menu') }}" class="btn" style="background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white; border: none; padding: 12px 30px;">
                <i class="fas fa-utensils"></i> Passer une commande
            </a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function downloadInvoice(factureId) {
        // Ouvrir la page de facture PDF dans un nouvel onglet
        window.open(`/client/invoice/${factureId}/download`, '_blank');
    }
</script>
@endsection
