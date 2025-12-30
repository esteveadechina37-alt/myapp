<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Commande;
use App\Models\Client;
use App\Models\LigneCommande;
use App\Models\Plat;
use Illuminate\Support\Facades\DB;

echo "=== TEST DU FLUX COMPLET DE COMMANDE ===\n\n";

try {
    // Récupérer un plat disponible
    $plat = Plat::where('est_disponible', true)->first();
    if (!$plat) {
        echo "❌ Aucun plat disponible\n";
        exit(1);
    }
    
    // Créer ou récupérer un client
    $client = Client::firstOrCreate(
        ['email' => 'test.commande@example.com'],
        ['nom' => 'Test', 'prenom' => 'Commande']
    );
    
    // 1. Créer une commande
    echo "1️⃣ Création d'une commande...\n";
    $commande = Commande::create([
        'numero' => 'TEST-' . date('YmdHis'),
        'client_id' => $client->id,
        'utilisateur_id' => null,
        'type_commande' => 'a_emporter',
        'montant_total_ht' => $plat->prix,
        'montant_tva' => $plat->prix * 0.196,
        'montant_tva_pourcentage' => 19.6,
        'montant_total_ttc' => $plat->prix * 1.196,
        'statut' => 'confirmee',
        'heure_commande' => now(),
        'heure_confirmation' => now(),
        'est_payee' => false,
        'facture_generee' => false,
        'commentaires' => 'Test du flux complet',
    ]);
    
    echo "   ✓ Commande créée: #" . $commande->numero . " (ID: " . $commande->id . ")\n";
    echo "   ✓ Statut: " . $commande->getStatutFrancais() . "\n";
    echo "   ✓ Montant: " . number_format($commande->montant_total_ttc, 0, ',', ' ') . " CFA\n";
    
    // 2. Ajouter une ligne de commande
    echo "\n2️⃣ Ajout d'une ligne de commande...\n";
    $ligne = LigneCommande::create([
        'commande_id' => $commande->id,
        'plat_id' => $plat->id,
        'quantite' => 2,
        'prix_unitaire_ht' => $plat->prix,
        'taux_tva' => 19.6,
        'statut' => 'en_attente'
    ]);
    echo "   ✓ Ligne créée: " . $plat->nom . " x" . $ligne->quantite . "\n";
    
    // 3. Workflow
    echo "\n3️⃣ Workflow de la commande...\n";
    
    $commande->envoyerCuisine();
    echo "   ✓ Envoyée à la cuisine → " . $commande->getStatutFrancais() . "\n";
    
    $commande->demarrerPreparation();
    echo "   ✓ Préparation démarrée → " . $commande->getStatutFrancais() . "\n";
    
    $commande->marquerPreteAEmporter();
    echo "   ✓ Prête à emporter → " . $commande->getStatutFrancais() . "\n";
    
    // 4. Paiement
    echo "\n4️⃣ Paiement...\n";
    $commande->enregistrerPaiement('especes', 'PAIEMENT-001');
    echo "   ✓ Payée via " . $commande->moyen_paiement . "\n";
    echo "   ✓ Statut: " . $commande->getStatutFrancais() . "\n";
    echo "   ✓ Montant payé: " . number_format($commande->calculerMontantTotal(), 0, ',', ' ') . " CFA\n";
    
    // 5. Facture
    echo "\n5️⃣ Génération de facture...\n";
    if (!$commande->facture_generee) {
        $commande->marquerFactureGeneree();
        echo "   ✓ Facture marquée comme générée\n";
        echo "   ✓ Numéro: " . $commande->numero_facture . "\n";
        echo "   ✓ Date: " . ($commande->date_facture ? $commande->date_facture->format('d/m/Y H:i') : 'N/A') . "\n";
    }
    
    // 6. Vérifications finales
    echo "\n6️⃣ Vérifications finales...\n";
    echo "   ✓ Commande payée: " . ($commande->estPayee() ? 'OUI' : 'NON') . "\n";
    echo "   ✓ Facture générée: " . ($commande->facture_generee ? 'OUI' : 'NON') . "\n";
    echo "   ✓ Type: " . $commande->type_commande . "\n";
    echo "   ✓ Lignes: " . $commande->lignesCommandes()->count() . "\n";
    
    // 7. Relations
    echo "\n7️⃣ Vérification des relations...\n";
    echo "   ✓ Client: " . ($commande->client ? $commande->client->email : 'N/A') . "\n";
    echo "   ✓ Lignes: " . count($commande->lignesCommandes) . "\n";
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "✅ FLUX COMPLET RÉUSSI!\n";
    echo "\nRésumé:\n";
    echo "  • Commande: #" . $commande->numero . "\n";
    echo "  • Statut: " . $commande->getStatutFrancais() . "\n";
    echo "  • Montant: " . number_format($commande->montant_total_ttc, 0, ',', ' ') . " CFA\n";
    echo "  • Payée: " . ($commande->est_payee ? 'OUI' : 'NON') . "\n";
    echo "  • Facture: " . ($commande->facture_generee ? $commande->numero_facture : 'En attente') . "\n";
    
} catch (Exception $e) {
    echo "\n❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
?>
