@extends('layouts.app')

@section('title', 'Commandes à Préparer')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h1 class="h3 fw-bold">
            <i class="fas fa-list-check" style="color: #d32f2f;"></i> Toutes les Commandes
        </h1>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('cuisinier.commandes') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="enregistree" {{ request('statut') === 'enregistree' ? 'selected' : '' }}>Enregistrée</option>
                        <option value="en_preparation" {{ request('statut') === 'en_preparation' ? 'selected' : '' }}>En préparation</option>
                        <option value="prete" {{ request('statut') === 'prete' ? 'selected' : '' }}>Prête</option>
                        <option value="servie" {{ request('statut') === 'servie' ? 'selected' : '' }}>Servie</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher (commande, client)..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Commande</th>
                        <th>Client</th>
                        <th>Articles</th>
                        <th>Statut</th>
                        <th>Durée</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandes as $commande)
                        <tr class="align-middle">
                            <td>
                                <strong>#{{ $commande->numero_commande }}</strong><br>
                                <small class="text-muted">{{ $commande->heure_commande->format('H:i') }}</small>
                            </td>
                            <td>{{ $commande->client->nom ?? 'Non spécifié' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $commande->lignes->count() }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $this->getStatutColor($commande->statut) }}">
                                    {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-danger">{{ $commande->heure_commande->diffInMinutes(now()) }}m</strong>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#details{{ $commande->id }}">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                @if($commande->statut === 'en_preparation')
                                    <button class="btn btn-sm btn-success" onclick="marquerPrete({{ $commande->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        <tr class="collapse" id="details{{ $commande->id }}">
                            <td colspan="6">
                                <div class="p-3 bg-light">
                                    <h6 class="mb-2">Articles à préparer:</h6>
                                    <ul class="mb-0">
                                        @foreach($commande->lignes as $ligne)
                                            <li>
                                                <strong>{{ $ligne->plat->nom }}</strong> x{{ $ligne->quantite }}
                                                <br><small class="text-muted">{{ $ligne->plat->description }}</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="alert alert-info mb-0">
                                    Aucune commande trouvée
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $commandes->links() }}
    </div>
</div>

<script>
function marquerPrete(commandeId) {
    if (!confirm('Marquer cette commande comme prête?')) return;

    fetch(`/cuisinier/commandes/${commandeId}/prete`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
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
    .catch(e => alert('Erreur réseau'));
}
</script>
@endsection
