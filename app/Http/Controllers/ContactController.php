<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:20',
            'sujet' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // Envoyer l'email (à configurer plus tard)
        // Mail::to('contact@tropitechno.com')->send(new ContactMail($validated));

        // Sauvegarder dans la base de données (optionnel)
        // Contact::create($validated);

        return redirect()->route('contact')->with('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
    }
}