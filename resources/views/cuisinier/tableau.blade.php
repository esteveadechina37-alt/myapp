@extends('layouts.app')

@section('title', 'Tableau Cuisine')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold">
                        <i class="fas fa-fire" style="color: #d32f2f;"></i> Tableau Cuisine
                    </h1>
                    <p class="text-muted">Vue d'ensemble des commandes en préparation</p>
                </div>
                <div>
                    <button class="btn btn-outline-primary" onclick="location.reload()">
                        <i class="fas fa-sync"></i> Rafraîchir
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-box" style="border-left: 4px solid #fbc02d;">
                    <h6 class="text-muted">En Attente</h6>
                    <p class="stat-value">{{ $en_attente ?? 0 }}</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-box" style="border-left: 4px solid #ff9800;">
                    <h6 class="text-muted">En Préparation</h6>
                    <p class="stat-value">{{ $en_preparation ?? 0 }}</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-box" style="border-left: 4px solid #4caf50;">
                    <h6 class="text-muted">Prêtes</h6>
                    <p class="stat-value">{{ $prete ?? 0 }}</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-box" style="border-left: 4px solid #2196f3;">
                    <h6 class="text-muted">Total Jour</h6>
                    <p class="stat-value">{{ $total ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="row mb-4">
            <div class="col-md-3">
                <label class="form-label"><small>Filtrer par statut</small></label>
                <select id="filterStatut" class="form-select">
                    <option value="">Tous</option>
                    <option value="en_attente">En Attente</option>
                    <option value="en_preparation" selected>En Préparation</option>
                    <option value="prete">Prête</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"><small>Filtrer par type</small></label>
                <select id="filterType" class="form-select">
                    <option value="">Tous les types</option>
                    <option value="sur_place">Sur Place</option>
                    <option value="a_emporter">À Emporter</option>
                    <option value="livraison">Livraison</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label"><small>&nbsp;</small></label>
                <button id="btnFilter" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
            </div>
        </div>

        <!-- Tableau Commandes -->
        <div id="commandesBoard" class="row">
            <!-- En Attente -->
            <div class="col-lg-4 mb-4">
                <div class="commande-column">
                    <div class="column-header" style="background: linear-gradient(135deg, #fbc02d 0%, #f57f17 100%); color: black;">
                        <h6 class="mb-0"><i class="fas fa-hourglass-start"></i> EN ATTENTE</h6>
                        <span class="count-badge" id="count-en_attente">0</span>
                    </div>
                    <div class="column-body" id="column-en_attente">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox"></i> Aucune commande
                        </div>
                    </div>
                </div>
            </div>

            <!-- En Préparation -->
            <div class="col-lg-4 mb-4">
                <div class="commande-column">
                    <div class="column-header" style="background: linear-gradient(135deg, #ff9800 0%, #e65100 100%); color: white;">
                        <h6 class="mb-0"><i class="fas fa-fire"></i> EN PRÉPARATION</h6>
                        <span class="count-badge" id="count-en_preparation">0</span>
                    </div>
                    <div class="column-body" id="column-en_preparation">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox"></i> Aucune commande
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prêtes -->
            <div class="col-lg-4 mb-4">
                <div class="commande-column">
                    <div class="column-header" style="background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%); color: white;">
                        <h6 class="mb-0"><i class="fas fa-check-circle"></i> PRÊTES</h6>
                        <span class="count-badge" id="count-prete">0</span>
                    </div>
                    <div class="column-body" id="column-prete">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox"></i> Aucune commande
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stat-box {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin: 10px 0 0 0;
        }

        .commande-column {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .column-header {
            padding: 15px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .count-badge {
            display: inline-block;
            background: rgba(255,255,255,0.3);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .column-body {
            flex: 1;
            overflow-y: auto;
            max-height: 700px;
            padding: 10px;
        }

        .commande-card {
            background: white;
            border: 2px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
            cursor: move;
        }

        .commande-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-color: #d32f2f;
        }

        .commande-card.urgent {
            border-left: 4px solid #f44336;
            background: #ffebee;
        }

        .commande-numero {
            font-weight: 700;
            color: #d32f2f;
            font-size: 1.1rem;
            margin-bottom: 8px;
        }

        .commande-info {
            font-size: 0.9rem;
            margin: 5px 0;
            color: #666;
        }

        .commande-type {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-right: 8px;
        }

        .type-sur_place {
            background: #e3f2fd;
            color: #1976d2;
        }

        .type-a_emporter {
            background: #fff3e0;
            color: #f57c00;
        }

        .type-livraison {
            background: #e8f5e9;
            color: #43a047;
        }

        .articles-list {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            margin: 10px 0;
            font-size: 0.85rem;
        }

        .article-item {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .article-item:last-child {
            border-bottom: none;
        }

        .commande-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .commande-actions button {
            flex: 1;
            padding: 6px;
            font-size: 0.8rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
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

        .btn-timer {
            background: #2196f3;
            color: white;
            font-size: 0.8rem;
        }

        .timer {
            font-weight: bold;
            color: #d32f2f;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }
    </style>

    <script>
        let timerIntervals = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadCommandes();
            document.getElementById('btnFilter').addEventListener('click', loadCommandes);
            setInterval(loadCommandes, 5000); // Actualiser toutes les 5s
        });

        function loadCommandes() {
            const statut = document.getElementById('filterStatut').value;
            const type = document.getElementById('filterType').value;

            fetch(`/api/commandes?statut=${statut}&type=${type}`)
                .then(response => response.json())
                .then(data => {
                    renderCommandes(data.commandes || []);
                })
                .catch(error => console.error('Error:', error));
        }

        function renderCommandes(commandes) {
            const groupes = {
                'en_attente': [],
                'en_preparation': [],
                'prete': []
            };

            commandes.forEach(cmd => {
                if (groupes[cmd.statut]) {
                    groupes[cmd.statut].push(cmd);
                }
            });

            ['en_attente', 'en_preparation', 'prete'].forEach(statut => {
                const container = document.getElementById(`column-${statut}`);
                const count = document.getElementById(`count-${statut}`);
                count.textContent = groupes[statut].length;

                if (groupes[statut].length === 0) {
                    container.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-inbox"></i> Aucune</div>';
                    return;
                }

                container.innerHTML = groupes[statut].map(cmd => {
                    const typeClass = `type-${cmd.type}`;
                    const typeFr = cmd.type === 'sur_place' ? 'Sur Place' : 
                                   cmd.type === 'a_emporter' ? 'À Emporter' : 'Livraison';
                    const articles = cmd.lignes ? cmd.lignes.map(l => `<div class="article-item">• ${l.plat_nom} (x${l.quantite})</div>`).join('') : '';
                    const timerClass = `timer-${cmd.id}`;

                    return `
                        <div class="commande-card" id="card-${cmd.id}">
                            <div class="commande-numero">
                                Cmd #${cmd.numero_commande}
                                <span class="btn-timer" style="float: right; font-size: 0.75rem;">
                                    <span class="${timerClass}">-</span>
                                </span>
                            </div>
                            <div class="commande-info">
                                <span class="commande-type ${typeClass}">${typeFr}</span>
                                <span style="font-weight: 600; color: #d32f2f;">${cmd.montant_ttc}€</span>
                            </div>
                            <div class="articles-list">${articles || '<em>Aucun article</em>'}</div>
                            <div class="commande-actions">
                                ${cmd.statut === 'en_attente' ? 
                                    `<button class="btn-start" onclick="updateStatut(${cmd.id}, 'en_preparation')">▶ Démarrer</button>` : ''
                                }
                                ${cmd.statut === 'en_preparation' ? 
                                    `<button class="btn-finish" onclick="updateStatut(${cmd.id}, 'prete')">✓ Terminer</button>` : ''
                                }
                            </div>
                        </div>
                    `;
                }).join('');
            });
        }

        function updateStatut(commandeId, newStatut) {
            fetch(`/api/commandes/${commandeId}/statut`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ statut: newStatut })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadCommandes();
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
