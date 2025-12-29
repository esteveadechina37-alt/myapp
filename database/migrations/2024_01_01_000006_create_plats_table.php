<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table plats
     * Menu des plats avec catégorie et prix
     */
    public function up(): void
    {
        Schema::create('plats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categories')->cascadeOnDelete();
            $table->string('nom');
            $table->text('description');
            $table->decimal('prix', 10, 2);
            $table->boolean('est_disponible')->default(true);
            $table->integer('temps_preparation')->default(15); // minutes
            $table->string('image')->nullable();
            $table->boolean('est_ephemere')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plats');
    }
};
