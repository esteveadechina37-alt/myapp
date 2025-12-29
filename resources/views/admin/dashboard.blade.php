<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Trial+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></link>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        /* Sidebar */
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

        .sidebar .nav-item {
            margin: 0;
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

        /* Main Content */
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

        .topbar h1 {
            margin: 0;
            color: #333;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .topbar .user-info .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Cards Statistiques */
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }

        .stat-card .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .stat-card.red .stat-icon {
            background: rgba(211, 47, 47, 0.1);
            color: #d32f2f;
        }

        .stat-card.blue .stat-icon {
            background: rgba(25, 118, 210, 0.1);
            color: #1976d2;
        }

        .stat-card.green .stat-icon {
            background: rgba(67, 160, 71, 0.1);
            color: #43a047;
        }

        .stat-card .stat-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-card .stat-label {
            color: #666;
            font-size: 0.7rem;
        }

        /* Table */
        .table-responsive {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 20px;
        }

        .table {
            margin: 0;
        }

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

        .badge {
            padding: 5px 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-success {
            background: #43a047;
            color: white;
        }

        .badge-warning {
            background: #ffa726;
            color: white;
        }

        .badge-info {
            background: #1976d2;
            color: white;
        }

        .badge-danger {
            background: #d32f2f;
            color: white;
        }

        /* Buttons */
        .btn-admin {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.4);
            color: white;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 25px;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
                padding: 15px 0;
                position: relative;
                margin-bottom: 20px;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .topbar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .topbar h1 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-utensils"></i> Trial+
        </div>
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-chart-line"></i> Tableau de Bord
            </a>
            <a class="nav-link {{ request()->routeIs('admin.commandes') ? 'active' : '' }}" href="{{ route('admin.commandes') }}">
                <i class="fas fa-shopping-bag"></i> Commandes
            </a>
            <a class="nav-link {{ request()->routeIs('admin.plats') ? 'active' : '' }}" href="{{ route('admin.plats') }}">
                <i class="fas fa-list"></i> Plats
            </a>
            <a class="nav-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}" href="{{ route('admin.categories') }}">
                <i class="fas fa-sitemap"></i> Catégories
            </a>
            <a class="nav-link" href="{{ route('admin.users') }}">
                <i class="fas fa-users"></i> Clients
            </a>
            <a class="nav-link" href="{{ route('admin.employes') }}">
                <i class="fas fa-users-cog"></i> Employés
            </a>
            <a class="nav-link {{ request()->routeIs('admin.rapports') ? 'active' : '' }}" href="{{ route('admin.rapports') }}">
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
            <h1><i class="fas fa-chart-line"></i> Tableau de Bord</h1>
            <div class="user-info">
                <span>{{ auth()->user()->name }}</span>
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            </div>
        </div>

        <!-- Messages Flash -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistiques Globales -->
        <div class="section-title"><i class="fas fa-chart-pie"></i> Statistiques Globales</div>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card red">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-value">{{ $stats['total_clients'] }}</div>
                    <div class="stat-label">Clients</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card blue">
                    <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
                    <div class="stat-value">{{ $stats['total_commandes'] }}</div>
                    <div class="stat-label">Commandes</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card green">
                    <div class="stat-icon"><i class="fas fa-utensils"></i></div>
                    <div class="stat-value">{{ $stats['total_plats'] }}</div>
                    <div class="stat-label">Plats</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card red">
                    <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
                    <div class="stat-value">{{ $stats['total_categories'] }}</div>
                    <div class="stat-label">Catégories</div>
                </div>
            </div>
        </div>

        <!-- Statuts Commandes -->
        <div class="section-title"><i class="fas fa-tasks"></i> Statuts des Commandes</div>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-value" style="color: #ffa726;">{{ $stats['commandes_en_attente'] }}</div>
                    <div class="stat-label">En attente</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-value" style="color: #1976d2;">{{ $stats['commandes_confirmees'] }}</div>
                    <div class="stat-label">Confirmées</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-value" style="color: #d32f2f;">{{ $stats['commandes_preparees'] }}</div>
                    <div class="stat-label">Préparées</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="stat-value" style="color: #43a047;">{{ $stats['commandes_livrees'] }}</div>
                    <div class="stat-label">Livrées</div>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="section-title"><i class="fas fa-money-bill"></i> Chiffre d'Affaires</div>
        <div class="row">
            <div class="col-md-12">
                <div class="stat-card" style="text-align: center;">
                    <div class="stat-value" style="font-size: 3rem; color: #43a047;">{{ number_format($stats['revenue_total'], 0, ',', ' ') }} CFA</div>
                    <div class="stat-label">Revenu Total</div>
                </div>
            </div>
        </div>

        <!-- Dernières Commandes -->
        <div class="section-title"><i class="fas fa-history"></i> Dernières Commandes</div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dernieres_commandes as $commande)
                        <tr>
                            <td>#{{ $commande->id }}</td>
                            <td>Client {{ $commande->client_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($commande->heure_commande)->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $statuts = [
                                        'en_attente' => 'warning',
                                        'confirmee' => 'info',
                                        'preparee' => 'danger',
                                        'livree' => 'success',
                                        'annulee' => 'dark'
                                    ];
                                    $badge = $statuts[$commande->statut] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ ucfirst(str_replace('_', ' ', $commande->statut)) }}</span>
                            </td>
                            <td>{{ number_format($commande->montant_total ?? 0, 0, ',', ' ') }} CFA</td>
                            <td>
                                <a href="{{ route('admin.commandes.show', $commande->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Aucune commande
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Plats Populaires -->
        <div class="section-title"><i class="fas fa-star"></i> Plats les Plus Populaires</div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nom du Plat</th>
                        <th>Nombre de Commandes</th>
                        <th>Quantité Vendue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plats_populaires as $plat)
                        <tr>
                            <td>{{ $plat->nom }}</td>
                            <td>
                                <span class="badge badge-info">{{ $plat->total }}</span>
                            </td>
                            <td>{{ $plat->quantite_totale ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Aucune donnée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Utilisateurs Récents -->
        <div class="section-title"><i class="fas fa-user-check"></i> Utilisateurs Récents</div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Date d'Inscription</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($utilisateurs_recents as $utilisateur)
                        <tr>
                            <td>{{ $utilisateur->name }}</td>
                            <td>{{ $utilisateur->email }}</td>
                            <td>{{ $utilisateur->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Aucun utilisateur
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <br><br>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
</body>
</html>
