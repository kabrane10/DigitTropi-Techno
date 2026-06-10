<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SuiviParcellaire;
use App\Models\Producteur;
use Illuminate\Http\Request;

class AgentSuiviController extends Controller
{
    /**
     * Liste des suivis de l'agent
     */
    public function index(Request $request)
    {
        $suivis = SuiviParcellaire::whereHas('producteur', function($q) use ($request) {
                $q->where('agent_terrain_id', $request->user()->id);
            })
            ->with(['producteur', 'animateur'])
            ->orderBy('date_suivi', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $suivis
        ]);
    }

    /**
     * Créer un nouveau suivi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'producteur_id' => 'required|exists:producteurs,id',
            'date_suivi' => 'required|date',
            'superficie_actuelle' => 'required|numeric|min:0',
            'hauteur_plantes' => 'nullable|numeric',
            'stade_croissance' => 'required|string',
            'sante_cultures' => 'required|in:excellente,bonne,moyenne,mauvaise,critique',
            'taux_levée' => 'nullable|integer|min:0|max:100',
            'problemes_constates' => 'nullable|string',
            'recommandations' => 'nullable|string',
            'actions_prises' => 'nullable|string'
        ]);

        // Vérifier que le producteur appartient à l'agent
        $producteur = Producteur::where('id', $validated['producteur_id'])
            ->where('agent_terrain_id', $request->user()->id)
            ->firstOrFail();

        $validated['code_suivi'] = 'SUIVI-' . str_pad(SuiviParcellaire::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validated['animateur_id'] = $request->user()->superviseur_id;

        $suivi = SuiviParcellaire::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Suivi enregistré avec succès',
            'data' => $suivi
        ], 201);
    }

    /**
     * Afficher un suivi
     */
    public function show($id, Request $request)
    {
        $suivi = SuiviParcellaire::whereHas('producteur', function($q) use ($request) {
                $q->where('agent_terrain_id', $request->user()->id);
            })
            ->with(['producteur', 'animateur'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $suivi
        ]);
    }

    /**
     * Synchronisation en batch
     */
    public function syncBatch(Request $request)
    {
        $suivis = $request->input('suivis', []);
        $results = [];
        
        foreach ($suivis as $data) {
            try {
                $existing = SuiviParcellaire::where('code_suivi', $data['code_suivi'] ?? null)->first();
                
                if (!$existing) {
                    $data['code_suivi'] = 'SUIVI-' . str_pad(SuiviParcellaire::max('id') + 1, 6, '0', STR_PAD_LEFT);
                    $data['animateur_id'] = $request->user()->superviseur_id;
                    
                    $suivi = SuiviParcellaire::create($data);
                    $results[] = ['temp_id' => $data['temp_id'], 'suivi' => $suivi, 'status' => 'created'];
                } else {
                    $results[] = ['temp_id' => $data['temp_id'], 'status' => 'already_exists'];
                }
            } catch (\Exception $e) {
                $results[] = ['temp_id' => $data['temp_id'], 'status' => 'error', 'message' => $e->getMessage()];
            }
        }
        
        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }

    /**
     * Récupérer les données pour synchronisation hors-ligne
     */
    public function getOfflineData(Request $request)
    {
        $suivis = SuiviParcellaire::whereHas('producteur', function($q) use ($request) {
                $q->where('agent_terrain_id', $request->user()->id);
            })
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'suivis' => $suivis,
            'last_sync' => now()->toIso8601String()
        ]);
    }
}