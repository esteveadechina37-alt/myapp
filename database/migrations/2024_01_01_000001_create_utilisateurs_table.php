<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table utilisateurs
     * Contient tous les rôles : Admin, Gérant, Serveur, Cuisinier, Livreur
     */
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('mot_de_passe');
            $table->enum('role', ['admin', 'gerant', 'serveur', 'cuisinier', 'livreur'])->default('serveur');
            $table->string('telephone')->nullable();
            $table->timestamp('date_creation')->useCurrent();
            $table->timestamp('derniere_connexion')->nullable();
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
