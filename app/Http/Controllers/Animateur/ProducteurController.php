<?php

namespace App\Http\Controllers\Animateur;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use App\Models\AgentTerrain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProducteurController extends Controller
{
    public function index(Request $request)
    {
        $animateur = Auth::guard('animateur')->user();
        $agentsIds = AgentTerrain::where('superviseur_id', $animateur->id)->pluck('id');
        
        $query = Producteur::whereIn('agent_terrain_id', $agentsIds);
        
        if ($request->filled('search')) {
            $query->where('nom_complet', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }
        
        $producteurs = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('animateur.producteurs.index', compact('producteurs'));
    }

    public function show($id)
    {
        $animateur = Auth::guard('animateur')->user();
        $agentsIds = AgentTerrain::where('superviseur_id', $animateur->id)->pluck('id');
        
        $producteur = Producteur::where('id', $id)
            ->whereIn('agent_terrain_id', $agentsIds)
            ->firstOrFail();
        
        $credits = $producteur->credits()->orderBy('created_at', 'desc')->get();
        $collectes = $producteur->collectes()->orderBy('date_collecte', 'desc')->limit(10)->get();
        
        return view('animateur.producteurs.show', compact('producteur', 'credits', 'collectes'));
    }
}