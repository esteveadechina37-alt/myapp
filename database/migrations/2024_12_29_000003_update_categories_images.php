<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Mettre à jour les images des catégories Entrées et Boissons
     */
    public function up(): void
    {
        DB::table('categories')->where('nom', 'Entrées')->update([
            'image' => 'https://images.pexels.com/photos/1092730/pexels-photo-1092730.jpeg?w=500&h=500&fit=crop'
        ]);

        DB::table('categories')->where('nom', 'Boissons')->update([
            'image' => 'https://images.pexels.com/photos/699953/pexels-photo-699953.jpeg?w=500&h=500&fit=crop'
        ]);
    }

    public function down(): void
    {
        // Aucune action à reverser
    }
};
