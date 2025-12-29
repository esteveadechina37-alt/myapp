@extends('layouts.app')

@section('title', 'Suivi Livraisons')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold">
                        <i class="fas fa-truck" style="color: #43a047;"></i> Suivi Livraisons
                    </h1>
                    <p class="text-muted">Gestion de vos livraisons en cours</p>
                </div>
                <div>
                    <span class="badge bg-success p-2">
                        <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 5px;"></i> En ligne
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">📋</div>
                    <div class="stat-info">
                        <h6>À faire</h6>
                        <p class="stat-value">{{ $a_faire ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">🚗</div>
                    <div class="stat-info">
                        <h6>En cours</h6>
                        <p class="stat-value">{{ $en_cours ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-info">
                        <h6>Livrées</h6>
                        <p class="stat-value">{{ $livrees ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">📍</div>
                    <div class="stat-info">
                        <h6>Distance Jour</h6>
                        <p class="stat-value">{{ $distance_jour ?? 0 }} km</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Carte -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #43a047 0%, #2e7d32 100%); color: white;">
                        <h6 class="mb-0"><i class="fas fa-map"></i> Carte des Livraisons</h6>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 500px; background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #666;">
                            <div class="text-center">
                                <i class="fas fa-map" style="font-size: 3rem; margin-bottom: 10px; opacity: 0.5;"></i><br>
                                <small>Intégration Carte (Google Maps / Leaflet à configurer)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste Livraisons -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%); color: white;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-list"></i> Mes Livraisons</h6>
                            <button class="btn btn-sm btn-light" onclick="location.reload()">
                                <i class="fas fa-sync"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        <div id="livraisonsList">
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-spinner fa-spin"></i> Chargement...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails Livraison -->
        <div class="card border-0 shadow-sm" id="detailsLivraison" style="display: none;">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-package"></i> Détails Livraison</h6>
                    <button type="button" class="btn-close btn-close-white" onclick="document.getElementById('detailsLivraison').style.display = 'none';"></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Adresse de Livraison</h6>
                        <div id="adresseLivraison" class="mb-4">
                            <!-- Chargé par JS -->
                        </div>

                        <h6 class="fw-bold mb-3">Contact Client</h6>
                        <div id="contactClient">
                            <!-- Chargé par JS -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Articles</h6>
                        <div id="articlesLivraison" style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                            <!-- Chargé par JS -->
                        </div>

                        <h6 class="fw-bold mt-4 mb-3">Actions</h6>
                        <div id="actionsLivraison">
                            <!-- Chargé par JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .stat-icon {
            font-size: 2rem;
        }

        .stat-info h6 {
            font-size: 0.85rem;
            color: #666;
            margin: 0;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin: 5px 0 0 0;
        }

        .livraison-item {
            background: white;
            border-left: 4px solid #43a047;
            padding: 15px;
            margin-bottom: 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .livraison-item:hover {
            box-shadow: 0 4px 12px rgba(67, 160, 71, 0.2);
            transform: translateX(5px);
        }

        .livraison-item.active {
            background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
            border-left-color: #1976d2;
        }

        .livraison-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .livraison-numero {
            font-weight: bold;
            color: #1976d2;
        }

        .livraison-statut {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .statut-a_faire {
            background: #fbc02d;
            color: #333;
        }

        .statut-en_cours {
            background: #ff9800;
            color: white;
        }

        .statut-livree {
            background: #4caf50;
            color: white;
        }

        .livraison-info {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 4px;
        }

        .livraison-distance {
            font-size: 0.85rem;
            color: #999;
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
            background: linear-gradient(135deg, #43a047 0%, #2e7d32 100%);
        }

        .btn-action {
            padding: 8px 12px;
            margin-bottom: 8px;
            border-radius: 6px;
            width: 100%;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-start {
            background: #ff9800;
            color: white;
        }

        .btn-start:hover {
            background: #f57c00;
        }

        .btn-finish {
            background: #4caf50;
            color: white;
        }

        .btn-finish:hover {
            background: #2e7d32;
        }

        .btn-call {
            background: #1976d2;
            color: white;
        }

        .btn-call:hover {
            background: #0d47a1;
        }

        .article-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }

        .article-item:last-child {
            border-bottom: none;
        }
    </style>

    <script>
        let selectedLivraisonId = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadLivraisons();
            setInterval(loadLivraisons, 10000); // Actualiser toutes les 10s
        });

        function loadLivraisons() {
            fetch('/api/livraisons')
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('livraisonsList');
                    
                    if (data.livraisons.length === 0) {
                        list.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-box"></i><br>Aucune livraison</div>';
                        return;
                    }

                    list.innerHTML = data.livraisons.map(liv => `
                        <div class="livraison-item ${selectedLivraisonId === liv.id ? 'active' : ''}" onclick="selectLivraison(${liv.id})">
                            <div class="livraison-header">
                                <span class="livraison-numero">#${liv.numero_commande}</span>
                                <span class="livraison-statut statut-${liv.statut}">
                                    ${getStatutFrancais(liv.statut)}
                                </span>
                            </div>
                            <div class="livraison-info">
                                <i class="fas fa-map-marker-alt"></i> ${liv.client_nom}
                            </div>
                            <div class="livraison-info">
                                <i class="fas fa-euro-sign"></i> ${liv.montant_ttc}€
                            </div>
                            <div class="livraison-distance">
                                <i class="fas fa-road"></i> ${liv.distance || 'N/A'} km | 
                                <i class="fas fa-clock"></i> ${liv.temps_estime || '?'} min
                            </div>
                        </div>
                    `).join('');
                })
                .catch(error => console.error('Error:', error));
        }

        function selectLivraison(livraisonId) {
            selectedLivraisonId = livraisonId;
            
            fetch(`/api/livraisons/${livraisonId}`)
                .then(response => response.json())
                .then(data => {
                    const liv = data.livraison;
                    
                    // Adresse
                    document.getElementById('adresseLivraison').innerHTML = `
                        <p class="mb-2">
                            <strong>${liv.client_nom}</strong><br>
                            <small class="text-muted">${liv.adresse_livraison || 'Adresse non spécifiée'}</small><br>
                            <small class="text-muted">${liv.code_postal} ${liv.ville}</small>
                        </p>
                    `;

                    // Contact
                    document.getElementById('contactClient').innerHTML = `
                        <p class="mb-0">
                            <i class="fas fa-phone"></i> <a href="tel:${liv.client_telephone}">${liv.client_telephone}</a><br>
                            <i class="fas fa-envelope"></i> <a href="mailto:${liv.client_email}">${liv.client_email}</a>
                        </p>
                    `;

                    // Articles
                    const articles = (liv.lignes || []).map(ligne => `
                        <div class="article-item">
                            <span>${ligne.plat_nom} (x${ligne.quantite})</span>
                            <span>${(ligne.quantite * ligne.prix_unitaire).toFixed(2)}€</span>
                        </div>
                    `).join('');
                    document.getElementById('articlesLivraison').innerHTML = articles || '<p class="mb-0 text-muted">Aucun article</p>';

                    // Actions
                    let actions = '';
                    if (liv.statut === 'a_faire') {
                        actions = `<button class="btn-action btn-start" onclick="updateLivraisonStatut(${liv.id}, 'en_cours')">
                            <i class="fas fa-play"></i> Démarrer Livraison
                        </button>`;
                    } else if (liv.statut === 'en_cours') {
                        actions = `
                            <button class="btn-action btn-finish" onclick="updateLivraisonStatut(${liv.id}, 'livree')">
                                <i class="fas fa-check"></i> Marquer Livrée
                            </button>
                            <button class="btn-action btn-call" onclick="callClient('${liv.client_telephone}')">
                                <i class="fas fa-phone"></i> Appeler Client
                            </button>
                        `;
                    } else {
                        actions = '<div class="text-success text-center"><i class="fas fa-check-circle"></i> Livraison Complétée</div>';
                    }
                    document.getElementById('actionsLivraison').innerHTML = actions;

                    document.getElementById('detailsLivraison').style.display = 'block';
                    loadLivraisons(); // Rafraîchir liste
                })
                .catch(error => console.error('Error:', error));
        }

        function updateLivraisonStatut(livraisonId, statut) {
            fetch(`/api/livraisons/${livraisonId}/statut`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ statut: statut })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Statut mis à jour');
                    loadLivraisons();
                    if (selectedLivraisonId === livraisonId) {
                        selectLivraison(livraisonId);
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function callClient(phone) {
            window.open(`tel:${phone}`);
        }

        function getStatutFrancais(statut) {
            const statuts = {
                'a_faire': 'À faire',
                'en_cours': 'En cours',
                'livree': 'Livrée'
            };
            return statuts[statut] || statut;
        }
    </script>
@endsection
