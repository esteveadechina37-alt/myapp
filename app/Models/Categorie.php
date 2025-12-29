<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modèle Categorie
 * Catégories de plats (Entrées, Plats, Desserts, etc.)
 */
class Categorie extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'nom',
        'description',
        'image',
        'ordre_affichage',
        'est_active',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'est_active' => 'boolean',
    ];

    // Relations
    public function plats()
    {
        return $this->hasMany(Plat::class);
    }
}
