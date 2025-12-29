<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horaire extends Model
{
    use HasFactory;

    protected $table = 'horaires';

    protected $fillable = [
        'jour',
        'heure_ouverture',
        'heure_fermeture',
        'heure_ouverture_soir',
        'heure_fermeture_soir',
        'est_ouvert',
        'notes',
    ];

    protected $casts = [
        'heure_ouverture' => 'datetime:H:i',
        'heure_fermeture' => 'datetime:H:i',
        'heure_ouverture_soir' => 'datetime:H:i',
        'heure_fermeture_soir' => 'datetime:H:i',
        'est_ouvert' => 'boolean',
    ];

    /**
     * Vérifier si le restaurant est ouvert maintenant
     */
    public static function estOuvertMaintenant(): bool
    {
        $maintenant = now();
        $jourActuel = $maintenant->format('l'); // Retourne le jour en anglais (Monday, Tuesday, etc.)
        
        $jours = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche',
        ];
        
        $jourNom = $jours[$jourActuel] ?? null;
        
        if (!$jourNom) {
            return false;
        }
        
        $horaire = self::where('jour', $jourNom)->first();
        
        if (!$horaire || !$horaire->est_ouvert) {
            return false;
        }
        
        $heureActuelle = $maintenant->format('H:i');
        
        // Vérifier si dans les horaires du matin
        if ($horaire->heure_ouverture && $horaire->heure_fermeture) {
            if ($heureActuelle >= $horaire->heure_ouverture->format('H:i') && 
                $heureActuelle < $horaire->heure_fermeture->format('H:i')) {
                return true;
            }
        }
        
        // Vérifier si dans les horaires du soir
        if ($horaire->heure_ouverture_soir && $horaire->heure_fermeture_soir) {
            if ($heureActuelle >= $horaire->heure_ouverture_soir->format('H:i') && 
                $heureActuelle < $horaire->heure_fermeture_soir->format('H:i')) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Obtenir le statut d'ouverture avec l'heure de fermeture
     */
    public static function getStatutOuverture()
    {
        $maintenant = now();
        $jourActuel = $maintenant->format('l');
        
        $jours = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche',
        ];
        
        $jourNom = $jours[$jourActuel] ?? null;
        $horaire = $jourNom ? self::where('jour', $jourNom)->first() : null;
        
        if (!$horaire || !$horaire->est_ouvert) {
            return [
                'ouvert' => false,
                'message' => 'Fermé',
                'prochaine_ouverture' => null,
            ];
        }
        
        $heureActuelle = $maintenant->format('H:i');
        $heureActuelleObj = \Carbon\Carbon::createFromFormat('H:i', $heureActuelle);
        
        // Vérifier si dans les horaires du matin
        if ($horaire->heure_ouverture && $horaire->heure_fermeture) {
            $ouv = \Carbon\Carbon::createFromFormat('H:i', $horaire->heure_ouverture->format('H:i'));
            $ferm = \Carbon\Carbon::createFromFormat('H:i', $horaire->heure_fermeture->format('H:i'));
            
            if ($heureActuelleObj >= $ouv && $heureActuelleObj < $ferm) {
                return [
                    'ouvert' => true,
                    'message' => 'Ouvert',
                    'ferme_a' => $horaire->heure_fermeture->format('H:i'),
                ];
            }
        }
        
        // Vérifier si dans les horaires du soir
        if ($horaire->heure_ouverture_soir && $horaire->heure_fermeture_soir) {
            $ouv = \Carbon\Carbon::createFromFormat('H:i', $horaire->heure_ouverture_soir->format('H:i'));
            $ferm = \Carbon\Carbon::createFromFormat('H:i', $horaire->heure_fermeture_soir->format('H:i'));
            
            if ($heureActuelleObj >= $ouv && $heureActuelleObj < $ferm) {
                return [
                    'ouvert' => true,
                    'message' => 'Ouvert',
                    'ferme_a' => $horaire->heure_fermeture_soir->format('H:i'),
                ];
            }
        }
        
        return [
            'ouvert' => false,
            'message' => 'Fermé',
            'prochaine_ouverture' => $horaire->heure_ouverture_soir ? 
                $horaire->heure_ouverture_soir->format('H:i') : 
                $horaire->heure_ouverture->format('H:i'),
        ];
    }
}
