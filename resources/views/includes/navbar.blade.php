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
                @if(auth()->check())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('client.dashboard') }}">
                            <i class="fas fa-chart-line"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('client.menu') }}">
                            <i class="fas fa-utensils"></i> Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('client.order-history') }}">
                            <i class="fas fa-shopping-cart"></i> Commandes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('client.invoices') }}">
                            <i class="fas fa-file-invoice"></i> Factures
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-connexion ms-3">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                @else
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
                @endif
            </ul>
        </div>
    </div>
</nav>
