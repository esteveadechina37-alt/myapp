@extends('layouts.app')

@section('title', 'Gestion Stock')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold">
                        <i class="fas fa-warehouse" style="color: #f57c00;"></i> Gestion Stock
                    </h1>
                    <p class="text-muted">Suivi des ingrédients et mouvements</p>
                </div>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mvtStockModal">
                    <i class="fas fa-plus"></i> Nouveau Mouvement
                </button>
            </div>
        </div>

        <!-- Statistiques Stock -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #43a047;">📦</div>
                    <h6>Total Ingrédients</h6>
                    <p class="stat-value">{{ $total_ingredients ?? 0 }}</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #4caf50;">✓</div>
                    <h6>En Stock Suffisant</h6>
                    <p class="stat-value">{{ $stock_ok ?? 0 }}</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #ff9800;">⚠</div>
                    <h6>Stock Faible</h6>
                    <p class="stat-value">{{ $stock_faible ?? 0 }}</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #f44336;">🔴</div>
                    <h6>Stock Critique</h6>
                    <p class="stat-value">{{ $stock_critique ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="row mb-4">
            <div class="col-md-3">
                <input type="text" id="searchIngredient" class="form-control" placeholder="Rechercher un ingrédient...">
            </div>
            <div class="col-md-3">
                <select id="filterStatut" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="ok">En stock</option>
                    <option value="faible">Stock faible</option>
                    <option value="critique">Critique</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterCategorie" class="form-select">
                    <option value="">Toutes les catégories</option>
                    <option value="legumes">Légumes</option>
                    <option value="viande">Viande</option>
                    <option value="fromage">Fromage</option>
                    <option value="sauce">Sauce</option>
                </select>
            </div>
            <div class="col-md-3">
                <button id="btnSearch" class="btn btn-outline-primary w-100">
                    <i class="fas fa-search"></i> Filtrer
                </button>
            </div>
        </div>

        <!-- Table Stock -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white;">
                <h6 class="mb-0"><i class="fas fa-list"></i> Stock Ingrédients</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th>Ingrédient</th>
                                <th>Catégorie</th>
                                <th>Quantité</th>
                                <th>Unité</th>
                                <th>Min</th>
                                <th>Statut</th>
                                <th>Dernière MAJ</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="stockTable">
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-spinner fa-spin"></i> Chargement...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mouvements Récents -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%); color: white;">
                <h6 class="mb-0"><i class="fas fa-history"></i> Mouvements Récents</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th>Date</th>
                                <th>Ingrédient</th>
                                <th>Type</th>
                                <th>Quantité</th>
                                <th>Motif</th>
                                <th>Utilisateur</th>
                            </tr>
                        </thead>
                        <tbody id="mouvementsTable">
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    <i class="fas fa-spinner fa-spin"></i> Chargement...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nouveau Mouvement -->
    <div class="modal fade" id="mvtStockModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient" style="background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white;">
                    <h5 class="modal-title">Nouveau Mouvement Stock</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="mvtStockForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type Mouvement <span class="text-danger">*</span></label>
                                <select name="type" id="mvtType" class="form-select" required>
                                    <option value="">Sélectionner</option>
                                    <option value="entree">Entrée (Achat)</option>
                                    <option value="sortie">Sortie (Utilisation)</option>
                                    <option value="retour">Retour Fournisseur</option>
                                    <option value="dechet">Déchet</option>
                                    <option value="inventaire">Inventaire</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ingrédient <span class="text-danger">*</span></label>
                                <select name="ingredient_id" class="form-select" required>
                                    <option value="">Sélectionner un ingrédient</option>
                                    <!-- Chargé dynamiquement -->
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quantité <span class="text-danger">*</span></label>
                                <input type="number" name="quantite" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unité</label>
                                <input type="text" class="form-control" readonly placeholder="kg, l, pcs..." id="uniteDisplay">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Motif / Référence</label>
                            <input type="text" name="motif" class="form-control" placeholder="Ex: Achat fournisseur #123, Utilisation cuisine...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prix Unitaire (optionnel)</label>
                            <div class="input-group">
                                <input type="number" name="prix_unitaire" class="form-control" step="0.01" placeholder="0.00">
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer Mouvement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .stat-card h6 {
            font-size: 0.9rem;
            color: #666;
            margin: 0;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
            margin-top: 5px;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .statut-ok { background: #d4edda; color: #155724; }
        .statut-faible { background: #fff3cd; color: #856404; }
        .statut-critique { background: #f8d7da; color: #721c24; }

        .type-entree { background: #d4edda; color: #155724; }
        .type-sortie { background: #d1ecf1; color: #0c5460; }
        .type-retour { background: #fff3cd; color: #856404; }
        .type-dechet { background: #f8d7da; color: #721c24; }

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
            background: linear-gradient(135deg, #f57c00 0%, #e65100 100%);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadStock();
            loadMouvements();

            document.getElementById('btnSearch').addEventListener('click', loadStock);
            document.getElementById('filterStatut').addEventListener('change', loadStock);
            document.getElementById('filterCategorie').addEventListener('change', loadStock);
            document.getElementById('searchIngredient').addEventListener('keyup', loadStock);

            document.getElementById('mvtStockForm').addEventListener('submit', submitMouvement);
        });

        function loadStock() {
            const search = document.getElementById('searchIngredient').value;
            const statut = document.getElementById('filterStatut').value;
            const categorie = document.getElementById('filterCategorie').value;

            fetch(`/api/stock?search=${search}&statut=${statut}&categorie=${categorie}`)
                .then(response => response.json())
                .then(data => {
                    const table = document.getElementById('stockTable');
                    
                    if (data.ingredients.length === 0) {
                        table.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">Aucun ingrédient trouvé</td></tr>';
                        return;
                    }

                    table.innerHTML = data.ingredients.map(ing => {
                        const statut = ing.quantite >= ing.quantite_minimale ? 'ok' : 
                                      ing.quantite > ing.quantite_minimale * 0.5 ? 'faible' : 'critique';
                        const statutLabel = statut === 'ok' ? 'OK' : statut === 'faible' ? 'Faible' : 'Critique';

                        return `
                            <tr>
                                <td><strong>${ing.nom}</strong></td>
                                <td><span class="badge bg-secondary">${ing.categorie}</span></td>
                                <td>${parseFloat(ing.quantite).toFixed(2)}</td>
                                <td>${ing.unite}</td>
                                <td>${parseFloat(ing.quantite_minimale).toFixed(2)}</td>
                                <td><span class="badge statut-${statut}">${statutLabel}</span></td>
                                <td><small>${new Date(ing.updated_at).toLocaleDateString('fr-FR')}</small></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="editIngredient(${ing.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                });
        }

        function loadMouvements() {
            fetch('/api/stock/mouvements?limit=10')
                .then(response => response.json())
                .then(data => {
                    const table = document.getElementById('mouvementsTable');
                    
                    if (data.mouvements.length === 0) {
                        table.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">Aucun mouvement</td></tr>';
                        return;
                    }

                    table.innerHTML = data.mouvements.map(mvt => `
                        <tr>
                            <td><small>${new Date(mvt.created_at).toLocaleString('fr-FR')}</small></td>
                            <td>${mvt.ingredient_nom}</td>
                            <td><span class="badge type-${mvt.type}">${mvt.type}</span></td>
                            <td>${parseFloat(mvt.quantite).toFixed(2)} ${mvt.unite}</td>
                            <td><small>${mvt.motif || '-'}</small></td>
                            <td><small>${mvt.utilisateur_nom}</small></td>
                        </tr>
                    `).join('');
                });
        }

        function submitMouvement(e) {
            e.preventDefault();
            const formData = new FormData(this);

            const data = {
                type: formData.get('type'),
                ingredient_id: formData.get('ingredient_id'),
                quantite: parseFloat(formData.get('quantite')),
                motif: formData.get('motif'),
                prix_unitaire: formData.get('prix_unitaire') || 0
            };

            fetch('/api/stock/mouvement', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Mouvement enregistré');
                    bootstrap.Modal.getInstance(document.getElementById('mvtStockModal')).hide();
                    document.getElementById('mvtStockForm').reset();
                    loadStock();
                    loadMouvements();
                } else {
                    alert('Erreur: ' + data.message);
                }
            });
        }
    </script>
@endsection
