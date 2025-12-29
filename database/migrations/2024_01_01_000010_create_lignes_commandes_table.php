<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table lignes_commandes
     * Détail de chaque plat commandé
     */
    public function up(): void
    {
        Schema::create('lignes_commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->cascadeOnDelete();
            $table->foreignId('plat_id')->constrained('plats')->restrictOnDelete();
            
            $table->integer('quantite');
            $table->decimal('prix_unitaire_ht', 10, 2);
            $table->decimal('taux_tva', 5, 2)->default(20);
            
            // Statut de la ligne
            $table->enum('statut', [
                'en_attente',
                'en_preparation',
                'prete',
                'servie',
                'annulee'
            ])->default('en_attente');
            
            $table->text('commentaire')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lignes_commandes');
    }
};
