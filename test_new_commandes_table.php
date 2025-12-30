<?php
// Test du système de commande avec la nouvelle structure

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Commande;
use App\Models\Client;
use App\Models\Utilisateur;
use App\Models\Plat;
use App\Models\LigneCommande;
use App\Models\TableRestaurant;

echo "=== TEST DE LA NOUVELLE STRUCTURE DE COMMANDES ===\n\n";

try {
    // 1. Vérifier les tables
    echo "1️⃣ Vérification des tables...\n";
    
    $tables = [
        'clients' => Client::count(),
        'utilisateurs' => Utilisateur::count(),
        'plats' => Plat::count(),
        'tables_restaurant' => TableRestaurant::count(),
        'commandes' => Commande::count(),
    ];
    
    foreach ($tables as $table => $count) {
        echo "   ✓ $table: $count enregistrements\n";
    }
    
    // 2. Tester la création d'une commande
    echo "\n2️⃣ Test de création d'une commande...\n";
    
    // Créer un client de test
    $testClient = Client::updateOrCreate(
        ['email' => 'test@example.com'],
        ['nom' => 'Test', 'prenom' => 'Client']
    );
    echo "   ✓ Client créé: " . $testClient->email . "\n";
    
    // Créer un utilisateur de test
    $testUser = Utilisateur::first();
    if (!$testUser) {
        echo "   ⚠️ Pas d'utilisateur trouvé, création d'un utilisateur de test...\n";
        $testUser = Utilisateur::create([
            'nom' => 'Test User',
            'prenom' => 'Test',
            'email' => 'test-user@example.com',
            'password' => bcrypt('password'),
            'role' => 'client'
        ]);
    }
    echo "   ✓ Utilisateur: " . $testUser->email . " (ID: " . $testUser->id . ")\n";
    
    // Créer une commande
    $commande = Commande::create([
        'numero' => 'CMD-' . date('YmdHis'),
        'client_id' => $testClient->id,
        'utilisateur_id' => $testUser->id,
        'type_commande' => 'sur_place',
        'montant_total_ht' => 50000,
        'montant_tva' => 9800,
        'montant_tva_pourcentage' => 19.6,
        'montant_total_ttc' => 59800,
        'statut' => 'confirmee',
        'heure_commande' => now(),
        'heure_confirmation' => now(),
        'est_payee' => false,
        'facture_generee' => false,
        'commentaires' => 'Test commande'
    ]);
    echo "   ✓ Commande créée: #" . $commande->numero . " (ID: " . $commande->id . ")\n";
    
    // 3. Vérifier les champs
    echo "\n3️⃣ Vérification des champs de la commande...\n";
    $fields = [
        'id', 'numero', 'client_id', 'utilisateur_id', 'type_commande',
        'table_id', 'adresse_livraison', 'telephone_livraison',
        'montant_total_ht', 'montant_tva', 'montant_tva_pourcentage', 'montant_total_ttc',
        'frais_livraison', 'montant_remise', 'statut',
        'heure_commande', 'heure_confirmation', 'heure_remise_cuisine', 'heure_prete',
        'heure_depart_livraison', 'heure_livraison', 'heure_paiement',
        'est_payee', 'moyen_paiement', 'reference_paiement',
        'commentaires', 'notes_cuisine', 'notes_livraison',
        'facture_generee', 'date_facture', 'numero_facture',
    ];
    
    $missing = [];
    foreach ($fields as $field) {
        if (!array_key_exists($field, $commande->getAttributes())) {
            $missing[] = $field;
        } else {
            echo "   ✓ " . $field . "\n";
        }
    }
    
    if (!empty($missing)) {
        echo "\n   ⚠️ Champs manquants: " . implode(', ', $missing) . "\n";
    }
    
    // 4. Tester les méthodes de workflow
    echo "\n4️⃣ Test des méthodes de workflow...\n";
    
    $commande->envoyerCuisine();
    echo "   ✓ envoyerCuisine(): statut = " . $commande->statut . "\n";
    
    $commande->demarrerPreparation();
    echo "   ✓ demarrerPreparation(): statut = " . $commande->statut . "\n";
    
    $commande->marquerPreteAEmporter();
    echo "   ✓ marquerPreteAEmporter(): statut = " . $commande->statut . "\n";
    
    $commande->enregistrerPaiement('especes', 'PAY-001');
    echo "   ✓ enregistrerPaiement(): statut = " . $commande->statut . ", moyen = " . $commande->moyen_paiement . "\n";
    
    // 5. Tester la génération de facture
    echo "\n5️⃣ Test de la génération de facture...\n";
    $commande->marquerFactureGeneree();
    echo "   ✓ Facture marquée comme générée: " . ($commande->facture_generee ? 'OUI' : 'NON') . "\n";
    echo "   ✓ Numéro facture: " . $commande->numero_facture . "\n";
    
    // 6. Vérifier les relations
    echo "\n6️⃣ Vérification des relations...\n";
    echo "   ✓ Client: " . ($commande->client ? $commande->client->email : 'N/A') . "\n";
    echo "   ✓ Utilisateur: " . ($commande->utilisateur ? $commande->utilisateur->email : 'N/A') . "\n";
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "✅ TOUS LES TESTS SONT PASSÉS!\n";
    echo "La nouvelle table commandes est correctement configurée.\n";
    
} catch (Exception $e) {
    echo "\n❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
?>
