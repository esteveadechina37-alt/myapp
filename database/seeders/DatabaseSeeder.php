<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use App\Models\Client;
use App\Models\TableRestaurant;
use App\Models\Categorie;
use App\Models\Ingredient;
use App\Models\Plat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Remplissage des données de seed
     */
    public function run(): void
    {
        // 0. Créer le compte admin
        $this->call(AdminSeeder::class);

        // 0.5. Créer les catégories
        $this->call(CategorieSeeder::class);

        // 1. Créer les utilisateurs avec différents rôles
        $this->creerUtilisateurs();

        // 2. Créer les clients
        $this->creerClients();

        // 3. Créer les tables du restaurant
        $this->creerTables();

        // 4. Créer les catégories
        $this->creerCategories();

        // 5. Créer les ingrédients
        $this->creerIngredients();

        // 6. Créer les plats
        $this->creerPlats();
    }

    private function creerUtilisateurs(): void
    {
        // Admin
        Utilisateur::create([
            'nom' => 'Admin',
            'prenom' => 'Administrateur',
            'email' => 'admin@restaurant.fr',
            'mot_de_passe' => Hash::make('password123'),
            'role' => 'admin',
            'telephone' => '01 23 45 67 89',
            'est_actif' => true,
        ]);

        // Gérant
        Utilisateur::create([
            'nom' => 'Martin',
            'prenom' => 'Gerant',
            'email' => 'gerant@restaurant.fr',
            'mot_de_passe' => Hash::make('password123'),
            'role' => 'gerant',
            'telephone' => '01 23 45 67 90',
            'est_actif' => true,
        ]);

        // Serveurs
        for ($i = 1; $i <= 3; $i++) {
            Utilisateur::create([
                'nom' => 'Serveur',
                'prenom' => 'Serveur ' . $i,
                'email' => "serveur$i@restaurant.fr",
                'mot_de_passe' => Hash::make('password123'),
                'role' => 'serveur',
                'telephone' => '01 23 45 67 ' . (90 + $i),
                'est_actif' => true,
            ]);
        }

        // Cuisiniers
        for ($i = 1; $i <= 2; $i++) {
            Utilisateur::create([
                'nom' => 'Chef',
                'prenom' => 'Cuisinier ' . $i,
                'email' => "cuisinier$i@restaurant.fr",
                'mot_de_passe' => Hash::make('password123'),
                'role' => 'cuisinier',
                'telephone' => '01 23 45 67 ' . (93 + $i),
                'est_actif' => true,
            ]);
        }

        // Livreur
        Utilisateur::create([
            'nom' => 'Dupont',
            'prenom' => 'Livreur',
            'email' => 'livreur@restaurant.fr',
            'mot_de_passe' => Hash::make('password123'),
            'role' => 'livreur',
            'telephone' => '06 12 34 56 78',
            'est_actif' => true,
        ]);
    }

    private function creerClients(): void
    {
        $noms = ['Durand', 'Moreau', 'Bernard', 'Thomas', 'Robert', 'Petit', 'Dubois', 'Laurent'];
        $prenoms = ['Jean', 'Marie', 'Pierre', 'Sophie', 'Luc', 'Anne', 'Marc', 'Isabelle'];
        $villes = ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg'];

        for ($i = 0; $i < 20; $i++) {
            Client::create([
                'nom' => $noms[array_rand($noms)],
                'prenom' => $prenoms[array_rand($prenoms)],
                'email' => 'client' . ($i + 1) . '@example.fr',
                'telephone' => '06 ' . random_int(10, 99) . ' ' . random_int(10, 99) . ' ' . random_int(10, 99),
                'adresse' => random_int(1, 200) . ' rue de la Paix',
                'code_postal' => random_int(75001, 95999),
                'ville' => $villes[array_rand($villes)],
                'nombre_visites' => random_int(0, 15),
            ]);
        }
    }

    private function creerTables(): void
    {
        $zones = ['Salle 1', 'Salle 2', 'Terrasse'];

        $tableNum = 1;
        for ($z = 0; $z < 3; $z++) {
            for ($i = 1; $i <= 10; $i++) {
                TableRestaurant::create([
                    'numero' => $tableNum++,
                    'capacite' => random_int(2, 8),
                    'zone' => $zones[$z],
                    'est_disponible' => true,
                    'position_x' => random_int(0, 100),
                    'position_y' => random_int(0, 100),
                ]);
            }
        }
    }

    private function creerCategories(): void
    {
        $categories = [
            [
                'nom' => 'Entrées',
                'description' => 'Entrées et amuse-bouches',
                'ordre_affichage' => 1,
            ],
            [
                'nom' => 'Plats chauds',
                'description' => 'Plats principaux chauds',
                'ordre_affichage' => 2,
            ],
            [
                'nom' => 'Plats froids',
                'description' => 'Plats froids et salades',
                'ordre_affichage' => 3,
            ],
            [
                'nom' => 'Desserts',
                'description' => 'Desserts et pâtisseries',
                'ordre_affichage' => 4,
            ],
            [
                'nom' => 'Boissons',
                'description' => 'Boissons chaudes et froides',
                'ordre_affichage' => 5,
            ],
        ];

        foreach ($categories as $cat) {
            Categorie::create($cat);
        }
    }

    private function creerIngredients(): void
    {
        $ingredients = [
            ['nom' => 'Farine', 'unite_mesure' => 'kg', 'stock_actuel' => 25, 'seuil_alerte' => 5],
            ['nom' => 'Lait', 'unite_mesure' => 'litre', 'stock_actuel' => 30, 'seuil_alerte' => 10],
            ['nom' => 'Œufs', 'unite_mesure' => 'unité', 'stock_actuel' => 200, 'seuil_alerte' => 50],
            ['nom' => 'Beurre', 'unite_mesure' => 'kg', 'stock_actuel' => 15, 'seuil_alerte' => 3],
            ['nom' => 'Sucre', 'unite_mesure' => 'kg', 'stock_actuel' => 20, 'seuil_alerte' => 5],
            ['nom' => 'Sel', 'unite_mesure' => 'kg', 'stock_actuel' => 10, 'seuil_alerte' => 2],
            ['nom' => 'Poivre', 'unite_mesure' => 'kg', 'stock_actuel' => 2, 'seuil_alerte' => 0.5],
            ['nom' => 'Tomate', 'unite_mesure' => 'kg', 'stock_actuel' => 40, 'seuil_alerte' => 10],
            ['nom' => 'Oignon', 'unite_mesure' => 'kg', 'stock_actuel' => 35, 'seuil_alerte' => 8],
            ['nom' => 'Poulet', 'unite_mesure' => 'kg', 'stock_actuel' => 50, 'seuil_alerte' => 15],
            ['nom' => 'Bœuf', 'unite_mesure' => 'kg', 'stock_actuel' => 40, 'seuil_alerte' => 12],
            ['nom' => 'Poisson', 'unite_mesure' => 'kg', 'stock_actuel' => 25, 'seuil_alerte' => 8],
            ['nom' => 'Fromage', 'unite_mesure' => 'kg', 'stock_actuel' => 15, 'seuil_alerte' => 5],
            ['nom' => 'Crème fraîche', 'unite_mesure' => 'litre', 'stock_actuel' => 20, 'seuil_alerte' => 5],
            ['nom' => 'Huile d\'olive', 'unite_mesure' => 'litre', 'stock_actuel' => 25, 'seuil_alerte' => 5],
        ];

        foreach ($ingredients as $ing) {
            Ingredient::create($ing);
        }
    }

    private function creerPlats(): void
    {
        // Récupérer les catégories
        $entreesId = Categorie::where('nom', 'Entrées')->first()->id;
        $platsChaudsId = Categorie::where('nom', 'Plats chauds')->first()->id;
        $platsFroidsId = Categorie::where('nom', 'Plats froids')->first()->id;
        $dessertsId = Categorie::where('nom', 'Desserts')->first()->id;
        $boissonsId = Categorie::where('nom', 'Boissons')->first()->id;

        $plats = [
            // Entrées
            [
                'categorie_id' => $entreesId,
                'nom' => 'Soupe à l\'oignon',
                'description' => 'Soupe traditionnelle à l\'oignon gratinée au fromage',
                'prix_ht' => 8.50,
                'taux_tva' => 20,
                'est_disponible' => true,
                'temps_preparation' => 10,
            ],
            [
                'categorie_id' => $entreesId,
                'nom' => 'Salade de tomates',
                'description' => 'Salade fraîche de tomates du marché',
                'prix_ht' => 9.00,
                'taux_tva' => 20,
                'est_disponible' => true,
                'temps_preparation' => 5,
            ],
            // Plats chauds
            [
                'categorie_id' => $platsChaudsId,
                'nom' => 'Poulet rôti',
                'description' => 'Poulet fermier rôti avec légumes de saison',
                'prix_ht' => 18.50,
                'taux_tva' => 20,
                'est_disponible' => true,
                'temps_preparation' => 35,
            ],
            [
                'categorie_id' => $platsChaudsId,
                'nom' => 'Steak-frites',
                'description' => 'Steak grillé avec frites maison',
                'prix_ht' => 22.00,
                'taux_tva' => 20,
                'est_disponible' => true,
                'temps_preparation' => 25,
            ],
            [
                'categorie_id' => $platsChaudsId,
                'nom' => 'Filet de poisson',
                'description' => 'Filet de poisson frais sauce beurre blanc',
                'prix_ht' => 20.50,
                'taux_tva' => 20,
                'est_disponible' => true,
                'temps_preparation' => 30,
            ],
            // Plats froids
            [
                'categorie_id' => $platsFroidsId,
                'nom' => 'Salade niçoise',
                'description' => 'Salade avec thon, œuf dur et anchois',
                'prix_ht' => 13.50,
                'taux_tva' => 20,
                'est_disponible' => true,
                'temps_preparation' => 5,
            ],
            // Desserts
            [
                'categorie_id' => $dessertsId,
                'nom' => 'Tarte aux fruits',
                'description' => 'Tarte garnie de fruits frais du jour',
                'prix_ht' => 7.50,
                'taux_tva' => 20,
                'est_disponible' => true,
                'temps_preparation' => 5,
            ],
            [
                'categorie_id' => $dessertsId,
                'nom' => 'Crème brûlée',
                'description' => 'Crème vanille caramélisée',
                'prix_ht' => 8.00,
                'taux_tva' => 20,
                'est_disponible' => true,
                'temps_preparation' => 5,
            ],
            // Boissons
            [
                'categorie_id' => $boissonsId,
                'nom' => 'Café',
                'description' => 'Café expresso ou allongé',
                'prix_ht' => 2.50,
                'taux_tva' => 20,
                'est_disponible' => true,
                'temps_preparation' => 3,
            ],
            [
                'categorie_id' => $boissonsId,
                'nom' => 'Verre de vin rouge',
                'description' => 'Verre de vin rouge de qualité',
                'prix_ht' => 5.00,
                'taux_tva' => 20,
                'est_disponible' => true,
                'temps_preparation' => 2,
            ],
        ];

        foreach ($plats as $plat) {
            Plat::create($plat);
        }
    }
}
