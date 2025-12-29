<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle LogActivite
 * Journal d'audit de toutes les actions du système
 */
class LogActivite extends Model
{
    use HasFactory;

    protected $table = 'logs_activite';

    protected $fillable = [
        'utilisateur_id',
        'action',
        'table_concernee',
        'id_concernee',
        'type_action',
        'details',
        'date_heure',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'date_heure',
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    // Accesseurs
    public function getTypeFrancais()
    {
        $types = [
            'creation' => 'Création',
            'modification' => 'Modification',
            'suppression' => 'Suppression',
            'visualisation' => 'Visualisation',
        ];
        return $types[$this->type_action] ?? $this->type_action;
    }
}
