<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('index');
    }

    /**
     * Gérer la connexion
     */
    public function login(Request $request)
    {
        // Valider les données
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.exists' => 'Aucun compte avec cet email.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        // Trouver l'utilisateur
        $user = User::where('email', $validated['email'])->first();

        // Vérifier le mot de passe
        if (!Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Le mot de passe est incorrect.'],
            ]);
        }

        // Authentifier l'utilisateur
        Auth::login($user, $request->has('remember'));

        // Rediriger selon le rôle
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Connecté avec succès! Bienvenue Admin!');
        } elseif ($user->role === 'gerant') {
            return redirect()->route('gerant.dashboard')
                ->with('success', 'Connecté avec succès! Bienvenue Gérant!');
        } elseif ($user->role === 'serveur') {
            return redirect()->route('serveur.dashboard')
                ->with('success', 'Connecté avec succès! Bienvenue Serveur!');
        } elseif ($user->role === 'cuisinier') {
            return redirect()->route('cuisinier.dashboard')
                ->with('success', 'Connecté avec succès! Bienvenue Cuisinier!');
        } elseif ($user->role === 'livreur') {
            return redirect()->route('livreur.dashboard')
                ->with('success', 'Connecté avec succès! Bienvenue Livreur!');
        }

        // Par défaut (clients)
        return redirect()->route('client.dashboard')
            ->with('success', 'Connecté avec succès! Bienvenue sur votre tableau de bord!');
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegisterForm()
    {
        return view('index');
    }

    /**
     * Gérer l'inscription
     */
    public function register(Request $request)
    {
        // Valider les données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'agree_terms' => 'required|accepted',
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'agree_terms.required' => 'Vous devez accepter les conditions d\'utilisation.',
        ]);

        // Créer l'utilisateur (SEULEMENT CLIENTS via inscription publique)
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'client', // Seul rôle autorisé pour l'inscription publique
            'statut' => 'actif',
        ]);

        // Authentifier l'utilisateur
        Auth::login($user);

        // Rediriger vers le menu
        return redirect()->route('menu.index')
            ->with('success', 'Compte créé et connecté avec succès!');
    }

    /**
     * Gérer la déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Déconnecté avec succès!');
    }
}
