<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table logs_activite
     * Historique de tous les actions du système
     */
    public function up(): void
    {
        Schema::create('logs_activite', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->restrictOnDelete();
            
            $table->string('action');
            $table->string('table_concernee')->nullable();
            $table->unsignedBigInteger('id_concernee')->nullable();
            $table->enum('type_action', ['creation', 'modification', 'suppression', 'visualisation']);
            $table->text('details')->nullable();
            
            $table->timestamp('date_heure')->useCurrent();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs_activite');
    }
};
