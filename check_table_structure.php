<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== STRUCTURE DE LA TABLE COMMANDES ===\n\n";

$columns = DB::select("DESCRIBE commandes");
foreach ($columns as $col) {
    printf("%-30s | %-40s | %-5s | %s\n", 
        $col->Field, 
        $col->Type, 
        $col->Null, 
        $col->Default ?? ''
    );
}

echo "\n✅ Total: " . count($columns) . " colonnes\n";

// Vérifier les statuts disponibles
$result = DB::select("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'commandes' AND COLUMN_NAME = 'statut'");
if ($result) {
    echo "\nStatuts disponibles:\n";
    $type = $result[0]->COLUMN_TYPE;
    $statuts = str_replace(['enum(', ')', "'"], '', $type);
    foreach (explode(',', $statuts) as $statut) {
        echo "  - " . trim($statut) . "\n";
    }
}
?>
