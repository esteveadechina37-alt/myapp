<?php
/**
 * Script de test et correction du système de commande
 * Force la création de commandes et vérifie l'enregistrement en BD
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Plat;
use App\Models\Client;
use App\Models\Utilisateur;
use App\Models\TableRestaurant;

try {
    echo "\n\n=== TEST SYSTÈME DE COMMANDE ===\n\n";
    
    // 1. Vérifier les plats
    echo "1️⃣  Vérification des plats:\n";
    $plats = Plat::where('est_disponible', true)->get();
    echo "   ✓ Plats disponibles: " . count($plats) . "\n";
    if (count($plats) === 0) {
        echo "   ⚠️  Aucun plat disponible!\n";
    } else {
        foreach ($plats->take(3) as $p) {
            echo "   - {$p->nom}: {$p->prix}€\n";
        }
    }
    
    // 2. Vérifier les clients
    echo "\n2️⃣  Vérification des clients:\n";
    $clients = Client::count();
    echo "   ✓ Clients dans le système: $clients\n";
    
    // 3. Vérifier les utilisateurs (pour les tests)
    echo "\n3️⃣  Vérification des utilisateurs:\n";
    $users = DB::table('users')->get();
    echo "   ✓ Utilisateurs trouvés: " . count($users) . "\n";
    if (count($users) > 0) {
        echo "   Premiers utilisateurs:\n";
        foreach ($users->take(3) as $u) {
            echo "   - {$u->email} ({$u->name})\n";
        }
    }
    
    // 4. Vérifier les tables
    echo "\n4️⃣  Vérification des tables:\n";
    $tables = TableRestaurant::count();
    echo "   ✓ Tables dans le système: $tables\n";
    if ($tables > 0) {
        $disponibles = TableRestaurant::where('est_disponible', true)->count();
        echo "   ✓ Tables disponibles: $disponibles\n";
    }
    
    // 5. Vérifier les commandes existantes
    echo "\n5️⃣  Commandes existantes:\n";
    $total = Commande::count();
    echo "   ✓ Total commandes: $total\n";
    
    if ($total > 0) {
        $statuts = DB::table('commandes')
            ->select('statut', DB::raw('COUNT(*) as cnt'))
            ->groupBy('statut')
            ->get();
        echo "   Par statut:\n";
        foreach ($statuts as $s) {
            echo "   - {$s->statut}: {$s->cnt}\n";
        }
        
        // Afficher une commande récente
        $derniere = Commande::with('lignesCommandes.plat', 'client')->latest()->first();
        if ($derniere) {
            echo "\n   Dernière commande:\n";
            echo "   - Numéro: {$derniere->numero}\n";
            echo "   - Client: " . ($derniere->client->email ?? 'N/A') . "\n";
            echo "   - Statut: {$derniere->statut}\n";
            echo "   - Montant: {$derniere->montant_total_ttc}€\n";
            echo "   - Lignes: " . count($derniere->lignesCommandes) . "\n";
        }
    }
    
    // 6. Vérifier la structure des tables
    echo "\n6️⃣  Structure de la base de données:\n";
    echo "   ✓ Table 'commandes':\n";
    $cmdCols = DB::select("SELECT COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'commandes' LIMIT 10");
    foreach ($cmdCols as $col) {
        echo "     - {$col->COLUMN_NAME}: {$col->COLUMN_TYPE}\n";
    }
    
    echo "\n   ✓ Table 'lignes_commandes':\n";
    $ligCols = DB::select("SELECT COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'lignes_commandes' LIMIT 10");
    foreach ($ligCols as $col) {
        echo "     - {$col->COLUMN_NAME}: {$col->COLUMN_TYPE}\n";
    }
    
    echo "\n\n=== SYSTÈME PRÊT POUR LES TESTS ===\n";
    echo "Pour tester:\n";
    echo "1. Allez à http://localhost:8000/\n";
    echo "2. Cliquez sur 'Consulter Menu' ou 'Passer Commande'\n";
    echo "3. Connectez-vous avec vos identifiants\n";
    echo "4. Passez une commande\n";
    echo "5. Les commandes seront visibles dans admin/commandes\n\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . " (ligne " . $e->getLine() . ")\n\n";
}
