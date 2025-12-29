<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Categorie;

// Mettre à jour Entrées
Categorie::where('nom', 'Entrées')->update([
    'image' => 'https://images.pexels.com/photos/1092730/pexels-photo-1092730.jpeg?w=500&h=500&fit=crop'
]);

// Mettre à jour Boissons
Categorie::where('nom', 'Boissons')->update([
    'image' => 'https://images.pexels.com/photos/699953/pexels-photo-699953.jpeg?w=500&h=500&fit=crop'
]);

echo "✅ Images mises à jour avec succès!\n";
