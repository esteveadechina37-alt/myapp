<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modèle Plat
 * Menu complet du restaurant
 */
class Plat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'plats';

    protected $fillable = [
        'categorie_id',
        'nom',
        'description',
        'prix',
        'est_disponible',
        'temps_preparation',
        'image',
        'est_ephemere',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'prix' => 'float',
        'est_disponible' => 'boolean',
        'est_ephemere' => 'boolean',
    ];

    // Relations
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'composition_plats')
            ->withPivot('quantite', 'unite')
            ->withTimestamps();
    }

    public function lignesCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }
}
