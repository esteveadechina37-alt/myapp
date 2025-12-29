<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Trial+</title>
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

        /* Content */
        .content-section {
            padding: 80px 0;
            background: white;
        }

        .content-section h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
            color: #333;
        }

        /* Accordions */
        .accordion {
            margin-top: 40px;
        }

        .accordion-item {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .accordion-button {
            background-color: #f8f9fa;
            color: #333;
            font-weight: 600;
            padding: 20px;
            box-shadow: none;
            border: none;
        }

        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
        }

        .accordion-button:focus {
            border-color: #d32f2f;
            box-shadow: 0 0 0 0.25rem rgba(211, 47, 47, 0.25);
        }

        .accordion-body {
            padding: 20px;
            color: #666;
            font-size: 1.05rem;
            line-height: 1.8;
        }

        .faq-icon {
            margin-right: 10px;
            color: #d32f2f;
        }

        .category-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #333;
            margin-top: 50px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #d32f2f;
        }

        .search-box {
            margin-bottom: 30px;
        }

        .search-box input {
            padding: 12px 20px;
            border: 2px solid #d32f2f;
            border-radius: 25px;
            font-size: 1rem;
        }

        .search-box input:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.1);
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
            <h1><i class="fas fa-question-circle"></i> Questions Fréquentes</h1>
            <p>Trouvez les réponses à vos questions</p>
        </div>
    </section>

    <!-- FAQ Content -->
    <section class="content-section">
        <div class="container">
            <h2>Foire Aux Questions</h2>
            
            <!-- Search Box -->
            <div class="search-box">
                <input type="text" class="form-control" id="faqSearch" placeholder="🔍 Recherchez une question...">
            </div>

            <!-- Général -->
            <div class="category-title"><i class="fas fa-info-circle faq-icon"></i>Général</div>
            <div class="accordion" id="generalAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#general1">
                            <i class="fas fa-restaurant faq-icon"></i>Qu'est-ce que Trial+?
                        </button>
                    </h2>
                    <div id="general1" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                        <div class="accordion-body">
                            Trial+ est un établissement gastronomique proposant une cuisine raffinée et diversifiée. 
                            Nous utilisons des ingrédients frais et de qualité pour offrir une expérience culinaire exceptionnelle 
                            à nos clients. Notre système de gestion permet une meilleure organisation des commandes et des réservations.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#general2">
                            <i class="fas fa-history faq-icon"></i>Depuis combien de temps existez-vous?
                        </button>
                    </h2>
                    <div id="general2" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                        <div class="accordion-body">
                            Trial+ existe depuis plus de 10 ans. Nous avons construit notre réputation sur la qualité 
                            de nos plats, le professionnalisme de notre équipe et notre engagement envers la satisfaction de nos clients.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#general3">
                            <i class="fas fa-map-marker-alt faq-icon"></i>Où êtes-vous situés?
                        </button>
                    </h2>
                    <div id="general3" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                        <div class="accordion-body">
                            Nous sommes situés à Porto-Novo, Bénin, Rue de l'Indépendance. Notre localisation est stratégique 
                            nous permet d'être facilement accessible en transport en commun et en voiture. Nous disposons également 
                            d'un parking à proximité.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horaires et Réservations -->
            <div class="category-title"><i class="fas fa-calendar-alt faq-icon"></i>Horaires et Réservations</div>
            <div class="accordion" id="horaireAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#horaire1">
                            <i class="fas fa-clock faq-icon"></i>Quels sont vos horaires d'ouverture?
                        </button>
                    </h2>
                    <div id="horaire1" class="accordion-collapse collapse" data-bs-parent="#horaireAccordion">
                        <div class="accordion-body">
                            <strong>Lundi à Vendredi:</strong> 11h30-14h00 et 18h30-22h00<br>
                            <strong>Samedi:</strong> 12h00-23h00 (service continu)<br>
                            <strong>Dimanche:</strong> 12h00-21h00<br>
                            <strong>Note:</strong> Nous sommes fermés le lundi. Veuillez consulter notre site pour les jours fériés.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#horaire2">
                            <i class="fas fa-book faq-icon"></i>Comment faire une réservation?
                        </button>
                    </h2>
                    <div id="horaire2" class="accordion-collapse collapse" data-bs-parent="#horaireAccordion">
                        <div class="accordion-body">
                            Vous pouvez faire une réservation de plusieurs façons:
                            <ul style="margin-top: 10px;">
                                <li>En ligne via notre système de gestion (créez un compte)</li>
                                <li>Par téléphone: 01 23 45 67 89</li>
                                <li>Par email: contact@restaurant.fr</li>
                                <li>En vous présentant directement au restaurant</li>
                            </ul>
                            Nous recommandons de réserver au moins 24 heures à l'avance pour les groupes de plus de 4 personnes.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#horaire3">
                            <i class="fas fa-users faq-icon"></i>Acceptez-vous les réservations pour groupes?
                        </button>
                    </h2>
                    <div id="horaire3" class="accordion-collapse collapse" data-bs-parent="#horaireAccordion">
                        <div class="accordion-body">
                            Oui, nous acceptons les réservations pour groupes. Pour les groupes de 8 personnes ou plus, 
                            veuillez nous contacter directement pour discuter des arrangements spéciaux, des menus de groupe 
                            et de la disponibilité.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu et Allergies -->
            <div class="category-title"><i class="fas fa-utensils faq-icon"></i>Menu et Allergies</div>
            <div class="accordion" id="menuAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#menu1">
                            <i class="fas fa-leaf faq-icon"></i>Proposez-vous des options végétariennes?
                        </button>
                    </h2>
                    <div id="menu1" class="accordion-collapse collapse" data-bs-parent="#menuAccordion">
                        <div class="accordion-body">
                            Oui, nous proposons une sélection de plats végétariens sur notre menu. Nos chefs peuvent également 
                            préparer des plats selon vos préférences alimentaires. N'hésitez pas à informer le serveur de vos 
                            restrictions alimentaires à votre arrivée.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#menu2">
                            <i class="fas fa-exclamation-triangle faq-icon"></i>Comment informez-vous des allergènes?
                        </button>
                    </h2>
                    <div id="menu2" class="accordion-collapse collapse" data-bs-parent="#menuAccordion">
                        <div class="accordion-body">
                            La sécurité alimentaire de nos clients est notre priorité. Tous les allergènes majeurs sont 
                            identifiés sur le menu. Si vous avez une allergie spécifique, veuillez en informer immédiatement 
                            un membre de notre équipe. Nous disposons d'une liste détaillée des ingrédients pour chaque plat.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#menu3">
                            <i class="fas fa-baby faq-icon"></i>Avez-vous un menu enfants?
                        </button>
                    </h2>
                    <div id="menu3" class="accordion-collapse collapse" data-bs-parent="#menuAccordion">
                        <div class="accordion-body">
                            Oui, nous avons un menu spécial enfants adapté à leurs goûts et besoins nutritionnels. 
                            Ce menu propose des portions réduites et des plats populaires auprès des enfants, à des prix avantageux.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Livraison et Commandes -->
            <div class="category-title"><i class="fas fa-truck faq-icon"></i>Livraison et Commandes</div>
            <div class="accordion" id="livraisonAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#livraison1">
                            <i class="fas fa-box faq-icon"></i>Proposez-vous des commandes à emporter?
                        </button>
                    </h2>
                    <div id="livraison1" class="accordion-collapse collapse" data-bs-parent="#livraisonAccordion">
                        <div class="accordion-body">
                            Oui, nous proposons des commandes à emporter. Vous pouvez commander via notre système en ligne 
                            ou par téléphone. Les commandes sont généralement prêtes en 30-45 minutes. Nous veillons à utiliser 
                            des emballages de qualité pour préserver la fraîcheur et la présentation de vos plats.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#livraison2">
                            <i class="fas fa-motorcycle faq-icon"></i>Livrez-vous à domicile?
                        </button>
                    </h2>
                    <div id="livraison2" class="accordion-collapse collapse" data-bs-parent="#livraisonAccordion">
                        <div class="accordion-body">
                            Oui, nous livrons à domicile dans un rayon de 5km autour de notre établissement. Les frais de 
                            livraison sont de 2,50€ pour les commandes de moins de 20€ et gratuits au-delà. Les délais de 
                            livraison sont généralement de 45 à 60 minutes selon votre localisation et le volume de commandes.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#livraison3">
                            <i class="fas fa-credit-card faq-icon"></i>Quels moyens de paiement acceptez-vous?
                        </button>
                    </h2>
                    <div id="livraison3" class="accordion-collapse collapse" data-bs-parent="#livraisonAccordion">
                        <div class="accordion-body">
                            Nous acceptons les moyens de paiement suivants:
                            <ul style="margin-top: 10px;">
                                <li>Cartes bancaires (Visa, Mastercard, American Express)</li>
                                <li>Paiements numériques (PayPal, Google Pay, Apple Pay)</li>
                                <li>Espèces (en restaurant uniquement)</li>
                                <li>Chèques (en restaurant uniquement)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact et Support -->
            <div class="category-title"><i class="fas fa-headset faq-icon"></i>Contact et Support</div>
            <div class="accordion" id="supportAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#support1">
                            <i class="fas fa-phone faq-icon"></i>Comment vous contacter?
                        </button>
                    </h2>
                    <div id="support1" class="accordion-collapse collapse" data-bs-parent="#supportAccordion">
                        <div class="accordion-body">
                            Vous pouvez nous contacter de plusieurs façons:
                            <ul style="margin-top: 10px;">
                                <li><strong>Téléphone:</strong> 01 23 45 67 89</li>
                                <li><strong>Email:</strong> contact@restaurant.fr</li>
                                <li><strong>Adresse:</strong> Rue de l'Indépendance, Porto-Novo, Bénin</li>
                                <li><strong>Formulaire:</strong> Via notre site web</li>
                            </ul>
                            Nous nous efforçons de répondre à tous les messages dans les 24 heures.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#support2">
                            <i class="fas fa-problem-solving faq-icon"></i>Que faire si j'ai un problème avec ma commande?
                        </button>
                    </h2>
                    <div id="support2" class="accordion-collapse collapse" data-bs-parent="#supportAccordion">
                        <div class="accordion-body">
                            Si vous avez un problème avec votre commande, veuillez nous contacter immédiatement. 
                            Nous proposons un service de garantie client et nous nous engageons à résoudre tout problème 
                            rapidement. Dans la plupart des cas, nous pouvons remplacer ou rembourser votre commande.
                        </div>
                    </div>
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
        // Simple search functionality
        document.getElementById('faqSearch').addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const accordionItems = document.querySelectorAll('.accordion-item');
            
            accordionItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });

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
