<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Employés - Admin</title>
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
            font-size: 1.2rem;
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
        
        .topbar h1 { margin: 0; color: #333; font-size: 1.4rem; font-weight: 700; }
        
        .btn-add {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
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
            padding: 12px;
            font-size: 0.75rem;
        }
        
        .table tbody td {
            padding: 10px;
            border: none;
            border-bottom: 1px solid #eee;
            font-size: 0.75rem;
        }
        
        .badge {
            padding: 5px 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .role-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .role-serveur { background: #bbdefb; color: #1976d2; }
        .role-cuisinier { background: #ffccbc; color: #d84315; }
        .role-livreur { background: #c8e6c9; color: #388e3c; }
        .role-gerant { background: #ffe0b2; color: #f57f17; }
        
        .statut-actif { background: #c8e6c9; color: #388e3c; }
        .statut-inactif { background: #f5f5f5; color: #666; }
        .statut-suspendu { background: #ffccbc; color: #d84315; }
        
        .btn-action {
            padding: 4px 8px;
            font-size: 0.65rem;
            margin: 0 2px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-action-group {
            display: flex;
            gap: 3px;
            flex-wrap: nowrap;
            align-items: center;
        }
        
        .btn-edit { background: #2196F3; color: white; }
        .btn-edit:hover { background: #1976d2; }
        
        .btn-reset { background: #ff9800; color: white; }
        .btn-reset:hover { background: #f57c00; }
        
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
            <a class="nav-link" href="{{ route('admin.users') }}">
                <i class="fas fa-users"></i> Clients
            </a>
            <a class="nav-link active" href="{{ route('admin.employes') }}">
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
            <h1><i class="fas fa-users-cog"></i> Gestion Employés</h1>
            <a href="{{ route('admin.employes.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Ajouter un Employé
            </a>
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

        <!-- Tableau Employés -->
        <div class="table-responsive">
            @if($employes->count() > 0)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Date Embauche</th>
                            <th>Téléphone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employes as $employe)
                            <tr>
                                <td><strong>{{ $employe->name }}</strong></td>
                                <td>{{ $employe->email }}</td>
                                <td>
                                    <span class="role-badge role-{{ $employe->role }}">
                                        {{ ucfirst($employe->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge statut-{{ $employe->statut }}">
                                        {{ ucfirst($employe->statut) }}
                                    </span>
                                </td>
                                <td>{{ $employe->date_embauche ? $employe->date_embauche->format('d/m/Y') : '-' }}</td>
                                <td>{{ $employe->telephone ?? '-' }}</td>
                                <td>
                                    <div class="btn-action-group">
                                        <a href="{{ route('admin.employes.edit', $employe->id) }}" class="btn-action btn-edit">
                                            <i class="fas fa-edit"></i> Éditer
                                        </a>
                                        <form action="{{ route('admin.employes.resetPassword', $employe->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-reset" onclick="return confirm('Êtes-vous sûr?')">
                                                <i class="fas fa-key"></i> Réinitialiser
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.employes.delete', $employe->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet employé?')">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $employes->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>Aucun employé trouvé</h3>
                    <p>Commencez par <a href="{{ route('admin.employes.create') }}">créer un nouvel employé</a></p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
