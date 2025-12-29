<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Catégories - Admin</title>
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
        
        .btn-add {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-add:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(211, 47, 47, 0.4); color: white; }
        
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
        
        .btn-action {
            padding: 6px 10px;
            font-size: 0.85rem;
            margin: 0 2px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-edit { background: #2196F3; color: white; }
        .btn-edit:hover { background: #1976d2; }
        
        .btn-delete { background: #f44336; color: white; }
        .btn-delete:hover { background: #d32f2f; }
        
        .alert { border-radius: 10px; border: none; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger { background: #f8d7da; color: #721c24; }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        
        .empty-state i { font-size: 3rem; margin-bottom: 20px; }
        
        .modal-header { background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white; }
        .modal-header .btn-close { filter: brightness(0) invert(1); }
        
        .form-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .form-card h3 { color: #333; margin-bottom: 20px; font-weight: 700; }
        
        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 0.95rem;
        }
        
        .form-control:focus { border-color: #1976d2; box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.25); }
        
        .btn-submit {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(211, 47, 47, 0.4); }
        
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
            <a class="nav-link" href="{{ route('admin.commandes') }}">
                <i class="fas fa-shopping-bag"></i> Commandes
            </a>
            <a class="nav-link" href="{{ route('admin.plats') }}">
                <i class="fas fa-list"></i> Plats
            </a>
            <a class="nav-link active" href="{{ route('admin.categories') }}">
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
            <h1><i class="fas fa-sitemap"></i> Gestion Catégories</h1>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-plus"></i> Ajouter une Catégorie
            </button>
        </div>

        <!-- Messages Flash -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> Erreurs:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Tableau Catégories -->
        <div class="table-responsive">
            @if($categories->count() > 0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Ordre</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td><strong>{{ $category->nom }}</strong></td>
                                <td>{{ $category->ordre_affichage ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $category->est_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $category->est_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-action btn-edit" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                        <i class="fas fa-edit"></i> Éditer
                                    </button>
                                    <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" onclick="return confirm('Êtes-vous sûr?')">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Édition Catégorie -->
                            <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Éditer Catégorie</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nom</label>
                                                    <input type="text" class="form-control" name="nom" value="{{ $category->nom }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ordre d'affichage</label>
                                                    <input type="number" class="form-control" name="ordre_affichage" value="{{ $category->ordre_affichage ?? 0 }}">
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="est_active" id="active{{ $category->id }}" {{ $category->est_active ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="active{{ $category->id }}">
                                                            Catégorie active
                                                        </label>
                                                    </div>
                                                </div>
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
                    {{ $categories->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>Aucune catégorie trouvée</h3>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Ajouter Catégorie -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une Catégorie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ordre d'affichage</label>
                            <input type="number" class="form-control" name="ordre_affichage" value="0">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="est_active" id="newActive" checked>
                                <label class="form-check-label" for="newActive">
                                    Catégorie active
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
