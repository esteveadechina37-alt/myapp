<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table des horaires d'ouverture du restaurant
     */
    public function up(): void
    {
        Schema::create('horaires', function (Blueprint $table) {
            $table->id();
            $table->string('jour'); // lundi, mardi, etc.
            $table->time('heure_ouverture')->nullable();
            $table->time('heure_fermeture')->nullable();
            $table->time('heure_ouverture_soir')->nullable();
            $table->time('heure_fermeture_soir')->nullable();
            $table->boolean('est_ouvert')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horaires');
    }
};
