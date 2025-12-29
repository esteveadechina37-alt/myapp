<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer Menu - Trial+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f5f7fa; }
        
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .card-header {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            border: none;
        }
        
        .table {
            margin-bottom: 0;
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
        
        .btn-primary {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            border: none;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><i class="fas fa-store"></i> Gérant</div>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('gerant.dashboard') }}">
                <i class="fas fa-chart-line"></i> Tableau de Bord
            </a>
            <a class="nav-link active" href="{{ route('gerant.menu') }}">
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

    <div class="main-content">
        <div class="topbar">
            <h1><i class="fas fa-utensils"></i> Gérer Menu</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Liste des Plats</h5>
            </div>
            <div class="card-body">
                @if($plats->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Plat</th>
                                    <th>Catégorie</th>
                                    <th>Prix</th>
                                    <th>Disponibilité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plats as $plat)
                                    <tr>
                                        <td><strong>{{ $plat->nom }}</strong></td>
                                        <td>{{ $plat->categorie->nom ?? '-' }}</td>
                                        <td>{{ number_format($plat->prix, 2) }} FCFA</td>
                                        <td>
                                            <span class="badge bg-{{ $plat->est_disponible ? 'success' : 'danger' }}">
                                                {{ $plat->est_disponible ? 'Disponible' : 'Indisponible' }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $plats->links() }}</div>
                @else
                    <p class="text-muted">Aucun plat trouvé</p>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
