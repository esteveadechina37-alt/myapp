<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Client\ClientOrderController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\TableRestaurantController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GerantController;
use App\Http\Controllers\ServeurController;
use App\Http\Controllers\CuisinierController;
use App\Http\Controllers\LivreurController;

// Pages publiques
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/apropos', function () {
    return view('apropos');
})->name('apropos');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::middleware('auth')->group(function () {
    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Routes Admin
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::get('/plats', [AdminController::class, 'plats'])->name('plats');
        Route::get('/plats/create', [AdminController::class, 'createPlat'])->name('plats.create');
        Route::get('/plats/{id}/edit', [AdminController::class, 'editPlat'])->name('plats.edit');
        Route::post('/plats', [AdminController::class, 'storePlat'])->name('plats.store');
        Route::patch('/plats/{id}', [AdminController::class, 'updatePlat'])->name('plats.update');
        Route::delete('/plats/{id}', [AdminController::class, 'deletePlat'])->name('plats.delete');
        Route::get('/commandes', [AdminController::class, 'commandes'])->name('commandes');
        Route::get('/commandes/{id}', [AdminController::class, 'showCommande'])->name('commandes.show');
        Route::patch('/commandes/{id}/statut', [AdminController::class, 'updateStatutCommande'])->name('commandes.updateStatut');
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminController::class, 'storeCategorie'])->name('categories.store');
        Route::patch('/categories/{id}', [AdminController::class, 'updateCategorie'])->name('categories.update');
        Route::delete('/categories/{id}', [AdminController::class, 'deleteCategorie'])->name('categories.delete');
        Route::get('/rapports', [AdminController::class, 'rapports'])->name('rapports');
        
        // Gestion des employés
        Route::get('/employes', [AdminController::class, 'employes'])->name('employes');
        Route::get('/employes/create', [AdminController::class, 'createEmploye'])->name('employes.create');
        Route::post('/employes', [AdminController::class, 'storeEmploye'])->name('employes.store');
        Route::get('/employes/{id}/edit', [AdminController::class, 'editEmploye'])->name('employes.edit');
        Route::patch('/employes/{id}', [AdminController::class, 'updateEmploye'])->name('employes.update');
        Route::delete('/employes/{id}', [AdminController::class, 'deleteEmploye'])->name('employes.delete');
        Route::post('/employes/{id}/reset-password', [AdminController::class, 'resetEmployePassword'])->name('employes.resetPassword');
    });

    // Menu et consultation (avec protection QR)
    Route::middleware(['verify-qr'])->group(function () {
        Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
        Route::get('/menu/plat/{id}', [MenuController::class, 'showPlat'])->name('menu.plat');
    });

    // Menu public (sans protection)
    Route::get('/menu/qr-code', [MenuController::class, 'qrCodeMenu'])->name('menu.qr-code');
    Route::get('/menu/client', [MenuController::class, 'menuClient'])->name('menu.client');
    
    // API Routes pour le menu client
    Route::get('/api/categories', [MenuController::class, 'apiCategories'])->name('api.categories');
    Route::get('/api/menu/plats/{categoryId}', [MenuController::class, 'getPlatsByCategory'])->name('api.plats');
    Route::get('/api/tables/statut', [MenuController::class, 'getTablesStatut'])->name('api.tables');
    Route::get('/api/menu/search', [MenuController::class, 'search'])->name('api.search');

    // Commandes (Admin/Support)
    Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.list');
    Route::get('/commandes/{id}', [CommandeController::class, 'show'])->name('commandes.show');
    Route::patch('/commandes/{id}/statut', [CommandeController::class, 'updateStatut'])->name('commandes.updateStatut');
    Route::delete('/commandes/{id}', [CommandeController::class, 'destroy'])->name('commandes.cancel');

    // Factures
    Route::get('/factures', [FactureController::class, 'index'])->name('factures.index');
    Route::get('/factures/{id}', [FactureController::class, 'show'])->name('factures.show');
    Route::get('/factures/{id}/telecharger', [FactureController::class, 'telecharger'])->name('factures.telecharger');
    Route::post('/factures/generer/{commandeId}', [FactureController::class, 'generer'])->name('factures.generer');

    // Tables et réservations
    Route::get('/tables', [TableRestaurantController::class, 'index'])->name('tables.index');
    Route::get('/api/tables/statut', [TableRestaurantController::class, 'getStatut']);
    Route::post('/tables/attribuer', [TableRestaurantController::class, 'attribuer'])->name('tables.attribuer');
    Route::post('/tables/{id}/liberer', [TableRestaurantController::class, 'liberer'])->name('tables.liberer');
    Route::post('/reservations', [TableRestaurantController::class, 'reserver'])->name('reservations.store');

    // Routes Gérant
    Route::prefix('gerant')->name('gerant.')->group(function () {
        Route::get('/dashboard', [GerantController::class, 'dashboard'])->name('dashboard');
        Route::get('/menu', [GerantController::class, 'gererMenu'])->name('menu');
        Route::get('/stocks', [GerantController::class, 'gererStocks'])->name('stocks');
        Route::get('/statistiques', [GerantController::class, 'statistiques'])->name('statistiques');
        Route::get('/paiements', [GerantController::class, 'encaisserPaiement'])->name('paiements');
        Route::post('/paiements/{commande}', [GerantController::class, 'markAsPaid'])->name('mark-paid');
    });

    // Routes Serveur
    Route::prefix('serveur')->name('serveur.')->group(function () {
        Route::get('/dashboard', [ServeurController::class, 'dashboard'])->name('dashboard');
        Route::get('/commandes', [ServeurController::class, 'consulterCommandes'])->name('commandes');
        Route::get('/prendre-commande', [ServeurController::class, 'prendreCommande'])->name('prendre-commande');
        Route::post('/store-commande', [ServeurController::class, 'storeCommande'])->name('store-commande');
        Route::post('/commandes/{commande}/servir', [ServeurController::class, 'servir'])->name('servir');
        Route::post('/tables/attribuer', [ServeurController::class, 'attribuerTable'])->name('attribuer-table');
    });

    // Routes Cuisinier
    Route::prefix('cuisinier')->name('cuisinier.')->group(function () {
        Route::get('/dashboard', [CuisinierController::class, 'dashboard'])->name('dashboard');
        Route::get('/commandes', [CuisinierController::class, 'consulterCommandes'])->name('commandes');
        Route::post('/commandes/{commande}/prete', [CuisinierController::class, 'marquerPrete'])->name('marquer-prete');
        Route::patch('/details/{detail}/statut', [CuisinierController::class, 'updateDetailStatut'])->name('update-detail-statut');
    });

    // Routes Livreur
    Route::prefix('livreur')->name('livreur.')->group(function () {
        Route::get('/dashboard', [LivreurController::class, 'dashboard'])->name('dashboard');
        Route::get('/livraisons', [LivreurController::class, 'consulterLivraisons'])->name('livraisons');
        Route::post('/livraisons/{commande}/prendre', [LivreurController::class, 'prendreLivraison'])->name('prendre-livraison');
        Route::post('/livraisons/{commande}/confirmer', [LivreurController::class, 'confirmerLivraison'])->name('confirmer-livraison');
    });

    // Routes Client - Système de commande complet
    Route::prefix('client')->name('client.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [ClientOrderController::class, 'dashboard'])->name('dashboard');
        
        // Menu et panier
        Route::get('/menu', [ClientOrderController::class, 'menu'])->name('menu');
        Route::get('/cart', [ClientOrderController::class, 'viewCart'])->name('view-cart');
        Route::post('/order/add/{platId}', [ClientOrderController::class, 'addToCart'])->name('add-to-cart');
        Route::post('/order/cart/update/{platId}', [ClientOrderController::class, 'updateCart'])->name('update-cart');
        Route::post('/order/remove/{platId}', [ClientOrderController::class, 'removeFromCart'])->name('remove-from-cart');
        Route::post('/order/clear', [ClientOrderController::class, 'clearCart'])->name('clear-cart');
        
        // Checkout et commandes
        Route::get('/checkout', [ClientOrderController::class, 'checkoutForm'])->name('checkout-form');
        Route::post('/checkout', [ClientOrderController::class, 'storeCommande'])->name('store-order');
        Route::get('/order/{id}', [ClientOrderController::class, 'orderDetail'])->name('order-detail');
        Route::get('/orders', [ClientOrderController::class, 'orderHistory'])->name('order-history');
        Route::delete('/order/{id}', [ClientOrderController::class, 'cancelOrder'])->name('cancel-order');
        
        // Paiement et factures
        Route::post('/payment/{commandeId}', [ClientOrderController::class, 'processPayment'])->name('payment');
        Route::get('/invoices', [ClientOrderController::class, 'invoices'])->name('invoices');
        Route::get('/invoice/{id}/download', [ClientOrderController::class, 'downloadInvoice'])->name('download-invoice');
        
        // API
        Route::get('/api/plat/{platId}', [ClientOrderController::class, 'getPlatDetails'])->name('plat-details');
        Route::get('/api/search', [ClientOrderController::class, 'searchPlats'])->name('search');
    });
});

// Route test pour debug
Route::get('/test-categories', function () {
    $categories = \App\Models\Categorie::where('est_active', true)
        ->orderBy('ordre_affichage')
        ->get();
    
    return view('test-categories', compact('categories'));
});

// Route de diagnostic du système de commande
Route::get('/test-commande-system', function () {
    return view('test-commande-system');
});
