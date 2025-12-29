<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table reservations
     * Gestion des réservations de tables
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('table_id')->nullable()->constrained('tables_restaurant')->setOnDelete('set null');
            $table->string('nom_client');
            $table->integer('nombre_personnes');
            $table->dateTime('date_heure');
            $table->integer('duree_prevue')->default(120); // minutes
            $table->enum('statut', ['en_attente', 'confirmee', 'en_cours', 'terminer', 'annulee'])->default('en_attente');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
