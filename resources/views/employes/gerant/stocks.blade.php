<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocks - Trial+</title>
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
        .btn-sm { padding: 5px 10px; font-size: 0.85rem; }
        .btn-primary {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            border: none;
        }
        
        .badge-low { background: #f44336; }
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
            <a class="nav-link active" href="{{ route('gerant.stocks') }}">
                <i class="fas fa-cubes"></i> Stocks
            </a>
            <a class="nav-link" href="{{ route('gerant.statistiques') }}">
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
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-cubes"></i> Gestion des Stocks</h1>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStockModal">
                    <i class="fas fa-plus"></i> Ajouter Stock
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Inventaire des Plats</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Plat</th>
                                <th>Catégorie</th>
                                <th>Quantité Stock</th>
                                <th>Quantité Min.</th>
                                <th>Statut</th>
                                <th>Dernier Restockage</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stocks as $ingredient)
                            <tr>
                                <td><strong>{{ $ingredient->nom }}</strong></td>
                                <td>{{ $ingredient->unite_mesure }}</td>
                                <td>
                                    <span class="badge {{ $ingredient->stock_actuel > $ingredient->seuil_alerte ? 'bg-success' : 'bg-danger' }}">
                                        {{ $ingredient->stock_actuel }}
                                    </span>
                                </td>
                                <td>{{ $ingredient->seuil_alerte }}</td>
                                <td>
                                    @if($ingredient->stock_actuel > $ingredient->seuil_alerte)
                                        <span class="badge bg-success">Disponible</span>
                                    @else
                                        <span class="badge bg-danger">Stock Bas</span>
                                    @endif
                                </td>
                                <td>{{ $ingredient->updated_at ? $ingredient->updated_at->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" title="Modifier"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#restockModal" title="Restockage"><i class="fas fa-plus-circle"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Aucun ingrédient trouvé</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $stocks->links() }}</div>
            </div>
        </div>

        <div class="mt-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Articles à Restockage Urgent</h5>
                </div>
                <div class="card-body">
                    @php
                        $articlesEnAlerte = $stocks->filter(function($item) {
                            return $item->stock_actuel <= $item->seuil_alerte;
                        });
                    @endphp
                    @if($articlesEnAlerte->count() > 0)
                        <div class="alert alert-warning">
                            <strong>Attention!</strong> {{ $articlesEnAlerte->count() }} article(s) ont un stock inférieur au minimum requis
                        </div>
                        <ul>
                            @foreach($articlesEnAlerte as $article)
                                <li><strong>{{ $article->nom }}</strong> - Stock: {{ $article->stock_actuel }} / Minimum: {{ $article->seuil_alerte }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-success">
                            Tous les articles sont bien approvisionnés
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Restockage -->
    <div class="modal fade" id="restockModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Restockage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Quantité à Ajouter</label>
                            <input type="number" class="form-control" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date Livraison</label>
                            <input type="date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fournisseur</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Confirmer Restockage</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter Stock -->
    <div class="modal fade" id="addStockModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Plat</label>
                            <select class="form-select" required>
                                <option>Sélectionner un plat</option>
                                <option>Alloco</option>
                                <option>Riz Sauce</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantité</label>
                            <input type="number" class="form-control" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantité Minimale</label>
                            <input type="number" class="form-control" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
