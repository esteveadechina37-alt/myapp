@extends('layouts.app')

@section('title', 'Logs Système')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="mb-4">
            <h1 class="h3 fw-bold">
                <i class="fas fa-history" style="color: #558b2f;"></i> Logs d'Activité Système
            </h1>
            <p class="text-muted">Audit complet de toutes les actions du système</p>
        </div>

        <!-- Filtres -->
        <div class="row mb-4">
            <div class="col-md-2">
                <input type="date" id="filterDate" class="form-control">
            </div>
            <div class="col-md-2">
                <select id="filterUtilisateur" class="form-select">
                    <option value="">Tous les utilisateurs</option>
                    <!-- Chargé dynamiquement -->
                </select>
            </div>
            <div class="col-md-2">
                <select id="filterModule" class="form-select">
                    <option value="">Tous les modules</option>
                    <option value="commandes">Commandes</option>
                    <option value="factures">Factures</option>
                    <option value="stock">Stock</option>
                    <option value="utilisateurs">Utilisateurs</option>
                    <option value="tables">Tables</option>
                </select>
            </div>
            <div class="col-md-2">
                <select id="filterAction" class="form-select">
                    <option value="">Toutes les actions</option>
                    <option value="CREATE">Création</option>
                    <option value="READ">Lecture</option>
                    <option value="UPDATE">Modification</option>
                    <option value="DELETE">Suppression</option>
                    <option value="LOGIN">Connexion</option>
                </select>
            </div>
            <div class="col-md-2">
                <select id="filterNiveau" class="form-select">
                    <option value="">Tous les niveaux</option>
                    <option value="INFO">Info</option>
                    <option value="WARNING">Attention</option>
                    <option value="ERROR">Erreur</option>
                </select>
            </div>
            <div class="col-md-2">
                <button id="btnSearch" class="btn btn-outline-primary w-100">
                    <i class="fas fa-search"></i> Filtrer
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <h6 class="text-muted">Logs Aujourd'hui</h6>
                    <p class="stat-value">{{ $logs_today ?? 0 }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h6 class="text-muted">Erreurs</h6>
                    <p class="stat-value" style="color: #f44336;">{{ $errors_today ?? 0 }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h6 class="text-muted">Connexions</h6>
                    <p class="stat-value">{{ $logins_today ?? 0 }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h6 class="text-muted">Modifications</h6>
                    <p class="stat-value">{{ $updates_today ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Table Logs -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #558b2f 0%, #33691e 100%); color: white;">
                <h6 class="mb-0"><i class="fas fa-list"></i> Historique</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th>Date/Heure</th>
                                <th>Utilisateur</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Description</th>
                                <th>Niveau</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody id="logsTable">
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-spinner fa-spin"></i> Chargement...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <nav aria-label="Pagination" class="mt-4">
            <ul class="pagination justify-content-center" id="pagination">
                <!-- Chargé dynamiquement -->
            </ul>
        </nav>
    </div>

    <style>
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
            margin-top: 5px;
        }

        .table tbody tr {
            border-bottom: 1px solid #eee;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 4px;
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
            background: linear-gradient(135deg, #558b2f 0%, #33691e 100%);
        }

        .action-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .action-CREATE { background: #c8e6c9; color: #2e7d32; }
        .action-READ { background: #e3f2fd; color: #1976d2; }
        .action-UPDATE { background: #fff3e0; color: #e65100; }
        .action-DELETE { background: #ffcdd2; color: #c62828; }
        .action-LOGIN { background: #f3e5f5; color: #7b1fa2; }

        .level-INFO { background: #e8f5e9; color: #2e7d32; }
        .level-WARNING { background: #fff3e0; color: #e65100; }
        .level-ERROR { background: #ffcdd2; color: #c62828; }

        .description {
            font-size: 0.9rem;
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .ip-address {
            font-family: monospace;
            font-size: 0.85rem;
            color: #666;
        }
    </style>

    <script>
        let currentPage = 1;

        document.addEventListener('DOMContentLoaded', function() {
            loadLogs();

            document.getElementById('btnSearch').addEventListener('click', () => {
                currentPage = 1;
                loadLogs();
            });

            [].forEach.call(document.querySelectorAll('[id^="filter"]'), el => {
                el.addEventListener('change', () => {
                    currentPage = 1;
                    loadLogs();
                });
            });
        });

        function loadLogs(page = 1) {
            const date = document.getElementById('filterDate').value;
            const utilisateur = document.getElementById('filterUtilisateur').value;
            const module = document.getElementById('filterModule').value;
            const action = document.getElementById('filterAction').value;
            const niveau = document.getElementById('filterNiveau').value;

            fetch(`/api/logs?date=${date}&utilisateur=${utilisateur}&module=${module}&action=${action}&niveau=${niveau}&page=${page}`)
                .then(response => response.json())
                .then(data => {
                    const table = document.getElementById('logsTable');
                    
                    if (data.logs.length === 0) {
                        table.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Aucun log trouvé</td></tr>';
                        return;
                    }

                    table.innerHTML = data.logs.map(log => `
                        <tr>
                            <td>
                                <small>${new Date(log.created_at).toLocaleString('fr-FR')}</small>
                            </td>
                            <td>
                                <strong>${log.utilisateur_nom}</strong><br>
                                <small class="text-muted">${log.utilisateur_email}</small>
                            </td>
                            <td>
                                <span class="action-badge action-${log.action}">
                                    ${log.action}
                                </span>
                            </td>
                            <td><span class="badge bg-secondary">${log.module}</span></td>
                            <td class="description" title="${log.description}">${log.description}</td>
                            <td>
                                <span class="badge level-${log.niveau}">
                                    ${log.niveau}
                                </span>
                            </td>
                            <td class="ip-address">${log.ip_address}</td>
                        </tr>
                    `).join('');

                    renderPagination(data.total, data.per_page, page);
                })
                .catch(error => console.error('Error:', error));
        }

        function renderPagination(total, perPage, currentPage) {
            const totalPages = Math.ceil(total / perPage);
            const pagination = document.getElementById('pagination');
            
            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let html = '';

            // Précédent
            if (currentPage > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadLogs(${currentPage - 1}); return false;">Précédent</a></li>`;
            }

            // Pages
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);

            if (startPage > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadLogs(1); return false;">1</a></li>`;
                if (startPage > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }

            for (let i = startPage; i <= endPage; i++) {
                const isActive = i === currentPage;
                html += `<li class="page-item ${isActive ? 'active' : ''}"><a class="page-link" href="#" onclick="loadLogs(${i}); return false;">${i}</a></li>`;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadLogs(${totalPages}); return false;">${totalPages}</a></li>`;
            }

            // Suivant
            if (currentPage < totalPages) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadLogs(${currentPage + 1}); return false;">Suivant</a></li>`;
            }

            pagination.innerHTML = html;
        }
    </script>
@endsection
