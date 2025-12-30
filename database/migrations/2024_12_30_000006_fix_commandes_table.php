<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Corriger et améliorer la table commandes
     * Ajouter les colonnes manquantes et les contraintes
     */
    public function up(): void
    {
        // Vérifier si la colonne utilisateur_id existe, sinon l'ajouter
        if (!Schema::hasColumn('commandes', 'utilisateur_id')) {
            Schema::table('commandes', function (Blueprint $table) {
                $table->foreignId('utilisateur_id')
                    ->nullable()
                    ->after('table_id')
                    ->constrained('utilisateurs')
                    ->restrictOnDelete();
            });
        }

        // Ajouter les colonnes de livraison si elles n'existent pas
        if (!Schema::hasColumn('commandes', 'adresse_livraison')) {
            Schema::table('commandes', function (Blueprint $table) {
                $table->string('adresse_livraison')->nullable()->after('commentaires');
                $table->string('telephone_livraison')->nullable()->after('adresse_livraison');
                $table->timestamp('heure_livraison')->nullable()->after('heure_livraison_demandee');
            });
        }

        // Assurer que la colonne statut a les bonnes valeurs par défaut
        DB::statement("ALTER TABLE commandes MODIFY statut ENUM('en_attente', 'confirmee', 'enregistree', 'en_preparation', 'prete', 'prete_a_emporter', 'prete_a_livrer', 'en_livraison', 'servie', 'payee', 'livree', 'annulee') DEFAULT 'en_attente'");
    }

    public function down(): void
    {
        // Pour le downgrade, on ne supprime pas les colonnes, juste les modifications
        if (Schema::hasColumn('commandes', 'adresse_livraison')) {
            Schema::table('commandes', function (Blueprint $table) {
                $table->dropColumn('adresse_livraison');
                $table->dropColumn('telephone_livraison');
                $table->dropColumn('heure_livraison');
            });
        }
    }
};
