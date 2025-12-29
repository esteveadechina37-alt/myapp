@extends('layouts.client')

@section('title', 'Mon Tableau de Bord')

@section('extra-styles')
<style>
    .action-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-left: 5px solid #d32f2f;
        display: flex;
        align-items: center;
        gap: 20px;
        height: 100%;
    }

    .action-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        transform: translateY(-3px);
    }

    .action-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
    }

    .action-icon.delivery {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E72 100%);
    }

    .action-icon.takeaway {
        background: linear-gradient(135deg, #4ECDC4 0%, #44A08D 100%);
    }

    .action-icon.qrcode {
        background: linear-gradient(135deg, #F38181 0%, #AA96DA 100%);
    }

    .action-icon.bill {
        background: linear-gradient(135deg, #FCBAD3 0%, #A6C0FE 100%);
    }

    .action-icon.history {
        background: linear-gradient(135deg, #A8EDEA 0%, #FED6E3 100%);
    }

    .action-content {
        flex-grow: 1;
    }

    .action-content h5 {
        font-weight: 700;
        margin-bottom: 5px;
        color: #333;
    }

    .action-content p {
        font-size: 0.9rem;
        margin-bottom: 12px;
        color: #666;
    }
    
    #reader {
        width: 100% !important;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .card-header {
        background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        border: none;
    }

    .list-group-item {
        border: 1px solid #e0e0e0;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="mb-5">
            <h1 class="h3 fw-bold">
                <i class="fas fa-chart-line" style="color: #d32f2f;"></i> Mon Tableau de Bord
            </h1>
            <p class="text-muted">Bienvenue, {{ auth()->user()->name }}!</p>
        </div>

        <!-- Quick Actions Grid -->
        <div class="row mb-5">
            <!-- Scanner QR (OBLIGATOIRE) -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="action-card">
                    <div class="action-icon qrcode">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <div class="action-content">
                        <h5>Scanner Code QR</h5>
                        <p>Scannez le code QR pour accéder au menu</p>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#qrScannerModal">
                            <i class="fas fa-camera"></i> Scanner
                        </button>
                    </div>
                </div>
            </div>

            <!-- Commander en Livraison -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="action-card">
                    <div class="action-icon delivery">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="action-content">
                        <h5>Commander en Livraison</h5>
                        <p>Faites-vous livrer votre repas à domicile</p>
                        <button class="btn btn-primary btn-sm" onclick="goToMenu('delivery')" id="deliveryBtn" disabled title="Scannez le QR d'abord">
                            <i class="fas fa-arrow-right"></i> Commencer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Commander à Emporter -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="action-card">
                    <div class="action-icon takeaway">
                        <i class="fas fa-bag-shopping"></i>
                    </div>
                    <div class="action-content">
                        <h5>Commander à Emporter</h5>
                        <p>Récupérez votre commande au restaurant</p>
                        <button class="btn btn-primary btn-sm" onclick="goToMenu('takeaway')" id="takeawayBtn" disabled title="Scannez le QR d'abord">
                            <i class="fas fa-arrow-right"></i> Commencer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Historique Commandes -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="action-card">
                    <div class="action-icon history">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="action-content">
                        <h5>Historique Commandes</h5>
                        <p>Consultez vos commandes précédentes</p>
                        <a href="{{ route('client.order-history') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right"></i> Voir Historique
                        </a>
                    </div>
                </div>
            </div>

            <!-- Demander Addition -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="action-card">
                    <div class="action-icon bill">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="action-content">
                        <h5>Demander Addition</h5>
                        <p>Demander l'addition pour votre table</p>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#requestBillModal">
                            <i class="fas fa-arrow-right"></i> Demander
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mes Factures -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="action-card">
                    <div class="action-icon delivery">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="action-content">
                        <h5>Mes Factures</h5>
                        <p>Consultez vos factures et paiements</p>
                        <a href="{{ route('client.invoices') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-right"></i> Voir Factures
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart"></i> Commandes Récentes
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($recentCommands->count() > 0)
                            <div class="list-group">
                                @foreach($recentCommands as $commande)
                                    <a href="{{ route('client.order-detail', $commande->id) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Commande #{{ $commande->id }}</h6>
                                            <small class="badge bg-{{ $commande->statut === 'livree' ? 'success' : ($commande->statut === 'en_preparation' ? 'info' : 'warning') }}">
                                                {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                                            </small>
                                        </div>
                                        <p class="mb-1 text-muted">
                                            <i class="fas fa-calendar"></i> {{ $commande->created_at->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="mb-0">
                                            <strong>{{ number_format($commande->montant_total_ttc, 2) }} FCFA</strong>
                                        </p>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> Vous n'avez pas encore de commande.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Invoices -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-file-invoice"></i> Factures Récentes
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(isset($invoices) && $invoices->count() > 0)
                            <div class="list-group">
                                @foreach($invoices->take(5) as $facture)
                                    <a href="{{ route('client.facture', $facture->id) }}" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Facture #{{ $facture->id }}</h6>
                                            <small class="badge bg-{{ (isset($facture->est_payee) && $facture->est_payee) ? 'success' : 'warning' }}">
                                                {{ (isset($facture->est_payee) && $facture->est_payee) ? 'Payée' : 'En attente' }}
                                            </small>
                                        </div>
                                        <p class="mb-1 text-muted">
                                            <i class="fas fa-calendar"></i> {{ $facture->created_at->format('d/m/Y') }}
                                        </p>
                                        <p class="mb-0">
                                            <strong>{{ number_format($facture->montant_ttc, 2) }} FCFA</strong>
                                        </p>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> Aucune facture pour le moment.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Scanner Modal -->
    <div class="modal fade" id="qrScannerModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-qrcode"></i> Scanner Code QR
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
                    <div id="qr-result" class="alert alert-success mt-3" style="display: none;">
                        <i class="fas fa-check-circle"></i> Menu déverrouillé ! Redirection en cours...
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Bill Modal -->
    <div class="modal fade" id="requestBillModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Demander Addition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="billRequestForm">
                        <div class="mb-3">
                            <label for="tableNumber" class="form-label">Numéro de Table</label>
                            <input type="text" class="form-control" id="tableNumber" placeholder="Ex: T1, T5, etc..." required>
                        </div>
                        <div class="mb-3">
                            <label for="billMessage" class="form-label">Message (optionnel)</label>
                            <textarea class="form-control" id="billMessage" rows="3" placeholder="Remarques supplémentaires..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="submitBillRequest">
                        <i class="fas fa-paper-plane"></i> Envoyer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js"></script>
<script>
    let qrScanner = null;
    let qrScanned = false;

    // Initialiser le scanner QR quand le modal s'ouvre
    document.getElementById('qrScannerModal').addEventListener('show.bs.modal', function() {
        if (!qrScanner) {
            qrScanner = new Html5Qrcode("reader");
            qrScanner.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText) => {
                    console.log(`Code scanned: ${decodedText}`);
                    document.getElementById('qr-result').style.display = 'block';
                    qrScanned = true;
                    
                    // Arrêter le scanner
                    if (qrScanner) {
                        qrScanner.stop();
                    }
                    
                    // Appeler la route pour marquer le scan dans la session
                    fetch('{{ route("client.mark-qr-scanned") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => response.json())
                      .then(data => {
                        if (data.success) {
                            // Activer les boutons
                            document.getElementById('deliveryBtn').disabled = false;
                            document.getElementById('takeawayBtn').disabled = false;
                            document.getElementById('deliveryBtn').removeAttribute('title');
                            document.getElementById('takeawayBtn').removeAttribute('title');
                            
                            // Rediriger vers le menu après 2 secondes
                            setTimeout(() => {
                                bootstrap.Modal.getInstance(document.getElementById('qrScannerModal')).hide();
                                window.location.href = '{{ route("menu.index") }}';
                            }, 2000);
                        }
                    });
                },
                (error) => {
                    // Erreur ignorée silencieusement
                }
            );
        }
    });

    // Arrêter le scanner quand le modal ferme
    document.getElementById('qrScannerModal').addEventListener('hide.bs.modal', function() {
        if (qrScanner && !qrScanned) {
            qrScanner.stop().then(() => {
                qrScanner.clear();
                qrScanner = null;
            }).catch(() => {
                qrScanner = null;
            });
        }
    });

    // Aller au menu
    function goToMenu(type) {
        // Le middleware verify-qr gérera la vérification
        window.location.href = '{{ route("menu.index") }}?type=' + type;
    }

    // Demander addition
    document.getElementById('submitBillRequest').addEventListener('click', function() {
        const tableNumber = document.getElementById('tableNumber').value;
        const message = document.getElementById('billMessage').value;
        
        if (!tableNumber) {
            alert('Veuillez entrer le numéro de table');
            return;
        }
        
        alert('Demande d\'addition envoyée pour la table ' + tableNumber);
        document.getElementById('billRequestForm').reset();
        bootstrap.Modal.getInstance(document.getElementById('requestBillModal')).hide();
    });
</script>
@endsection
