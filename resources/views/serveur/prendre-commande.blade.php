@extends('layouts.app')

@section('title', 'Prise de Commande')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h1 class="h3 fw-bold">
            <i class="fas fa-utensils" style="color: #1976d2;"></i> Prise de Commande
        </h1>
    </div>

    <div class="row">
        <!-- Plan des tables (gauche) -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-chair"></i> Plan des Tables</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2" id="tablesContainer">
                        @foreach($tables as $table)
                            <div class="col-md-4 col-lg-3">
                                <button class="btn btn-block w-100 border-2 py-3 table-btn {{ $table->statut === 'occupee' ? 'btn-danger' : 'btn-outline-success' }}"
                                        data-table-id="{{ $table->id }}"
                                        onclick="selectTable({{ $table->id }}, this)">
                                    <div class="text-center">
                                        <i class="fas fa-circle-{{ $table->statut === 'occupee' ? 'solid' : 'o' }}" style="font-size: 1.5rem;"></i>
                                        <div class="mt-2">
                                            <strong>Table {{ $table->numero }}</strong><br>
                                            <small>{{ $table->capacite }} places</small>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Panier (droite) -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-shopping-cart"></i> Commande</h6>
                </div>
                <div class="card-body">
                    <input type="hidden" id="selectedTableId" value="">

                    <div class="mb-3">
                        <label class="form-label">Table sélectionnée:</label>
                        <div id="selectedTableInfo" class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle"></i> Sélectionnez une table
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nombre de personnes:</label>
                        <input type="number" id="nbPersonnes" class="form-control" min="1" value="1">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catégorie:</label>
                        <select id="categorySelect" class="form-select" onchange="loadPlats(this.value)">
                            <option value="">-- Sélectionnez une catégorie --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Plats disponibles:</label>
                        <div id="pLatsList" class="list-group" style="max-height: 300px; overflow-y: auto;">
                            <div class="text-muted text-center py-3">Sélectionnez une catégorie</div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-2">Commande:</h6>
                    <div id="cartItems" class="mb-3" style="max-height: 250px; overflow-y: auto;">
                        <div class="text-muted text-center py-3">Panier vide</div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total:</span>
                            <strong id="subtotal">0.00€</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>TVA (20%):</span>
                            <strong id="tva">0.00€</strong>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2">
                            <span class="h6 mb-0">TOTAL:</span>
                            <strong class="h6 mb-0 text-primary" id="total">0.00€</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes:</label>
                        <textarea id="notes" class="form-control" rows="2" placeholder="Instructions spéciales..."></textarea>
                    </div>

                    <button class="btn btn-success w-100 btn-lg" onclick="validateAndSubmit()">
                        <i class="fas fa-check-circle"></i> Valider la Commande
                    </button>
                    <button class="btn btn-secondary w-100 mt-2" onclick="resetCart()">
                        <i class="fas fa-trash"></i> Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let cart = {};
let selectedTableId = null;

function selectTable(tableId, element) {
    selectedTableId = tableId;
    document.getElementById('selectedTableId').value = tableId;

    // Update UI
    document.querySelectorAll('.table-btn').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');

    // Get table info
    const table = document.querySelector(`button[data-table-id="${tableId}"]`);
    document.getElementById('selectedTableInfo').innerHTML = `
        <i class="fas fa-check-circle"></i> Table ${table.innerText.match(/\d+/)[0]} sélectionnée
    `;
}

function loadPlats(categoryId) {
    if (!categoryId) {
        document.getElementById('pLatsList').innerHTML = '<div class="text-muted text-center py-3">Sélectionnez une catégorie</div>';
        return;
    }

    fetch(`/api/menu/plats/${categoryId}`)
        .then(r => r.json())
        .then(plats => {
            let html = '';
            plats.forEach(plat => {
                html += `
                    <div class="list-group-item cursor-pointer" onclick="addToCart(${plat.id}, '${plat.nom}', ${plat.prix})">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>${plat.nom}</strong><br>
                                <small class="text-muted">${plat.description}</small>
                            </div>
                            <span class="badge bg-success">${plat.prix}€</span>
                        </div>
                    </div>
                `;
            });
            document.getElementById('pLatsList').innerHTML = html;
        });
}

function addToCart(platId, nom, prix) {
    if (!cart[platId]) {
        cart[platId] = { nom, prix, quantite: 0 };
    }
    cart[platId].quantite++;
    updateCart();
}

function removeFromCart(platId) {
    if (cart[platId]) {
        cart[platId].quantite--;
        if (cart[platId].quantite <= 0) {
            delete cart[platId];
        }
    }
    updateCart();
}

function updateCart() {
    let html = '';
    let total = 0;

    Object.entries(cart).forEach(([id, item]) => {
        const subtotal = item.prix * item.quantite;
        total += subtotal;
        html += `
            <div class="card mb-2">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${item.nom}</strong><br>
                            <small class="text-muted">${item.prix}€</small>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${id})">-</button>
                            <span class="mx-2">${item.quantite}</span>
                            <button class="btn btn-sm btn-outline-success" onclick="addToCart(${id}, '${item.nom}', ${item.prix})">+</button>
                        </div>
                        <div class="text-end">
                            <strong>${subtotal.toFixed(2)}€</strong>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    if (!html) {
        html = '<div class="text-muted text-center py-3">Panier vide</div>';
    }

    document.getElementById('cartItems').innerHTML = html;

    const tva = total * 0.20;
    const finalTotal = total + tva;

    document.getElementById('subtotal').textContent = total.toFixed(2) + '€';
    document.getElementById('tva').textContent = tva.toFixed(2) + '€';
    document.getElementById('total').textContent = finalTotal.toFixed(2) + '€';
}

function validateAndSubmit() {
    if (!selectedTableId) {
        alert('❌ Sélectionnez une table');
        return;
    }

    if (Object.keys(cart).length === 0) {
        alert('❌ Le panier est vide');
        return;
    }

    const items = Object.entries(cart).map(([id, item]) => ({
        plat_id: id,
        quantite: item.quantite
    }));

    fetch('/serveur/store-commande', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            table_id: selectedTableId,
            type_commande: 'sur_place',
            nb_personnes: document.getElementById('nbPersonnes').value,
            items: items,
            notes: document.getElementById('notes').value
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('✓ Commande créée: #' + data.order_number);
            resetCart();
            location.href = '/serveur/commandes';
        } else {
            alert('✗ Erreur: ' + data.message);
        }
    })
    .catch(e => alert('Erreur réseau: ' + e.message));
}

function resetCart() {
    cart = {};
    selectedTableId = null;
    document.getElementById('selectedTableId').value = '';
    document.getElementById('selectedTableInfo').innerHTML = '<i class="fas fa-exclamation-triangle"></i> Sélectionnez une table';
    document.getElementById('categorySelect').value = '';
    document.getElementById('pLatsList').innerHTML = '<div class="text-muted text-center py-3">Sélectionnez une catégorie</div>';
    document.getElementById('notes').value = '';
    updateCart();
}
</script>
@endsection
