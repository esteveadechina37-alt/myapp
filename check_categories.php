<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Categorie;

echo "\n=== Vérification des catégories en base de données ===\n\n";

$categories = Categorie::where('est_active', true)->orderBy('ordre_affichage')->get();

foreach ($categories as $cat) {
    echo "Catégorie: {$cat->nom}\n";
    echo "Image: {$cat->image}\n";
    echo "Description: {$cat->description}\n";
    echo "---\n";
}

echo "\nTotal: " . $categories->count() . " catégories\n\n";
