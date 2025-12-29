<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Client;

class CreateMissingClients extends Command
{
    protected $signature = 'clients:create-missing';
    protected $description = 'Créer les clients manquants pour les utilisateurs existants';

    public function handle()
    {
        $users = User::where('role', 'client')->get();
        
        $this->info('Création des clients manquants...');
        $created = 0;
        
        foreach ($users as $user) {
            $client = Client::where('email', $user->email)->first();
            
            if (!$client) {
                try {
                    $nameParts = explode(' ', $user->name);
                    Client::create([
                        'email' => $user->email,
                        'nom' => $nameParts[0] ?? $user->name,
                        'prenom' => isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '',
                        'telephone' => $user->telephone ?? null,
                    ]);
                    $this->info("✓ Client créé pour: {$user->email}");
                    $created++;
                } catch (\Exception $e) {
                    $this->error("✗ Erreur pour {$user->email}: " . $e->getMessage());
                }
            }
        }
        
        $this->info("Total créés: $created");
    }
}
