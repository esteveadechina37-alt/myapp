@extends('layouts.client')

@section('title', 'Finaliser la commande')

@section('extra-styles')
<style>
    .checkout-container {
        max-width: 1000px;
    }

    .type-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .type-card {
        padding: 20px;
        border: 2px solid #ddd;
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .type-card:hover {
        border-color: #d32f2f;
        background: #ffebee;
    }

    .type-card.selected {
        border-color: #d32f2f;
        background: #d32f2f;
        color: white;
    }

    .type-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .checkout-form-section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .section-title {
        font-weight: 700;
        margin-bottom: 20px;
        font-size: 1.2rem;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #d32f2f;
        box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
    }

    .table-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
    }

    .table-option {
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .table-option:hover {
        border-color: #d32f2f;
    }

    .table-option input[type="radio"] {
        display: none;
    }

    .table-option input[type="radio"]:checked ~ label {
        color: #d32f2f;
        font-weight: 700;
    }

    .table-option.selected {
        border-color: #d32f2f;
        background: #ffebee;
    }

    .conditional-section {
        display: none;
    }

    .conditional-section.active {
        display: block;
    }

    .summary-section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        position: sticky;
        top: 20px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .summary-item.total {
        border: none;
        padding-top: 15px;
        font-weight: 700;
        font-size: 1.1rem;
        color: #d32f2f;
    }

    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
        color: white;
        border: none;
        padding: 15px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 20px;
        transition: all 0.3s ease;
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(211, 47, 47, 0.4);
    }

    .btn-submit:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
    }

    .info-banner {
        background: #e3f2fd;
        border-left: 4px solid #1976d2;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
        color: #1565c0;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .checkout-grid {
            grid-template-columns: 1fr;
        }

        .summary-section {
            position: static;
        }

        .type-selector {
            grid-template-columns: 1fr;
        }

        .table-grid {
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="checkout-container">
        <h1 class="mb-4">
            <i class="fas fa-receipt"></i> Finaliser la commande
        </h1>

        <div class="checkout-grid">
            <div>
                <!-- Type de commande -->
                <div class="checkout-form-section">
                    <h3 class="section-title">Type de commande</h3>
                    
                    <div class="type-selector">
                        <label class="type-card" onclick="selectType(this, 'sur_place')">
                            <input type="radio" name="type_commande_radio" value="sur_place" style="display: none;">
                            <div class="type-icon">🪑</div>
                            <div>Sur place</div>
                        </label>

                        <label class="type-card" onclick="selectType(this, 'a_emporter')">
                            <input type="radio" name="type_commande_radio" value="a_emporter" style="display: none;">
                            <div class="type-icon">🛍️</div>
                            <div>À emporter</div>
                        </label>

                        <label class="type-card" onclick="selectType(this, 'livraison')">
                            <input type="radio" name="type_commande_radio" value="livraison" style="display: none;">
                            <div class="type-icon">🚚</div>
                            <div>Livraison</div>
                        </label>
                    </div>
                </div>

                <!-- Sélection de table -->
                <div class="checkout-form-section conditional-section" id="tableSection">
                    <h3 class="section-title">Sélectionner une table</h3>
                    
                    <div class="table-grid">
                        @foreach ($tables as $table)
                            <label class="table-option">
                                <input type="radio" name="table_id_radio" value="{{ $table->id }}">
                                <label>
                                    <i class="fas fa-table"></i>
                                    Table {{ $table->numero }}
                                </label>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Adresse de livraison -->
                <div class="checkout-form-section conditional-section" id="deliverySection">
                    <h3 class="section-title">Adresse de livraison</h3>
                    
                    <div class="form-group">
                        <label for="adresse_livraison_input">Adresse complète</label>
                        <textarea name="adresse_livraison_input" id="adresse_livraison_input" placeholder="Rue, numéro, code postal, ville..."></textarea>
                    </div>
                </div>

                <!-- Commentaires -->
                <div class="checkout-form-section">
                    <h3 class="section-title">Commentaires spéciaux (optionnel)</h3>
                    
                    <div class="form-group">
                        <label for="commentaires_input">Allergies, préférences, demandes...</label>
                        <textarea name="commentaires_input" id="commentaires_input" placeholder="Ex: Sans oignon, allergique aux cacahuètes..."></textarea>
                    </div>
                </div>

                <!-- Infos utilisateur -->
                <div class="checkout-form-section">
                    <h3 class="section-title">Vos informations</h3>
                    
                    <div class="info-banner">
                        <i class="fas fa-info-circle"></i> Ces informations sont pré-remplies à partir de votre compte
                    </div>

                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" id="name" value="{{ $user->name }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" value="{{ $user->email }}" disabled>
                    </div>
                </div>

                <!-- FORMULAIRE PRINCIPAL -->
                <form method="POST" action="{{ route('client.store-order') }}" id="checkoutForm" class="w-100">
                    @csrf
                    
                    <!-- Inputs réels du formulaire -->
                    <input type="hidden" name="type_commande" id="type_commande_field" value="">
                    <input type="hidden" name="table_id" id="table_id_field" value="">
                    <input type="hidden" name="adresse_livraison" id="adresse_livraison_field" value="">
                    <input type="hidden" name="commentaires" id="commentaires_field" value="">

                    <button type="submit" class="btn-submit w-100" id="submitBtn">
                        <i class="fas fa-check-circle"></i> Confirmer la commande
                    </button>
                </form>
            </div>

            <!-- Résumé -->
            <div class="summary-section">
                <h5 style="font-weight: 700; margin-bottom: 20px;">Résumé</h5>

                <div style="max-height: 400px; overflow-y: auto; margin-bottom: 20px;">
                    @foreach ($items as $item)
                        <div class="summary-item">
                            <span>{{ $item['nom'] }} x{{ $item['quantite'] }}</span>
                            <span>{{ number_format($item['sousTotal'], 0, ',', ' ') }} CFA</span>
                        </div>
                    @endforeach
                </div>

                <div class="summary-item">
                    <span>Sous-total HT</span>
                    <span>{{ number_format($subTotal, 0, ',', ' ') }} CFA</span>
                </div>

                <div class="summary-item">
                    <span>TVA (19.6%)</span>
                    <span>{{ number_format($tva, 0, ',', ' ') }} CFA</span>
                </div>

                <div class="summary-item total">
                    <span>TOTAL TTC</span>
                    <span>{{ number_format($total, 0, ',', ' ') }} CFA</span>
                </div>

                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #f0f0f0;">
                    <p class="text-muted small">
                        <i class="fas fa-lock"></i> Paiement sécurisé
                    </p>
                    <p class="text-muted small">
                        <i class="fas fa-undo"></i> Annulation gratuite
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let selectedType = null;
    let selectedTable = null;
    let selectedAddress = '';
    let selectedComments = '';

    function selectType(el, type) {
        selectedType = type;
        document.getElementById('type_commande_field').value = type;
        
        // Mettre à jour l'affichage
        document.querySelectorAll('.type-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input[type="radio"]').checked = true;

        // Afficher/masquer les sections
        document.getElementById('tableSection').classList.remove('active');
        document.getElementById('deliverySection').classList.remove('active');

        if (type === 'sur_place') {
            document.getElementById('tableSection').classList.add('active');
        } else if (type === 'livraison') {
            document.getElementById('deliverySection').classList.add('active');
        }
    }

    // Mise à jour de la table sélectionnée
    document.addEventListener('change', function(e) {
        if (e.target.name === 'table_id_radio') {
            selectedTable = e.target.value;
            document.getElementById('table_id_field').value = selectedTable;
            document.querySelectorAll('.table-option').forEach(o => o.classList.remove('selected'));
            e.target.closest('.table-option').classList.add('selected');
        }
    });

    // Gestion de la soumission du formulaire
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        console.log('Form submission started...');
        console.log('Type:', selectedType);
        console.log('Table:', selectedTable);
        
        // Valider le type de commande
        if (!selectedType) {
            alert('❌ Sélectionnez un type de commande (Sur place, À emporter ou Livraison)');
            return false;
        }

        // Valider la table (si sur place)
        if (selectedType === 'sur_place' && !selectedTable) {
            alert('❌ Sélectionnez une table');
            return false;
        }

        // Valider l'adresse (si livraison)
        if (selectedType === 'livraison') {
            const addressInput = document.getElementById('adresse_livraison_input');
            selectedAddress = addressInput ? addressInput.value.trim() : '';
            
            if (!selectedAddress) {
                alert('❌ Entrez une adresse de livraison');
                return false;
            }
            document.getElementById('adresse_livraison_field').value = selectedAddress;
        }

        // Récupérer les commentaires
        const commentInput = document.getElementById('commentaires_input');
        selectedComments = commentInput ? commentInput.value.trim() : '';
        document.getElementById('commentaires_field').value = selectedComments;

        // Désactiver le bouton et afficher le traitement
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement en cours...';

        // Afficher les valeurs avant soumission pour debugging
        console.log('Submitting form with:');
        console.log('type_commande:', document.getElementById('type_commande_field').value);
        console.log('table_id:', document.getElementById('table_id_field').value);
        console.log('adresse_livraison:', document.getElementById('adresse_livraison_field').value);
        console.log('commentaires:', document.getElementById('commentaires_field').value);

        // Soumettre le formulaire
        setTimeout(() => {
            this.submit();
        }, 200);
    });
</script>
@endsection
