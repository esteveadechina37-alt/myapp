<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livraisons - Trial+</title>
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
        .card-header { background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white; border-radius: 10px 10px 0 0; border: none; }
        .table { margin-bottom: 0; }
        .table thead th { background: #f5f7fa; color: #333; font-weight: 600; border: none; padding: 15px; }
        .table tbody td { padding: 15px; border: none; border-bottom: 1px solid #eee; }
        .btn-sm { padding: 5px 10px; font-size: 0.85rem; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"><i class="fas fa-truck"></i> Livreur</div>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('livreur.dashboard') }}">
                <i class="fas fa-chart-line"></i> Tableau de Bord
            </a>
            <a class="nav-link active" href="{{ route('livreur.livraisons') }}">
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
            <h1><i class="fas fa-list"></i> Toutes les Livraisons</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Liste des Livraisons</h5>
            </div>
            <div class="card-body">
                @if($livraisons->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Commande</th>
                                    <th>Client</th>
                                    <th>Adresse</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($livraisons as $cmd)
                                    <tr>
                                        <td><strong>#{{ $cmd->id }}</strong></td>
                                        <td>{{ $cmd->client->nom ?? '-' }}</td>
                                        <td>{{ $cmd->client->adresse ?? '-' }}</td>
                                        <td>{{ number_format($cmd->montant_total_ttc, 2) }} FCFA</td>
                                        <td>
                                            <span class="badge bg-{{ $cmd->statut === 'livree' ? 'success' : 'warning' }}">
                                                {{ ucfirst($cmd->statut) }}
                                            </span>
                                        </td>
                                        <td>{{ $cmd->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($cmd->statut === 'prete')
                                                <form action="{{ route('livreur.prendre-livraison', $cmd->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button class="btn btn-sm btn-primary" title="Prendre en charge">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @elseif($cmd->statut === 'en_livraison')
                                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal{{ $cmd->id }}" title="Confirmer livraison">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $livraisons->links() }}</div>
                @else
                    <p class="text-muted">Aucune livraison</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Modals de confirmation -->
    @foreach($livraisons as $cmd)
        @if($cmd->statut === 'en_livraison')
            <div class="modal fade" id="confirmModal{{ $cmd->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirmer Livraison</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('livreur.confirmer-livraison', $cmd->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="code{{ $cmd->id }}" class="form-label">Code de Confirmation</label>
                                    <input type="text" class="form-control" id="code{{ $cmd->id }}" name="code" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-success">Confirmer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
