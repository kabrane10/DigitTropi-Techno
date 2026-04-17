<?php

namespace App\Http\Controllers\Animateur;

use App\Http\Controllers\Controller;
use App\Models\SuiviParcellaire;
use App\Models\Producteur;
use App\Models\AgentTerrain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuiviController extends Controller
{
    public function index(Request $request)
    {
        $animateur = Auth::guard('animateur')->user();
        $agentsIds = AgentTerrain::where('superviseur_id', $animateur->id)->pluck('id');
        $producteursIds = Producteur::whereIn('agent_terrain_id', $agentsIds)->pluck('id');
        
        $query = SuiviParcellaire::whereIn('producteur_id', $producteursIds)
            ->with(['producteur', 'animateur']);
        
        if ($request->filled('producteur_id')) {
            $query->where('producteur_id', $request->producteur_id);
        }
        
        $suivis = $query->orderBy('date_suivi', 'desc')->paginate(15);
        $producteurs = Producteur::whereIn('agent_terrain_id', $agentsIds)->get();
        
        return view('animateur.suivi.index', compact('suivis', 'producteurs'));
    }

    public function show($id)
    {
        $animateur = Auth::guard('animateur')->user();
        $agentsIds = AgentTerrain::where('superviseur_id', $animateur->id)->pluck('id');
        $producteursIds = Producteur::whereIn('agent_terrain_id', $agentsIds)->pluck('id');
        
        $suivi = SuiviParcellaire::where('id', $id)
            ->whereIn('producteur_id', $producteursIds)
            ->with(['producteur', 'animateur'])
            ->firstOrFail();
        
        return view('animateur.suivi.show', compact('suivi'));
    }
}