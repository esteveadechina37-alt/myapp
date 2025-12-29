<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use App\Models\Client;

echo "=== Création des clients manquants ===\n\n";

$users = User::where('role', 'client')->get();
$created = 0;
$skipped = 0;

foreach ($users as $user) {
    $client = Client::where('email', $user->email)->first();
    
    if ($client) {
        echo "✓ Client existant: {$user->email} (ID: {$client->id})\n";
        $skipped++;
    } else {
        try {
            $nameParts = explode(' ', $user->name);
            $newClient = Client::create([
                'email' => $user->email,
                'nom' => $nameParts[0] ?? $user->name,
                'prenom' => isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '',
                'telephone' => $user->telephone ?? null,
            ]);
            echo "✓ Client créé: {$user->email} (ID: {$newClient->id})\n";
            $created++;
        } catch (Exception $e) {
            echo "✗ Erreur pour {$user->email}: {$e->getMessage()}\n";
        }
    }
}

echo "\n=== Résumé ===\n";
echo "Créés: $created\n";
echo "Existants: $skipped\n";
echo "Total traités: " . ($created + $skipped) . "\n";
echo "✅ Complété!\n";
