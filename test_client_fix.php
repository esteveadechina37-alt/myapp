<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST DE LA CORRECTION ===\n\n";

// Test 1: Vérifier qu'on peut récupérer un utilisateur
echo "1. Récupération d'un utilisateur:\n";
$user = \App\Models\User::find(3);
echo "   Email: " . $user->email . "\n";
echo "   Nom: " . $user->name . "\n";

// Test 2: Vérifier la recherche de client
echo "\n2. Recherche du client par email:\n";
$client = \App\Models\Client::where('email', $user->email)->first();
if ($client) {
    echo "   ✓ Client trouvé: " . $client->nom . " (ID: " . $client->id . ")\n";
} else {
    echo "   ✗ Pas de client trouvé\n";
}

// Test 3: Vérifier firstOrCreate
echo "\n3. Test firstOrCreate:\n";
$clientAuto = \App\Models\Client::firstOrCreate(
    ['email' => $user->email],
    ['nom' => $user->name]
);
echo "   ✓ Client créé/trouvé: " . $clientAuto->nom . " (ID: " . $clientAuto->id . ")\n";

// Test 4: Vérifier les commandes de ce client
echo "\n4. Commandes du client:\n";
$commandes = \App\Models\Commande::where('client_id', $clientAuto->id)->get();
echo "   Total: " . count($commandes) . "\n";
if (count($commandes) > 0) {
    foreach ($commandes as $cmd) {
        echo "   - CMD: " . $cmd->numero . " (Statut: " . $cmd->statut . ")\n";
    }
}

// Test 5: Vérifier les factures
echo "\n5. Factures du client:\n";
$factures = \App\Models\Facture::whereHas('commande', function ($query) use ($clientAuto) {
    $query->where('client_id', $clientAuto->id);
})->get();
echo "   Total: " . count($factures) . "\n";

echo "\n✅ Tous les tests complétés!\n";
