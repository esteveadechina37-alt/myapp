<?php
require 'bootstrap/app.php';

$app = app();
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test Database
echo "=== TEST DE CONNEXION À LA BASE DE DONNÉES ===\n\n";

try {
    // Test simple de requête
    $testDb = \Illuminate\Support\Facades\DB::connection()->select('select 1');
    echo "✓ Connexion BD OK\n\n";
    
    // Vérifier les données
    echo "Données disponibles:\n";
    
    // Catégories
    $catCount = \App\Models\Categorie::count();
    $activeCatCount = \App\Models\Categorie::where('est_active', true)->count();
    echo "  • Catégories: $catCount total, $activeCatCount actives\n";
    
    // Plats
    $platCount = \App\Models\Plat::count();
    $availablePlatCount = \App\Models\Plat::where('est_disponible', true)->count();
    echo "  • Plats: $platCount total, $availablePlatCount disponibles\n";
    
    // Clients
    $clientCount = \App\Models\Client::count();
    echo "  • Clients: $clientCount\n";
    
    // Utilisateurs
    $userCount = \App\Models\User::count();
    echo "  • Utilisateurs: $userCount\n";
    
    // Tables
    $tableCount = \App\Models\TableRestaurant::count();
    $availTableCount = \App\Models\TableRestaurant::where('est_disponible', true)->count();
    echo "  • Tables: $tableCount total, $availTableCount disponibles\n";
    
    // Commandes
    $commandeCount = \App\Models\Commande::count();
    echo "  • Commandes: $commandeCount\n";
    
    // Factures
    $factureCount = \App\Models\Facture::count();
    echo "  • Factures: $factureCount\n";
    
    echo "\n✓ Système prêt!\n";
    
} catch (\Exception $e) {
    echo "✗ Erreur: " . $e->getMessage() . "\n";
    echo $e->getFile() . ":" . $e->getLine() . "\n";
}
?>
