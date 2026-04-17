<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActualiteController extends Controller
{
    public function index()
    {
        return view('actualites');
    }

    public function show($slug)
    {
        $actualite = Actualite::where('slug', $slug)
            ->where('est_publie', true)
            ->firstOrFail();
        
        $actualites_recentes = Actualite::where('est_publie', true)
            ->where('id', '!=', $actualite->id)
            ->orderBy('date_publication', 'desc')
            ->limit(3)
            ->get();
        
        return view('actualite-detail', compact('actualite', 'actualites_recentes'));
    }

    /**
     * API pour le chargement AJAX des actualités
     */
    public function apiData(Request $request)
    {
        try {
            // Récupérer toutes les actualités publiées
            $actualites = Actualite::where('est_publie', true)
                ->orderBy('est_en_avant', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(9);
            
            // Ajouter l'excerpt
            foreach ($actualites as $actu) {
                $actu->excerpt = Str::limit(strip_tags($actu->contenu), 150);
            }
            
            // Actualités en avant
            $actualites_en_avant = Actualite::where('est_publie', true)
                ->where('est_en_avant', true)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
            foreach ($actualites_en_avant as $actu) {
                $actu->excerpt = Str::limit(strip_tags($actu->contenu), 150);
            }
            
            // Campagnes
            $campagnes_en_cours = Actualite::where('est_publie', true)
                ->where('categorie', 'campagne')
                ->orderBy('created_at', 'desc')
                ->get();
            foreach ($campagnes_en_cours as $actu) {
                $actu->excerpt = Str::limit(strip_tags($actu->contenu), 150);
            }
            
            return response()->json([
                'status' => 'success',
                'actualites' => $actualites,
                'actualites_en_avant' => $actualites_en_avant,
                'campagnes_en_cours' => $campagnes_en_cours
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}