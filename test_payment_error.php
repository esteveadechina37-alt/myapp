<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Payment Flow ===\n\n";

try {
    // Simulate what happens when confirming payment
    $commande = \App\Models\Commande::with(['table', 'client', 'lignesCommandes'])->first();
    
    if (!$commande) {
        echo "No commande found in database\n";
        exit(1);
    }
    
    echo "Commande ID: " . $commande->id . "\n";
    echo "Commande Numero: " . $commande->numero . "\n";
    echo "Table ID: " . ($commande->table_id ?? 'NULL') . "\n";
    
    if ($commande->table_id) {
        echo "\n=== Trying to access table relation ===\n";
        $table = $commande->table;
        echo "Table found: " . ($table ? "YES" : "NO") . "\n";
        if ($table) {
            echo "Table numero: " . $table->numero . "\n";
        }
    }
    
    echo "\n=== Trying to validate table exists ===\n";
    // This is what the validation rule does
    $tableId = 2;
    $exists = \DB::table('tables_restaurant')->where('id', $tableId)->exists();
    echo "Table ID $tableId exists: " . ($exists ? "YES" : "NO") . "\n";
    
    // Try with the wrong table name to reproduce the error
    echo "\n=== Trying with wrong table name (tables_restaurants) ===\n";
    try {
        $exists = \DB::table('tables_restaurants')->where('id', $tableId)->exists();
        echo "This should not work!\n";
    } catch (\Exception $e) {
        echo "ERROR (expected): " . $e->getMessage() . "\n";
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
