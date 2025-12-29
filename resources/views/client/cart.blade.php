@extends('layouts.client')

@section('title', 'Mon Panier')

@section('extra-styles')
<style>
    body {
        font-size: 13px !important;
    }

    .cart-container {
        max-width: 1200px;
    }

    .cart-header {
        margin-bottom: 25px;
    }

    .cart-header h1 {
        font-weight: 700;
        margin-bottom: 6px;
        font-size: 1.4rem;
    }

    .cart-header p {
        font-size: 0.8rem;
    }

    .cart-item {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 12px;
        display: grid;
        grid-template-columns: 70px 1fr auto;
        gap: 15px;
        align-items: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .cart-item-image {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        object-fit: cover;
        background: #f0f0f0;
    }

    .cart-item-details h5 {
        font-weight: 700;
        margin-bottom: 3px;
        font-size: 0.9rem;
    }

    .cart-item-details p {
        color: #999;
        font-size: 0.75rem;
        margin-bottom: 8px;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 8px;
        width: fit-content;
        flex-wrap: wrap;
    }

    .qty-btn {
        width: 28px;
        height: 28px;
        border: 1px solid #ddd;
        background: white;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.8rem;
    }

    .qty-btn:hover {
        border-color: #d32f2f;
        color: #d32f2f;
    }

    .qty-display {
        min-width: 30px;
        text-align: center;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .remove-btn {
        background: #ffebee;
        color: #d32f2f;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.75rem;
        transition: all 0.3s ease;
    }

    .remove-btn:hover {
        background: #d32f2f;
        color: white;
    }

    .cart-summary {
        background: white;
        border-radius: 10px;
        padding: 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.85rem;
    }

    .summary-row.total {
        border: none;
        padding-top: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        color: #d32f2f;
    }

    .btn-checkout {
        width: 100%;
        background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 15px;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }

    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(211, 47, 47, 0.4);
    }

    .empty-cart {
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

    .cart-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .cart-item {
            grid-template-columns: 60px 1fr auto;
            gap: 12px;
        }

        .cart-item-image {
            width: 60px;
            height: 60px;
        }

        .cart-grid {
            grid-template-columns: 1fr;
        }

        .cart-summary {
            position: static;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="cart-header">
        <h1><i class="fas fa-shopping-cart"></i> Mon Panier</h1>
        <p class="text-muted">{{ $cartCount }} article(s) dans votre panier</p>
    </div>

    @if (count($items) > 0)
        <div class="cart-grid">
            <div>
                @foreach ($items as $item)
                    <div class="cart-item" data-plat-id="{{ $item['id'] }}">
                        @if ($item['image'])
                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['nom'] }}" class="cart-item-image">
                        @else
                            <div class="cart-item-image" style="background: linear-gradient(135deg, #FF6B6B 0%, #FF8E72 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-utensils" style="font-size: 1.5rem; color: white;"></i>
                            </div>
                        @endif

                        <div class="cart-item-details">
                            <h5>{{ $item['nom'] }}</h5>
                            @if ($item['description'])
                                <p>{{ substr($item['description'], 0, 60) }}...</p>
                            @endif
                            <div class="quantity-control">
                                <button class="qty-btn" onclick="updateQty({{ $item['id'] }}, -1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="qty-display" id="qty-{{ $item['id'] }}">{{ $item['quantite'] }}</span>
                                <button class="qty-btn" onclick="updateQty({{ $item['id'] }}, 1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button class="remove-btn ms-2" onclick="removeItem({{ $item['id'] }})">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>

                        <div style="text-align: right; min-width: 90px;">
                            <div style="font-weight: 600; color: #d32f2f; font-size: 0.95rem;">
                                {{ number_format($item['sousTotal'], 0, ',', ' ') }} CFA
                            </div>
                            <div style="color: #999; font-size: 0.75rem; margin-top: 4px;">
                                x {{ $item['quantite'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary" style="height: fit-content; position: sticky; top: 20px;">
                <h5 class="mb-3" style="font-weight: 700;">Résumé</h5>

                <div class="summary-row">
                    <span>Sous-total HT</span>
                    <span>{{ number_format($subTotal, 0, ',', ' ') }} CFA</span>
                </div>

                <div class="summary-row">
                    <span>TVA (19.6%)</span>
                    <span>{{ number_format($tva, 0, ',', ' ') }} CFA</span>
                </div>

                <div class="summary-row total">
                    <span>TOTAL TTC</span>
                    <span>{{ number_format($total, 0, ',', ' ') }} CFA</span>
                </div>

                <a href="{{ route('client.checkout-form') }}" class="btn-checkout">
                    <i class="fas fa-credit-card"></i> Procéder au paiement
                </a>

                <a href="{{ route('client.menu') }}" class="btn btn-outline-primary w-100 mt-2">
                    <i class="fas fa-arrow-left"></i> Continuer les achats
                </a>

                <button onclick="clearCart()" class="btn btn-outline-danger w-100 mt-2">
                    <i class="fas fa-trash"></i> Vider le panier
                </button>
            </div>
        </div>
    @else
        <div class="empty-cart">
            <div class="empty-icon"><i class="fas fa-shopping-cart"></i></div>
            <h3 style="color: #999; margin-bottom: 15px;">Panier vide</h3>
            <p class="text-muted mb-4">Ajoutez des plats à votre panier pour commencer</p>
            <a href="{{ route('client.menu') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); border: none; padding: 12px 30px;">
                <i class="fas fa-utensils"></i> Parcourir le menu
            </a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function updateQty(platId, change) {
        const qtyEl = document.getElementById(`qty-${platId}`);
        let newQty = parseInt(qtyEl.textContent) + change;

        if (newQty < 1) return removeItem(platId);

        fetch(`/client/order/cart/update/${platId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ quantity: newQty })
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) location.reload();
        })
        .catch(e => console.error(e));
    }

    function removeItem(platId) {
        if (confirm('Supprimer cet article?')) {
            fetch(`/client/order/remove/${platId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) location.reload();
            });
        }
    }

    function clearCart() {
        if (confirm('Vider tout le panier?')) {
            fetch(`/client/order/clear`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) location.reload();
            });
        }
    }
</script>
@endsection
