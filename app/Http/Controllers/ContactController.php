<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Afficher la page de contact
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Traiter la soumission du formulaire de contact
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'sujet' => 'required|in:reservation,menu,commande,reclamation,partenariat,autre',
            'message' => 'required|string|min:10|max:5000',
        ], [
            'nom.required' => 'Le nom est requis',
            'email.required' => 'L\'email est requis',
            'email.email' => 'Veuillez entrer un email valide',
            'sujet.required' => 'Le sujet est requis',
            'message.required' => 'Le message est requis',
            'message.min' => 'Le message doit contenir au moins 10 caractères',
        ]);

        try {
            // Envoyer l'email (optionnel - peut être configuré selon les besoins)
            // Mail::send('emails.contact', $validated, function($mail) use ($validated) {
            //     $mail->to('contact@restaurant.fr')
            //          ->subject('Nouveau message de contact: ' . $validated['sujet']);
            // });

            // Pour l'instant, on stocke juste un message de succès
            return back()->with('success', 'Votre message a été envoyé avec succès! Nous vous répondrons dans les 24 heures.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de l\'envoi du message. Veuillez réessayer plus tard.']);
        }
    }
}
