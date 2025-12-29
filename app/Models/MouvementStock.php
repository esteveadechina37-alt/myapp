<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle MouvementStock
 * Historique de tous les mouvements de stock
 */
class MouvementStock extends Model
{
    use HasFactory;

    protected $table = 'mouvements_stock';

    protected $fillable = [
        'ingredient_id',
        'type_mouvement',
        'quantite',
        'date_mouvement',
        'commentaire',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'date_mouvement',
    ];

    protected $casts = [
        'quantite' => 'float',
    ];

    // Relations
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    // Accesseurs
    public function getTypeFrancais()
    {
        $types = [
            'entree' => 'Entrée',
            'sortie' => 'Sortie',
            'ajustement' => 'Ajustement',
        ];
        return $types[$this->type_mouvement] ?? $this->type_mouvement;
    }
}
