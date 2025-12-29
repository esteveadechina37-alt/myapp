<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil avec les catégories
     */
    public function index(): View
    {
        $categories = Categorie::where('est_active', true)
            ->orderBy('ordre_affichage')
            ->get();

        \Log::debug('Categories from HomeController:', $categories->toArray());

        return view('index', compact('categories'));
    }
}
