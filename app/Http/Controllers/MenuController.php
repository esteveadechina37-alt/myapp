<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use App\Models\Categorie;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Facture;
use App\Models\TableRestaurant;
use Illuminate\Http\Request;

/**
 * Contrôleur Menu Client
 * Gère l'affichage du menu dynamique et la consultation via QR Code
 */
class MenuController extends Controller
{
    /**
     * Afficher le menu complet par catégories
     */
    public function index()
    {
        $categories = Categorie::where('est_active', true)
            ->orderBy('ordre_affichage')
            ->with(['plats' => function($query) {
                $query->where('est_disponible', true)->orderBy('nom');
            }])
            ->get();

        return view('client.menu.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Afficher le menu via QR Code (page de scan)
     */
    public function qrCodeMenu()
    {
        $categories = Categorie::where('est_active', true)
            ->orderBy('ordre_affichage')
            ->with(['plats' => function($query) {
                $query->where('est_disponible', true)->orderBy('nom');
            }])
            ->get();

        return view('client.menu.qr-code', [
            'categories' => $categories,
        ]);
    }

    /**
     * Afficher les détails d'un plat
     */
    public function showPlat($id)
    {
        $plat = Plat::with(['categorie', 'ingredients'])->findOrFail($id);

        return view('client.menu.plat-detail', [
            'plat' => $plat,
        ]);
    }

    /**
     * API - Récupérer les plats par catégorie (AJAX)
     */
    public function getPlatsByCategory($categoryId)
    {
        $plats = Plat::where('categorie_id', $categoryId)
            ->where('est_disponible', true)
            ->orderBy('nom')
            ->get();

        return response()->json($plats);
    }

    /**
     * API - Récupérer la liste des catégories actives (pour le menu client)
     */
    public function apiCategories()
    {
        $categories = Categorie::where('est_active', true)
            ->orderBy('ordre_affichage')
            ->get(['id', 'nom', 'description', 'image']);

        return response()->json($categories);
    }

    /**
     * API - Récupérer le statut des tables (disponible ou non)
     */
    public function getTablesStatut()
    {
        $tables = TableRestaurant::orderBy('numero')
            ->get(['id', 'numero', 'est_disponible']);

        return response()->json($tables);
    }

    /**
     * Rechercher des plats
     */
    public function search(Request $request)
    {
        $search = $request->input('q', '');
        
        if (strlen($search) < 2) {
            return response()->json(['plats' => []]);
        }

        $plats = Plat::where('est_disponible', true)
            ->where(function($query) use ($search) {
                $query->where('nom', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
            })
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'plats' => $plats,
        ]);
    }
}
