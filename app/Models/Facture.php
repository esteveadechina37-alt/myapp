<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modèle Facture
 * Factures générées après le paiement
 */
class Facture extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'factures';

    protected $fillable = [
        'commande_id',
        'numero',
        'montant_ht',
        'montant_tva',
        'montant_ttc',
        'date_emission',
        'statut',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'date_emission',
    ];

    protected $casts = [
        'montant_ht' => 'float',
        'montant_tva' => 'float',
        'montant_ttc' => 'float',
    ];

    // Relations
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    // Accesseurs
    public function getStatutFrancais()
    {
        $statuts = [
            'brouillon' => 'Brouillon',
            'emise' => 'Émise',
            'payee' => 'Payée',
            'annulee' => 'Annulée',
        ];
        return $statuts[$this->statut] ?? $this->statut;
    }
}
