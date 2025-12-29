<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Mettre à jour l'image de la catégorie Boissons avec une source plus fiable
     */
    public function up(): void
    {
        DB::table('categories')->where('nom', 'Boissons')->update([
            'image' => 'https://cdn.pixabay.com/photo/2017/07/16/10/43/glass-2508584_1280.jpg'
        ]);
    }

    public function down(): void
    {
        // Aucune action à reverser
    }
};
