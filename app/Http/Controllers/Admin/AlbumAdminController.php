<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Galerie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AlbumAdminController extends Controller
{
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
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120'
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('couverture')) {
                $result = Cloudinary::upload($request->file('couverture')->getRealPath(), [
                    'folder' => 'albums'
                ]);
                $validated['couverture'] = $result->getSecurePath();
                $validated['couverture_public_id'] = $result->getPublicId();
            }

            $validated['est_publie'] = true;
            $album = Album::create($validated);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $result = Cloudinary::upload($image->getRealPath(), [
                        'folder' => 'galerie'
                    ]);

                    Galerie::create([
                        'titre' => $validated['titre'] . ' - Image ' . ($index + 1),
                        'image' => $result->getSecurePath(),
                        'image_public_id' => $result->getPublicId(),
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

            DB::commit();

            return redirect()->route('admin.albums.index')
                ->with('success', 'Album créé avec ' . count($request->file('images', [])) . ' images');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur de création d'album: " . $e->getMessage());

            return redirect()->back()
                ->with('error', "Une erreur est survenue lors de la création de l\'album. Veuillez vérifier vos informations et les logs pour plus de détails.");
        }
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

        DB::beginTransaction();
        try {
            if ($request->hasFile('couverture')) {
                if ($album->couverture_public_id) {
                    Cloudinary::destroy($album->couverture_public_id);
                }
                $result = Cloudinary::upload($request->file('couverture')->getRealPath(), [
                    'folder' => 'albums'
                ]);

                $validated['couverture'] = $result->getSecurePath();
                $validated['couverture_public_id'] = $result->getPublicId();
            }

            $album->update($validated);

            if ($request->hasFile('new_images')) {
                $lastOrder = $album->images()->max('ordre') ?? 0;
                foreach ($request->file('new_images') as $index => $image) {
                    $result = Cloudinary::upload($image->getRealPath(), [
                        'folder' => 'galerie'
                    ]);
                    Galerie::create([
                        'titre' => $album->titre . ' - Image ' . ($lastOrder + $index + 1),
                        'image' => $result->getSecurePath(),
                        'image_public_id' => $result->getPublicId(),
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

            DB::commit();

            return redirect()->route('admin.albums.index')
                ->with('success', 'Album mis à jour');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur de mise à jour d'album: " . $e->getMessage());

            return redirect()->back()
                ->with('error', "Une erreur est survenue lors de la mise à jour de l\'album. Veuillez vérifier vos informations et les logs pour plus de détails.");
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $album = Album::with('images')->findOrFail($id);
            
            foreach ($album->images as $image) {
                if ($image->image_public_id) {
                    Cloudinary::destroy($image->image_public_id);
                }
                $image->delete();
            }
            
            if ($album->couverture_public_id) {
                Cloudinary::destroy($album->couverture_public_id);
            }
            
            $album->delete();

            DB::commit();
            
            return redirect()->route('admin.albums.index')
                ->with('success', 'Album supprimé');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur de suppression d'album: " . $e->getMessage());

            return redirect()->back()
                ->with('error', "Une erreur est survenue lors de la suppression de l\'album. Veuillez vérifier les logs pour plus de détails.");
        }
    }

    public function addImages(Request $request, $id)
    {
        $album = Album::findOrFail($id);
        
        $request->validate([
            'new_images' => 'required|array',
            'new_images.*' => 'image|max:5120'
        ]);
        
        DB::beginTransaction();
        try {
            $lastOrder = $album->images()->max('ordre') ?? 0;
            
            foreach ($request->file('new_images') as $index => $image) {
                $result = Cloudinary::upload($image->getRealPath(), [
                    'folder' => 'galerie'
                ]);
                Galerie::create([
                    'titre' => $album->titre . ' - Photo ' . ($lastOrder + $index + 1),
                    'image' => $result->getSecurePath(),
                    'image_public_id' => $result->getPublicId(),
                    'description' => $album->description,
                    'categorie' => $album->categorie,
                    'lieu' => $album->lieu,
                    'date_prise' => $album->date_evenement,
                    'est_publie' => $album->est_publie,
                    'ordre' => $lastOrder + $index + 1,
                    'album_id' => $album->id
                ]);
            }

            DB::commit();
            
            return redirect()->route('admin.albums.show', $album)
                ->with('success', count($request->file('new_images')) . ' photo(s) ajoutée(s)');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur d'ajout d'images à l'album: " . $e->getMessage());

            return redirect()->back()
                ->with('error', "Une erreur est survenue lors de l\'ajout des images. Veuillez vérifier les logs pour plus de détails.");
        }
    }
}
