<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À Propos - Trial+</title>
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

        /* Header */
        .header-section {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
        }

        .header-section h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header-section p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* Contenu */
        .content-section {
            padding: 80px 0;
            background: white;
        }

        .content-section h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 30px;
            color: #333;
        }

        .content-section p {
            font-size: 1.05rem;
            line-height: 1.8;
            color: #666;
            margin-bottom: 20px;
        }

        .team-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .team-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }

        .team-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 3rem;
            color: white;
        }

        .team-name {
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .team-role {
            color: #d32f2f;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .value-card {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            border-left: 4px solid #d32f2f;
        }

        .value-icon {
            font-size: 2.5rem;
            color: #d32f2f;
            margin-bottom: 15px;
        }

        .value-title {
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 10px;
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
            justify-content: center;
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

        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 2rem;
            }

            .content-section h2 {
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

    <!-- Header -->
    <section class="header-section">
        <div class="container">
            <h1><i class="fas fa-info-circle"></i> À Propos de Nous</h1>
            <p>Découvrez l'histoire et les valeurs de notre restaurant</p>
        </div>
    </section>

    <!-- Notre Histoire -->
    <section class="content-section">
        <div class="container">
            <h2>Notre Histoire</h2>
            <p>
                Depuis plus de 10 ans, le Trial+ offre une expérience culinaire exceptionnelle à ses clients. 
                Fondé par une famille passionnée par la gastronomie, notre établissement s'est construit sur des valeurs 
                simples mais fondamentales : la qualité, l'authenticité et le service irréprochable.
            </p>
            <p>
                Chaque plat est préparé avec soin par nos chefs expérimentés, utilisant les meilleurs ingrédients frais 
                disponibles. Nous croyons que la bonne nourriture crée des moments mémorables et renforce les liens entre 
                les gens.
            </p>
            <p>
                Notre évolution vers la digitalisation avec ce système de gestion reflète notre engagement envers l'innovation 
                tout en préservant nos traditions culinaires et notre cœur de métier.
            </p>
        </div>
    </section>

    <!-- Notre Équipe -->
    <section class="content-section" style="background: #f8f9fa;">
        <div class="container">
            <h2 style="text-align: center;">Notre Équipe</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="team-card">
                        <div class="team-avatar">👨‍🍳</div>
                        <div class="team-name">Chef Pierre</div>
                        <div class="team-role">Chef Cuisinier</div>
                        <p>Maître cuisinier avec 15 ans d'expérience dans les meilleurs restaurants.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="team-card">
                        <div class="team-avatar">👩‍💼</div>
                        <div class="team-name">Marie Dubois</div>
                        <div class="team-role">Gérante</div>
                        <p>Gestionnaire passionnée assurant l'excellence du service chaque jour.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="team-card">
                        <div class="team-avatar">👨‍💼</div>
                        <div class="team-name">Jean Martin</div>
                        <div class="team-role">Responsable Service</div>
                        <p>Coordonnateur expérimenté garantissant une expérience client impeccable.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Nos Valeurs -->
    <section class="content-section">
        <div class="container">
            <h2 style="text-align: center;">Nos Valeurs</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-star"></i></div>
                    <div class="value-title">Qualité</div>
                    <p>Nous sélectionnons les meilleurs ingrédients pour garantir des plats savoureux et sains.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-heart"></i></div>
                    <div class="value-title">Passion</div>
                    <p>Chaque plat est préparé avec passion et amour pour nos clients.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-handshake"></i></div>
                    <div class="value-title">Respect</div>
                    <p>Nous respectons nos clients, notre équipe et l'environnement.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon"><i class="fas fa-rocket"></i></div>
                    <div class="value-title">Innovation</div>
                    <p>Nous innovons constamment pour améliorer votre expérience.</p>
                </div>
            </div>
        </div>
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
    </script>
</body>
</html>
