<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Commandes - Admin</title>
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
        
        .topbar h1 { margin: 0; color: #333; font-size: 1.8rem; font-weight: 700; }
        
        .table-responsive {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 20px;
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
        
        .badge {
            padding: 5px 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge-en-attente { background: #fff3cd; color: #856404; }
        .badge-confirmee { background: #cfe2ff; color: #084298; }
        .badge-en-preparation { background: #ffccbc; color: #bf360c; }
        .badge-prete { background: #c8e6c9; color: #33691e; }
        .badge-livree { background: #b3e5fc; color: #01579b; }
        .badge-annulee { background: #ffcdd2; color: #b71c1c; }
        
        .btn-action {
            padding: 6px 10px;
            font-size: 0.85rem;
            margin: 0 2px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-view { background: #2196F3; color: white; }
        .btn-view:hover { background: #1976d2; }
        
        .btn-status { background: #ff9800; color: white; }
        .btn-status:hover { background: #f57c00; }
        
        .alert { border-radius: 10px; border: none; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        
        .empty-state i { font-size: 3rem; margin-bottom: 20px; }
        
        .modal-header { background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white; }
        .modal-header .btn-close { filter: brightness(0) invert(1); }
        
        @media (max-width: 768px) {
            .sidebar { width: 100%; min-height: auto; padding: 15px 0; position: relative; margin-bottom: 20px; }
            .main-content { margin-left: 0; padding: 15px; }
            .topbar { flex-direction: column; gap: 15px; text-align: center; }
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
            <a class="nav-link active" href="{{ route('admin.commandes') }}">
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
            <a class="nav-link" href="{{ route('admin.rapports') }}">
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
            <h1><i class="fas fa-shopping-bag"></i> Gestion Commandes</h1>
        </div>

        <!-- Messages Flash -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Tableau Commandes -->
        <div class="table-responsive">
            @if($commandes->count() > 0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Commande</th>
                            <th>Client</th>
                            <th>Type</th>
                            <th>Date & Heure</th>
                            <th>Montant TTC</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commandes as $commande)
                            <tr>
                                <td><strong>{{ $commande->numero }}</strong></td>
                                <td>{{ $commande->client->nom ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $commande->type_commande)) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($commande->heure_commande)->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format($commande->montant_total_ttc, 0, ',', ' ') }} CFA</td>
                                <td>
                                    <span class="badge badge-{{ strtolower($commande->statut ?? 'en_attente') }}">
                                        {{ ucfirst(str_replace('_', ' ', $commande->statut ?? 'En attente')) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-action btn-view" data-bs-toggle="modal" data-bs-target="#commandeModal{{ $commande->id }}">
                                        <i class="fas fa-eye"></i> Détail
                                    </button>
                                    <button class="btn-action btn-status" data-bs-toggle="modal" data-bs-target="#statutModal{{ $commande->id }}">
                                        <i class="fas fa-sync"></i> Statut
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Détail Commande -->
                            <div class="modal fade" id="commandeModal{{ $commande->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Détail Commande {{ $commande->numero }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Numéro:</strong> {{ $commande->numero }}</p>
                                            <p><strong>Client:</strong> {{ $commande->client->nom ?? 'N/A' }}</p>
                                            <p><strong>Email:</strong> {{ $commande->user->email ?? ($commande->client->email ?? 'N/A') }}</p>
                                            <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $commande->type_commande)) }}</p>
                                            <p><strong>Date & Heure:</strong> {{ \Carbon\Carbon::parse($commande->heure_commande)->format('d/m/Y H:i') }}</p>
                                            <p><strong>Montant HT:</strong> {{ number_format($commande->montant_total_ht, 0, ',', ' ') }} CFA</p>
                                            <p><strong>Montant TVA:</strong> {{ number_format($commande->montant_tva, 0, ',', ' ') }} CFA</p>
                                            <p><strong>Montant TTC:</strong> {{ number_format($commande->montant_total_ttc, 0, ',', ' ') }} CFA</p>
                                            <p><strong>Statut:</strong> {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}</p>
                                            <p><strong>Payée:</strong> {{ $commande->est_payee ? 'Oui' : 'Non' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Changement Statut -->
                            <div class="modal fade" id="statutModal{{ $commande->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Changer le Statut</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.commandes.updateStatut', $commande->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <select name="statut" class="form-control">
                                                    <option value="en_attente" {{ $commande->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                                                    <option value="confirmee" {{ $commande->statut === 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                                                    <option value="en_preparation" {{ $commande->statut === 'en_preparation' ? 'selected' : '' }}>En préparation</option>
                                                    <option value="prete" {{ $commande->statut === 'prete' ? 'selected' : '' }}>Prête</option>
                                                    <option value="servie" {{ $commande->statut === 'servie' ? 'selected' : '' }}>Servie</option>
                                                    <option value="payee" {{ $commande->statut === 'payee' ? 'selected' : '' }}>Payée</option>
                                                    <option value="livree" {{ $commande->statut === 'livree' ? 'selected' : '' }}>Livrée</option>
                                                    <option value="annulee" {{ $commande->statut === 'annulee' ? 'selected' : '' }}>Annulée</option>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $commandes->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>Aucune commande trouvée</h3>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
