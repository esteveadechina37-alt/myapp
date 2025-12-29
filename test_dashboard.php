<?php
echo "=== Test du Dashboard Client ===\n\n";

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

// Récupérer l'utilisateur client
$user = User::where('role', 'client')->first();

if (!$user) {
    echo "❌ Aucun utilisateur client trouvé!\n";
    exit;
}

echo "✓ Utilisateur trouvé: {$user->name} ({$user->email})\n";

// Vérifier que le client existe
$client = Client::where('email', $user->email)->first();

if (!$client) {
    echo "❌ Aucun client associé à cet email!\n";
    exit;
}

echo "✓ Client trouvé: {$client->nom} {$client->prenom}\n";

// Vérifier les commandes
$commandes = DB::table('commandes')
    ->where('client_id', $client->id)
    ->get();

echo "✓ Total commandes: " . count($commandes) . "\n";

// Vérifier les catégories
$categories = DB::table('categories')
    ->where('est_active', true)
    ->get();

echo "✓ Total catégories actives: " . count($categories) . "\n";

// Vérifier les plats
$plats = DB::table('plats')
    ->where('est_disponible', true)
    ->get();

echo "✓ Total plats actifs: " . count($plats) . "\n";

echo "\n✅ Tout est prêt pour le dashboard!\n";
echo "\nDonnées de test:\n";
echo "- URL: /client/dashboard\n";
echo "- Email: {$user->email}\n";
echo "- Client: {$client->nom} {$client->prenom}\n";
echo "- Plats disponibles: " . count($plats) . "\n";
