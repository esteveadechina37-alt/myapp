@extends('layouts.app')

@section('title', 'Dashboard Cuisinier')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 fw-bold">
            <i class="fas fa-user-chef" style="color: #d32f2f;"></i> Dashboard Cuisinier
        </h1>
        <p class="text-muted">Gestion de la préparation des commandes</p>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-danger text-white">
                <div class="card-body">
                    <h6 class="text-white-80">En préparation</h6>
                    <h3 class="mb-0">{{ $stats['en_preparation'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <h6 class="text-white-80">Prêtes à servir</h6>
                    <h3 class="mb-0">{{ $stats['prete'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <h6 class="text-white-80">Aujourd'hui</h6>
                    <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-80">Temps moyen</h6>
                    <h3 class="mb-0">{{ $stats['temps_moyen'] ?? 0 }}m</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Commandes en préparation -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-fire"></i> Commandes en Préparation</h5>
        </div>
        <div class="card-body">
            @if($commandesEnPrep->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Commande</th>
                                <th>Client</th>
                                <th>Articles</th>
                                <th>Heure</th>
                                <th>Durée</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commandesEnPrep as $commande)
                                <tr>
                                    <td>
                                        <strong>#{{ $commande->numero_commande }}</strong>
                                    </td>
                                    <td>{{ $commande->client->nom ?? 'Non spécifié' }}</td>
                                    <td>
                                        <div class="badge bg-info">{{ $commande->lignes->count() }} article(s)</div>
                                    </td>
                                    <td>{{ $commande->heure_commande->format('H:i') }}</td>
                                    <td>
                                        <span class="badge bg-warning">
                                            {{ $commande->heure_commande->diffInMinutes(now()) }}m
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#commandeModal{{ $commande->id }}">
                                            <i class="fas fa-eye"></i> Détails
                                        </button>
                                        <button class="btn btn-sm btn-success" onclick="marquerPrete({{ $commande->id }})">
                                            <i class="fas fa-check"></i> Prête
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Détails -->
                                <div class="modal fade" id="commandeModal{{ $commande->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Commande #{{ $commande->numero_commande }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h6 class="mb-3">Articles à préparer:</h6>
                                                <ul class="list-group">
                                                    @foreach($commande->lignes as $ligne)
                                                        <li class="list-group-item">
                                                            <div class="d-flex justify-content-between">
                                                                <span>
                                                                    <strong>{{ $ligne->plat->nom }}</strong><br>
                                                                    <small class="text-muted">{{ $ligne->plat->description }}</small>
                                                                </span>
                                                                <span class="badge bg-primary">x{{ $ligne->quantite }}</span>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>

                                                <div class="alert alert-info mt-3">
                                                    <strong>Instructions:</strong><br>
                                                    {{ $commande->notes_cuisine ?? 'Aucune instruction spéciale' }}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                <button type="button" class="btn btn-success" onclick="marquerPrete({{ $commande->id }})">
                                                    <i class="fas fa-check-circle"></i> Marquer comme prête
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-thumbs-up"></i> Aucune commande en préparation actuellement
                </div>
            @endif
        </div>
    </div>

    <!-- Commandes prêtes à servir -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0"><i class="fas fa-check-circle"></i> Commandes Prêtes à Servir</h5>
        </div>
        <div class="card-body">
            @if($commandesPretesAServir->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Commande</th>
                                <th>Client</th>
                                <th>Table</th>
                                <th>Prête depuis</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commandesPretesAServir as $commande)
                                <tr>
                                    <td><strong>#{{ $commande->numero_commande }}</strong></td>
                                    <td>{{ $commande->client->nom ?? 'Non spécifié' }}</td>
                                    <td>
                                        @if($commande->table)
                                            <span class="badge bg-primary">Table {{ $commande->table->numero }}</span>
                                        @else
                                            <span class="badge bg-secondary">À emporter</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $commande->heure_prete->diffInMinutes(now()) }}m
                                        </span>
                                    </td>
                                    <td><span class="badge bg-success">Prête</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-hourglass-end"></i> Aucune commande prête à servir
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function marquerPrete(commandeId) {
    if (!confirm('Marquer cette commande comme prête?')) return;

    fetch(`/cuisinier/commandes/${commandeId}/prete`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('✓ Commande marquée comme prête');
            location.reload();
        } else {
            alert('✗ Erreur: ' + data.message);
        }
    })
    .catch(e => alert('Erreur: ' + e.message));
}
</script>
@endsection
