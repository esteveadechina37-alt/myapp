<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Cuisinier - Trial+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f5f7fa; }
        .sidebar { background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); min-height: 100vh; padding: 30px 0; position: fixed; width: 260px; left: 0; top: 0; color: white; overflow-y: auto; }
        .sidebar .logo { padding: 20px; text-align: center; font-size: 1rem; font-weight: 700; margin-bottom: 30px; border-bottom: 2px solid rgba(255,255,255,0.2); }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 15px 25px; display: block; text-decoration: none; transition: all 0.3s ease; border-left: 4px solid transparent; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,0.15); color: white; border-left-color: white; }
        .main-content { margin-left: 260px; padding: 30px; }
        .topbar { background: white; padding: 15px 30px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 5px 20px rgba(0,0,0,0.12); }
        .stat-card.red .stat-icon { background: rgba(211, 47, 47, 0.1); color: #d32f2f; }
        .stat-card.blue .stat-icon { background: rgba(25, 118, 210, 0.1); color: #1976d2; }
        .stat-card .stat-icon { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 15px; }
        .stat-card .stat-value { font-size: 1.2rem; font-weight: 700; color: #333; margin-bottom: 5px; }
        .stat-card .stat-label { color: #666; font-size: 0.7rem; }
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .card-header { background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white; border-radius: 10px 10px 0 0; border: none; padding: 15px 20px; }
        .card-header h5 { margin: 0; font-weight: 600; font-size: 1rem; }
        .alert-info { background: rgba(25, 118, 210, 0.1); border: 1px solid #1976d2; color: #1976d2; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><i class="fas fa-chef-hat"></i> Cuisinier</div>
        <nav class="nav flex-column">
            <a class="nav-link active" href="{{ route('cuisinier.dashboard') }}">
                <i class="fas fa-chart-line"></i> Tableau de Bord
            </a>
            <a class="nav-link" href="{{ route('cuisinier.commandes') }}">
                <i class="fas fa-list"></i> Commandes
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
            <h1><i class="fas fa-chef-hat"></i> Dashboard Cuisinier</h1>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-bell"></i> <strong>Priorité :</strong> {{ $commandesEnPreparation }} commandes à préparer
        </div>

        <div class="stats-grid">
            <div class="stat-card red">
                <div class="stat-icon"><i class="fas fa-fire"></i></div>
                <div class="stat-value">{{ $commandesEnPreparation }}</div>
                <div class="stat-label">En Préparation</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-check"></i></div>
                <div class="stat-value">{{ $commandesPretes }}</div>
                <div class="stat-label">Prêtes</div>
            </div>
            <div class="stat-card red">
                <div class="stat-icon"><i class="fas fa-calendar"></i></div>
                <div class="stat-value">{{ $commandesAujourdhui }}</div>
                <div class="stat-label">Aujourd'hui</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-fire"></i> Commandes en Préparation</h5>
            </div>
            <div class="card-body">
                @if($commandesEnCours->count() > 0)
                    <div class="row g-2">
                        @foreach($commandesEnCours as $cmd)
                            <div class="col-md-6">
                                <div class="p-3 border rounded" style="background: white; border-left: 4px solid #d32f2f;">
                                    <h6 style="color: #333; margin-bottom: 10px;"><i class="fas fa-utensils"></i> Commande #{{ $cmd->id }}</h6>
                                    <ul class="mb-2 small" style="list-style: none; padding: 0;">
                                        @foreach($cmd->details as $detail)
                                            <li style="padding: 5px; background: #f5f7fa; margin: 5px 0; border-radius: 4px;">{{ $detail->plat->nom }} <span style="color: #d32f2f; font-weight: 600;">x{{ $detail->quantite }}</span></li>
                                        @endforeach
                                    </ul>
                                    <small class="text-muted">{{ $cmd->created_at->format('H:i') }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Aucune commande en préparation</p>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
