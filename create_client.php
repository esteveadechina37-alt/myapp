<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CRÉATION DU CLIENT ===\n";

$user = \App\Models\User::find(3);
echo "Utilisateur: " . $user->name . " (" . $user->email . ")\n";

try {
    $client = \App\Models\Client::create([
        'email' => $user->email,
        'nom' => 'Gide',
        'prenom' => 'Emmanuel',
        'telephone' => null
    ]);
    echo "✓ Client créé avec ID: " . $client->id . "\n";
} catch (\Exception $e) {
    echo "✗ Erreur: " . $e->getMessage() . "\n";
}
