<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modèle Commande
 * Gestion complète des commandes (sur place, emporter, livraison)
 */
class Commande extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'commandes';

    protected $fillable = [
        'numero',
        'client_id',
        'table_id',
        'utilisateur_id',
        'type_commande',
        'montant_total_ht',
        'montant_tva',
        'montant_total_ttc',
        'statut',
        'heure_commande',
        'heure_remise_cuisine',
        'heure_prete',
        'heure_livraison_demandee',
        'est_payee',
        'moyen_paiement',
        'commentaires',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'heure_commande',
        'heure_remise_cuisine',
        'heure_prete',
        'heure_livraison_demandee',
    ];

    protected $casts = [
        'montant_total_ht' => 'float',
        'montant_tva' => 'float',
        'montant_total_ttc' => 'float',
        'est_payee' => 'boolean',
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

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function lignesCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    // Alias pour lignesCommandes
    public function details()
    {
        return $this->hasMany(LigneCommande::class);
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }

    // Accesseurs
    public function getStatutFrancais()
    {
        $statuts = [
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmée',
            'en_preparation' => 'En préparation',
            'prete' => 'Prête',
            'servie' => 'Servie',
            'payee' => 'Payée',
            'livree' => 'Livrée',
            'annulee' => 'Annulée',
        ];
        return $statuts[$this->statut] ?? $this->statut;
    }

    public function getTempsPreparation()
    {
        if ($this->heure_remise_cuisine && $this->heure_prete) {
            return $this->heure_prete->diffInMinutes($this->heure_remise_cuisine);
        }
        return null;
    }

    // Workflow Methods
    /**
     * Confirmer la commande
     */
    public function confirmer()
    {
        $this->statut = 'confirmee';
        return $this->save();
    }

    /**
     * Envoyer la commande à la cuisine
     */
    public function envoyerCuisine()
    {
        $this->statut = 'en_preparation';
        $this->heure_remise_cuisine = now();
        return $this->save();
    }

    /**
     * Marquer la commande comme prête
     */
    public function marquerPrete()
    {
        $this->statut = 'prete';
        $this->heure_prete = now();
        return $this->save();
    }

    /**
     * Marquer la commande comme servie (sur place)
     */
    public function servir()
    {
        $this->statut = 'servie';
        return $this->save();
    }

    /**
     * Marquer la commande comme livrée
     */
    public function livrer()
    {
        $this->statut = 'livree';
        return $this->save();
    }

    /**
     * Enregistrer le paiement
     */
    public function enregistrerPaiement($moyen_paiement = 'especes')
    {
        $this->est_payee = true;
        $this->moyen_paiement = $moyen_paiement;
        $this->statut = 'payee';
        return $this->save();
    }

    /**
     * Générer la facture
     */
    public function genererFacture()
    {
        return Facture::create([
            'commande_id' => $this->id,
            'numero_facture' => 'FACT-' . $this->numero,
            'montant_ht' => $this->montant_total_ht,
            'montant_tva' => $this->montant_tva,
            'montant_ttc' => $this->montant_total_ttc,
            'date_facture' => now(),
            'statut' => 'emise',
        ]);
    }

    /**
     * Archiver la commande
     */
    public function archiver()
    {
        // Mettre à jour le stock pour chaque article
        foreach ($this->lignesCommandes as $ligne) {
            MouvementStock::create([
                'ingredient_id' => $ligne->plat_id,
                'type_mouvement' => 'sortie',
                'quantite' => $ligne->quantite,
                'motif' => 'Commande #' . $this->numero,
                'reference_commande' => $this->id,
            ]);
        }
        
        $this->soft_delete = true;
        return $this->save();
    }

    /**
     * Vérifier si la commande peut être payée
     */
    public function peutEtrePaye()
    {
        return in_array($this->statut, ['prete', 'servie', 'livree']);
    }

    /**
     * Vérifier si la commande est complète
     */
    public function estComplete()
    {
        return in_array($this->statut, ['servie', 'livree', 'payee']);
    }
}