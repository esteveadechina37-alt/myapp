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
                    @if(!auth()->check())
                        <li><a href="/login">Connexion</a></li>
                    @else
                        <li><a href="{{ route('client.dashboard') }}">Tableau de bord</a></li>
                    @endif
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

<style>
    .footer {
        background: #1a1a1a;
        color: #eee;
        padding: 50px 0 20px;
        margin-top: 80px;
    }

    .footer h5 {
        color: #d32f2f;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .footer p {
        font-size: 0.9rem;
        line-height: 1.8;
    }

    .footer-links {
        list-style: none;
        padding: 0;
    }

    .footer-links li {
        margin-bottom: 10px;
    }

    .footer-links a {
        color: #bbb;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .footer-links a:hover {
        color: #d32f2f;
        padding-left: 5px;
    }

    .socials {
        display: flex;
        gap: 15px;
    }

    .socials a {
        display: inline-block;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .socials a:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
    }

    .footer-bottom {
        border-top: 1px solid #333;
        padding-top: 20px;
        margin-top: 30px;
        text-align: center;
        color: #999;
    }
</style>
