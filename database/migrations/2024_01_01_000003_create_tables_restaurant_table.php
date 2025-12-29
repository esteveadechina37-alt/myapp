<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table tables_restaurant
     * Gère les tables disponibles avec numéro, capacité et position
     */
    public function up(): void
    {
        Schema::create('tables_restaurant', function (Blueprint $table) {
            $table->id();
            $table->integer('numero')->unique();
            $table->integer('capacite');
            $table->string('zone')->nullable(); // Salle 1, Terrasse, etc.
            $table->boolean('est_disponible')->default(true);
            $table->decimal('position_x', 8, 2)->nullable();
            $table->decimal('position_y', 8, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tables_restaurant');
    }
};
