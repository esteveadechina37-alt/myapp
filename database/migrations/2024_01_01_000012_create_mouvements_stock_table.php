<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table mouvements_stock
     * Historique des mouvements de stock
     */
    public function up(): void
    {
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained('ingredients')->restrictOnDelete();
            
            $table->enum('type_mouvement', ['entree', 'sortie', 'ajustement'])->default('sortie');
            $table->decimal('quantite', 10, 2);
            $table->date('date_mouvement')->useCurrent();
            $table->text('commentaire')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
    }
};
