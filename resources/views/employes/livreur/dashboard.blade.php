<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Livreur - Trial+</title>
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
        .card-header h5 { margin: 0; font-weight: 600; font-size: 1.1rem; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><i class="fas fa-truck"></i> Livreur</div>
        <nav class="nav flex-column">
            <a class="nav-link active" href="{{ route('livreur.dashboard') }}">
                <i class="fas fa-chart-line"></i> Tableau de Bord
            </a>
            <a class="nav-link" href="{{ route('livreur.livraisons') }}">
                <i class="fas fa-list"></i> Livraisons
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
            <h1><i class="fas fa-truck"></i> Dashboard Livreur</h1>
        </div>

        <div class="stats-grid">
            <div class="stat-card red">
                <div class="stat-icon"><i class="fas fa-spinner"></i></div>
                <div class="stat-value">{{ $livraisonsEnCours }}</div>
                <div class="stat-label">En Cours</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-value">{{ $livraisonsLivrees }}</div>
                <div class="stat-label">Livrées</div>
            </div>
            <div class="stat-card red">
                <div class="stat-icon"><i class="fas fa-calendar"></i></div>
                <div class="stat-value">{{ $livraisonsAujourdhui }}</div>
                <div class="stat-label">Aujourd'hui</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list"></i> Livraisons à Faire</h5>
            </div>
            <div class="card-body">
                @if($livraisons->count() > 0)
                    <div class="list-group">
                        @foreach($livraisons as $cmd)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Commande #{{ $cmd->id }}</h6>
                                    <small class="text-muted">Client: {{ $cmd->client->nom ?? 'N/A' }}</small><br>
                                    <small class="text-muted">Adresse: {{ $cmd->client->adresse ?? '-' }}</small>
                                </div>
                                <span class="badge bg-{{ $cmd->statut === 'en_livraison' ? 'warning' : 'info' }}">{{ ucfirst($cmd->statut) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Aucune livraison à faire</p>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
