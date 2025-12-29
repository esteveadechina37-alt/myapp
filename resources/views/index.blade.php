<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trial+ - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar .nav-link {
            color: white !important;
            margin: 0 15px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .navbar .nav-link:hover {
            color: #fff !important;
            text-shadow: 0 0 10px rgba(255,255,255,0.5);
        }

        .navbar .btn-connexion {
            background: white;
            color: #d32f2f;
            font-weight: 600;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
        }

        .navbar .btn-connexion:hover {
            background: #f0f0f0;
        }

        /* Header Hero */
        .hero-section {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
            animation: slideInDown 0.8s ease;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .hero-section p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .hero-section .btn-menu {
            background: white;
            color: #d32f2f;
            font-weight: 600;
            padding: 12px 40px;
            border-radius: 30px;
            border: none;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .hero-section .btn-menu:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        /* Section Horaires */
        .horaires-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .horaires-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 50px;
            text-align: center;
            color: #333;
        }

        .horaires-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s ease;
        }

        .horaires-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }

        .horaires-card .jour {
            font-weight: 600;
            color: #d32f2f;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .horaires-card .heure {
            color: #666;
            font-size: 1rem;
        }

        .horaires-card .ferme {
            color: #f44336;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-top: 15px;
            animation: pulse 2s infinite;
        }

        .status-open {
            background-color: #4caf50;
            color: white;
        }

        .status-closed {
            background-color: #f44336;
            color: white;
        }

        .status-open::before {
            content: '● ';
            margin-right: 5px;
        }

        .status-closed::before {
            content: '● ';
            margin-right: 5px;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Section Plats */
        .plats-section {
            padding: 80px 0;
        }

        .plats-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 50px;
            text-align: center;
            color: #333;
        }

        .plat-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .plat-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }

        .plat-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        .plat-info {
            padding: 20px;
        }

        .plat-nom {
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 8px;
            color: #333;
        }

        .plat-prix {
            color: #d32f2f;
            font-weight: 600;
            font-size: 1.3rem;
        }

        /* Section Nos Spécialités */
        .specialites-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
            padding: 60px 20px;
            margin: 40px 0;
        }

        .specialites-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 50px;
            text-align: center;
            color: #d32f2f;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .categorie-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .categorie-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(211, 47, 47, 0.2);
        }

        .categorie-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background-color: #f0f0f0;
            display: block;
        }

        .categorie-content {
            padding: 25px;
        }

        .categorie-nom {
            font-size: 1.4rem;
            font-weight: 700;
            color: #d32f2f;
            margin-bottom: 10px;
        }

        .categorie-desc {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 15px;
        }

        .btn-decouvrir {
            display: inline-block;
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-decouvrir:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
            color: white;
        }

        /* Footer */
        .footer {
            background: #333;
            color: white;
            padding: 50px 0 20px;
        }

        .footer h5 {
            font-weight: 700;
            margin-bottom: 20px;
            color: #d32f2f;
        }

        .footer p {
            opacity: 0.8;
            margin-bottom: 10px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: #d32f2f;
        }

        .footer-bottom {
            border-top: 1px solid #555;
            padding-top: 30px;
            text-align: center;
            opacity: 0.7;
        }

        .socials {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .socials a {
            width: 40px;
            height: 40px;
            background: #d32f2f;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .socials a:hover {
            background: #1976d2;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Modal Auth */
        .modal-auth .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .modal-auth .modal-header {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border: none;
        }

        .modal-auth .nav-tabs {
            border-bottom: 2px solid #e0e0e0;
        }

        .modal-auth .nav-link {
            color: #666;
            font-weight: 600;
            border: none;
            padding: 15px 30px;
        }

        .modal-auth .nav-link.active {
            color: #d32f2f;
            border-bottom: 3px solid #d32f2f;
            background: white;
        }

        .modal-auth .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 0.95rem;
        }

        .modal-auth .form-control:focus {
            border-color: #d32f2f;
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
        }

        .modal-auth .btn-login {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .modal-auth .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.4);
            color: white;
        }

        .modal-auth .form-check-input {
            border-color: #d32f2f;
            cursor: pointer;
        }

        .modal-auth .form-check-input:checked {
            background-color: #d32f2f;
            border-color: #d32f2f;
        }

        .modal-auth .text-muted {
            font-size: 0.9rem;
        }

        .modal-auth .input-group-text {
            border: 1px solid #ddd;
            background: white;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }

            .hero-section p {
                font-size: 1rem;
            }

            .plats-section h2,
            .horaires-section h2 {
                font-size: 1.8rem;
            }

            .modal-auth .nav-link {
                padding: 12px 15px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-utensils"></i> Trial+
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/apropos">À Propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/faq">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-connexion ms-3" data-bs-toggle="modal" data-bs-target="#authModal">
                            <i class="fas fa-sign-in-alt"></i> Connexion
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1><i class="fas fa-fire"></i> Bienvenue au Restaurant Trial+</h1>
            <p>Découvrez nos délicieux plats préparés avec passion</p>
            <button type="button" class="btn btn-menu" data-bs-toggle="modal" data-bs-target="#authModal">
                <i class="fas fa-shopping-bag"></i> Consulter Menu
            </button>
            <button type="button" class="btn btn-menu" data-bs-toggle="modal" data-bs-target="#authModal">
                <i class="fas fa-clock"></i> Passer Commande
            </button>
        </div>
    </section>

    <!-- Section Horaires -->
    <section class="horaires-section">
        <div class="container">
            <h2>Nos Horaires</h2>
            
            <!-- Indicateur de statut -->
            <div style="text-align: center; margin-bottom: 30px;">
                <div id="status-container">
                    <span class="status-badge status-open" id="status-badge">Ouvert maintenant</span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="horaires-card">
                        <div class="jour"><i class="fas fa-calendar-day"></i> Lundi - Vendredi</div>
                        <div class="heure">11:30 - 14:00</div>
                        <div class="heure">18:30 - 22:00</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="horaires-card">
                        <div class="jour"><i class="fas fa-calendar-day"></i> Samedi</div>
                        <div class="heure">12:00 - 23:00</div>
                        <div class="heure">(Sans interruption)</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="horaires-card">
                        <div class="jour"><i class="fas fa-calendar-day"></i> Dimanche</div>
                        <div class="heure">12:00 - 21:00</div>
                        <div class="ferme">
                            <i class="fas fa-lock"></i> Fermé le Lundi
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Plats -->
    <!-- <section class="plats-section">
        <div class="container">
            <h2><i class="fas fa-utensils"></i> Nos Spécialités</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="plat-card">
                        <div class="plat-image">🍕</div>
                        <div class="plat-info">
                            <div class="plat-nom">Pizza Gourmet</div>
                            <p style="color: #666; margin: 10px 0;">Pizza traditionnelle avec ingrédients frais</p>
                            <div class="plat-prix">8 500 CFA</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="plat-card">
                        <div class="plat-image">🍝</div>
                        <div class="plat-info">
                            <div class="plat-nom">Pâtes Carbonara</div>
                            <p style="color: #666; margin: 10px 0;">Pâtes al dente avec sauce crémeuse</p>
                            <div class="plat-prix">7 500 CFA</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="plat-card">
                        <div class="plat-image">🍔</div>
                        <div class="plat-info">
                            <div class="plat-nom">Burger Premium</div>
                            <p style="color: #666; margin: 10px 0;">Burger savoureux avec fromage fondant</p>
                            <div class="plat-prix">7 200 CFA</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="plat-card">
                        <div class="plat-image">🥗</div>
                        <div class="plat-info">
                            <div class="plat-nom">Salade Fraîcheur</div>
                            <p style="color: #666; margin: 10px 0;">Salade colorée avec vinaigrette maison</p>
                            <div class="plat-prix">6 500 CFA</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="plat-card">
                        <div class="plat-image">🍰</div>
                        <div class="plat-info">
                            <div class="plat-nom">Dessert Chocolat</div>
                            <p style="color: #666; margin: 10px 0;">Fondant au chocolat irrésistible</p>
                            <div class="plat-prix">4 600 CFA</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="plat-card">
                        <div class="plat-image">🥤</div>
                        <div class="plat-info">
                            <div class="plat-nom">Boisson Fraîche</div>
                            <p style="color: #666; margin: 10px 0;">Jus frais pressé 100% naturel</p>
                            <div class="plat-prix">2 600 CFA</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Nos Spécialités Section -->
    <section class="specialites-section">
        <h2><i class="fas fa-star"></i> Nos Spécialités</h2>
        
        @if(isset($categories) && $categories->count() > 0)
            <div class="categories-grid">
                @foreach($categories as $categorie)
                    <div class="categorie-card">
                        <img 
                            src="{{ $categorie->image }}" 
                            alt="{{ $categorie->nom }}" 
                            class="categorie-image"
                            onerror="this.src='https://via.placeholder.com/500x500?text={{ urlencode($categorie->nom) }}'"
                            loading="eager">
                        
                        <div class="categorie-content">
                            <div class="categorie-nom">{{ $categorie->nom }}</div>
                            <p class="categorie-desc">{{ $categorie->description }}</p>
                            <a href="{{ route('menu.index') }}" class="btn-decouvrir">
                                <i class="fas fa-eye"></i> Découvrir
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h5><i class="fas fa-utensils"></i> Trial+</h5>
                    <p>Votre restaurant préféré pour une expérience culinaire inoubliable.</p>
                </div>
                <div class="col-md-3">
                    <h5><i class="fas fa-link"></i> Navigation</h5>
                    <ul class="footer-links">
                        <li><a href="/">Accueil</a></li>
                        <li><a href="/apropos">À Propos</a></li>
                        <li><a href="/faq">FAQ</a></li>
                        <li><a href="/login">Connexion</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5><i class="fas fa-phone"></i> Contact</h5>
                    <p><i class="fas fa-phone"></i> 01 23 45 67 89</p>
                    <p><i class="fas fa-envelope"></i> contact@restaurant.fr</p>
                    <p><i class="fas fa-map-marker-alt"></i> Rue de l'Indépendance, Porto-Novo, Bénin</p>
                </div>
                <div class="col-md-3">
                    <h5><i class="fas fa-share-alt"></i> Nous Suivre</h5>
                    <div class="socials">
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Trial+. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Modal Authentification -->
    <div class="modal fade modal-auth" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">
                        <i class="fas fa-lock"></i> Connexion / Inscription
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">
                                <i class="fas fa-sign-in-alt"></i> Connexion
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">
                                <i class="fas fa-user-plus"></i> Inscription
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content mt-4">
                        <!-- Connexion Tab -->
                        <div class="tab-pane fade show active" id="login" role="tabpanel">
                            <form id="loginForm" action="/login" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="loginEmail" class="form-label">
                                        <i class="fas fa-envelope"></i> Email
                                    </label>
                                    <input type="email" class="form-control" id="loginEmail" name="email" placeholder="votre@email.com" required>
                                </div>

                                <div class="mb-3">
                                    <label for="loginPassword" class="form-label">
                                        <i class="fas fa-lock"></i> Mot de passe
                                    </label>
                                    <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Votre mot de passe" required>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                                    <label class="form-check-label" for="rememberMe">
                                        Se souvenir de moi
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-login mb-3">
                                    <i class="fas fa-sign-in-alt"></i> Se connecter
                                </button>

                                <div class="text-center text-muted">
                                    <small>Mot de passe oublié? <a href="#" class="text-decoration-none" style="color: #d32f2f;">Cliquez ici</a></small>
                                </div>
                            </form>
                        </div>

                        <!-- Inscription Tab -->
                        <div class="tab-pane fade" id="register" role="tabpanel">
                            <form id="registerForm" action="/register" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="registerName" class="form-label">
                                        <i class="fas fa-user"></i> Nom complet
                                    </label>
                                    <input type="text" class="form-control" id="registerName" name="name" placeholder="Jean Dupont" required>
                                </div>

                                <div class="mb-3">
                                    <label for="registerEmail" class="form-label">
                                        <i class="fas fa-envelope"></i> Email
                                    </label>
                                    <input type="email" class="form-control" id="registerEmail" name="email" placeholder="votre@email.com" required>
                                </div>

                                <div class="mb-3">
                                    <label for="registerPassword" class="form-label">
                                        <i class="fas fa-lock"></i> Mot de passe
                                    </label>
                                    <input type="password" class="form-control" id="registerPassword" name="password" placeholder="Min. 8 caractères" required minlength="8">
                                </div>

                                <div class="mb-3">
                                    <label for="registerPasswordConfirm" class="form-label">
                                        <i class="fas fa-lock"></i> Confirmer le mot de passe
                                    </label>
                                    <input type="password" class="form-control" id="registerPasswordConfirm" name="password_confirmation" placeholder="Confirmer votre mot de passe" required minlength="8">
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="agreeTerms" name="agree_terms" required>
                                    <label class="form-check-label" for="agreeTerms">
                                        J'accepte les <a href="#" class="text-decoration-none" style="color: #d32f2f;">conditions d'utilisation</a>
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-login mb-3">
                                    <i class="fas fa-user-plus"></i> S'inscrire
                                </button>

                                <div class="text-center text-muted">
                                    <small>Vous avez déjà un compte? <a href="#" class="text-decoration-none" style="color: #d32f2f; cursor: pointer;" onclick="document.querySelector('#login-tab').click()">Connectez-vous</a></small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Valider les mots de passe à l'inscription
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('registerPassword').value;
            const passwordConfirm = document.getElementById('registerPasswordConfirm').value;
            
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas!');
                return false;
            }
        });

        // Script pour vérifier si le restaurant est ouvert
        function checkRestaurantStatus() {
            const now = new Date();
            const day = now.getDay(); // 0 = Dimanche, 1 = Lundi, ..., 6 = Samedi
            const hours = now.getHours();
            const minutes = now.getMinutes();
            const currentTime = hours + (minutes / 60);

            let isOpen = false;

            // Horaires du restaurant
            if (day === 0) { // Dimanche
                // 12:00 - 21:00
                isOpen = currentTime >= 12 && currentTime < 21;
            } else if (day >= 1 && day <= 5) { // Lundi à Vendredi
                // 11:30 - 14:00 et 18:30 - 22:00
                isOpen = (currentTime >= 11.5 && currentTime < 14) || (currentTime >= 18.5 && currentTime < 22);
            } else if (day === 6) { // Samedi
                // 12:00 - 23:00
                isOpen = currentTime >= 12 && currentTime < 23;
            }

            const badge = document.getElementById('status-badge');
            
            if (isOpen) {
                badge.textContent = '● Ouvert maintenant';
                badge.classList.remove('status-closed');
                badge.classList.add('status-open');
            } else {
                badge.textContent = '● Fermé en ce moment';
                badge.classList.remove('status-open');
                badge.classList.add('status-closed');
            }
        }

        // Vérifier le statut au chargement de la page
        checkRestaurantStatus();

        // Vérifier le statut toutes les minutes
        setInterval(checkRestaurantStatus, 60000);
    </script>
</body>
</html>
