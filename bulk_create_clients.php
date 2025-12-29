<?php
// Script simple pour créer les clients manquants
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

// Récupérer tous les utilisateurs clients
$users = DB::table('users')->where('role', 'client')->get();

echo count($users) . " utilisateurs trouvés\n";

foreach ($users as $user) {
    // Chercher un client existant
    $existing = DB::table('clients')->where('email', $user->email)->first();
    
    if ($existing) {
        echo "[" . $user->id . "] Existe: " . $user->email . "\n";
    } else {
        // Créer le client
        $parts = explode(' ', $user->name);
        $inserted = DB::table('clients')->insert([
            'email' => $user->email,
            'nom' => $parts[0] ?? $user->name,
            'prenom' => isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        if ($inserted) {
            echo "[" . $user->id . "] CRÉÉ: " . $user->email . "\n";
        }
    }
}

echo "\nDone!\n";
