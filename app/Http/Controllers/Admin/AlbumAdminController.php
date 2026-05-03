<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Galerie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AlbumAdminController extends Controller
{
    public function index()
    {
        $albums = Album::withCount('images')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.albums.index', compact('albums'));
    }

    public function create()
    {
        return view('admin.albums.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'categorie' => 'required|in:terrain,formation,recolte,producteurs,evenement',
            'lieu' => 'nullable|string',
            'date_evenement' => 'required|date',
            'description' => 'nullable|string',
            'couverture' => 'nullable|image|max:5120',
            'images' => 'required|array',
            'images.*' => 'image|max:5120'
        ]);

        // Gérer la couverture
        if ($request->hasFile('couverture')) {
            $path = $request->file('couverture')->store('albums', 'public');
            $validated['couverture'] = $path;
        }

        $validated['est_publie'] = true;
        $album = Album::create($validated);

        // Gérer les images multiples
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('galerie', 'public');
                Galerie::create([
                    'titre' => $validated['titre'] . ' - Image ' . ($index + 1),
                    'image' => $path,
                    'description' => $validated['description'] ?? null,
                    'categorie' => $validated['categorie'],
                    'lieu' => $validated['lieu'],
                    'date_prise' => $validated['date_evenement'],
                    'est_publie' => true,
                    'ordre' => $index + 1,
                    'album_id' => $album->id
                ]);
            }
        }

        return redirect()->route('admin.albums.index')
            ->with('success', 'Album créé avec ' . count($request->file('images', [])) . ' images');
    }

    public function show($id)
    {
        $album = Album::with('images')->findOrFail($id);
        return view('admin.albums.show', compact('album'));
    }

    public function edit($id)
    {
        $album = Album::findOrFail($id);
        return view('admin.albums.edit', compact('album'));
    }

    public function update(Request $request, $id)
    {
        $album = Album::findOrFail($id);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'categorie' => 'required|in:terrain,formation,recolte,producteurs,evenement',
            'lieu' => 'nullable|string',
            'date_evenement' => 'required|date',
            'description' => 'nullable|string',
            'couverture' => 'nullable|image|max:5120',
            'est_publie' => 'boolean'
        ]);

        if ($request->hasFile('couverture')) {
            if ($album->couverture) {
                Storage::disk('public')->delete($album->couverture);
            }
            $path = $request->file('couverture')->store('albums', 'public');
            $validated['couverture'] = $path;
        }

        $album->update($validated);

        // Ajouter des images supplémentaires
        if ($request->hasFile('new_images')) {
            $lastOrder = $album->images()->max('ordre') ?? 0;
            foreach ($request->file('new_images') as $index => $image) {
                $path = $image->store('galerie', 'public');
                Galerie::create([
                    'titre' => $album->titre . ' - Image ' . ($lastOrder + $index + 1),
                    'image' => $path,
                    'description' => $album->description,
                    'categorie' => $album->categorie,
                    'lieu' => $album->lieu,
                    'date_prise' => $album->date_evenement,
                    'est_publie' => $album->est_publie,
                    'ordre' => $lastOrder + $index + 1,
                    'album_id' => $album->id
                ]);
            }
        }

        return redirect()->route('admin.albums.index')
            ->with('success', 'Album mis à jour');
    }

    public function destroy($id)
    {
        $album = Album::with('images')->findOrFail($id);
        
        foreach ($album->images as $image) {
            Storage::disk('public')->delete($image->image);
            $image->delete();
        }
        
        if ($album->couverture) {
            Storage::disk('public')->delete($album->couverture);
        }
        
        $album->delete();
        
        return redirect()->route('admin.albums.index')
            ->with('success', 'Album supprimé');
    }

    public function addImages(Request $request, $id)
{
    $album = Album::findOrFail($id);
    
    $request->validate([
        'new_images' => 'required|array',
        'new_images.*' => 'image|max:5120'
    ]);
    
    $lastOrder = $album->images()->max('ordre') ?? 0;
    
    foreach ($request->file('new_images') as $index => $image) {
        $path = $image->store('galerie', 'public');
        Galerie::create([
            'titre' => $album->titre . ' - Photo ' . ($lastOrder + $index + 1),
            'image' => $path,
            'description' => $album->description,
            'categorie' => $album->categorie,
            'lieu' => $album->lieu,
            'date_prise' => $album->date_evenement,
            'est_publie' => $album->est_publie,
            'ordre' => $lastOrder + $index + 1,
            'album_id' => $album->id
        ]);
    }
    
    return redirect()->route('admin.albums.show', $album)
        ->with('success', count($request->file('new_images')) . ' photo(s) ajoutée(s)');
}

public function index(Request $request)
{
    $query = Album::withCount('images');
    
    if ($request->filled('categorie')) {
        $query->where('categorie', $request->categorie);
    }
    
    if ($request->filled('statut')) {
        $query->where('est_publie', $request->statut == 'publie');
    }
    
    $albums = $query->orderBy('created_at', 'desc')->paginate(12);
    
    return view('admin.albums.index', compact('albums'));
}

}