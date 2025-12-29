<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modèle TableRestaurant
 * Représente les tables physiques du restaurant
 */
class TableRestaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tables_restaurant';

    protected $fillable = [
        'numero',
        'capacite',
        'zone',
        'est_disponible',
        'position_x',
        'position_y',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'est_disponible' => 'boolean',
        'position_x' => 'float',
        'position_y' => 'float',
    ];

    // Relations
    public function commandes()
    {
        return $this->hasMany(Commande::class, 'table_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'table_id');
    }

    // Accesseurs
    public function getStatut()
    {
        return $this->est_disponible ? 'Disponible' : 'Occupée';
    }
}
