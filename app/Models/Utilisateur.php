<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modèle Utilisateur
 * Représente tous les rôles du système
 */
class Utilisateur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'role',
        'telephone',
        'date_creation',
        'derniere_connexion',
        'est_actif',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'date_creation',
        'derniere_connexion',
    ];

    // Relations
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function logsActivite()
    {
        return $this->hasMany(LogActivite::class);
    }

    // Accesseurs
    public function getNomComplet()
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
