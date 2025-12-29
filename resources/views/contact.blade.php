<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Trial+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f7fa;
        }

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

        .header-section {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            padding: 60px 0 40px;
            text-align: center;
        }

        .header-section h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .header-section p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .content-section {
            padding: 60px 0;
        }

        .contact-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .contact-info-item {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .contact-info-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            flex-shrink: 0;
        }

        .contact-info-content h5 {
            color: #333;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .contact-info-content p {
            color: #666;
            margin: 0;
            font-size: 1rem;
        }

        .contact-form {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .form-label {
            color: #333;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #d32f2f;
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
        }

        .form-control::placeholder {
            color: #999;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 150px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border: none;
            padding: 14px 35px;
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

        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .map-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            height: 400px;
            margin-bottom: 30px;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .horaires-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .horaire-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .horaire-item:last-child {
            border-bottom: none;
        }

        .horaire-jour {
            font-weight: 600;
            color: #333;
        }

        .horaire-heure {
            color: #d32f2f;
            font-weight: 600;
        }

        footer {
            background: #222;
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
        }

        footer h5 {
            font-weight: 700;
            margin-bottom: 15px;
        }

        footer p {
            color: #aaa;
            margin-bottom: 10px;
        }

        footer a {
            color: #aaa;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #d32f2f;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 8px;
        }

        .socials a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: #333;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .socials a:hover {
            background: #d32f2f;
            transform: scale(1.1);
        }

        .social-icon {
            color: white;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #333;
            color: #aaa;
        }

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
    <!-- <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
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
                        <a class="nav-link active" href="/contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-connexion ms-3" data-bs-toggle="modal" data-bs-target="#authModal">
                            <i class="fas fa-sign-in-alt"></i> Connexion
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav> -->

    <!-- Header -->
    <section class="header-section">
        <div class="container">
            <h1><i class="fas fa-envelope"></i> Nous Contacter</h1>
            <p>Nous sommes à votre écoute 24/7 pour répondre à vos questions</p>
        </div>
    </section>

    <!-- Content -->
    <section class="content-section">
        <div class="container">
            <div class="row">
                <!-- Formulaire -->
                <div class="col-lg-6 mb-4">
                    <div class="contact-form">
                        <h3 class="mb-4"><i class="fas fa-pen-fancy"></i> Envoyez-nous un Message</h3>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

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

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="nom" class="form-label">
                                    <i class="fas fa-user"></i> Nom Complet
                                </label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" placeholder="Votre nom" value="{{ old('nom') }}" required>
                                @error('nom')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="votre@email.com" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="telephone" class="form-label">
                                    <i class="fas fa-phone"></i> Téléphone
                                </label>
                                <input type="tel" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" placeholder="+33 1 23 45 67 89" value="{{ old('telephone') }}">
                                @error('telephone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="sujet" class="form-label">
                                    <i class="fas fa-list"></i> Sujet
                                </label>
                                <select class="form-control @error('sujet') is-invalid @enderror" id="sujet" name="sujet" required>
                                    <option value="">-- Sélectionnez un sujet --</option>
                                    <option value="reservation">Réservation</option>
                                    <option value="menu">Menu & Tarifs</option>
                                    <option value="commande">Commande</option>
                                    <option value="reclamation">Réclamation</option>
                                    <option value="partenariat">Partenariat</option>
                                    <option value="autre">Autre</option>
                                </select>
                                @error('sujet')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">
                                    <i class="fas fa-comment"></i> Message
                                </label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" placeholder="Votre message..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-paper-plane"></i> Envoyer le Message
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Informations de Contact -->
                <div class="col-lg-6">
                    <div class="contact-card">
                        <h3 class="mb-4"><i class="fas fa-info-circle"></i> Nos Coordonnées</h3>

                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Adresse</h5>
                                <p>Rue de l'Indépendance<br>Porto-Novo, Bénin</p>
                            </div>
                        </div>

                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Téléphone</h5>
                                <p><a href="tel:+33123456789" style="color: inherit; text-decoration: none;">+33 1 23 45 67 89</a></p>
                                <p><small style="color: #999;">Lun-Dim: 11h-23h</small></p>
                            </div>
                        </div>

                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Email</h5>
                                <p><a href="mailto:contact@restaurant.fr" style="color: inherit; text-decoration: none;">contact@restaurant.fr</a></p>
                                <p><small style="color: #999;">Réponse sous 24h</small></p>
                            </div>
                        </div>
                    </div>

                    <!-- Horaires -->
                    <div class="horaires-section">
                        <h4 class="mb-4"><i class="fas fa-clock"></i> Nos Horaires</h4>
                        <div class="horaire-item">
                            <span class="horaire-jour">Lundi - Jeudi</span>
                            <span class="horaire-heure">11h30 - 14h00 / 18h00 - 23h00</span>
                        </div>
                        <div class="horaire-item">
                            <span class="horaire-jour">Vendredi - Samedi</span>
                            <span class="horaire-heure">11h30 - 14h00 / 18h00 - 00h00</span>
                        </div>
                        <div class="horaire-item">
                            <span class="horaire-jour">Dimanche</span>
                            <span class="horaire-heure">12h00 - 23h00 (Continu)</span>
                        </div>
                        <div class="horaire-item">
                            <span class="horaire-jour">Jours Fériés</span>
                            <span class="horaire-heure">Ouvert 12h00 - 23h00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3985.6587823486485!2d2.6327!3d6.4969!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1029a0b8d9d9d9d9%3A0xd9d9d9d9d9d9d9d9!2sRue%20de%20l%27Ind%C3%A9pendance%2C%20Porto-Novo%2C%20B%C3%A9nin!5e0!3m2!1sfr!2sfr!4v1234567890" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
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
                        <li><a href="/contact">Contact</a></li>
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
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f social-icon"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter social-icon"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram social-icon"></i></a>
                        <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in social-icon"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Trial+. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Auth Modal -->
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
