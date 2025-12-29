<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prendre Commande - Trial+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            min-height: 100vh;
            padding: 30px 0;
            position: fixed;
            width: 260px;
            left: 0;
            top: 0;
            color: white;
            overflow-y: auto;
        }

        .sidebar .logo {
            padding: 20px;
            text-align: center;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 30px;
            border-bottom: 2px solid rgba(255,255,255,0.2);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 15px 25px;
            display: block;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left-color: white;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        .topbar {
            background: white;
            padding: 15px 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .topbar h1 {
            margin: 0;
            color: #333;
            font-size: 1.2rem;
            font-weight: 700;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            border: none;
            padding: 15px 20px;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
        }

        .card-body {
            padding: 20px;
        }

        /* Table Card */
        .table-card {
            background: white;
            border: 2px solid #eee;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .table-card:hover {
            border-color: #1976d2;
            box-shadow: 0 3px 10px rgba(25, 118, 210, 0.2);
        }

        .table-card.selected {
            border-color: #d32f2f;
            background: rgba(211, 47, 47, 0.05);
        }

        .table-card i {
            font-size: 1.8rem;
            color: #1976d2;
            margin-bottom: 10px;
        }

        .table-card.selected i {
            color: #d32f2f;
        }

        /* Plat Card */
        .plat-card {
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .plat-card:hover {
            border-color: #1976d2;
            box-shadow: 0 3px 10px rgba(25, 118, 210, 0.2);
            transform: translateY(-2px);
        }

        .plat-card.selected {
            border-color: #d32f2f;
            background: rgba(211, 47, 47, 0.05);
        }

        .plat-name {
            font-weight: 600;
            font-size: 0.75rem;
            color: #333;
            margin-bottom: 5px;
        }

        .plat-desc {
            font-size: 0.65rem;
            color: #999;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .plat-price {
            font-weight: 700;
            color: #d32f2f;
            font-size: 0.75rem;
        }

        .badge-category {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            font-size: 0.75rem;
            padding: 4px 8px;
            font-weight: 600;
        }

        /* Order Summary */
        .order-summary {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            max-height: 400px;
            overflow-y: auto;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            font-size: 0.95rem;
        }

        .order-item-controls {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .order-item-controls button {
            background: none;
            border: 1px solid #ddd;
            width: 22px;
            height: 22px;
            padding: 0;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .order-item-controls button:hover {
            background: #d32f2f;
            color: white;
            border-color: #d32f2f;
        }

        .order-total {
            font-size: 1.3rem;
            font-weight: 700;
            color: #d32f2f;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #eee;
        }

        /* Buttons */
        .btn-submit {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 10px;
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.4);
            color: white;
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-reset {
            background: #999;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            cursor: pointer;
        }

        .btn-reset:hover {
            background: #666;
            transform: translateY(-2px);
        }

        /* Filter Buttons */
        .filter-btn {
            background: white;
            border: 1px solid #ddd;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 8px;
            margin-bottom: 10px;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border-color: transparent;
        }

        /* Alert */
        .alert-info {
            background: rgba(25, 118, 210, 0.1);
            border: 1px solid #1976d2;
            color: #1976d2;
            padding: 12px;
            border-radius: 5px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
                padding: 15px 0;
                position: relative;
                margin-bottom: 20px;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .topbar {
                padding: 15px;
            }

            .topbar h1 {
                font-size: 1.3rem;
            }

            .card-body {
                padding: 15px;
            }

            .plat-card {
                padding: 10px;
                min-height: 90px;
            }

            .table-card {
                min-height: 100px;
                padding: 10px;
            }

            .table-card i {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo"><i class="fas fa-user-tie"></i> Serveur</div>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('serveur.dashboard') }}">
                <i class="fas fa-chart-line"></i> Tableau de Bord
            </a>
            <a class="nav-link" href="{{ route('serveur.commandes') }}">
                <i class="fas fa-list"></i> Commandes
            </a>
            <a class="nav-link active" href="{{ route('serveur.prendre-commande') }}">
                <i class="fas fa-pen"></i> Prendre Commande
            </a>
            <hr style="opacity: 0.2; margin: 20px 0;">
            <form action="{{ route('logout') }}" method="POST" style="padding: 0 25px;">
                @csrf
                <button type="submit" class="nav-link" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </button>
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h1><i class="fas fa-pen"></i> Prendre une Nouvelle Commande</h1>
        </div>

        <div class="row g-3">
            <!-- Sélection Table -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Sélection de Table</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2" id="tablesContainer">
                            @forelse($tables as $table)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="table-card" data-table="{{ $table->id }}" onclick="selectTable(this)">
                                        <i class="fas fa-chair"></i>
                                        <p class="mb-0"><strong>Table {{ $table->numero }}</strong></p>
                                        <small class="text-muted">Cliquez pour sélectionner</small>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Aucune table disponible
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sélection Plats -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Sélection des Plats</h5>
                    </div>
                    <div class="card-body">
                        <!-- Filtres Catégories -->
                        <div class="mb-3">
                            <button class="filter-btn active" onclick="filterCategory('all')">Tous</button>
                            @foreach($categories ?? [] as $cat)
                                <button class="filter-btn" onclick="filterCategory('{{ $cat->id }}')">{{ $cat->nom }}</button>
                            @endforeach
                        </div>

                        <!-- Liste Plats -->
                        <div class="row g-2" id="platsContainer">
                            @forelse($plats as $plat)
                                <div class="col-6 col-md-4 col-lg-6">
                                    <div class="plat-card" data-category="{{ $plat->categorie_id }}" onclick="addToOrder({{ $plat->id }}, '{{ $plat->nom }}', {{ $plat->prix }}, this)">
                                        <div>
                                            <div class="plat-name">{{ $plat->nom }}</div>
                                            <div class="plat-desc">{{ Str::limit($plat->description, 40) }}</div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="plat-price">{{ number_format($plat->prix, 0, ',', ' ') }} FCFA</div>
                                            <span class="badge-category">{{ $plat->categorie->nom ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Aucun plat disponible
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé Commande -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 30px;">
                    <div class="card-header">
                        <h5 class="mb-0">Résumé de Commande</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3" id="tableInfo" style="display: none;">
                            <p class="mb-0">
                                <strong>Table:</strong>
                                <span id="selectedTable" class="badge" style="background: #1976d2; color: white;">Aucune</span>
                            </p>
                        </div>

                        <div id="orderSummary" class="order-summary" style="display: none;">
                            <h6 class="mb-3">Articles commandés:</h6>
                            <div id="orderItems"></div>
                            <div class="order-total">
                                Total: <span id="orderTotal">0</span> FCFA
                            </div>
                        </div>

                        @if(!isset($table))
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> Sélectionnez une table d'abord
                            </div>
                        @endif

                        <button class="btn-submit" id="submitBtn" disabled onclick="submitOrder()">
                            <i class="fas fa-check"></i> Valider Commande
                        </button>
                        <button class="btn-reset" onclick="resetForm()">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let orderData = {};
        let selectedTableId = null;

        function selectTable(element) {
            document.querySelectorAll('.table-card').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
            selectedTableId = element.getAttribute('data-table');
            const tableNum = element.querySelector('strong').textContent;
            document.getElementById('selectedTable').textContent = tableNum;
            document.getElementById('tableInfo').style.display = 'block';
            updateSubmitBtn();
        }

        function addToOrder(platId, name, price, element) {
            if (!selectedTableId) {
                alert('Veuillez d\'abord sélectionner une table');
                return;
            }

            if (!orderData[platId]) {
                orderData[platId] = { name, price, quantity: 0 };
            }
            orderData[platId].quantity++;
            updateOrderSummary();
            updateSubmitBtn();
        }

        function removeFromOrder(platId) {
            if (orderData[platId]) {
                orderData[platId].quantity--;
                if (orderData[platId].quantity === 0) {
                    delete orderData[platId];
                }
                updateOrderSummary();
                updateSubmitBtn();
            }
        }

        function updateOrderSummary() {
            let html = '';
            let total = 0;
            for (let platId in orderData) {
                const item = orderData[platId];
                const qty = item.quantity;
                const price = item.price;
                const subtotal = qty * price;
                total += subtotal;
                html += `<div class="order-item">
                    <div>
                        <div>${item.name}</div>
                        <small class="text-muted">x${qty}</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span>${subtotal.toLocaleString()} FCFA</span>
                        <div class="order-item-controls">
                            <button onclick="removeFromOrder(${platId})">-</button>
                            <button onclick="addToOrder(${platId}, '${item.name}', ${price})">+</button>
                        </div>
                    </div>
                </div>`;
            }
            document.getElementById('orderItems').innerHTML = html;
            document.getElementById('orderTotal').textContent = total.toLocaleString();
            document.getElementById('orderSummary').style.display = Object.keys(orderData).length > 0 ? 'block' : 'none';
        }

        function updateSubmitBtn() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = !selectedTableId || Object.keys(orderData).length === 0;
        }

        function filterCategory(categoryId) {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            document.querySelectorAll('.plat-card').forEach(card => {
                if (categoryId === 'all' || card.getAttribute('data-category') === categoryId) {
                    card.parentElement.style.display = 'block';
                } else {
                    card.parentElement.style.display = 'none';
                }
            });
        }

        function submitOrder() {
            if (!selectedTableId || Object.keys(orderData).length === 0) {
                alert('Veuillez sélectionner une table et ajouter des articles');
                return;
            }

            let items = [];
            for (let platId in orderData) {
                items.push({
                    plat_id: platId,
                    quantite: orderData[platId].quantity
                });
            }

            fetch('{{ route("serveur.store-commande") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    table_id: selectedTableId,
                    plats: items
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Commande créée avec succès!');
                    resetForm();
                } else {
                    alert('Erreur: ' + (data.message || 'Impossible de créer la commande'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la création de la commande');
            });
        }

        function resetForm() {
            orderData = {};
            selectedTableId = null;
            document.querySelectorAll('.table-card').forEach(el => el.classList.remove('selected'));
            document.getElementById('tableInfo').style.display = 'none';
            document.getElementById('orderSummary').style.display = 'none';
            updateSubmitBtn();
        }
    </script>
</body>
</html>
