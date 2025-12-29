<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modèle Ingredient
 * Ingrédients utilisés dans les plats
 */
class Ingredient extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ingredients';

    protected $fillable = [
        'nom',
        'unite_mesure',
        'stock_actuel',
        'seuil_alerte',
        'fournisseur_est_perissable',
        'date_peremption',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'date_peremption',
    ];

    protected $casts = [
        'stock_actuel' => 'float',
        'seuil_alerte' => 'float',
        'fournisseur_est_perissable' => 'boolean',
    ];

    // Relations
    public function plats()
    {
        return $this->belongsToMany(Plat::class, 'composition_plats')
            ->withPivot('quantite', 'unite')
            ->withTimestamps();
    }

    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class);
    }

    // Accesseurs
    public function getEstEnAlerte()
    {
        return $this->stock_actuel <= $this->seuil_alerte;
    }
}
