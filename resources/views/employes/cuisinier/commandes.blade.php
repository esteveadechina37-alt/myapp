<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes Cuisinier - Trial+</title>
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
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .card-header { background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white; border-radius: 10px 10px 0 0; border: none; padding: 15px 20px; }
        .card-header h5 { margin: 0; font-weight: 600; font-size: 1rem; }
        .commande-card { background: white; border-left: 4px solid #d32f2f; padding: 18px; margin-bottom: 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: all 0.3s ease; }
        .commande-card:hover { box-shadow: 0 4px 15px rgba(211, 47, 47, 0.2); }
        .commande-card h5 { color: #333; font-weight: 600; }
        .plat-item { padding: 10px; background: #f5f7fa; border-left: 3px solid #1976d2; border-radius: 4px; margin: 8px 0; font-size: 0.95rem; }
        .btn-sm { padding: 8px 12px; font-size: 0.85rem; font-weight: 600; }
        .alert-info { background: rgba(25, 118, 210, 0.1); border: 1px solid #1976d2; color: #1976d2; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><i class="fas fa-chef-hat"></i> Cuisinier</div>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('cuisinier.dashboard') }}">
                <i class="fas fa-chart-line"></i> Tableau de Bord
            </a>
            <a class="nav-link active" href="{{ route('cuisinier.commandes') }}">
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
            <h1><i class="fas fa-list"></i> Commandes à Préparer</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-fire"></i> Commandes en Attente</h5>
            </div>
            <div class="card-body">
        @if($commandes->count() > 0)
            @foreach($commandes as $cmd)
                <div class="commande-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-2">Commande #{{ $cmd->id }}</h5>
                            <small class="text-muted">Depuis {{ $cmd->created_at->format('H:i') }}</small>
                        </div>
                        <form action="{{ route('cuisinier.marquer-prete', $cmd->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button class="btn btn-sm btn-success" title="Marquer comme prête">
                                <i class="fas fa-check-circle"></i> Prête
                            </button>
                        </form>
                    </div>

                    <h6 class="mt-3 mb-2"><i class="fas fa-utensils"></i> Plats:</h6>
                    <ul class="list-unstyled">
                        @foreach($cmd->details as $detail)
                            <li class="plat-item">
                                <strong>{{ $detail->plat->nom }}</strong> x{{ $detail->quantite }}
                                @if($detail->notes)
                                    <br><small>Notes: {{ $detail->notes }}</small>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">
                <i class="fas fa-check-circle"></i> Aucune commande à préparer
            </div>
        @endif
            </div>
        </div>

        <div class="mt-4">{{ $commandes->links() }}</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
