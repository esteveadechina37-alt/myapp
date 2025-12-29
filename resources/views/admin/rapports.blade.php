<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f5f7fa; margin: 0; padding: 0; }
        
        .sidebar {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            min-height: 100vh;
            padding: 30px 0;
            position: fixed;
            width: 260px;
            left: 0;
            top: 0;
            color: white;
            overflow-y: auto;
        }
        
        .sidebar .logo {
            padding: 20px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 30px;
            border-bottom: 2px solid rgba(255,255,255,0.2);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 15px 25px;
            display: block;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left-color: white;
        }
        
        .main-content {
            margin-left: 260px;
            padding: 30px;
        }
        
        .topbar {
            background: white;
            padding: 15px 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .topbar h1 { margin: 0; color: #333; font-size: 1.8rem; font-weight: 700; }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 5px solid;
            text-align: center;
        }
        
        .stat-card.revenue { border-left-color: #4caf50; }
        .stat-card.orders { border-left-color: #2196f3; }
        .stat-card.customers { border-left-color: #ff9800; }
        .stat-card.average { border-left-color: #9c27b0; }
        
        .stat-card h3 { margin: 0 0 10px 0; color: #999; font-size: 0.9rem; font-weight: 500; }
        .stat-card .value { font-size: 2rem; font-weight: 700; color: #333; }
        .stat-card .icon { font-size: 2.5rem; margin-bottom: 15px; }
        
        .stat-card.revenue .icon { color: #4caf50; }
        .stat-card.orders .icon { color: #2196f3; }
        .stat-card.customers .icon { color: #ff9800; }
        .stat-card.average .icon { color: #9c27b0; }
        
        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .chart-container h3 { margin: 0 0 20px 0; color: #333; font-weight: 700; }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .table-responsive {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 20px;
            margin-top: 20px;
        }
        
        .table { margin: 0; }
        .table thead th {
            background: #f5f7fa;
            color: #333;
            font-weight: 600;
            border: none;
            padding: 15px;
        }
        
        .table tbody td {
            padding: 15px;
            border: none;
            border-bottom: 1px solid #eee;
        }
        
        @media (max-width: 768px) {
            .sidebar { width: 100%; min-height: auto; padding: 15px 0; position: relative; margin-bottom: 20px; }
            .main-content { margin-left: 0; padding: 15px; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-utensils"></i> Admin
        </div>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-chart-line"></i> Tableau de Bord
            </a>
            <a class="nav-link" href="{{ route('admin.commandes') }}">
                <i class="fas fa-shopping-bag"></i> Commandes
            </a>
            <a class="nav-link" href="{{ route('admin.plats') }}">
                <i class="fas fa-list"></i> Plats
            </a>
            <a class="nav-link" href="{{ route('admin.categories') }}">
                <i class="fas fa-sitemap"></i> Catégories
            </a>
            <a class="nav-link" href="{{ route('admin.users') }}">
                <i class="fas fa-users"></i> Clients
            </a>
            <a class="nav-link" href="{{ route('admin.employes') }}">
                <i class="fas fa-users-cog"></i> Employés
            </a>
            <a class="nav-link active" href="{{ route('admin.rapports') }}">
                <i class="fas fa-chart-bar"></i> Rapports
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

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h1><i class="fas fa-chart-bar"></i> Rapports & Statistiques</h1>
        </div>

        <!-- Statistiques Clés -->
        <div class="stats-grid">
            <div class="stat-card revenue">
                <i class="fas fa-coins icon"></i>
                <h3>Chiffre d'Affaires</h3>
                <div class="value">{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }} CFA</div>
            </div>
            <div class="stat-card orders">
                <i class="fas fa-shopping-bag icon"></i>
                <h3>Commandes (7j)</h3>
                <div class="value">{{ $totalOrders ?? 0 }}</div>
            </div>
            <div class="stat-card customers">
                <i class="fas fa-users icon"></i>
                <h3>Clients Actifs</h3>
                <div class="value">{{ $totalCustomers ?? 0 }}</div>
            </div>
            <div class="stat-card average">
                <i class="fas fa-calculator icon"></i>
                <h3>Panier Moyen</h3>
                <div class="value">{{ number_format($averageOrder ?? 0, 0, ',', ' ') }} CFA</div>
            </div>
        </div>

        <!-- Graphiques -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
            <!-- Commandes par jour -->
            <div class="chart-container">
                <h3><i class="fas fa-calendar-alt"></i> Commandes (7 derniers jours)</h3>
                <canvas id="ordersChart"></canvas>
            </div>

            <!-- Ventes par catégorie -->
            <div class="chart-container">
                <h3><i class="fas fa-pie-chart"></i> Ventes par Catégorie</h3>
                <canvas id="categoriesChart"></canvas>
            </div>
        </div>

        <!-- Tableau Commandes Détaillées -->
        <div class="table-responsive">
            <h3><i class="fas fa-list"></i> Dernières Commandes (7 jours)</h3>
            @if(isset($recentOrders) && $recentOrders->count() > 0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Commande</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->numero }}</td>
                                <td>{{ $order->client->nom ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->heure_commande)->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format($order->montant_total_ttc, 0, ',', ' ') }} CFA</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ ucfirst(str_replace('_', ' ', $order->statut ?? 'En attente')) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align: center; color: #999;">Aucune commande sur les 7 derniers jours</p>
            @endif
        </div>
    </div>

    <script>
        // Graphique Commandes par jour
        const ordersCtx = document.getElementById('ordersChart')?.getContext('2d');
        if (ordersCtx) {
            new Chart(ordersCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(isset($ordersDates) ? $ordersDates : []) !!},
                    datasets: [{
                        label: 'Commandes',
                        data: {!! json_encode(isset($ordersData) ? $ordersData : []) !!},
                        borderColor: '#1976d2',
                        backgroundColor: 'rgba(25, 118, 210, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: true } }
                }
            });
        }

        // Graphique Catégories
        const categoriesCtx = document.getElementById('categoriesChart')?.getContext('2d');
        if (categoriesCtx) {
            new Chart(categoriesCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(isset($categoriesLabels) ? $categoriesLabels : []) !!},
                    datasets: [{
                        data: {!! json_encode(isset($categoriesData) ? $categoriesData : []) !!},
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#FF6384', '#C9CBCF'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
