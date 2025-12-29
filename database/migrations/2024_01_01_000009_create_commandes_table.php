<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table commandes
     * Gestion complète des commandes (sur place, à emporter, livraison)
     */
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('client_id')->nullable()->constrained('clients')->setOnDelete('set null');
            $table->foreignId('table_id')->nullable()->constrained('tables_restaurant')->setOnDelete('set null');
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->restrictOnDelete();
            
            // Type de commande
            $table->enum('type_commande', ['sur_place', 'a_emporter', 'livraison'])->default('sur_place');
            
            // Montants financiers
            $table->decimal('montant_total_ht', 10, 2)->default(0);
            $table->decimal('montant_tva', 10, 2)->default(0);
            $table->decimal('montant_total_ttc', 10, 2)->default(0);
            
            // Statut de la commande
            $table->enum('statut', [
                'en_attente',
                'confirmee',
                'en_preparation',
                'prete',
                'servie',
                'payee',
                'livree',
                'annulee'
            ])->default('en_attente');
            
            // Horaires
            $table->timestamp('heure_commande')->useCurrent();
            $table->timestamp('heure_remise_cuisine')->nullable();
            $table->timestamp('heure_prete')->nullable();
            $table->timestamp('heure_livraison_demandee')->nullable();
            
            // Paiement
            $table->boolean('est_payee')->default(false);
            $table->enum('moyen_paiement', ['especes', 'carte', 'cheque', 'virement'])->nullable();
            
            // Commentaires
            $table->text('commentaires')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
