<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $facture->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 3px solid linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            padding-bottom: 20px;
        }
        
        .restaurant-info {
            flex: 1;
        }
        
        .restaurant-name {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        
        .facture-number {
            text-align: right;
            flex: 1;
        }
        
        .facture-number h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #d32f2f;
        }
        
        .facture-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .meta-section {
            background: #f5f7fa;
            padding: 15px;
            border-radius: 8px;
        }
        
        .meta-section h3 {
            font-size: 0.9rem;
            color: #999;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .meta-section p {
            font-size: 1rem;
            margin-bottom: 5px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table thead {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
        }
        
        .items-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .items-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .text-right {
            text-align: right;
        }
        
        .summary {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }
        
        .summary-box {
            width: 300px;
            background: #f5f7fa;
            padding: 20px;
            border-radius: 8px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .summary-row.total {
            border: none;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #d32f2f;
            font-weight: 700;
            font-size: 1.2rem;
            color: #d32f2f;
        }
        
        .payment-info {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #4caf50;
        }
        
        .payment-info h4 {
            color: #2e7d32;
            margin-bottom: 5px;
        }
        
        .payment-info p {
            color: #558b2f;
            font-size: 0.95rem;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 0.9rem;
        }
        
        @media print {
            body {
                background: white;
            }
            
            .container {
                max-width: 100%;
            }
            
            .print-button {
                display: none;
            }
        }
        
        .print-button {
            display: block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }
        
        .print-button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="print-button" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimer / Télécharger en PDF
        </button>
        
        <div class="header">
            <div class="restaurant-info">
                <div class="restaurant-name">🍽️ Restaurant Trial+</div>
                <p>Cuisine gastronomique</p>
            </div>
            <div class="facture-number">
                <h2>Facture</h2>
                <p><strong>#{{ str_pad($facture->id, 6, '0', STR_PAD_LEFT) }}</strong></p>
            </div>
        </div>
        
        <div class="facture-meta">
            <div class="meta-section">
                <h3>Client</h3>
                <p><strong>{{ $facture->commande->client->nom ?? 'Client' }} {{ $facture->commande->client->prenom ?? '' }}</strong></p>
                <p>Email: {{ $facture->commande->client->email ?? '-' }}</p>
                @if($facture->commande->table)
                    <p>Table: {{ $facture->commande->table->numero }}</p>
                @endif
            </div>
            
            <div class="meta-section">
                <h3>Détails Commande</h3>
                <p><strong>Commande #{{ $facture->commande->numero }}</strong></p>
                <p>Type: <strong>{{ ucfirst(str_replace('_', ' ', $facture->commande->type_commande)) }}</strong></p>
                <p>Date: <strong>{{ $facture->created_at->format('d/m/Y à H:i') }}</strong></p>
            </div>
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th>Plat</th>
                    <th class="text-right">Quantité</th>
                    <th class="text-right">Prix Unitaire</th>
                    <th class="text-right">Total HT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facture->commande->lignesCommandes as $ligne)
                    <tr>
                        <td>
                            <strong>{{ $ligne->plat->nom }}</strong>
                            @if($ligne->plat->description)
                                <br><small style="color: #999;">{{ Str::limit($ligne->plat->description, 60) }}</small>
                            @endif
                        </td>
                        <td class="text-right">{{ $ligne->quantite }}</td>
                        <td class="text-right">{{ number_format($ligne->prix_unitaire_ht, 0, ',', ' ') }} CFA</td>
                        <td class="text-right"><strong>{{ number_format($ligne->prix_unitaire_ht * $ligne->quantite, 0, ',', ' ') }} CFA</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="summary">
            <div class="summary-box">
                <div class="summary-row">
                    <span>Sous-total HT:</span>
                    <span>{{ number_format($facture->commande->montant_total_ht, 0, ',', ' ') }} CFA</span>
                </div>
                <div class="summary-row">
                    <span>TVA (19.6%):</span>
                    <span>{{ number_format($facture->commande->montant_tva, 0, ',', ' ') }} CFA</span>
                </div>
                <div class="summary-row total">
                    <span>Total TTC:</span>
                    <span>{{ number_format($facture->commande->montant_total_ttc, 0, ',', ' ') }} CFA</span>
                </div>
            </div>
        </div>
        
        @if($facture->commande->est_payee)
            <div class="payment-info">
                <h4>✓ Paiement Effectué</h4>
                <p>
                    Méthode de paiement: <strong>{{ ucfirst($facture->commande->moyen_paiement ?? 'Non spécifié') }}</strong>
                    <br>
                    Date de paiement: <strong>{{ $facture->date_paiement->format('d/m/Y à H:i') }}</strong>
                </p>
            </div>
        @endif
        
        @if($facture->commande->commentaires)
            <div style="background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107; margin-bottom: 30px;">
                <h4 style="color: #856404; margin-bottom: 5px;">Notes</h4>
                <p style="color: #856404;">{{ $facture->commande->commentaires }}</p>
            </div>
        @endif
        
        <div class="footer">
            <p>Facture générée automatiquement par Restaurant Trial+</p>
            <p>Merci de votre visite! 🍽️</p>
            <p style="margin-top: 10px; font-size: 0.8rem;">{{ config('app.name') }} - {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
