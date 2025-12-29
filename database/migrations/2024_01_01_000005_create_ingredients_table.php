<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table ingredients
     * Stock des ingrédients avec quantités
     */
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('unite_mesure'); // kg, litre, unité, etc.
            $table->decimal('stock_actuel', 10, 2)->default(0);
            $table->decimal('seuil_alerte', 10, 2)->default(0);
            $table->boolean('fournisseur_est_perissable')->default(false);
            $table->date('date_peremption')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
