<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Plat;
use App\Models\Categorie;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Facture;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Afficher le tableau de bord admin
     */
    public function dashboard()
    {
        // Vérifier que l'utilisateur est admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé');
        }

        // Statistiques globales
        $stats = [
            'total_clients' => User::where('role', 'client')->count(),
            'total_commandes' => DB::table('commandes')->count(),
            'total_plats' => Plat::count(),
            'total_categories' => Categorie::count(),
            'commandes_en_attente' => DB::table('commandes')->where('statut', 'en_attente')->count(),
            'commandes_confirmees' => DB::table('commandes')->where('statut', 'confirmee')->count(),
            'commandes_preparees' => DB::table('commandes')->where('statut', 'preparee')->count(),
            'commandes_livrees' => DB::table('commandes')->where('statut', 'livree')->count(),
            'revenue_total' => DB::table('factures')->sum('montant_ttc'),
        ];

        // Dernieres commandes
        $dernieres_commandes = DB::table('commandes')
            ->orderBy('heure_commande', 'desc')
            ->limit(10)
            ->get();

        // Plats les plus populaires
        $plats_populaires = DB::table('lignes_commandes')
            ->join('plats', 'lignes_commandes.plat_id', '=', 'plats.id')
            ->select('plats.nom', DB::raw('COUNT(*) as total'), DB::raw('SUM(lignes_commandes.quantite) as quantite_totale'))
            ->groupBy('plats.id', 'plats.nom')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Catégories
        $categories = Categorie::all();

        // Utilisateurs récents
        $utilisateurs_recents = User::where('role', 'client')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'dernieres_commandes',
            'plats_populaires',
            'categories',
            'utilisateurs_recents'
        ));
    }

    /**
     * Gérer les utilisateurs
     */
    public function users()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $users = User::where('role', 'client')->paginate(15);
        return view('admin.users', compact('users'));
    }

    /**
     * Gérer les plats
     */
    public function plats()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $plats = Plat::paginate(15);
        $categories = Categorie::all();
        return view('admin.plats', compact('plats', 'categories'));
    }

    /**
     * Afficher formulaire création plat
     */
    public function createPlat()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $categories = Categorie::all();
        return view('admin.plats-create', compact('categories'));
    }

    /**
     * Afficher formulaire édition plat
     */
    public function editPlat($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $plat = Plat::findOrFail($id);
        $categories = Categorie::all();
        return view('admin.plats-create', compact('plat', 'categories'));
    }

    /**
     * Ajouter/Modifier un plat
     */
    public function storePlat(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|string',
            'est_disponible' => 'boolean',
        ]);

        if ($request->input('id')) {
            Plat::find($request->input('id'))->update($validated);
            return redirect()->route('admin.plats')->with('success', 'Plat modifié!');
        } else {
            Plat::create($validated);
            return redirect()->route('admin.plats')->with('success', 'Plat ajouté!');
        }
    }

    /**
     * Mettre à jour un plat
     */
    public function updatePlat(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'image' => 'nullable|string',
            'est_disponible' => 'boolean',
        ]);

        Plat::findOrFail($id)->update($validated);
        return redirect()->route('admin.plats')->with('success', 'Plat mis à jour!');
    }

    /**
     * Supprimer un plat
     */
    public function deletePlat($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        Plat::find($id)->delete();
        return redirect()->route('admin.plats')->with('success', 'Plat supprimé!');
    }

    /**
     * Gérer les commandes
     */
    public function commandes()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $commandes = DB::table('commandes')
            ->orderBy('heure_commande', 'desc')
            ->paginate(15);

        return view('admin.commandes', compact('commandes'));
    }

    /**
     * Voir détails commande
     */
    public function showCommande($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $commande = DB::table('commandes')->where('id', $id)->first();
        $lignes = DB::table('lignes_commandes')
            ->where('commande_id', $id)
            ->join('plats', 'lignes_commandes.plat_id', '=', 'plats.id')
            ->select('plats.nom', 'lignes_commandes.quantite', 'lignes_commandes.prix_unitaire')
            ->get();

        return view('admin.commande-detail', compact('commande', 'lignes'));
    }

    /**
     * Modifier statut commande
     */
    public function updateStatutCommande(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'statut' => 'required|in:en_attente,confirmee,preparee,livree,annulee',
        ]);

        DB::table('commandes')
            ->where('id', $id)
            ->update(['statut' => $validated['statut']]);

        return redirect()->back()->with('success', 'Statut mis à jour!');
    }

    /**
     * Voir les catégories
     */
    public function categories()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $categories = Categorie::paginate(15);
        return view('admin.categories', compact('categories'));
    }

    /**
     * Rapports et statistiques
     */
    public function rapports()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Chiffre d'affaires (7 derniers jours)
        $totalRevenue = DB::table('commandes')
            ->where('heure_commande', '>=', now()->subDays(7))
            ->sum('montant_total_ttc');

        // Nombre de commandes (7 derniers jours)
        $totalOrders = DB::table('commandes')
            ->where('heure_commande', '>=', now()->subDays(7))
            ->count();

        // Nombre de clients actifs
        $totalCustomers = User::where('role', 'client')
            ->where('statut', 'actif')
            ->count();

        // Panier moyen
        $averageOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Graphique commandes par jour (7 derniers jours)
        $commandes_7jours = DB::table('commandes')
            ->select(DB::raw('DATE(heure_commande) as jour'), DB::raw('COUNT(*) as total'))
            ->where('heure_commande', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(heure_commande)'))
            ->orderBy('jour')
            ->get();

        $ordersDates = $commandes_7jours->pluck('jour')->toArray();
        $ordersData = $commandes_7jours->pluck('total')->toArray();

        // Graphique ventes par catégorie
        $ventes_categories = DB::table('lignes_commandes')
            ->join('plats', 'lignes_commandes.plat_id', '=', 'plats.id')
            ->join('categories', 'plats.categorie_id', '=', 'categories.id')
            ->select('categories.nom', DB::raw('SUM(lignes_commandes.quantite) as total_ventes'))
            ->groupBy('categories.id', 'categories.nom')
            ->orderByDesc('total_ventes')
            ->limit(10)
            ->get();

        $categoriesLabels = $ventes_categories->pluck('nom')->toArray();
        $categoriesData = $ventes_categories->pluck('total_ventes')->toArray();

        // Dernières commandes (7 jours)
        $recentOrders = Commande::with('client')
            ->where('heure_commande', '>=', now()->subDays(7))
            ->orderByDesc('heure_commande')
            ->limit(10)
            ->get();

        return view('admin.rapports', compact(
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'averageOrder',
            'ordersDates',
            'ordersData',
            'categoriesLabels',
            'categoriesData',
            'recentOrders'
        ));
    }

    /**
     * Gérer les employés
     */
    public function employes()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $employes = User::whereIn('role', ['serveur', 'cuisinier', 'livreur', 'gerant'])
            ->paginate(15);
        
        return view('admin.employes', compact('employes'));
    }

    /**
     * Créer un nouvel employé
     */
    public function createEmploye()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $roles = ['serveur', 'cuisinier', 'livreur', 'gerant'];
        return view('admin.employes-create', compact('roles'));
    }

    /**
     * Stocker un nouvel employé
     */
    public function storeEmploye(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:serveur,cuisinier,livreur,gerant',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:500',
            'numero_id' => 'nullable|string|max:50|unique:users',
            'date_embauche' => 'required|date',
        ]);

        try {
            $employe = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role'],
                'telephone' => $validated['telephone'] ?? null,
                'adresse' => $validated['adresse'] ?? null,
                'numero_id' => $validated['numero_id'] ?? null,
                'date_embauche' => $validated['date_embauche'],
                'statut' => 'actif',
            ]);

            return redirect()->route('admin.employes')
                ->with('success', 'Employé créé avec succès. Email: ' . $employe->email);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création de l\'employé: ' . $e->getMessage()]);
        }
    }

    /**
     * Éditer un employé
     */
    public function editEmploye($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $employe = User::findOrFail($id);
        $roles = ['serveur', 'cuisinier', 'livreur', 'gerant'];
        
        return view('admin.employes-edit', compact('employe', 'roles'));
    }

    /**
     * Mettre à jour un employé
     */
    public function updateEmploye(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $employe = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:serveur,cuisinier,livreur,gerant',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:500',
            'numero_id' => 'nullable|string|max:50|unique:users,numero_id,' . $id,
            'date_embauche' => 'required|date',
            'statut' => 'required|in:actif,inactif,suspendu',
        ]);

        try {
            $employe->update($validated);

            return redirect()->route('admin.employes')
                ->with('success', 'Employé modifié avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour: ' . $e->getMessage()]);
        }
    }

    /**
     * Supprimer un employé
     */
    public function deleteEmploye($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        try {
            $employe = User::findOrFail($id);
            $employe->delete();

            return redirect()->route('admin.employes')
                ->with('success', 'Employé supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression: ' . $e->getMessage()]);
        }
    }

    /**
     * Réinitialiser le mot de passe d'un employé
     */
    public function resetEmployePassword($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $employe = User::findOrFail($id);
        
        // Générer un mot de passe temporaire
        $temporary_password = bin2hex(random_bytes(6)); // Mot de passe aléatoire de 12 caractères
        
        $employe->update([
            'password' => bcrypt($temporary_password),
        ]);

        return back()->with('success', 'Nouveau mot de passe temporaire: ' . $temporary_password . ' (À communiquer à l\'employé)');
    }

    /**
     * Supprimer un utilisateur/client
     */
    public function deleteUser($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        User::find($id)->delete();
        return redirect()->route('admin.users')->with('success', 'Client supprimé!');
    }

    /**
     * Ajouter une catégorie
     */
    public function storeCategorie(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'ordre_affichage' => 'nullable|integer',
        ]);

        Categorie::create([
            'nom' => $validated['nom'],
            'ordre_affichage' => $validated['ordre_affichage'] ?? 0,
            'est_active' => $request->has('est_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.categories')->with('success', 'Catégorie ajoutée!');
    }

    /**
     * Modifier une catégorie
     */
    public function updateCategorie(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'ordre_affichage' => 'nullable|integer',
        ]);

        Categorie::find($id)->update([
            'nom' => $validated['nom'],
            'ordre_affichage' => $validated['ordre_affichage'] ?? 0,
            'est_active' => $request->has('est_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.categories')->with('success', 'Catégorie mise à jour!');
    }

    /**
     * Supprimer une catégorie
     */
    public function deleteCategorie($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        Categorie::find($id)->delete();
        return redirect()->route('admin.categories')->with('success', 'Catégorie supprimée!');
    }
}
