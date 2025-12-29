<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer Employé - Admin</title>
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 30px;
            max-width: 600px;
        }
        
        .form-label {
            color: #333;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #d32f2f;
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(211, 47, 47, 0.4);
            color: white;
        }
        
        .btn-back {
            background: #f0f0f0;
            color: #333;
            border: none;
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .btn-back:hover {
            background: #e0e0e0;
            color: #333;
        }
        
        .form-group { margin-bottom: 20px; }
        
        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .alert { border-radius: 10px; border: none; margin-bottom: 20px; }
        .alert-danger { background: #f8d7da; color: #721c24; }
        
        @media (max-width: 768px) {
            .sidebar { width: 100%; min-height: auto; padding: 15px 0; position: relative; margin-bottom: 20px; }
            .main-content { margin-left: 0; padding: 15px; }
            .form-card { padding: 20px; }
            .two-columns { grid-template-columns: 1fr; }
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
            <a class="nav-link" href="{{ route('admin.employes') }}">
                <i class="fas fa-users-cog"></i> Employés
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
            <h1><i class="fas fa-user-plus"></i> Créer un Employé</h1>
            <a href="{{ route('admin.employes') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Erreurs -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> Erreurs du formulaire:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Formulaire -->
        <div class="form-card">
            <form action="{{ route('admin.employes.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-user"></i> Nom Complet *
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                        id="name" name="name" placeholder="Jean Dupont" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i> Email *
                    </label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                        id="email" name="email" placeholder="jean@restaurant.fr" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="two-columns">
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i> Mot de passe *
                        </label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                            id="password" name="password" placeholder="Min. 8 caractères" required>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-check-circle"></i> Confirmer *
                        </label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                            id="password_confirmation" name="password_confirmation" placeholder="Confirmer mot de passe" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="two-columns">
                    <div class="form-group">
                        <label for="role" class="form-label">
                            <i class="fas fa-briefcase"></i> Rôle *
                        </label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">-- Sélectionnez un rôle --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date_embauche" class="form-label">
                            <i class="fas fa-calendar"></i> Date d'Embauche *
                        </label>
                        <input type="date" class="form-control @error('date_embauche') is-invalid @enderror" 
                            id="date_embauche" name="date_embauche" value="{{ old('date_embauche') }}" required>
                        @error('date_embauche')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="two-columns">
                    <div class="form-group">
                        <label for="telephone" class="form-label">
                            <i class="fas fa-phone"></i> Téléphone
                        </label>
                        <input type="tel" class="form-control @error('telephone') is-invalid @enderror" 
                            id="telephone" name="telephone" placeholder="+33 6 12 34 56 78" value="{{ old('telephone') }}">
                        @error('telephone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="numero_id" class="form-label">
                            <i class="fas fa-id-card"></i> Numéro ID/NUSS
                        </label>
                        <input type="text" class="form-control @error('numero_id') is-invalid @enderror" 
                            id="numero_id" name="numero_id" placeholder="Ex: ID-001" value="{{ old('numero_id') }}">
                        @error('numero_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="adresse" class="form-label">
                        <i class="fas fa-map-marker-alt"></i> Adresse
                    </label>
                    <textarea class="form-control @error('adresse') is-invalid @enderror" 
                        id="adresse" name="adresse" placeholder="Adresse complète" rows="3">{{ old('adresse') }}</textarea>
                    @error('adresse')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit mb-3">
                    <i class="fas fa-save"></i> Créer l'Employé
                </button>
                <a href="{{ route('admin.employes') }}" class="btn-back" style="display: block; text-align: center;">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
