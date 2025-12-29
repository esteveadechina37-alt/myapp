<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== État de la base de données ===\n";

// Récupérer tous les noms de tables
$tables = DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE()");

$totalRows = 0;
foreach ($tables as $table) {
    $tableName = $table->TABLE_NAME;
    $count = DB::table($tableName)->count();
    $totalRows += $count;
    echo "$tableName: $count lignes\n";
}

echo "\nTotal: $totalRows lignes\n";

if ($totalRows == 0) {
    echo "\n✅ La base de données est COMPLÈTEMENT VIDE!\n";
} else {
    echo "\n❌ La base de données contient encore des données!\n";
}
