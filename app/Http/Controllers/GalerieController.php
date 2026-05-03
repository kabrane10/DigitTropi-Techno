<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Galerie;
use Illuminate\Http\Request;

class GalerieController extends Controller
{
    public function index()
    {
        $categories = Galerie::select('categorie')
            ->distinct()
            ->pluck('categorie');
            
        $images = Galerie::publie()
            ->orderBy('ordre')
            ->orderBy('date_prise', 'desc')
            ->paginate(12);
            
        return view('galerie', compact('images', 'categories'));
    }
    
    public function filter($categorie)
    {
        $categories = Galerie::select('categorie')->distinct()->pluck('categorie');
        
        $images = Galerie::publie()
            ->where('categorie', $categorie)
            ->orderBy('ordre')
            ->orderBy('date_prise', 'desc')
            ->paginate(12);
            
        return view('galerie', compact('images', 'categories'));
    }

    public function apiData(Request $request)
{
    try {
        $categories = Galerie::select('categorie')->distinct()->pluck('categorie');
        
        // Récupérer TOUTES les images publiées
        $images = Galerie::where('est_publie', true)
            ->orderBy('ordre', 'asc')
            ->orderBy('date_prise', 'desc')
            ->paginate(12);
        
        // Ajouter l'URL complète
        $images->getCollection()->transform(function($image) {
            $image->image_url = asset('storage/' . $image->image);
            return $image;
        });
        
        return response()->json([
            'status' => 'success',
            'categories' => $categories,
            'images' => $images
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Erreur API galerie: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}

public function getAlbums()
{
    $albums = Album::withCount('images')
        ->where('est_publie', true)
        ->orderBy('date_evenement', 'desc')
        ->get();
    
    return response()->json(['albums' => $albums]);
}

public function getAlbumImages($id)
{
    $album = Album::with('images')->findOrFail($id);
    
    $album->images->transform(function($image) {
        $image->image_url = asset('storage/' . $image->image);
        return $image;
    });
    
    return response()->json($album);
}
public function getPhotos(Request $request)
{
    $photos = Galerie::where('est_publie', true)
        ->whereNull('album_id')
        ->orderBy('date_prise', 'desc')
        ->paginate(12);
    
    $photos->getCollection()->transform(function($photo) {
        $photo->image_url = asset('storage/' . $photo->image);
        return $photo;
    });
    
    return response()->json(['photos' => $photos]);
}

}