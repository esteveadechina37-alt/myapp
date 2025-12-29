@extends('layouts.app')

@section('title', 'Gestion Paiements')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="mb-4">
            <h1 class="h3 fw-bold">
                <i class="fas fa-cash-register" style="color: #43a047;"></i> Gestion Paiements
            </h1>
            <p class="text-muted">Gestion des paiements et encaissements</p>
        </div>

        <!-- Caisse du Jour -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="caisse-card">
                    <h6>Encaissé</h6>
                    <p class="caisse-value">{{ number_format($encaisse_jour ?? 0, 0, ',', ' ') }} €</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="caisse-card">
                    <h6>Commandes Payées</h6>
                    <p class="caisse-value">{{ $commandes_payees ?? 0 }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="caisse-card">
                    <h6>Commandes En Attente</h6>
                    <p class="caisse-value">{{ $commandes_attente ?? 0 }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="caisse-card">
                    <h6>Montant En Attente</h6>
                    <p class="caisse-value">{{ number_format($montant_attente ?? 0, 0, ',', ' ') }} €</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Paiements Rapides -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-shopping-cart"></i> Commandes à Payer</h6>
                            <span class="badge bg-light text-dark" id="countAttente">0</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="commandes-list">
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-spinner fa-spin"></i> Chargement...
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé Paiement -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%); color: white;">
                        <h6 class="mb-0"><i class="fas fa-receipt"></i> Détails Paiement</h6>
                    </div>
                    <div class="card-body">
                        <div id="paiement-details">
                            <p class="text-muted">Sélectionnez une commande</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique Paiements -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-history"></i> Historique Paiements</h6>
                    <button class="btn btn-sm btn-light" onclick="loadHistorique()">
                        <i class="fas fa-sync"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead style="background-color: #f8f9fa;">
                            <tr>
                                <th>Date</th>
                                <th>Commande</th>
                                <th>Montant</th>
                                <th>Mode Paiement</th>
                                <th>Statut</th>
                                <th>Serveur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="historique-table">
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
    </div>

    <!-- Modal Paiement -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient" style="background: linear-gradient(135deg, #43a047 0%, #2e7d32 100%); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-credit-card"></i> Effectuer Paiement
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="paymentForm">
                    <input type="hidden" id="commandeId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Commande</label>
                            <input type="text" id="commandeNum" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Montant Total</label>
                            <div class="input-group">
                                <input type="number" id="montantTotal" class="form-control" readonly step="0.01">
                                <span class="input-group-text">€</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mode de Paiement <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="modePaiement" id="mode_especes" value="especes" checked>
                                    <label class="form-check-label" for="mode_especes">
                                        <i class="fas fa-coins"></i> Espèces
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="modePaiement" id="mode_carte" value="carte">
                                    <label class="form-check-label" for="mode_carte">
                                        <i class="fas fa-credit-card"></i> Carte Bancaire
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="modePaiement" id="mode_cheque" value="cheque">
                                    <label class="form-check-label" for="mode_cheque">
                                        <i class="fas fa-file"></i> Chèque
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="modePaiement" id="mode_virement" value="virement">
                                    <label class="form-check-label" for="mode_virement">
                                        <i class="fas fa-exchange-alt"></i> Virement
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="paiement-partiel-section" style="display: none;">
                            <label class="form-label">Montant Versé</label>
                            <div class="input-group">
                                <input type="number" id="montantVerse" class="form-control" step="0.01">
                                <span class="input-group-text">€</span>
                            </div>
                        </div>

                        <div class="alert alert-info mb-0">
                            <strong id="montantReste"></strong>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-danger" onclick="paymentPartiel()">Paiement Partiel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Encaisser
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .caisse-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .caisse-card h6 {
            color: #666;
            margin: 0;
            font-size: 0.9rem;
        }

        .caisse-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #43a047;
            margin: 10px 0 0 0;
        }

        .commande-item {
            background: white;
            border-left: 4px solid #f57c00;
            padding: 15px;
            margin-bottom: 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .commande-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-left-color: #43a047;
        }

        .commande-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .commande-numero {
            font-weight: bold;
            color: #1976d2;
        }

        .commande-montant {
            font-weight: bold;
            color: #43a047;
            font-size: 1.2rem;
        }

        .commande-info {
            font-size: 0.9rem;
            color: #666;
            margin: 5px 0;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
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

        .mode-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .mode-especes { background: #e8f5e9; color: #2e7d32; }
        .mode-carte { background: #e3f2fd; color: #1976d2; }
        .mode-cheque { background: #f3e5f5; color: #6a1b9a; }
        .mode-virement { background: #fff3e0; color: #e65100; }

        .form-check {
            margin-bottom: 10px;
        }

        .form-check-label {
            margin-left: 5px;
        }
    </style>

    <script>
        let selectedCommandeId = null;
        let montantTotal = 0;

        document.addEventListener('DOMContentLoaded', function() {
            loadCommandes();
            loadHistorique();

            document.getElementById('paymentForm').addEventListener('submit', submitPaiement);
            setInterval(loadCommandes, 10000); // Actualiser toutes les 10s
        });

        function loadCommandes() {
            fetch('/api/commandes?statut=prete&statut=completee&type=sur_place')
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('commandes-list');
                    document.getElementById('countAttente').textContent = data.commandes.length;

                    if (data.commandes.length === 0) {
                        list.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-inbox"></i><br>Aucune commande</div>';
                        return;
                    }

                    list.innerHTML = data.commandes.map(cmd => `
                        <div class="commande-item" onclick="selectCommande(${cmd.id})">
                            <div class="commande-header">
                                <span class="commande-numero">#${cmd.numero_commande}</span>
                                <span class="commande-montant">${cmd.montant_ttc}€</span>
                            </div>
                            <div class="commande-info">
                                <i class="fas fa-chair"></i> Table ${cmd.table_numero || 'N/A'} | 
                                <span class="badge bg-success">${cmd.statut === 'prete' ? 'Prête' : 'Complétée'}</span>
                            </div>
                            <div class="commande-info">
                                <i class="fas fa-clock"></i> ${new Date(cmd.date_commande).toLocaleTimeString('fr-FR')}
                            </div>
                        </div>
                    `).join('');
                });
        }

        function selectCommande(commandeId) {
            selectedCommandeId = commandeId;

            fetch(`/api/commandes/${commandeId}`)
                .then(response => response.json())
                .then(data => {
                    const cmd = data.commande;
                    montantTotal = cmd.montant_ttc;

                    const details = document.getElementById('paiement-details');
                    details.innerHTML = `
                        <div class="mb-3">
                            <strong>Numéro Commande:</strong><br>
                            ${cmd.numero_commande}
                        </div>
                        <div class="mb-3">
                            <strong>Table:</strong><br>
                            Table ${cmd.table_numero || 'N/A'}
                        </div>
                        <div class="mb-3">
                            <strong>Articles:</strong><br>
                            <ul style="font-size: 0.9rem;">
                                ${cmd.lignes.map(l => `<li>${l.plat_nom} (x${l.quantite})</li>`).join('')}
                            </ul>
                        </div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Montant HT:</span>
                                <strong>${cmd.montant_ht}€</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>TVA:</span>
                                <strong>${cmd.montant_tva}€</strong>
                            </div>
                            <div class="d-flex justify-content-between" style="border-top: 1px solid #ddd; padding-top: 10px; font-size: 1.2rem; color: #43a047;">
                                <span><strong>Total:</strong></span>
                                <strong>${cmd.montant_ttc}€</strong>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#paymentModal" onclick="preparePayment(${cmd.id})">
                            <i class="fas fa-credit-card"></i> Effectuer Paiement
                        </button>
                    `;
                });
        }

        function preparePayment(commandeId) {
            fetch(`/api/commandes/${commandeId}`)
                .then(response => response.json())
                .then(data => {
                    const cmd = data.commande;
                    document.getElementById('commandeId').value = commandeId;
                    document.getElementById('commandeNum').value = cmd.numero_commande;
                    document.getElementById('montantTotal').value = cmd.montant_ttc;
                    document.getElementById('montantReste').textContent = `À payer: ${cmd.montant_ttc}€`;
                    document.getElementById('paiement-partiel-section').style.display = 'none';
                });
        }

        function paymentPartiel() {
            document.getElementById('paiement-partiel-section').style.display = 'block';
        }

        function submitPaiement(e) {
            e.preventDefault();
            
            const commandeId = document.getElementById('commandeId').value;
            const modePaiement = document.querySelector('input[name="modePaiement"]:checked').value;
            const montantVerse = document.getElementById('montantVerse').value;
            const montantTotal = parseFloat(document.getElementById('montantTotal').value);

            const data = {
                commande_id: commandeId,
                mode_paiement: modePaiement,
                montant_paye: montantVerse ? parseFloat(montantVerse) : montantTotal,
                montant_total: montantTotal
            };

            fetch('/api/paiements', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Paiement effectué avec succès');
                    bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
                    loadCommandes();
                    loadHistorique();
                } else {
                    alert('Erreur: ' + data.message);
                }
            });
        }

        function loadHistorique() {
            fetch('/api/paiements?limit=10')
                .then(response => response.json())
                .then(data => {
                    const table = document.getElementById('historique-table');
                    
                    if (data.paiements.length === 0) {
                        table.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-3">Aucun paiement</td></tr>';
                        return;
                    }

                    table.innerHTML = data.paiements.map(pay => `
                        <tr>
                            <td><small>${new Date(pay.created_at).toLocaleString('fr-FR')}</small></td>
                            <td><strong>${pay.commande_numero}</strong></td>
                            <td><strong>${pay.montant_paye}€</strong></td>
                            <td><span class="mode-badge mode-${pay.mode_paiement}">${pay.mode_paiement}</span></td>
                            <td>
                                <span class="badge bg-${pay.statut === 'valide' ? 'success' : 'warning'}">
                                    ${pay.statut === 'valide' ? 'Validé' : 'Partiel'}
                                </span>
                            </td>
                            <td>${pay.utilisateur_nom}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-receipt"></i>
                                </button>
                            </td>
                        </tr>
                    `).join('');
                });
        }
    </script>
@endsection
