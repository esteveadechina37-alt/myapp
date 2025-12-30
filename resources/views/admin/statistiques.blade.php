@extends('layouts.app')

@section('title', 'Statistiques Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h1 class="h3 fw-bold">
            <i class="fas fa-chart-line" style="color: #d32f2f;"></i> Statistiques Commandes
        </h1>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Commandes Totales</h6>
                    <h3 class="text-primary mb-0">{{ $stats['total_commandes'] ?? 0 }}</h3>
                    <small class="text-muted">{{ $period }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Montant Total</h6>
                    <h3 class="text-success mb-0">{{ $stats['montant_total'] ?? 0 }}€</h3>
                    <small class="text-muted">Chiffre d'affaires</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Montant Moyen</h6>
                    <h3 class="text-info mb-0">{{ round($stats['montant_moyen'] ?? 0, 2) }}€</h3>
                    <small class="text-muted">Par commande</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Taux Complétude</h6>
                    <h3 class="text-warning mb-0">
                        @if($stats['total_commandes'] > 0)
                            {{ round(($stats['commandes_payees'] / $stats['total_commandes']) * 100) }}%
                        @else
                            0%
                        @endif
                    </h3>
                    <small class="text-muted">Commandes payées</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">Distribution Statuts</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartStatuts"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">Revenus par Jour</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartRevenus"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Données détaillées -->
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0">Résumé Statuts</h6>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Payées</span>
                        <span class="badge bg-success">{{ $stats['commandes_payees'] ?? 0 }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Annulées</span>
                        <span class="badge bg-danger">{{ $stats['commandes_annulees'] ?? 0 }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>En cours</span>
                        <span class="badge bg-warning">{{ $stats['commandes_en_cours'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0">Performance</h6>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Temps moyen préparation</span>
                        <strong>{{ $stats['temps_moyen_preparation'] ?? 0 }} min</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Satisfaction client</span>
                        <strong>4.5/5 ⭐</strong>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span>Plat le plus commandé</span>
                        <strong>{{ $platLePlusCdmande ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart Statuts
const ctx1 = document.getElementById('chartStatuts').getContext('2d');
new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: ['Payées', 'Annulées', 'En cours'],
        datasets: [{
            data: [{{ $stats['commandes_payees'] ?? 0 }}, {{ $stats['commandes_annulees'] ?? 0 }}, {{ ($stats['total_commandes'] ?? 0) - ($stats['commandes_payees'] ?? 0) - ($stats['commandes_annulees'] ?? 0) }}],
            backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
            borderColor: ['#fff', '#fff', '#fff'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// Chart Revenus
const ctx2 = document.getElementById('chartRevenus').getContext('2d');
new Chart(ctx2, {
    type: 'line',
    data: {
        labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
        datasets: [{
            label: 'Revenus (€)',
            data: [450, 520, 480, 550, 620, 800, 750],
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true, position: 'top' }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection
