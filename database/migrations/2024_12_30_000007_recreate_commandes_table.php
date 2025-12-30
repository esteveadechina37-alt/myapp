<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Supprimer et recréer complètement la table commandes
     * Intègre tous les champs nécessaires pour:
     * - Gérer les trois types de commandes (sur place, à emporter, livraison)
     * - Générer les factures
     * - Traiter les commandes correctement
     */
    public function up(): void
    {
        // Désactiver les contraintes de clés étrangères
        Schema::disableForeignKeyConstraints();
        
        // Supprimer la table existante si elle existe
        Schema::dropIfExists('commandes');
        
        // Réactiver les contraintes
        Schema::enableForeignKeyConstraints();

        // Créer la nouvelle table avec tous les champs nécessaires
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            
            // Identification
            $table->string('numero')->unique();
            $table->foreignId('client_id')->nullable()->constrained('clients')->setOnDelete('set null');
            $table->foreignId('utilisateur_id')->nullable()->constrained('utilisateurs')->setOnDelete('set null');
            
            // Type de commande et configuration
            $table->enum('type_commande', ['sur_place', 'a_emporter', 'livraison'])->default('sur_place');
            $table->foreignId('table_id')->nullable()->constrained('tables_restaurant')->setOnDelete('set null');
            
            // Infos de livraison
            $table->string('adresse_livraison')->nullable();
            $table->string('telephone_livraison')->nullable();
            $table->string('nom_client_livraison')->nullable();
            $table->string('prenom_client_livraison')->nullable();
            
            // Montants financiers HT/TTC
            $table->decimal('montant_total_ht', 10, 2)->default(0);
            $table->decimal('montant_tva', 10, 2)->default(0);
            $table->decimal('montant_tva_pourcentage', 5, 2)->default(19.6);
            $table->decimal('montant_total_ttc', 10, 2)->default(0);
            
            // Frais et remises
            $table->decimal('frais_livraison', 10, 2)->default(0);
            $table->decimal('montant_remise', 10, 2)->default(0);
            $table->string('code_remise')->nullable();
            
            // Statut et workflow
            $table->enum('statut', [
                'en_attente',           // Créée, en attente de confirmation
                'confirmee',            // Confirmée par le client
                'enregistree',          // Enregistrée en cuisine
                'en_preparation',       // En cours de préparation
                'prete',                // Prête (générique)
                'prete_a_emporter',     // Prête à emporter
                'prete_a_livrer',       // Prête à livrer
                'en_livraison',         // En cours de livraison
                'servie',               // Servie au client (sur place)
                'payee',                // Payée
                'livree',               // Livrée
                'annulee'               // Annulée
            ])->default('en_attente');
            
            // Timestamps pour le workflow
            $table->timestamp('heure_commande')->useCurrent();
            $table->timestamp('heure_confirmation')->nullable();
            $table->timestamp('heure_remise_cuisine')->nullable();
            $table->timestamp('heure_prete')->nullable();
            $table->timestamp('heure_depart_livraison')->nullable();
            $table->timestamp('heure_livraison')->nullable();
            $table->timestamp('heure_paiement')->nullable();
            
            // Horaires demandés
            $table->timestamp('heure_livraison_demandee')->nullable();
            $table->timestamp('heure_service_demandee')->nullable();
            
            // Paiement
            $table->boolean('est_payee')->default(false);
            $table->enum('moyen_paiement', ['especes', 'carte', 'cheque', 'virement', 'mobile_money', 'autre'])->nullable();
            $table->string('reference_paiement')->nullable();
            
            // Informations complémentaires
            $table->text('commentaires')->nullable();
            $table->text('notes_cuisine')->nullable();
            $table->text('notes_livraison')->nullable();
            
            // Facture
            $table->boolean('facture_generee')->default(false);
            $table->timestamp('date_facture')->nullable();
            $table->string('numero_facture')->nullable();
            
            // Métadonnées
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes pour performances
            $table->index('client_id');
            $table->index('utilisateur_id');
            $table->index('table_id');
            $table->index('statut');
            $table->index('type_commande');
            $table->index('est_payee');
            $table->index('facture_generee');
            $table->index('created_at');
            $table->index('heure_commande');
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('commandes');
        Schema::enableForeignKeyConstraints();
    }
};
