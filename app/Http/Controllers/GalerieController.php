<?php

namespace App\Http\Controllers;

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
}