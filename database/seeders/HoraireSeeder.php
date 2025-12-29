<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horaire;

class HoraireSeeder extends Seeder
{
    /**
     * Créer les horaires d'ouverture du restaurant
     */
    public function run(): void
    {
        $horaires = [
            [
                'jour' => 'Lundi',
                'heure_ouverture' => '11:30',
                'heure_fermeture' => '14:00',
                'heure_ouverture_soir' => '18:30',
                'heure_fermeture_soir' => '22:00',
                'est_ouvert' => true,
                'notes' => 'Ouvert le midi et le soir',
            ],
            [
                'jour' => 'Mardi',
                'heure_ouverture' => '11:30',
                'heure_fermeture' => '14:00',
                'heure_ouverture_soir' => '18:30',
                'heure_fermeture_soir' => '22:00',
                'est_ouvert' => true,
                'notes' => 'Ouvert le midi et le soir',
            ],
            [
                'jour' => 'Mercredi',
                'heure_ouverture' => '11:30',
                'heure_fermeture' => '14:00',
                'heure_ouverture_soir' => '18:30',
                'heure_fermeture_soir' => '22:00',
                'est_ouvert' => true,
                'notes' => 'Ouvert le midi et le soir',
            ],
            [
                'jour' => 'Jeudi',
                'heure_ouverture' => '11:30',
                'heure_fermeture' => '14:00',
                'heure_ouverture_soir' => '18:30',
                'heure_fermeture_soir' => '22:00',
                'est_ouvert' => true,
                'notes' => 'Ouvert le midi et le soir',
            ],
            [
                'jour' => 'Vendredi',
                'heure_ouverture' => '11:30',
                'heure_fermeture' => '14:00',
                'heure_ouverture_soir' => '18:30',
                'heure_fermeture_soir' => '23:00',
                'est_ouvert' => true,
                'notes' => 'Ouvert le midi et le soir jusqu\'à 23h',
            ],
            [
                'jour' => 'Samedi',
                'heure_ouverture' => '12:00',
                'heure_fermeture' => '23:00',
                'heure_ouverture_soir' => null,
                'heure_fermeture_soir' => null,
                'est_ouvert' => true,
                'notes' => 'Ouvert sans interruption',
            ],
            [
                'jour' => 'Dimanche',
                'heure_ouverture' => '12:00',
                'heure_fermeture' => '21:00',
                'heure_ouverture_soir' => null,
                'heure_fermeture_soir' => null,
                'est_ouvert' => true,
                'notes' => 'Ouvert sans interruption',
            ],
        ];

        foreach ($horaires as $horaire) {
            Horaire::firstOrCreate(
                ['jour' => $horaire['jour']],
                $horaire
            );
        }

        echo "\n✅ Horaires créés avec succès!\n\n";
    }
}
