@extends('layouts.app')

@section('title', 'Gestion Menu')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold">
                        <i class="fas fa-utensils" style="color: #1976d2;"></i> Gestion Menu
                    </h1>
                    <p class="text-muted">Gestion des plats et catégories</p>
                </div>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newPlatModal">
                    <i class="fas fa-plus"></i> Ajouter Plat
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="platsTab" data-bs-toggle="tab" href="#plats" role="tab">Plats</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="categoriesTab" data-bs-toggle="tab" href="#categories" role="tab">Catégories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="ingredientsTab" data-bs-toggle="tab" href="#ingredients" role="tab">Ingrédients</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Plats -->
            <div class="tab-pane fade show active" id="plats" role="tabpanel">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <input type="text" id="searchPlat" class="form-control" placeholder="Rechercher un plat...">
                    </div>
                    <div class="col-md-6">
                        <select id="filterCategoriePlat" class="form-select">
                            <option value="">Toutes les catégories</option>
                            <!-- Chargé dynamiquement -->
                        </select>
                    </div>
                </div>

                <div class="row" id="platsGrid">
                    <!-- Plats chargés par JS -->
                </div>
            </div>

            <!-- Catégories -->
            <div class="tab-pane fade" id="categories" role="tabpanel">
                <div class="mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCategoryModal">
                        <i class="fas fa-plus"></i> Nouvelle Catégorie
                    </button>
                </div>

                <div class="row" id="categoriesGrid">
                    <!-- Catégories chargées par JS -->
                </div>
            </div>

            <!-- Ingrédients -->
            <div class="tab-pane fade" id="ingredients" role="tabpanel">
                <div class="mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newIngredientModal">
                        <i class="fas fa-plus"></i> Nouvel Ingrédient
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                <th>Unité</th>
                                <th>Prix Unitaire</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="ingredientsTable">
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-spinner fa-spin"></i> Chargement...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter Plat -->
    <div class="modal fade" id="newPlatModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient" style="background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white;">
                    <h5 class="modal-title">Ajouter Plat</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="newPlatForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom du Plat <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                                <select name="categorie_id" class="form-select" required>
                                    <option value="">Sélectionner</option>
                                    <!-- Chargé dynamiquement -->
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Prix HT <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="prix_ht" class="form-control" step="0.01" required>
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">TVA (%)</label>
                                <input type="number" name="tva" class="form-control" step="0.01" value="20">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Prix TTC</label>
                                <div class="input-group">
                                    <input type="number" id="prixTTC" class="form-control" step="0.01" readonly>
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Temps Préparation (min)</label>
                                <input type="number" name="temps_preparation" class="form-control" value="15">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Image URL</label>
                                <input type="url" name="image" class="form-control" placeholder="https://...">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="disponible" name="disponible" value="1" checked>
                                <label class="form-check-label" for="disponible">
                                    Plat disponible
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Créer Plat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Nouvelle Catégorie -->
    <div class="modal fade" id="newCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-gradient" style="background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%); color: white;">
                    <h5 class="modal-title">Nouvelle Catégorie</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="newCategoryForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ordre d'affichage</label>
                            <input type="number" name="ordre" class="form-control" value="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Créer Catégorie</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Nouvel Ingrédient -->
    <div class="modal fade" id="newIngredientModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-gradient" style="background: linear-gradient(135deg, #f57c00 0%, #e65100 100%); color: white;">
                    <h5 class="modal-title">Nouvel Ingrédient</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="newIngredientForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catégorie</label>
                            <input type="text" name="categorie" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unité</label>
                                <select name="unite" class="form-select">
                                    <option value="kg">kg</option>
                                    <option value="l">litre</option>
                                    <option value="pcs">pièce</option>
                                    <option value="gr">gramme</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prix Unitaire</label>
                                <input type="number" name="prix_unitaire" class="form-control" step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Créer Ingrédient</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .plat-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .plat-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }

        .plat-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #1976d2;
        }

        .plat-info {
            padding: 15px;
        }

        .plat-nom {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .plat-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
        }

        .plat-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .plat-prix {
            font-weight: bold;
            color: #d32f2f;
            font-size: 1.2rem;
        }

        .plat-actions {
            display: flex;
            gap: 5px;
        }

        .plat-actions button {
            padding: 4px 8px;
            font-size: 0.8rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .category-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .category-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .category-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .category-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .bg-gradient {
            background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%);
        }

        .nav-tabs .nav-link {
            color: #666;
            border: none;
            border-bottom: 3px solid transparent;
        }

        .nav-tabs .nav-link.active {
            color: #1976d2;
            border-bottom: 3px solid #1976d2;
            background: none;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadPlats();
            loadCategories();
            loadIngredients();

            document.getElementById('newPlatForm').addEventListener('submit', createPlat);
            document.getElementById('newCategoryForm').addEventListener('submit', createCategory);
            document.getElementById('newIngredientForm').addEventListener('submit', createIngredient);

            // Calcul prix TTC
            document.querySelector('input[name="prix_ht"]').addEventListener('input', function() {
                const ht = parseFloat(this.value) || 0;
                const tva = parseFloat(document.querySelector('input[name="tva"]').value) || 20;
                const ttc = ht * (1 + tva / 100);
                document.getElementById('prixTTC').value = ttc.toFixed(2);
            });
        });

        function loadPlats() {
            fetch('/api/plats')
                .then(response => response.json())
                .then(data => {
                    const grid = document.getElementById('platsGrid');
                    grid.innerHTML = data.plats.map(plat => `
                        <div class="col-md-4 mb-3">
                            <div class="plat-card">
                                <div class="plat-image">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="plat-info">
                                    <div class="plat-nom">${plat.nom}</div>
                                    <div class="plat-description">${plat.description || ''}</div>
                                    <div class="plat-footer">
                                        <div class="plat-prix">${plat.prix_ttc}€</div>
                                        <div class="plat-actions">
                                            <button class="btn btn-outline-primary" onclick="editPlat(${plat.id})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deletePlat(${plat.id})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                });
        }

        function loadCategories() {
            fetch('/api/categories')
                .then(response => response.json())
                .then(data => {
                    const grid = document.getElementById('categoriesGrid');
                    grid.innerHTML = data.categories.map(cat => `
                        <div class="col-md-4">
                            <div class="category-card">
                                <div class="category-icon">📋</div>
                                <div class="category-name">${cat.nom}</div>
                                <small class="text-muted">${cat.description || ''}</small><br><br>
                                <button class="btn btn-sm btn-outline-primary" onclick="editCategory(${cat.id})">
                                    <i class="fas fa-edit"></i> Éditer
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory(${cat.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `).join('');
                });
        }

        function loadIngredients() {
            fetch('/api/ingredients')
                .then(response => response.json())
                .then(data => {
                    const table = document.getElementById('ingredientsTable');
                    table.innerHTML = data.ingredients.map(ing => `
                        <tr>
                            <td><strong>${ing.nom}</strong></td>
                            <td>${ing.categorie}</td>
                            <td>${ing.unite}</td>
                            <td>${ing.prix_unitaire}€</td>
                            <td>${ing.quantite} ${ing.unite}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">Éditer</button>
                            </td>
                        </tr>
                    `).join('');
                });
        }

        function createPlat(e) {
            e.preventDefault();
            const formData = new FormData(this);
            alert('Plat créé (fonctionnalité à implémenter en backend)');
        }

        function createCategory(e) {
            e.preventDefault();
            alert('Catégorie créée (fonctionnalité à implémenter en backend)');
        }

        function createIngredient(e) {
            e.preventDefault();
            alert('Ingrédient créé (fonctionnalité à implémenter en backend)');
        }

        function deletePlat(id) {
            if (confirm('Supprimer ce plat?')) {
                alert('Plat supprimé');
                loadPlats();
            }
        }

        function deleteCategory(id) {
            if (confirm('Supprimer cette catégorie?')) {
                alert('Catégorie supprimée');
                loadCategories();
            }
        }
    </script>
@endsection
