<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table factures
     * Factures générées après le paiement
     */
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->restrictOnDelete();
            
            $table->string('numero')->unique();
            $table->decimal('montant_ht', 10, 2);
            $table->decimal('montant_tva', 10, 2);
            $table->decimal('montant_ttc', 10, 2);
            
            $table->timestamp('date_emission')->useCurrent();
            $table->enum('statut', ['brouillon', 'emise', 'payee', 'annulee'])->default('emise');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
