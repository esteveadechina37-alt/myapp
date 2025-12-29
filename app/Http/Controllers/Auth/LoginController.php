<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * Afficher la page de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traiter la soumission du formulaire de connexion
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'mot_de_passe' => 'required|string|min:6',
        ]);

        // Changement du nom du champ pour correspondre à la structure
        $credentials['mot_de_passe'] = $request->mot_de_passe;
        
        if (Auth::guard('web')->attempt(
            ['email' => $request->email, 'mot_de_passe' => $request->mot_de_passe],
            $request->remember
        )) {
            $request->session()->regenerate();
            
            // Rediriger selon le rôle
            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirection basée sur le rôle de l'utilisateur
     */
    private function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'gerant':
                return redirect('/gerant/dashboard');
            case 'serveur':
                return redirect('/serveur/dashboard');
            case 'cuisinier':
                return redirect('/cuisinier/dashboard');
            case 'livreur':
                return redirect('/livreur/dashboard');
            case 'client':
                return redirect('/client/dashboard');
            default:
                return redirect('/client/menu');
        }
    }
}
