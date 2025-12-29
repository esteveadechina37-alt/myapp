@extends('layouts.client')

@section('title', 'Menu')

@section('extra-styles')
<style>
    body {
        font-size: 13px !important;
    }

    .menu-header {
        background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
        color: white;
        padding: 25px 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
    }

    .menu-header h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
    }

    .menu-header p {
        font-size: 0.8rem;
        opacity: 0.9;
        margin: 0;
    }

    .cart-link {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
        font-size: 0.85rem;
    }

    .cart-link:hover {
        background: rgba(255,255,255,0.3);
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ffc107;
        color: #333;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.7rem;
    }

    .search-bar {
        margin-bottom: 20px;
    }

    .search-bar input {
        width: 100%;
        padding: 10px 16px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .search-bar input:focus {
        outline: none;
        border-color: #d32f2f;
        box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
    }

    .categories {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        overflow-x: auto;
        padding-bottom: 8px;
    }

    .category-btn {
        padding: 8px 16px;
        border: 2px solid #ddd;
        background: white;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 600;
        white-space: nowrap;
        transition: all 0.3s ease;
        font-size: 0.8rem;
    }

    .category-btn:hover,
    .category-btn.active {
        border-color: #d32f2f;
        background: #d32f2f;
        color: white;
    }

    .plats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }

    .plat-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .plat-card:hover {
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }

    .plat-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E72 100%);
    }

    .plat-body {
        padding: 12px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .plat-name {
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 4px;
        color: #333;
        line-height: 1.3;
    }

    .plat-description {
        color: #999;
        font-size: 0.75rem;
        margin-bottom: 8px;
        min-height: 30px;
        flex: 1;
        line-height: 1.3;
    }

    .plat-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .plat-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: #d32f2f;
    }

    .add-to-cart-btn {
        width: 100%;
        background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
        color: white;
        border: none;
        padding: 8px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.75rem;
    }

    .add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(211, 47, 47, 0.4);
    }

    .qty-selector {
        display: flex;
        align-items: center;
        gap: 4px;
        background: #f0f0f0;
        border-radius: 4px;
        padding: 2px 4px;
    }

    .qty-btn {
        width: 20px;
        height: 20px;
        border: none;
        background: white;
        border-radius: 3px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.8rem;
    }

    .qty-btn:hover {
        background: #d32f2f;
        color: white;
    }

    .qty-display {
        min-width: 18px;
        text-align: center;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .empty-icon {
        font-size: 2.5rem;
        color: #ccc;
        margin-bottom: 12px;
    }

    @media (max-width: 768px) {
        .plats-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        }

        .menu-header {
            flex-direction: column;
            text-align: center;
        }

        .menu-header h1 {
            font-size: 1.2rem;
        }

        .plat-image {
            height: 120px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="menu-header">
        <div>
            <h1><i class="fas fa-utensils"></i> Notre Menu</h1>
            <p class="mb-0">Découvrez nos délicieux plats</p>
        </div>
        <a href="{{ route('client.view-cart') }}" class="cart-link">
            <i class="fas fa-shopping-cart"></i> Panier
            @if ($cartCount > 0)
                <span class="cart-badge">{{ $cartCount }}</span>
            @endif
        </a>
    </div>

    <!-- Recherche -->
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="🔍 Rechercher un plat..." onkeyup="searchPlats()">
    </div>

    <!-- Catégories -->
    @if ($categories->count() > 0)
        <div class="categories">
            <button class="category-btn active" onclick="filterCategory('all')">Tous</button>
            @foreach ($categories as $categorie)
                <button class="category-btn" onclick="filterCategory({{ $categorie->id }})">
                    {{ $categorie->nom }}
                </button>
            @endforeach
        </div>
    @endif

    <!-- Grille de plats -->
    <div id="platsContainer">
        <div class="plats-grid">
            @foreach ($categories as $categorie)
                @if ($categorie->plats->count() > 0)
                    @foreach ($categorie->plats as $plat)
                        <div class="plat-card" data-categorie="{{ $categorie->id }}" data-nom="{{ strtolower($plat->nom) }}">
                            @if ($plat->image)
                                <img src="{{ asset('storage/' . $plat->image) }}" alt="{{ $plat->nom }}" class="plat-image">
                            @else
                                <div class="plat-image" style="background: linear-gradient(135deg, #FF6B6B 0%, #FF8E72 100%); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-utensils" style="font-size: 3rem; color: white;"></i>
                                </div>
                            @endif

                            <div class="plat-body">
                                <h5 class="plat-name">{{ $plat->nom }}</h5>
                                <p class="plat-description">{{ substr($plat->description, 0, 80) }}...</p>

                                <div class="plat-footer">
                                    <span class="plat-price">{{ number_format($plat->prix, 0, ',', ' ') }} CFA</span>
                                    <div class="qty-selector">
                                        <button class="qty-btn" onclick="decrementQty(this)">−</button>
                                        <span class="qty-display">1</span>
                                        <button class="qty-btn" onclick="incrementQty(this)">+</button>
                                    </div>
                                </div>

                                <button class="add-to-cart-btn" onclick="addToCart({{ $plat->id }}, this)">
                                    <i class="fas fa-shopping-cart"></i> Ajouter
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>

    @if ($categories->sum(function($c) { return $c->plats->count(); }) === 0)
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-inbox"></i></div>
            <h3 style="color: #999; margin-bottom: 15px;">Aucun plat disponible</h3>
            <p class="text-muted">Notre menu sera bientôt enrichi!</p>
        </div>
    @endif
</div>

<!-- Toast notification -->
<div id="toast" style="position: fixed; bottom: 20px; right: 20px; background: #4caf50; color: white; padding: 15px 20px; border-radius: 8px; display: none; z-index: 1000;">
    <i class="fas fa-check-circle"></i> <span id="toastMessage"></span>
</div>
@endsection

@section('scripts')
<script>
    function addToCart(platId, btn) {
        const qtyDisplay = btn.previousElementSibling.querySelector('.qty-display');
        const quantity = parseInt(qtyDisplay.textContent);

        fetch(`/client/order/add/${platId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    function incrementQty(btn) {
        const display = btn.nextElementSibling;
        display.textContent = parseInt(display.textContent) + 1;
    }

    function decrementQty(btn) {
        const display = btn.nextElementSibling;
        const qty = parseInt(display.textContent);
        if (qty > 1) {
            display.textContent = qty - 1;
        }
    }

    function filterCategory(catId) {
        const cards = document.querySelectorAll('.plat-card');
        cards.forEach(card => {
            if (catId === 'all' || card.getAttribute('data-categorie') == catId) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });

        document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
    }

    function searchPlats() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.plat-card');
        cards.forEach(card => {
            const nom = card.getAttribute('data-nom');
            if (nom.includes(search)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function showToast(message) {
        const toast = document.getElementById('toast');
        document.getElementById('toastMessage').textContent = message;
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }
</script>
@endsection
