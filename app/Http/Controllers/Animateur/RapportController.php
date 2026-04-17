<?php

namespace App\Http\Controllers\Animateur;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use App\Models\Collecte;
use App\Models\AgentTerrain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RapportController extends Controller
{
    public function index()
    {
        $animateur = Auth::guard('animateur')->user();
        $agentsIds = AgentTerrain::where('superviseur_id', $animateur->id)->pluck('id');
        $producteursIds = Producteur::whereIn('agent_terrain_id', $agentsIds)->pluck('id');
        
        $stats = [
            'total_producteurs' => Producteur::whereIn('agent_terrain_id', $agentsIds)->count(),
            'total_collectes' => Collecte::whereIn('producteur_id', $producteursIds)->sum('quantite_nette'),
            'valeur_collectes' => Collecte::whereIn('producteur_id', $producteursIds)->sum('montant_total'),
            'total_agents' => $agentsIds->count(),
            'total_superficie' => Producteur::whereIn('agent_terrain_id', $agentsIds)->sum('superficie_totale'),
        ];
        
        $collectesParMois = Collecte::whereIn('producteur_id', $producteursIds)
            ->select(DB::raw('strftime("%Y-%m", date_collecte) as mois'), DB::raw('SUM(quantite_nette) as total'))
            ->groupBy('mois')
            ->orderBy('mois', 'desc')
            ->limit(6)
            ->get();
        
        $topProducteurs = Producteur::whereIn('agent_terrain_id', $agentsIds)
            ->withSum('collectes', 'quantite_nette')
            ->orderBy('collectes_sum_quantite_nette', 'desc')
            ->limit(5)
            ->get();
        
        return view('animateur.rapports.index', compact('stats', 'collectesParMois', 'topProducteurs'));
    }
}