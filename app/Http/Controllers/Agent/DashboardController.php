<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use App\Models\SuiviParcellaire;
use App\Models\Collecte;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = Auth::guard('agent')->user();
        
        // Producteurs enregistrés par cet agent
        $producteurs = Producteur::where('agent_terrain_id', $agent->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Statistiques
        $stats = [
            'total_producteurs' => Producteur::where('agent_terrain_id', $agent->id)->count(),
            'producteurs_actifs' => Producteur::where('agent_terrain_id', $agent->id)
                ->where('statut', 'actif')
                ->count(),
            'total_collectes' => Collecte::whereHas('producteur', function($q) use ($agent) {
                $q->where('agent_terrain_id', $agent->id);
            })->sum('quantite_nette'),
            'valeur_collectes' => Collecte::whereHas('producteur', function($q) use ($agent) {
                $q->where('agent_terrain_id', $agent->id);
            })->sum('montant_total'),
            'suivis_mois' => SuiviParcellaire::where('animateur_id', $agent->superviseur_id)
                ->whereMonth('date_suivi', now()->month)
                ->count(),
            'superficie_totale' => Producteur::where('agent_terrain_id', $agent->id)->sum('superficie_totale'),
        ];
        
        // Dernières collectes
        $dernieres_collectes = Collecte::whereHas('producteur', function($q) use ($agent) {
            $q->where('agent_terrain_id', $agent->id);
        })
        ->with('producteur')
        ->orderBy('date_collecte', 'desc')
        ->limit(5)
        ->get();
        
        return view('agent.dashboard', compact('agent', 'producteurs', 'stats', 'dernieres_collectes'));
    }
}