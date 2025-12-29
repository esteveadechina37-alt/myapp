<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Gestion Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #d32f2f;
            --secondary-color: #1976d2;
            --success-color: #388e3c;
            --warning-color: #f57c00;
            --danger-color: #d32f2f;
            --dark-color: #212121;
            --light-color: #f5f5f5;
        }

        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-color);
        }

        /* Navigation */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: white !important;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: all 0.3s ease;
            margin: 0 5px;
        }

        .nav-link:hover {
            color: white !important;
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
        }

        /* Sidebar */
        .sidebar {
            background: white;
            min-height: 100vh;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            padding-top: 2rem;
        }

        .sidebar .nav-link {
            color: var(--dark-color);
            padding: 12px 20px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .sidebar .nav-link:hover {
            background-color: var(--light-color);
            border-left-color: var(--primary-color);
            color: var(--primary-color);
        }

        .sidebar .nav-link.active {
            background-color: var(--light-color);
            border-left-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: bold;
        }

        /* Main Content */
        .main-content {
            padding: 2rem;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.4);
            background: linear-gradient(135deg, #b71c1c 0%, #c62828 100%);
        }

        .btn-secondary {
            background: var(--secondary-color);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
        }

        .btn-success {
            background: var(--success-color);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
        }

        /* Badges */
        .badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-success {
            background-color: var(--success-color);
        }

        .badge-warning {
            background-color: var(--warning-color);
            color: white;
        }

        .badge-danger {
            background-color: var(--danger-color);
            color: white;
        }

        /* Table */
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--danger-color) 100%);
            color: white;
            font-weight: bold;
            border: none;
            padding: 15px;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: var(--light-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        /* Alert */
        .alert {
            border-radius: 10px;
            border: none;
        }

        /* Footer */
        footer {
            background: var(--dark-color);
            color: white;
            padding: 2rem;
            margin-top: 3rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-bottom: 2rem;
            }

            .navbar-brand {
                font-size: 1.4rem;
            }
        }

        /* Loading spinner */
        .spinner-custom {
            border: 3px solid var(--light-color);
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Menu Items */
        .menu-item {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .menu-item:hover {
            border-left-color: var(--primary-color);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateX(5px);
        }

        .menu-item-title {
            font-weight: bold;
            color: var(--dark-color);
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .menu-item-price {
            color: var(--primary-color);
            font-weight: bold;
            font-size: 1.2rem;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-utensils"></i> Restaurant
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('menu.index') }}">
                                <i class="fas fa-book"></i> Menu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('commandes.list') }}">
                                <i class="fas fa-shopping-cart"></i> Commandes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('factures.index') }}">
                                <i class="fas fa-file-invoice"></i> Factures
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->prenom }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Paramètres</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Connexion
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (optionnel) -->
            @yield('sidebar')

            <!-- Content -->
            <div class="@yield('col-class', 'col-12') main-content">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading"><i class="fas fa-exclamation-circle"></i> Erreur(s)</h4>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <h5>À propos</h5>
                    <p>Système de gestion complet pour restaurants modernes.</p>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p><i class="fas fa-phone"></i> +33 1 23 45 67 89<br>
                       <i class="fas fa-envelope"></i> contact@restaurant.fr</p>
                </div>
                <div class="col-md-4">
                    <h5>Horaires</h5>
                    <p>Lun-Dim: 12h00 - 14h00 / 19h00 - 23h00</p>
                </div>
            </div>
            <hr>
            <p class="text-center mb-0">&copy; 2024 Gestion Restaurant. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>
