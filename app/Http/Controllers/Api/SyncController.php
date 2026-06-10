<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use App\Models\Collecte;
use App\Models\SuiviParcellaire;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    /**
     * Synchronisation complète des données
     */
    public function syncAll(Request $request)
    {
        $agentId = $request->user()->id;
        
        // Récupérer les dernières modifications
        $lastSync = $request->input('last_sync', now()->subDays(30)->toIso8601String());
        
        $producteurs = Producteur::where('agent_terrain_id', $agentId)
            ->where('updated_at', '>=', $lastSync)
            ->get();
        
        $collectes = Collecte::whereHas('producteur', function($q) use ($agentId) {
                $q->where('agent_terrain_id', $agentId);
            })
            ->where('updated_at', '>=', $lastSync)
            ->get();
        
        $suivis = SuiviParcellaire::whereHas('producteur', function($q) use ($agentId) {
                $q->where('agent_terrain_id', $agentId);
            })
            ->where('updated_at', '>=', $lastSync)
            ->get();
        
        // Données de référence
        $cooperatives = \App\Models\Cooperative::where('statut', 'active')->get(['id', 'nom', 'region']);
        $semences = \App\Models\Semence::where('est_actif', true)->get(['id', 'nom', 'variete', 'unite']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'producteurs' => $producteurs,
                'collectes' => $collectes,
                'suivis' => $suivis,
                'cooperatives' => $cooperatives,
                'semences' => $semences
            ],
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Statut de la synchronisation
     */
    public function status(Request $request)
    {
        $agentId = $request->user()->id;
        
        $stats = [
            'total_producteurs' => Producteur::where('agent_terrain_id', $agentId)->count(),
            'total_collectes' => Collecte::whereHas('producteur', function($q) use ($agentId) {
                $q->where('agent_terrain_id', $agentId);
            })->count(),
            'total_suivis' => SuiviParcellaire::whereHas('producteur', function($q) use ($agentId) {
                $q->where('agent_terrain_id', $agentId);
            })->count(),
            'last_sync' => $request->user()->last_sync ?? null
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
            'is_online' => true
        ]);
    }
}