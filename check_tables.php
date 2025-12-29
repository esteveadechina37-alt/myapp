<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Checking Database Tables ===\n\n";

try {
    $tables = DB::select('SHOW TABLES');
    
    echo "Tables in database:\n";
    foreach($tables as $table) {
        foreach($table as $key => $value) {
            echo "  - $value\n";
            
            // Check if it's a table related to our issue
            if (strpos($value, 'table') !== false) {
                echo "    ^ This table contains 'table' in its name\n";
            }
        }
    }
    
    echo "\n=== Checking TableRestaurant Model ===\n";
    $model = new \App\Models\TableRestaurant();
    echo "Model table name: " . $model->getTable() . "\n";
    
    echo "\n=== Trying to count records ===\n";
    try {
        $count = \App\Models\TableRestaurant::count();
        echo "Number of records in tables_restaurant: $count\n";
    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
