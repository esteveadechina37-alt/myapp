<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST DE CORRECTION ===\n\n";

// Vérifier les clients
$clients = \App\Models\Client::all();
echo "Clients trouvés: " . count($clients) . "\n";
echo "Emails des clients:\n";
foreach ($clients as $client) {
    echo "  - " . $client->email . " (" . $client->nom . ")\n";
}

// Vérifier les users
$users = \App\Models\User::all();
echo "\nUtilisateurs trouvés: " . count($users) . "\n";
echo "Emails des utilisateurs:\n";
foreach ($users as $user) {
    echo "  - " . $user->email . " (ID: " . $user->id . ")\n";
}

// Vérifier s'il y a une correspondance
echo "\n=== CORRESPONDANCES EMAIL ===\n";
foreach ($users as $user) {
    $client = \App\Models\Client::where('email', $user->email)->first();
    if ($client) {
        echo "✓ " . $user->email . " => Client " . $client->nom . "\n";
    } else {
        echo "✗ " . $user->email . " => PAS DE CLIENT\n";
    }
}

echo "\n✅ Vérification terminée!\n";
