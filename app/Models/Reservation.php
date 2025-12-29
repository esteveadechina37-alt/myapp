<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modèle Reservation
 * Gestion des réservations de tables
 */
class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reservations';

    protected $fillable = [
        'client_id',
        'table_id',
        'nom_client',
        'nombre_personnes',
        'date_heure',
        'duree_prevue',
        'statut',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'date_heure',
    ];

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function table()
    {
        return $this->belongsTo(TableRestaurant::class, 'table_id');
    }

    // Accesseurs
    public function getEtatFrancais()
    {
        $etats = [
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmée',
            'en_cours' => 'En cours',
            'terminer' => 'Terminée',
            'annulee' => 'Annulée',
        ];
        return $etats[$this->statut] ?? $this->statut;
    }
}
