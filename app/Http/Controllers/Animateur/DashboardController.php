<?php

namespace App\Http\Controllers\Animateur;

use App\Http\Controllers\Controller;
use App\Models\AgentTerrain;
use App\Models\Producteur;
use App\Models\SuiviParcellaire;
use App\Models\Collecte;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $animateur = Auth::guard('animateur')->user();
        $agents = AgentTerrain::where('superviseur_id', $animateur->id)->get();
        $agentsIds = $agents->pluck('id');
        
        $stats = [
            'total_agents' => $agents->count(),
            'agents_actifs' => $agents->where('statut', 'actif')->count(),
            'total_producteurs' => Producteur::whereIn('agent_terrain_id', $agentsIds)->count(),
            'producteurs_actifs' => Producteur::whereIn('agent_terrain_id', $agentsIds)->where('statut', 'actif')->count(),
            'total_superficie' => Producteur::whereIn('agent_terrain_id', $agentsIds)->sum('superficie_totale'),
            'total_collectes' => Collecte::whereIn('producteur_id', fn($q) => $q->select('id')->from('producteurs')->whereIn('agent_terrain_id', $agentsIds))->sum('quantite_nette'),
            'suivis_mois' => SuiviParcellaire::where('animateur_id', $animateur->id)->whereMonth('date_suivi', now()->month)->count(),
        ];
        
        $performanceAgents = AgentTerrain::where('superviseur_id', $animateur->id)->withCount('producteurs')->orderBy('producteurs_count', 'desc')->get();
        $derniersProducteurs = Producteur::whereIn('agent_terrain_id', $agentsIds)->orderBy('created_at', 'desc')->limit(5)->get();
        
        return view('animateur.dashboard', compact('animateur', 'agents', 'stats', 'performanceAgents', 'derniersProducteurs'));
    }
}