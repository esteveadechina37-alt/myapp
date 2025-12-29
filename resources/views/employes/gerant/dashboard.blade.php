<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Gérant - Trial+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
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
            font-size: 1rem;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .topbar h1 { margin: 0; color: #333; font-size: 1.8rem; font-weight: 700; }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 5px solid #d32f2f;
        }
        
        .stat-card .icon {
            font-size: 2rem;
            color: #d32f2f;
            margin-bottom: 10px;
        }
        
        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
        }
        
        .stat-card .label {
            color: #999;
            font-size: 0.9rem;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            font-weight: 600;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.4);
        }
        
        @media (max-width: 768px) {
            .sidebar { width: 100%; min-height: auto; position: relative; margin-bottom: 20px; }
            .main-content { margin-left: 0; padding: 15px; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-store"></i> Gérant
        </div>
        <nav class="nav flex-column">
            <a class="nav-link active" href="{{ route('gerant.dashboard') }}">
                <i class="fas fa-chart-line"></i> Tableau de Bord
            </a>
            <a class="nav-link" href="{{ route('gerant.menu') }}">
                <i class="fas fa-utensils"></i> Gérer Menu
            </a>
            <a class="nav-link" href="{{ route('gerant.stocks') }}">
                <i class="fas fa-boxes"></i> Gérer Stocks
            </a>
            <a class="nav-link" href="{{ route('gerant.statistiques') }}">
                <i class="fas fa-chart-bar"></i> Statistiques
            </a>
            <a class="nav-link" href="{{ route('gerant.paiements') }}">
                <i class="fas fa-credit-card"></i> Encaisser
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
        <div class="topbar">
            <h1><i class="fas fa-store"></i> Tableau de Bord Gérant</h1>
            <span>Bienvenue, {{ auth()->user()->name }}</span>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="value">{{ $totalCommandes }}</div>
                <div class="label">Total Commandes</div>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="fas fa-spinner"></i></div>
                <div class="value">{{ $commandesPendantes }}</div>
                <div class="label">Commandes en Cours</div>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="value">{{ number_format($totalVentes, 0) }} FCFA</div>
                <div class="label">Total Ventes</div>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="fas fa-wallet"></i></div>
                <div class="value">{{ number_format($revenus['today'], 0) }} FCFA</div>
                <div class="label">Revenus Aujourd'hui</div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list"></i> Commandes Récentes</h5>
            </div>
            <div class="card-body">
                @if($recentCommandes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr style="background: #f5f7fa;">
                                    <th>Commande</th>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentCommandes as $cmd)
                                    <tr>
                                        <td><strong>#{{ $cmd->id }}</strong></td>
                                        <td>{{ $cmd->client->nom ?? '-' }}</td>
                                        <td>{{ number_format($cmd->montant_total_ttc, 2) }} FCFA</td>
                                        <td>
                                            <span class="badge bg-{{ $cmd->statut === 'livree' ? 'success' : 'warning' }}">
                                                {{ ucfirst($cmd->statut) }}
                                            </span>
                                        </td>
                                        <td>{{ $cmd->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Aucune commande trouvée</p>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
