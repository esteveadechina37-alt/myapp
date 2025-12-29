<?php

use Illuminate\Support\Facades\Route;
use App\Models\Categorie;

Route::get('/test-categories', function () {
    $categories = Categorie::where('est_active', true)
        ->orderBy('ordre_affichage')
        ->get();
    
    return view('test-categories', compact('categories'));
});
