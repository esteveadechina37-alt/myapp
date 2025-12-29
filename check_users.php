<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$users = DB::table('users')->get();

echo "=== TOUS LES UTILISATEURS ===\n\n";
foreach ($users as $u) {
    echo "ID: {$u->id} | {$u->name} | {$u->email} | Role: {$u->role}\n";
}

echo "\n=== CLIENTS EXISTANTS ===\n\n";
$clients = DB::table('clients')->get();
echo "Total clients: " . count($clients) . "\n";
foreach ($clients as $c) {
    echo "ID: {$c->id} | {$c->nom} {$c->prenom} | {$c->email}\n";
}
