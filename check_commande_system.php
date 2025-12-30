<?php
/**
 * Vérification et correction du système de commande
 * Analyse la structure de la BD et du système de commande
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

// Démarrer l'application
$app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Support\Facades\DB;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Plat;
use App\Models\Client;
use App\Models\Categorie;

echo "\n=== VÉRIFICATION SYSTÈME DE COMMANDE ===\n\n";

// 1. Vérifier la structure de la table commandes
echo "1️⃣  STRUCTURE TABLE COMMANDES:\n";
$columns = DB::select("SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'commandes'");
if (empty($columns)) {
    echo "❌ Table 'commandes' non trouvée!\n";
} else {
    echo "✓ Colonnes trouvées: " . count($columns) . "\n";
    foreach ($columns as $col) {
        $nullable = $col->IS_NULLABLE === 'YES' ? ' (NULLABLE)' : '';
        echo "  - {$col->COLUMN_NAME}: {$col->COLUMN_TYPE}{$nullable}\n";
    }
}

// 2. Compter les commandes existantes
echo "\n2️⃣  COMMANDES EXISTANTES:\n";
try {
    $count = DB::table('commandes')->count();
    echo "✓ Nombre total: $count\n";
    
    if ($count > 0) {
        $statuts = DB::table('commandes')
            ->select('statut', DB::raw('COUNT(*) as total'))
            ->groupBy('statut')
            ->get();
        echo "  Par statut:\n";
        foreach ($statuts as $s) {
            echo "  - {$s->statut}: {$s->total}\n";
        }
    }
} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

// 3. Vérifier la structure des lignes de commande
echo "\n3️⃣  STRUCTURE TABLE LIGNES_COMMANDES:\n";
$columns = DB::select("SELECT COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'lignes_commandes'");
if (empty($columns)) {
    echo "❌ Table 'lignes_commandes' non trouvée!\n";
} else {
    echo "✓ Colonnes trouvées: " . count($columns) . "\n";
    foreach ($columns as $col) {
        echo "  - {$col->COLUMN_NAME}: {$col->COLUMN_TYPE}\n";
    }
}

// 4. Vérifier les plats
echo "\n4️⃣  PLATS DISPONIBLES:\n";
try {
    $plats = Plat::where('est_disponible', true)->count();
    echo "✓ Plats disponibles: $plats\n";
} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

// 5. Vérifier les clients
echo "\n5️⃣  CLIENTS DANS LE SYSTÈME:\n";
try {
    $clients = Client::count();
    echo "✓ Nombre de clients: $clients\n";
} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

// 6. Vérifier les catégories
echo "\n6️⃣  CATÉGORIES:\n";
try {
    $categories = Categorie::where('est_active', true)->count();
    echo "✓ Catégories actives: $categories\n";
} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

// 7. Vérifier une commande complète (si elle existe)
echo "\n7️⃣  EXEMPLE DE COMMANDE:\n";
try {
    $commande = Commande::with('lignesCommandes.plat', 'client')->first();
    if ($commande) {
        echo "✓ Commande trouvée: #{$commande->numero}\n";
        echo "  - ID: {$commande->id}\n";
        echo "  - Statut: {$commande->statut}\n";
        echo "  - Montant: {$commande->montant_total_ttc} €\n";
        echo "  - Client: {$commande->client->email ?? 'N/A'}\n";
        echo "  - Lignes: " . $commande->lignesCommandes()->count() . "\n";
    } else {
        echo "⚠️  Aucune commande trouvée\n";
    }
} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== FIN VÉRIFICATION ===\n\n";
