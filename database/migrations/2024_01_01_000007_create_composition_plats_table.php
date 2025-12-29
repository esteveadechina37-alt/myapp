<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table composition_plats
     * Lie les ingrédients aux plats avec quantités
     */
    public function up(): void
    {
        Schema::create('composition_plats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plat_id')->constrained('plats')->cascadeOnDelete();
            $table->foreignId('ingredient_id')->constrained('ingredients')->cascadeOnDelete();
            $table->decimal('quantite', 10, 2);
            $table->string('unite');
            $table->timestamps();
            
            // Clé unique pour éviter les doublons
            $table->unique(['plat_id', 'ingredient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('composition_plats');
    }
};
