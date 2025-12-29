@extends('layouts.app')

@section('title', 'Prise de Commande')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="mb-4">
            <h1 class="h3 fw-bold">
                <i class="fas fa-utensils" style="color: #1976d2;"></i> Prise de Commande
            </h1>
            <p class="text-muted">Gestion des tables et commandes</p>
        </div>

        <div class="row">
            <!-- Plan des Tables -->
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%); color: white;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-chair"></i> Plan des Tables</h6>
                            <span id="tablesStatut" class="badge bg-light text-dark">0/30 occupées</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="tablesGrid" class="tables-grid">
                            <!-- Tables chargées par JS -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails Table Sélectionnée -->
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #43a047 0%, #2e7d32 100%); color: white;">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Détails Table</h6>
                    </div>
                    <div class="card-body">
                        <div id="tableDetail">
                            <p class="text-muted">Sélectionnez une table</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Menu Rapide -->
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white;">
                        <h6 class="mb-0"><i class="fas fa-search"></i> Rechercher Plat</h6>
                    </div>
                    <div class="card-body">
                        <input type="text" id="searchPlat" class="form-control mb-3" placeholder="Rechercher un plat...">
                        <div id="platsList" class="plats-grid">
                            <!-- Plats chargés par JS -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panier Table -->
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #7b1fa2 0%, #4a148c 100%); color: white;">
                        <h6 class="mb-0"><i class="fas fa-shopping-cart"></i> Commande Table</h6>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        <div id="commandePanier">
                            <p class="text-muted">Sélectionnez une table</p>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div id="commandeTotal" style="font-size: 1.2rem; font-weight: bold; margin-bottom: 10px; text-align: right;">
                            Total: 0 €
                        </div>
                        <div class="d-grid gap-2">
                            <button id="btnValiderCommande" class="btn btn-success" disabled>
                                <i class="fas fa-check"></i> Valider Commande
                            </button>
                            <button id="btnEffacerCommande" class="btn btn-outline-danger" disabled>
                                <i class="fas fa-trash"></i> Effacer Panier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nouvelle Commande -->
    <div class="modal fade" id="newCommandeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-gradient" style="background: linear-gradient(135deg, #43a047 0%, #2e7d32 100%); color: white;">
                    <h5 class="modal-title">Créer Nouvelle Commande</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Type de Commande</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="typeCmd" id="type_sur_place" value="sur_place" checked>
                                <label class="form-check-label" for="type_sur_place">
                                    <i class="fas fa-chair"></i> Sur Place
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="typeCmd" id="type_a_emporter" value="a_emporter">
                                <label class="form-check-label" for="type_a_emporter">
                                    <i class="fas fa-shopping-bag"></i> À Emporter
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="typeCmd" id="type_livraison" value="livraison">
                                <label class="form-check-label" for="type_livraison">
                                    <i class="fas fa-truck"></i> Livraison
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nombre de Personnes (si sur place)</label>
                        <input type="number" id="nbPersonnes" class="form-control" value="1" min="1" max="20">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="createNewCommande()">Créer</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(70px, 1fr));
            gap: 10px;
        }

        .table-btn {
            aspect-ratio: 1;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 5px;
            background: white;
        }

        .table-btn:hover {
            border-color: #1976d2;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.2);
        }

        .table-btn.libre {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-color: #43a047;
            color: #2e7d32;
        }

        .table-btn.occupee {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            border-color: #f57c00;
            color: #e65100;
        }

        .table-btn.active {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-color: #1976d2;
            color: #0d47a1;
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
        }

        .table-numero {
            font-size: 1.2rem;
        }

        .table-status {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .plats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
        }

        .plat-card {
            background: white;
            border: 2px solid #eee;
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .plat-card:hover {
            border-color: #d32f2f;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.2);
            transform: translateY(-2px);
        }

        .plat-card h6 {
            font-size: 0.9rem;
            margin: 5px 0;
            font-weight: 600;
        }

        .plat-prix {
            color: #d32f2f;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .panier-item {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .panier-item-info {
            flex: 1;
        }

        .panier-item-nom {
            font-weight: 600;
            margin-bottom: 3px;
        }

        .panier-item-prix {
            color: #d32f2f;
            font-weight: bold;
        }

        .panier-item-qty {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .panier-item-qty button {
            width: 24px;
            height: 24px;
            padding: 0;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 0.8rem;
            cursor: pointer;
        }

        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .card-header {
            color: white;
            border: none;
            padding: 15px 20px;
        }

        .bg-gradient {
            background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%);
        }
    </style>

    <script>
        let selectedTableId = null;
        let commandePanier = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadTables();
            loadPlats();

            document.getElementById('searchPlat').addEventListener('input', function(e) {
                const search = e.target.value.toLowerCase();
                document.querySelectorAll('.plat-card').forEach(card => {
                    card.style.display = card.textContent.toLowerCase().includes(search) ? '' : 'none';
                });
            });

            document.getElementById('btnValiderCommande').addEventListener('click', validerCommande);
            document.getElementById('btnEffacerCommande').addEventListener('click', () => {
                commandePanier = {};
                updatePanier();
            });
        });

        function loadTables() {
            fetch('/api/tables')
                .then(response => response.json())
                .then(data => {
                    const grid = document.getElementById('tablesGrid');
                    let occupees = 0;

                    grid.innerHTML = data.tables.map(table => {
                        const isOccupied = table.commande_active;
                        if (isOccupied) occupees++;

                        return `
                            <button class="table-btn ${isOccupied ? 'occupee' : 'libre'}" onclick="selectTable(${table.id}, '${table.numero}')">
                                <span class="table-numero">T${table.numero}</span>
                                <span class="table-status">${isOccupied ? 'Occupée' : 'Libre'}</span>
                            </button>
                        `;
                    }).join('');

                    document.getElementById('tablesStatut').textContent = `${occupees}/${data.tables.length} occupées`;
                })
                .catch(error => console.error('Error:', error));
        }

        function loadPlats() {
            fetch('/api/plats')
                .then(response => response.json())
                .then(data => {
                    const grid = document.getElementById('platsList');
                    grid.innerHTML = data.plats.map(plat => `
                        <div class="plat-card" onclick="addToPanier(${plat.id}, '${plat.nom}', ${plat.prix_ttc})">
                            <h6>${plat.nom}</h6>
                            <div class="plat-prix">${plat.prix_ttc} €</div>
                        </div>
                    `).join('');
                })
                .catch(error => console.error('Error:', error));
        }

        function selectTable(tableId, tableNum) {
            selectedTableId = tableId;
            document.querySelectorAll('.table-btn').forEach(btn => btn.classList.remove('active'));
            event.target.closest('.table-btn').classList.add('active');

            // Charger détails table
            fetch(`/api/tables/${tableId}`)
                .then(response => response.json())
                .then(data => {
                    const detail = document.getElementById('tableDetail');
                    const table = data.table;

                    if (table.commande_active) {
                        // Charger commande existante
                        commandePanier = {};
                        table.commande_active.lignes.forEach(ligne => {
                            const key = `${ligne.plat_id}`;
                            commandePanier[key] = {
                                id: ligne.plat_id,
                                nom: ligne.plat_nom,
                                prix: ligne.prix_unitaire,
                                qty: ligne.quantite
                            };
                        });

                        detail.innerHTML = `
                            <div class="alert alert-info mb-3">
                                <strong>Commande en cours</strong><br>
                                <small>Numéro: ${table.commande_active.numero_commande}</small><br>
                                <small>Créée: ${new Date(table.commande_active.date_commande).toLocaleString('fr-FR')}</small>
                            </div>
                            <button class="btn btn-sm btn-outline-primary w-100" onclick="viewCommande(${table.commande_active.id})">
                                <i class="fas fa-eye"></i> Voir Commande
                            </button>
                        `;
                    } else {
                        // Nouvelle commande
                        commandePanier = {};
                        detail.innerHTML = `
                            <p class="mb-3"><strong>Table ${table.numero}</strong></p>
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#newCommandeModal">
                                <i class="fas fa-plus"></i> Nouvelle Commande
                            </button>
                        `;
                    }

                    updatePanier();
                })
                .catch(error => console.error('Error:', error));
        }

        function addToPanier(platId, platNom, prix) {
            if (!selectedTableId) {
                alert('Veuillez sélectionner une table');
                return;
            }

            const key = `${platId}`;
            if (commandePanier[key]) {
                commandePanier[key].qty++;
            } else {
                commandePanier[key] = { id: platId, nom: platNom, prix: prix, qty: 1 };
            }
            updatePanier();
        }

        function updatePanier() {
            const panier = document.getElementById('commandePanier');
            if (Object.keys(commandePanier).length === 0) {
                panier.innerHTML = '<p class="text-muted">Aucun article</p>';
                document.getElementById('commandeTotal').textContent = 'Total: 0 €';
                document.getElementById('btnValiderCommande').disabled = true;
                document.getElementById('btnEffacerCommande').disabled = true;
                return;
            }

            let total = 0;
            panier.innerHTML = Object.values(commandePanier).map(item => {
                const montant = item.qty * item.prix;
                total += montant;
                return `
                    <div class="panier-item">
                        <div class="panier-item-info">
                            <div class="panier-item-nom">${item.nom}</div>
                            <div class="panier-item-prix">${montant.toFixed(2)} €</div>
                        </div>
                        <div class="panier-item-qty">
                            <button onclick="updateQty(${item.id}, -1)">−</button>
                            <span>${item.qty}</span>
                            <button onclick="updateQty(${item.id}, +1)">+</button>
                            <button onclick="removeFromPanier(${item.id})" style="background: #f44336; color: white;">×</button>
                        </div>
                    </div>
                `;
            }).join('');

            document.getElementById('commandeTotal').textContent = `Total: ${total.toFixed(2)} €`;
            document.getElementById('btnValiderCommande').disabled = false;
            document.getElementById('btnEffacerCommande').disabled = false;
        }

        function updateQty(platId, delta) {
            const key = `${platId}`;
            if (commandePanier[key]) {
                commandePanier[key].qty += delta;
                if (commandePanier[key].qty <= 0) {
                    delete commandePanier[key];
                }
                updatePanier();
            }
        }

        function removeFromPanier(platId) {
            delete commandePanier[`${platId}`];
            updatePanier();
        }

        function validerCommande() {
            if (!selectedTableId || Object.keys(commandePanier).length === 0) return;

            const lignes = Object.values(commandePanier).map(item => ({
                plat_id: item.id,
                quantite: item.qty,
                prix_unitaire: item.prix
            }));

            fetch('/api/commandes', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table_id: selectedTableId,
                    type: 'sur_place',
                    lignes: lignes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Commande créée: #' + data.commande.numero_commande);
                    commandePanier = {};
                    loadTables();
                    selectTable(selectedTableId, '');
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function createNewCommande() {
            // Logique pour créer nouvelle commande
            new bootstrap.Modal(document.getElementById('newCommandeModal')).hide();
        }
    </script>
@endsection
