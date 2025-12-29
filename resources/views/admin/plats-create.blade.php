<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($plat) ? 'Éditer' : 'Ajouter' }} un Plat - Admin</title>
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
        
        .form-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 0.95rem;
        }
        
        .form-control:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.25);
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(211, 47, 47, 0.4); }
        
        .btn-cancel {
            background: #f5f5f5;
            color: #333;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn-cancel:hover { background: #e0e0e0; }
        
        .image-preview {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid #ddd;
            margin-top: 10px;
        }
        
        .image-placeholder {
            width: 150px;
            height: 150px;
            background: #f5f5f5;
            border-radius: 10px;
            border: 2px dashed #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
        }
        
        .alert { border-radius: 10px; border: none; margin-bottom: 20px; }
        .alert-danger { background: #f8d7da; color: #721c24; }
        
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
            <a class="nav-link active" href="{{ route('admin.plats') }}">
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
            <h1>
                <i class="fas fa-{{ isset($plat) ? 'edit' : 'plus' }}"></i>
                {{ isset($plat) ? 'Éditer le Plat' : 'Ajouter un Plat' }}
            </h1>
        </div>

        <!-- Erreurs -->
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> Erreurs:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire -->
        <div class="form-card">
            <form action="{{ isset($plat) ? route('admin.plats.update', $plat->id) : route('admin.plats.store') }}" method="POST">
                @csrf
                @if(isset($plat))
                    @method('PATCH')
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Nom du Plat *</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" value="{{ old('nom', $plat->nom ?? '') }}" required>
                            @error('nom') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Catégorie *</label>
                            <select class="form-control @error('categorie_id') is-invalid @enderror" name="categorie_id" required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ (old('categorie_id', $plat->categorie_id ?? '') == $cat->id) ? 'selected' : '' }}>
                                        {{ $cat->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categorie_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Prix (CFA) *</label>
                            <input type="number" class="form-control @error('prix') is-invalid @enderror" name="prix" step="0.01" value="{{ old('prix', isset($plat) ? $plat->prix : '') }}" required>
                            <small class="text-muted">Prix du plat en CFA</small>
                            @error('prix') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <input type="checkbox" name="est_disponible" value="1" {{ (old('est_disponible', $plat->est_disponible ?? true)) ? 'checked' : '' }}>
                                Disponible
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description', $plat->description ?? '') }}</textarea>
                            @error('description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">URL de l'Image</label>
                            <input type="url" class="form-control @error('image') is-invalid @enderror" name="image" placeholder="https://example.com/image.jpg" value="{{ old('image', $plat->image ?? '') }}">
                            @error('image') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        @if(isset($plat) && $plat->image)
                            <div>
                                <p style="font-size: 0.9rem; color: #666;">Aperçu actuel:</p>
                                @if(strpos($plat->image, 'http') === 0)
                                    <img src="{{ $plat->image }}" alt="{{ $plat->nom }}" class="image-preview">
                                @else
                                    <img src="{{ asset('storage/' . $plat->image) }}" alt="{{ $plat->nom }}" class="image-preview">
                                @endif
                            </div>
                        @else
                            <div>
                                <p style="font-size: 0.9rem; color: #666;">Aperçu de l'image:</p>
                                <div class="image-placeholder">
                                    <i class="fas fa-image" style="font-size: 2rem; color: #ccc;"></i>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> {{ isset($plat) ? 'Mettre à jour' : 'Ajouter' }} le Plat
                    </button>
                    <a href="{{ route('admin.plats') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
