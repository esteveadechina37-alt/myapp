<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle LigneCommande
 * Détails de chaque plat dans une commande
 */
class LigneCommande extends Model
{
    use HasFactory;

    protected $table = 'lignes_commandes';

    protected $fillable = [
        'commande_id',
        'plat_id',
        'quantite',
        'prix_unitaire_ht',
        'taux_tva',
        'statut',
        'commentaire',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'prix_unitaire_ht' => 'float',
        'taux_tva' => 'float',
    ];

    // Relations
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function plat()
    {
        return $this->belongsTo(Plat::class);
    }

    // Accesseurs
    public function getMontantHT()
    {
        return $this->quantite * $this->prix_unitaire_ht;
    }

    public function getMontantTVA()
    {
        return $this->getMontantHT() * ($this->taux_tva / 100);
    }

    public function getMontantTTC()
    {
        return $this->getMontantHT() + $this->getMontantTVA();
    }

    public function getStatutFrancais()
    {
        $statuts = [
            'en_attente' => 'En attente',
            'en_preparation' => 'En préparation',
            'prete' => 'Prête',
            'servie' => 'Servie',
            'annulee' => 'Annulée',
        ];
        return $statuts[$this->statut] ?? $this->statut;
    }
}
