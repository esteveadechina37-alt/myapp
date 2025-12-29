<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    /**
     * Créer les catégories de plats
     */
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Plats Principaux',
                'description' => 'Nos délicieux plats chauds et savoureux',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&h=500&fit=crop',
                'ordre_affichage' => 1,
                'est_active' => true,
            ],
            [
                'nom' => 'Entrées',
                'description' => 'Starters et petits mets pour commencer votre repas',
                'image' => 'https://images.pexels.com/photos/1092730/pexels-photo-1092730.jpeg?w=500&h=500&fit=crop',
                'ordre_affichage' => 2,
                'est_active' => true,
            ],
            [
                'nom' => 'Desserts',
                'description' => 'Nos gourmandises sucrées et délicieuses',
                'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500&h=500&fit=crop',
                'ordre_affichage' => 3,
                'est_active' => true,
            ],
            [
                'nom' => 'Boissons',
                'description' => 'Jus frais, sodas et boissons variées',
                'image' => 'https://images.pexels.com/photos/699953/pexels-photo-699953.jpeg?w=500&h=500&fit=crop',
                'ordre_affichage' => 4,
                'est_active' => true,
            ],
            [
                'nom' => 'Pizza & Pâtes',
                'description' => 'Pizzas artisanales et pâtes fraîches',
                'image' => 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=500&h=500&fit=crop',
                'ordre_affichage' => 5,
                'est_active' => true,
            ],
        ];

        foreach ($categories as $categorie) {
            Categorie::firstOrCreate(
                ['nom' => $categorie['nom']],
                $categorie
            );
        }

        echo "\n✅ 5 catégories créées avec succès!\n\n";
    }
}
