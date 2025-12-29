<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Trial+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f5f7fa; }
        .sidebar { background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); min-height: 100vh; padding: 30px 0; position: fixed; width: 260px; left: 0; top: 0; color: white; overflow-y: auto; }
        .sidebar .logo { padding: 20px; text-align: center; font-size: 1.5rem; font-weight: 700; margin-bottom: 30px; border-bottom: 2px solid rgba(255,255,255,0.2); }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 15px 25px; display: block; text-decoration: none; transition: all 0.3s ease; border-left: 4px solid transparent; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,0.15); color: white; border-left-color: white; }
        .main-content { margin-left: 260px; padding: 30px; }
        .topbar { background: white; padding: 15px 30px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .card-header {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            border: none;
        }
        .stat-box { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); text-align: center; }
        .stat-value { font-size: 2rem; font-weight: 700; color: #d32f2f; }
        .stat-label { color: #666; font-size: 0.9rem; }
        
        .btn-primary {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            border: none;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><i class="fas fa-utensils"></i> Gérant</div>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('gerant.dashboard') }}">
                <i class="fas fa-chart-line"></i> Tableau de Bord
            </a>
            <a class="nav-link" href="{{ route('gerant.menu') }}">
                <i class="fas fa-book"></i> Menu
            </a>
            <a class="nav-link" href="{{ route('gerant.stocks') }}">
                <i class="fas fa-cubes"></i> Stocks
            </a>
            <a class="nav-link active" href="{{ route('gerant.statistiques') }}">
                <i class="fas fa-chart-bar"></i> Statistiques
            </a>
            <a class="nav-link" href="{{ route('gerant.paiements') }}">
                <i class="fas fa-money-bill-wave"></i> Paiements
            </a>
            <hr style="opacity: 0.2; margin: 20px 0;">
            <form action="{{ route('logout') }}" method="POST" style="padding: 0 25px;">
                @csrf
                <button type="submit" class="nav-link" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </button>
            </form>
        </nav>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1><i class="fas fa-chart-bar"></i> Statistiques & Analyses</h1>
        </div>

        <!-- Statistiques Principales -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-value">{{ $totalCommandes }}</div>
                    <div class="stat-label">Total Commandes</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-value">{{ number_format($totalVentes, 0) }}</div>
                    <div class="stat-label">Revenu Total (FCFA)</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-value">{{ $commandesLivrees }}</div>
                    <div class="stat-label">Commandes Livrées</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-value">{{ $totalClients }}</div>
                    <div class="stat-label">Nombre de Clients</div>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Revenu par Jour (7 derniers jours)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Commandes par Type</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="typeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plats Populaires -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Top 5 Plats Populaires</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Plat</th>
                                <th>Ventes</th>
                                <th>Popularité</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($top5Plats as $plat)
                            <tr>
                                <td><strong>{{ $plat->nom }}</strong></td>
                                <td>{{ $plat->lignes_commandes_count }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: {{ min(100, ($plat->lignes_commandes_count / ($top5Plats->first()->lignes_commandes_count ?? 1)) * 100) }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aucun plat</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Horaires de Pointe -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Commandes par Heure</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="hoursChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Clients par Jour de Semaine</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="weekChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueByDay['labels']) !!},
                datasets: [{
                    label: 'Revenu (FCFA)',
                    data: {!! json_encode($revenueByDay['data']) !!},
                    borderColor: '#1976d2',
                    backgroundColor: 'rgba(25, 118, 210, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });

        // Type Chart
        const typeCtx = document.getElementById('typeChart').getContext('2d');
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($ordersByType['labels']) !!},
                datasets: [{
                    data: {!! json_encode($ordersByType['data']) !!},
                    backgroundColor: ['#4caf50', '#ff9800', '#1976d2']
                }]
            },
            options: { responsive: true }
        });

        // Hours Chart
        const hoursCtx = document.getElementById('hoursChart').getContext('2d');
        new Chart(hoursCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($ordersByHour['labels']) !!},
                datasets: [{
                    label: 'Commandes',
                    data: {!! json_encode($ordersByHour['data']) !!},
                    backgroundColor: '#ff9800'
                }]
            },
            options: { responsive: true, indexAxis: 'x' }
        });

        // Week Chart
        const weekCtx = document.getElementById('weekChart').getContext('2d');
        new Chart(weekCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($clientsByDay['labels']) !!},
                datasets: [{
                    label: 'Nombre de Commandes',
                    data: {!! json_encode($clientsByDay['data']) !!},
                    backgroundColor: '#4caf50'
                }]
            },
            options: { responsive: true }
        });
    </script>
</body>
</html>
