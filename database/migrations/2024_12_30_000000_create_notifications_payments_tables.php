<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // order_status_change, order_cancelled, payment_received, new_order
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('is_read');
        });

        // Table Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->string('methode'); // card, cash, transfer, check
            $table->string('statut')->default('pending'); // pending, complete, refunded, failed
            $table->string('reference_transaction')->unique();
            $table->timestamp('date_paiement');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('commande_id');
            $table->index('statut');
        });

        // Ajouter colonnes manquantes à commandes
        Schema::table('commandes', function (Blueprint $table) {
            if (!Schema::hasColumn('commandes', 'motif_annulation')) {
                $table->string('motif_annulation')->nullable()->after('statut');
            }
            if (!Schema::hasColumn('commandes', 'heure_annulation')) {
                $table->timestamp('heure_annulation')->nullable()->after('motif_annulation');
            }
            if (!Schema::hasColumn('commandes', 'heure_prete')) {
                $table->timestamp('heure_prete')->nullable()->after('heure_commande');
            }
            if (!Schema::hasColumn('commandes', 'heure_servie')) {
                $table->timestamp('heure_servie')->nullable()->after('heure_prete');
            }
            if (!Schema::hasColumn('commandes', 'heure_livree')) {
                $table->timestamp('heure_livree')->nullable()->after('heure_servie');
            }
            if (!Schema::hasColumn('commandes', 'heure_paiement')) {
                $table->timestamp('heure_paiement')->nullable()->after('heure_livree');
            }
            if (!Schema::hasColumn('commandes', 'nb_personnes')) {
                $table->integer('nb_personnes')->default(1)->after('type_commande');
            }
            if (!Schema::hasColumn('commandes', 'notes_cuisine')) {
                $table->text('notes_cuisine')->nullable()->after('nb_personnes');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('notifications');
        
        Schema::table('commandes', function (Blueprint $table) {
            if (Schema::hasColumn('commandes', 'motif_annulation')) {
                $table->dropColumn('motif_annulation');
            }
            if (Schema::hasColumn('commandes', 'heure_annulation')) {
                $table->dropColumn('heure_annulation');
            }
            if (Schema::hasColumn('commandes', 'heure_prete')) {
                $table->dropColumn('heure_prete');
            }
            if (Schema::hasColumn('commandes', 'heure_servie')) {
                $table->dropColumn('heure_servie');
            }
            if (Schema::hasColumn('commandes', 'heure_livree')) {
                $table->dropColumn('heure_livree');
            }
            if (Schema::hasColumn('commandes', 'heure_paiement')) {
                $table->dropColumn('heure_paiement');
            }
            if (Schema::hasColumn('commandes', 'nb_personnes')) {
                $table->dropColumn('nb_personnes');
            }
            if (Schema::hasColumn('commandes', 'notes_cuisine')) {
                $table->dropColumn('notes_cuisine');
            }
        });
    }
};
