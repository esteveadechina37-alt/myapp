<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Créer un compte administrateur
     */
    public function run(): void
    {
        // Vérifier si l'admin existe déjà
        $adminExists = User::where('email', 'admin@restaurant.com')->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Administrateur Restaurant',
                'email' => 'admin@restaurant.com',
                'password' => Hash::make('Admin@2025!'),
                'role' => 'admin',
            ]);

            echo "\n✅ Compte admin créé:\n";
            echo "   Email: admin@restaurant.com\n";
            echo "   Password: Admin@2025!\n\n";
        }
    }
}
