<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modèle Client
 * Stocke les informations des clients
 */
class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clients';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'code_postal',
        'ville',
        'nombre_visites',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relations
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Accesseurs
    public function getNomComplet()
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
