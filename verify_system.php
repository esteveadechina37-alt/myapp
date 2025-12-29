<?php
require 'vendor/autoload.php';

try {
    // Initialiser Laravel
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    
    echo "=== VÉRIFICATION DU SYSTÈME CLIENT ===\n\n";
    
    // 1. Vérifier les contrôleurs
    echo "1. Contrôleurs:\n";
    if (file_exists('app/Http/Controllers/Client/ClientOrderController.php')) {
        echo "   ✓ ClientOrderController.php existe\n";
    } else {
        echo "   ✗ ClientOrderController.php MANQUANT\n";
    }
    
    // 2. Vérifier les vues
    echo "\n2. Fichiers de vue:\n";
    $views = [
        'dashboard.blade.php',
        'menu.blade.php',
        'cart.blade.php',
        'checkout.blade.php',
        'order-detail.blade.php',
        'order-history.blade.php',
        'invoices.blade.php'
    ];
    
    foreach ($views as $view) {
        $path = "resources/views/client/{$view}";
        if (file_exists($path)) {
            $size = filesize($path);
            echo "   ✓ {$view} ({$size} bytes)\n";
        } else {
            echo "   ✗ {$view} MANQUANT\n";
        }
    }
    
    echo "\n3. Routes client enregistrées:\n";
    $routes = require 'routes/web.php';
    
    echo "\n4. Modèles utilisés:\n";
    $models = ['Commande', 'LigneCommande', 'Plat', 'Client', 'Categorie', 'TableRestaurant', 'Facture'];
    foreach ($models as $model) {
        $class = "App\\Models\\{$model}";
        if (class_exists($class)) {
            echo "   ✓ {$model} model existe\n";
        } else {
            echo "   ✗ {$model} model MANQUANT\n";
        }
    }
    
    echo "\n5. Configuration du système:\n";
    echo "   Environment: " . env('APP_ENV', 'production') . "\n";
    echo "   Debug: " . (env('APP_DEBUG') ? 'ON' : 'OFF') . "\n";
    echo "   Database: " . env('DB_CONNECTION', 'mysql') . "\n";
    
    echo "\n=== VÉRIFICATION COMPLÈTE ===\n";
    
} catch (\Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
