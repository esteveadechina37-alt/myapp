<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }} - Page d'Accueil</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Bootstrap & Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
            * {
                font-family: 'Poppins', sans-serif;
            }

            body {
                background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
                overflow-x: hidden;
            }

            .hero-section {
                text-align: center;
                padding: 40px 20px;
                max-width: 900px;
            }

            .hero-section h1 {
                font-size: 3.5rem;
                font-weight: 700;
                margin-bottom: 20px;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
                animation: slideInDown 0.8s ease-out;
            }

            .hero-section .subtitle {
                font-size: 1.5rem;
                font-weight: 300;
                margin-bottom: 40px;
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
                animation: slideInUp 0.8s ease-out 0.2s both;
            }

            .hero-icon {
                font-size: 4rem;
                margin-bottom: 20px;
                animation: float 3s ease-in-out infinite;
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

            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes float {
                0%, 100% {
                    transform: translateY(0px);
                }
                50% {
                    transform: translateY(-20px);
                }
            }

            .btn-custom {
                padding: 14px 40px;
                font-size: 1.1rem;
                font-weight: 600;
                border-radius: 8px;
                transition: all 0.3s ease;
                margin: 10px;
                text-decoration: none;
                display: inline-block;
                cursor: pointer;
                border: 2px solid transparent;
            }

            .btn-menu {
                background-color: white;
                color: #d32f2f;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            }

            .btn-menu:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
                background-color: #f5f5f5;
                color: #d32f2f;
            }

            .btn-login {
                background-color: rgba(255, 255, 255, 0.15);
                color: white;
                border: 2px solid white;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            }

            .btn-login:hover {
                background-color: rgba(255, 255, 255, 0.25);
                transform: translateY(-3px);
                box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
                color: white;
            }

            .features {
                display: flex;
                justify-content: center;
                gap: 40px;
                flex-wrap: wrap;
                margin-top: 60px;
                animation: slideInUp 0.8s ease-out 0.4s both;
            }

            .feature-item {
                text-align: center;
                max-width: 200px;
            }

            .feature-icon {
                font-size: 2.5rem;
                margin-bottom: 15px;
            }

            .feature-title {
                font-size: 1.1rem;
                font-weight: 600;
                margin-bottom: 10px;
            }

            .feature-desc {
                font-size: 0.95rem;
                opacity: 0.9;
            }

            .navbar-custom {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                padding: 15px 0;
                position: fixed;
                width: 100%;
                top: 0;
                z-index: 1000;
            }

            .navbar-brand-text {
                font-size: 1.5rem;
                font-weight: 700;
                color: white;
            }

            .footer-custom {
                position: fixed;
                bottom: 0;
                width: 100%;
                background: rgba(0, 0, 0, 0.3);
                padding: 15px;
                text-align: center;
                font-size: 0.9rem;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }

            .specialties-section {
                background: white;
                color: #333;
                padding: 60px 20px;
                margin: 80px 0 80px 0;
                text-align: center;
            }

            .specialties-section h2 {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 50px;
                color: #d32f2f;
            }

            .categories-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 30px;
                max-width: 1200px;
                margin: 0 auto;
            }

            .category-card {
                background: white;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                cursor: pointer;
            }

            .category-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 40px rgba(211, 47, 47, 0.2);
            }

            .category-image {
                width: 100%;
                height: 200px;
                object-fit: cover;
            }

            .category-content {
                padding: 25px;
            }

            .category-name {
                font-size: 1.4rem;
                font-weight: 700;
                color: #d32f2f;
                margin-bottom: 10px;
            }

            .category-desc {
                font-size: 0.95rem;
                color: #666;
                margin-bottom: 15px;
            }

            .btn-explore {
                display: inline-block;
                background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
                color: white;
                padding: 10px 25px;
                border-radius: 5px;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-explore:hover {
                transform: scale(1.05);
                box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
                color: white;
            }
            @media (max-width: 768px) {
                .hero-section h1 {
                    font-size: 2rem;
                }

                .hero-section .subtitle {
                    font-size: 1rem;
                }

                .features {
                    flex-direction: column;
                    gap: 30px;
                }

                .btn-custom {
                    padding: 12px 30px;
                    font-size: 1rem;
                }
            }
        </style>
    </head>
    <body>
        <!-- Navbar -->
        <nav class="navbar-custom">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="navbar-brand-text">
                        <i class="fas fa-utensils"></i> {{ config('app.name') }}
                    </div>
                    @auth
                        <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-light" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-icon">
                <i class="fas fa-restaurant"></i>
            </div>

            <h1>Bienvenue au Restaurant</h1>
            
            <p class="subtitle">
                Découvrez notre menu délicieux et commandez en ligne
            </p>

            <div style="margin-bottom: 50px;">
                @auth
                    <a href="{{ route('menu.index') }}" class="btn-custom btn-menu">
                        <i class="fas fa-list"></i> Consulter le Menu
                    </a>
                    <a href="{{ route('commandes.list') }}" class="btn-custom btn-login">
                        <i class="fas fa-clipboard-list"></i> Mes Commandes
                    </a>
                @else
                    <a href="{{ route('menu.index') }}" class="btn-custom btn-menu">
                        <i class="fas fa-list"></i> Consulter le Menu
                    </a>
                    <a href="{{ route('login') }}" class="btn-custom btn-login">
                        <i class="fas fa-sign-in-alt"></i> Se Connecter
                    </a>
                @endauth
            </div>

            <!-- Features -->
            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5 class="feature-title">Support 24/7</h5>
                    <p class="feature-desc">Notre équipe est toujours à votre service</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h5 class="feature-title">Livraison Rapide</h5>
                    <p class="feature-desc">Livraison en moins de 30 minutes</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 class="feature-title">Qualité Garantie</h5>
                    <p class="feature-desc">Les meilleurs ingrédients frais</p>
                </div>
            </div>
        </div>

        <!-- Nos Spécialités Section -->
        <div class="specialties-section">
            <h2><i class="fas fa-star"></i> Nos Spécialités</h2>
            
            @if(isset($categories) && $categories->count() > 0)
                <div class="categories-grid">
                    @foreach($categories as $categorie)
                        <div class="category-card">
                            <img src="{{ $categorie->image ?? 'https://via.placeholder.com/250x200?text=' . urlencode($categorie->nom) }}" 
                                 alt="{{ $categorie->nom }}" 
                                 class="category-image"
                                 onerror="this.src='https://via.placeholder.com/250x200?text={{ urlencode($categorie->nom) }}'">
                            <div class="category-content">
                                <div class="category-name">{{ $categorie->nom }}</div>
                                <p class="category-desc">{{ $categorie->description ?? 'Découvrez notre sélection' }}</p>
                                <a href="{{ route('menu.index') }}" class="btn-explore">
                                    <i class="fas fa-eye"></i> Découvrir
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer-custom">
            <p class="mb-0">© 2024 {{ config('app.name') }}. Tous droits réservés.</p>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
