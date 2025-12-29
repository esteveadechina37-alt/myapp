<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Statistiques Base de Données ===\n";
echo "Catégories: " . \App\Models\Categorie::count() . "\n";
echo "Plats: " . \App\Models\Plat::count() . "\n";
echo "Clients: " . \App\Models\Client::count() . "\n";
echo "Commandes: " . \App\Models\Commande::count() . "\n";
echo "Tables: " . \App\Models\TableRestaurant::count() . "\n";
echo "Factures: " . \App\Models\Facture::count() . "\n";
echo "\n=== Vérification Modèles ===\n";

$categorie = \App\Models\Categorie::first();
if ($categorie) {
    echo "✓ Catégorie trouvée: " . $categorie->nom . "\n";
    $plats = $categorie->plats;
    echo "  - Plats dans cette catégorie: " . $plats->count() . "\n";
}

$plat = \App\Models\Plat::first();
if ($plat) {
    echo "✓ Plat trouvé: " . $plat->nom . " (" . $plat->prix . "€)\n";
}

$table = \App\Models\TableRestaurant::first();
if ($table) {
    echo "✓ Table trouvée: Table " . $table->numero . " (Disponible: " . ($table->est_disponible ? 'Oui' : 'Non') . ")\n";
}

$client = \App\Models\Client::first();
if ($client) {
    echo "✓ Client trouvé: " . $client->nom . "\n";
    $commandes = $client->commandes;
    echo "  - Commandes de ce client: " . $commandes->count() . "\n";
}

echo "\n✅ Système prêt!\n";
