<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiements - Trial+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
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
        .table { margin-bottom: 0; }
        .table thead th { background: #f5f7fa; color: #333; font-weight: 600; border: none; padding: 15px; }
        .table tbody td { padding: 15px; border: none; border-bottom: 1px solid #eee; }
        .stat-box { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); text-align: center; }
        .stat-value { font-size: 2rem; font-weight: 700; color: #d32f2f; }
        .stat-label { color: #666; font-size: 0.9rem; }
        .btn-sm { padding: 5px 10px; font-size: 0.85rem; }
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
            <a class="nav-link" href="{{ route('gerant.statistiques') }}">
                <i class="fas fa-chart-bar"></i> Statistiques
            </a>
            <a class="nav-link active" href="{{ route('gerant.paiements') }}">
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
            <h1><i class="fas fa-money-bill-wave"></i> Encaissement des Paiements</h1>
        </div>

        <!-- Statistiques Paiements -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-value">{{ $commandes->count() }}</div>
                    <div class="stat-label">Paiements en Attente</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-value">{{ number_format($commandes->sum('montant_total_ttc'), 0) }}</div>
                    <div class="stat-label">Montant Total (FCFA)</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-value">0</div>
                    <div class="stat-label">Paiements en Retard</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-value">100%</div>
                    <div class="stat-label">Taux Traitement</div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <input type="date" class="form-control" placeholder="Date début">
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" placeholder="Date fin">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select">
                            <option>-- Tous les Statuts --</option>
                            <option>Payé</option>
                            <option>En Attente</option>
                            <option>Retard</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100"><i class="fas fa-filter"></i> Filtrer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paiements en Attente -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock"></i> Paiements en Attente ({{ $commandes->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Commande</th>
                                <th>Client</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($commandes as $cmd)
                            <tr>
                                <td><strong>#COM-{{ $cmd->id }}</strong></td>
                                <td>{{ $cmd->client->nom ?? 'Client Inconnu' }}</td>
                                <td>{{ number_format($cmd->montant_total_ttc, 0) }} FCFA</td>
                                <td><span class="badge bg-warning">En Attente</span></td>
                                <td>{{ $cmd->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#paiementModal{{ $cmd->id }}" title="Encaisser">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Aucun paiement en attente</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $commandes->links() }}</div>
            </div>
        </div>

        <!-- Paiements Effectués -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-check-circle"></i> Paiements Effectués (Derniers 10)</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Affichage des paiements effectués à venir</p>
            </div>
        </div>
    </div>

    <!-- Modals pour les paiements -->
    @foreach($commandes as $cmd)
    <div class="modal fade" id="paiementModal{{ $cmd->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Encaisser Paiement - Commande #{{ $cmd->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('gerant.mark-paid', $cmd->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Montant</label>
                            <input type="text" class="form-control" value="{{ number_format($cmd->montant_total_ttc, 0) }} FCFA" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Moyen de Paiement</label>
                            <select name="moyen_paiement" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="especes">Espèces</option>
                                <option value="carte">Carte Bancaire</option>
                                <option value="cheque">Chèque</option>
                                <option value="virement">Virement</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Confirmer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
