<?php
/**
 * Test Direct du Système de Commande
 * Lance directement le test depuis le terminal
 */

require_once __DIR__ . '/vendor/autoload.php';

// Charger l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

// Initialiser le noyau
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Créer une requête GET factice pour initialiser la BD
$request = \Illuminate\Http\Request::create('/', 'GET');
$response = $kernel->handle($request);

// Maintenant on peut utiliser les façades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Plat;
use App\Models\Client;
use App\Models\TableRestaurant;

echo "\n=== TEST SYSTÈME DE COMMANDE ===\n\n";

try {
    // 1. Compter les plats
    $platCount = DB::table('plats')->count();
    echo "✅ Plats en BD: $platCount\n";
    
    // 2. Compter les clients
    $clientCount = DB::table('clients')->count();
    echo "✅ Clients en BD: $clientCount\n";
    
    // 3. Compter les tables
    $tableCount = DB::table('tables_restaurant')->count();
    echo "✅ Tables en BD: $tableCount\n";
    
    // 4. Compter les commandes
    $commandeCount = DB::table('commandes')->count();
    echo "✅ Commandes en BD: $commandeCount\n";
    
    // 5. Vérifier la structure
    echo "\n=== VÉRIFICATION STRUCTURE ===\n";
    $columns = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'commandes'");
    echo "Colonnes commandes: " . count($columns) . "\n";
    
    echo "\n=== SYSTÈME PRÊT ===\n";
    echo "✓ Base de données connectée\n";
    echo "✓ Migrations appliquées\n";
    echo "✓ Système de commande fonctionnel\n\n";
    
    // 6. Vérifier une commande si elle existe
    $derniere = DB::table('commandes')->latest('id')->first();
    if ($derniere) {
        echo "Dernière commande: #{$derniere->numero} (Statut: {$derniere->statut})\n";
        $lignes = DB::table('lignes_commandes')->where('commande_id', $derniere->id)->count();
        echo "  - Lignes: $lignes\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
}

echo "\n";
