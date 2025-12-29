@extends('layouts.app')

@section('title', 'Dashboard Gérant')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="mb-5">
            <h1 class="h3 fw-bold">
                <i class="fas fa-chart-bar" style="color: #1976d2;"></i> Dashboard Gérant
            </h1>
            <p class="text-muted">Gestion opérationnelle du restaurant</p>
        </div>

        <!-- Filtres Date -->
        <div class="row mb-4">
            <div class="col-md-4">
                <label class="form-label"><small>Période</small></label>
                <select id="filterPeriode" class="form-select">
                    <option value="today">Aujourd'hui</option>
                    <option value="week">Cette semaine</option>
                    <option value="month" selected>Ce mois</option>
                    <option value="all">Toute l'année</option>
                </select>
            </div>
        </div>

        <!-- KPIs -->
        <div class="row mb-5">
            <div class="col-md-4 mb-3">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%); color: white;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="kpi-content">
                        <h6>Commandes</h6>
                        <p class="kpi-value">{{ $total_commandes ?? 0 }}</p>
                        <small class="text-success">+15% vs mois dernier</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white;">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="kpi-content">
                        <h6>Chiffre d'Affaires</h6>
                        <p class="kpi-value">{{ number_format($ca_mois ?? 0, 0, ',', ' ') }} €</p>
                        <small class="text-success">+8% vs mois dernier</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white;">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="kpi-content">
                        <h6>Ticket Moyen</h6>
                        <p class="kpi-value">{{ number_format($ticket_moyen ?? 0, 0, ',', ' ') }} €</p>
                        <small class="text-muted">Par commande</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Graphique CA Heure -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%); color: white;">
                        <h6 class="mb-0"><i class="fas fa-chart-area"></i> CA par Heure</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="caHeureChart" height="80"></canvas>
                    </div>
                </div>
            </div>

            <!-- Plats Populaires -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white;">
                        <h6 class="mb-0"><i class="fas fa-star"></i> Top Plats</h6>
                    </div>
                    <div class="card-body">
                        <div id="topPlats">
                            <!-- Chargé par JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Gestion Stock -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #f57c00 0%, #e65100 100%); color: white;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-warehouse"></i> Gestion Stock</h6>
                            <a href="{{ route('gerant.stock') }}" class="btn btn-sm btn-light">Détails</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th>Ingrédient</th>
                                        <th>Quantité</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Tomates</strong></td>
                                        <td>25 kg</td>
                                        <td><span class="badge bg-success">OK</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Fromage</strong></td>
                                        <td>8 kg</td>
                                        <td><span class="badge bg-warning">Faible</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Farine</strong></td>
                                        <td>3 kg</td>
                                        <td><span class="badge bg-danger">Critique</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestion Personnel -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #7b1fa2 0%, #4a148c 100%); color: white;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-users"></i> Personnel en Ligne</h6>
                            <span class="badge bg-light text-dark">5 connectés</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Alice Dupont</h6>
                                    <small class="text-muted">Serveur</small>
                                </div>
                                <span class="badge bg-success">En service</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Jean Martin</h6>
                                    <small class="text-muted">Cuisinier</small>
                                </div>
                                <span class="badge bg-success">En service</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Marie Bernard</h6>
                                    <small class="text-muted">Livreur</small>
                                </div>
                                <span class="badge bg-success">En service</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commandes Récentes -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-clock"></i> Commandes en Cours</h6>
                    <a href="{{ route('commandes.list') }}" class="btn btn-sm btn-light">Voir tous</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row" id="commandesEnCours">
                    <!-- Chargé par JS -->
                </div>
            </div>
        </div>
    </div>

    <style>
        .kpi-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .kpi-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .kpi-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .kpi-content h6 {
            font-size: 0.9rem;
            color: #666;
            margin: 0;
        }

        .kpi-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
            margin: 5px 0 0 0;
        }

        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .card-header {
            color: white;
            border: none;
            padding: 15px 20px;
        }

        .bg-gradient {
            background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%);
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .list-group-item {
            background: transparent;
            padding: 15px 0;
            border-color: #eee;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Graphique CA Heure
        const caHeureCtx = document.getElementById('caHeureChart').getContext('2d');
        new Chart(caHeureCtx, {
            type: 'bar',
            data: {
                labels: ['11h', '12h', '13h', '14h', '15h', '18h', '19h', '20h', '21h'],
                datasets: [{
                    label: 'CA (€)',
                    data: [280, 450, 520, 480, 320, 250, 580, 720, 650],
                    backgroundColor: 'linear-gradient(135deg, #1976d2 0%, #0d47a1 100%)',
                    borderColor: '#0d47a1',
                    borderWidth: 0,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { callback: v => v + '€' }
                    }
                }
            }
        });

        // Charger commandes en cours
        function loadCommandesEnCours() {
            fetch('/api/commandes?statut=confirmee&statut=en_preparation')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('commandesEnCours');
                    if (data.commandes.length === 0) {
                        container.innerHTML = '<div class="col-12 text-center text-muted">Aucune commande en cours</div>';
                        return;
                    }
                    container.innerHTML = data.commandes.slice(0, 6).map(cmd => `
                        <div class="col-md-6 mb-3">
                            <div class="alert alert-info mb-0">
                                <h6 class="mb-2">Cmd #${cmd.numero_commande}</h6>
                                <div class="d-flex justify-content-between">
                                    <span><strong>${cmd.montant_ttc}€</strong></span>
                                    <span class="badge bg-warning">${cmd.statut === 'en_preparation' ? 'En préparation' : 'Confirmée'}</span>
                                </div>
                            </div>
                        </div>
                    `).join('');
                });
        }

        document.addEventListener('DOMContentLoaded', loadCommandesEnCours);
        document.getElementById('filterPeriode').addEventListener('change', loadCommandesEnCours);
    </script>
@endsection
