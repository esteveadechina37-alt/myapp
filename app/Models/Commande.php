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
        // Identification
        'numero',
        'client_id',
        'utilisateur_id',
        'type_commande',
        'table_id',
        
        // Infos de livraison
        'adresse_livraison',
        'telephone_livraison',
        'nom_client_livraison',
        'prenom_client_livraison',
        
        // Montants financiers
        'montant_total_ht',
        'montant_tva',
        'montant_tva_pourcentage',
        'montant_total_ttc',
        'frais_livraison',
        'montant_remise',
        'code_remise',
        
        // Statut et workflow
        'statut',
        'heure_commande',
        'heure_confirmation',
        'heure_remise_cuisine',
        'heure_prete',
        'heure_depart_livraison',
        'heure_livraison',
        'heure_paiement',
        'heure_livraison_demandee',
        'heure_service_demandee',
        
        // Paiement
        'est_payee',
        'moyen_paiement',
        'reference_paiement',
        
        // Informations complémentaires
        'commentaires',
        'notes_cuisine',
        'notes_livraison',
        
        // Facture
        'facture_generee',
        'date_facture',
        'numero_facture',
        
        // Métadonnées
        'metadata',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'heure_commande',
        'heure_confirmation',
        'heure_remise_cuisine',
        'heure_prete',
        'heure_depart_livraison',
        'heure_livraison',
        'heure_paiement',
        'heure_livraison_demandee',
        'heure_service_demandee',
        'date_facture',
    ];

    protected $casts = [
        'montant_total_ht' => 'float',
        'montant_tva' => 'float',
        'montant_tva_pourcentage' => 'float',
        'montant_total_ttc' => 'float',
        'frais_livraison' => 'float',
        'montant_remise' => 'float',
        'est_payee' => 'boolean',
        'facture_generee' => 'boolean',
        'metadata' => 'array',
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
            'enregistree' => 'Enregistrée',
            'en_preparation' => 'En préparation',
            'prete' => 'Prête',
            'prete_a_emporter' => 'Prête à emporter',
            'prete_a_livrer' => 'Prête à livrer',
            'en_livraison' => 'En livraison',
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
        $this->heure_confirmation = now();
        return $this->save();
    }

    /**
     * Envoyer la commande à la cuisine
     */
    public function envoyerCuisine()
    {
        $this->statut = 'enregistree';
        return $this->save();
    }

    /**
     * Marquer la commande comme en préparation
     */
    public function demarrerPreparation()
    {
        $this->statut = 'en_preparation';
        $this->heure_remise_cuisine = now();
        return $this->save();
    }

    /**
     * Marquer la commande comme prête (générique)
     */
    public function marquerPrete()
    {
        $this->statut = 'prete';
        $this->heure_prete = now();
        return $this->save();
    }

    /**
     * Marquer la commande comme prête à emporter
     */
    public function marquerPreteAEmporter()
    {
        $this->statut = 'prete_a_emporter';
        $this->heure_prete = now();
        return $this->save();
    }

    /**
     * Marquer la commande comme prête à livrer
     */
    public function marquerPreteALivrer()
    {
        $this->statut = 'prete_a_livrer';
        $this->heure_prete = now();
        return $this->save();
    }

    /**
     * Marquer la commande en cours de livraison
     */
    public function marquerEnLivraison()
    {
        $this->statut = 'en_livraison';
        $this->heure_depart_livraison = now();
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
        $this->heure_livraison = now();
        return $this->save();
    }

    /**
     * Enregistrer le paiement
     */
    public function enregistrerPaiement($moyen_paiement = 'especes', $reference = null)
    {
        $this->est_payee = true;
        $this->moyen_paiement = $moyen_paiement;
        $this->reference_paiement = $reference;
        $this->heure_paiement = now();
        $this->statut = 'payee';
        return $this->save();
    }

    /**
     * Générer et marquer la facture
     */
    public function marquerFactureGeneree($numero_facture = null)
    {
        if (!$numero_facture) {
            $numero_facture = 'FACT-' . $this->numero;
        }
        $this->facture_generee = true;
        $this->numero_facture = $numero_facture;
        $this->date_facture = now();
        return $this->save();
    }

    /**
     * Générer la facture (création complète)
     */
    public function genererFacture()
    {
        $facture = Facture::create([
            'commande_id' => $this->id,
            'numero_facture' => 'FACT-' . $this->numero,
            'montant_ht' => $this->montant_total_ht,
            'montant_tva' => $this->montant_tva,
            'montant_ttc' => $this->montant_total_ttc,
            'date_facture' => now(),
            'statut' => 'emise',
        ]);
        
        // Mettre à jour la commande
        $this->marquerFactureGeneree($facture->numero_facture);
        
        return $facture;
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
        
        $this->deleted_at = now();
        return $this->save();
    }

    /**
     * Annuler la commande
     */
    public function annuler()
    {
        $this->statut = 'annulee';
        if ($this->type_commande === 'sur_place' && $this->table_id) {
            $this->table()->update(['est_disponible' => true]);
        }
        return $this->save();
    }

    /**
     * Vérifier si la commande peut être payée
     */
    public function peutEtrePaye()
    {
        return in_array($this->statut, ['prete', 'prete_a_emporter', 'prete_a_livrer', 'servie', 'livree']) && !$this->est_payee;
    }

    /**
     * Vérifier si la commande est complète
     */
    public function estComplete()
    {
        return in_array($this->statut, ['servie', 'livree', 'payee']);
    }

    /**
     * Vérifier si la commande est annulée
     */
    public function estAnnulee()
    {
        return $this->statut === 'annulee';
    }

    /**
     * Vérifier si la commande est payée
     */
    public function estPayee()
    {
        return $this->est_payee === true || $this->statut === 'payee';
    }

    /**
     * Calculer le montant total avec frais et remise
     */
    public function calculerMontantTotal()
    {
        $montant = $this->montant_total_ttc + $this->frais_livraison - $this->montant_remise;
        return max(0, round($montant, 2));
    }
}