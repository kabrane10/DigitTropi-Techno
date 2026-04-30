<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ActualiteAdminController extends Controller
{
    public function index()
    {
        $actualites = Actualite::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.actualites.index', compact('actualites'));
    }

    public function create()
    {
        return view('admin.actualites.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'categorie' => 'required|in:campagne,evenement,formation,annonce',
            'date_publication' => 'required|date',
            'date_fin' => 'nullable|date|after:date_publication',
            'lieu' => 'nullable|string',
            'est_en_avant' => 'boolean',
            'image_couverture' => 'nullable|image|max:2048'
        ]);

        $validated['slug'] = Str::slug($validated['titre']) . '-' . time();
        $validated['est_publie'] = true;

        if ($request->hasFile('image_couverture')) {
            $path = $request->file('image_couverture')->store('actualites', 'public');
            $validated['image_couverture'] = $path;
        }

        Actualite::create($validated);

        return redirect()->route('admin.actualites.index')
            ->with('success', 'Actualité publiée avec succès');
    }

    public function edit(Actualite $actualite)
    {
        return view('admin.actualites.edit', compact('actualite'));
    }

    public function update(Request $request, Actualite $actualite)
{
    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'contenu' => 'required|string',
        'categorie' => 'required|in:campagne,evenement,formation,annonce',
        'date_publication' => 'required|date',
        'date_fin' => 'nullable|date|after:date_publication',
        'lieu' => 'nullable|string',
        'est_en_avant' => 'boolean',
        'est_publie' => 'boolean',
        'image_couverture' => 'nullable|image|max:2048'
    ]);

    // Gérer les valeurs booléennes
    $validated['est_en_avant'] = $request->has('est_en_avant');
    $validated['est_publie'] = $request->has('est_publie');

    // Gérer l'image
    if ($request->hasFile('image_couverture')) {
        // Supprimer l'ancienne image si elle existe
        if ($actualite->image_couverture) {
            Storage::disk('public')->delete($actualite->image_couverture);
        }
        
        $path = $request->file('image_couverture')->store('actualites', 'public');
        $validated['image_couverture'] = $path;
    }

    $actualite->update($validated);

    return redirect()->route('admin.actualites.index')
        ->with('success', 'Actualité mise à jour avec succès');
}
}