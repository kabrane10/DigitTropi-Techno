<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentProducteurController extends Controller
{
    /**
     * Liste des producteurs de l'agent
     */
    public function index(Request $request)
    {
        $producteurs = Producteur::where('agent_terrain_id', $request->user()->id)
            ->with('cooperative')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $producteurs
        ]);
    }

    /**
     * Créer un nouveau producteur (en ligne)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'contact' => 'required|string|unique:producteurs',
            'email' => 'nullable|email|unique:producteurs',
            'localisation' => 'required|string',
            'region' => 'required|in:Centrale,Kara,Savanes',
            'culture_pratiquee' => 'required|string',
            'superficie_totale' => 'required|numeric|min:0',
            'cooperative_id' => 'nullable|exists:cooperatives,id',
            'notes' => 'nullable|string'
        ]);

        $validated['code_producteur'] = 'PRD-' . str_pad(Producteur::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validated['date_enregistrement'] = now();
        $validated['statut'] = 'actif';
        $validated['agent_terrain_id'] = $request->user()->id;

        $producteur = Producteur::create($validated);

        // Mettre à jour le compteur de l'agent
        $request->user()->increment('producteurs_enregistres');

        return response()->json([
            'success' => true,
            'message' => 'Producteur enregistré avec succès',
            'data' => $producteur
        ], 201);
    }

    /**
     * Afficher un producteur
     */
    public function show($id, Request $request)
    {
        $producteur = Producteur::where('id', $id)
            ->where('agent_terrain_id', $request->user()->id)
            ->with(['cooperative', 'credits', 'collectes'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $producteur
        ]);
    }

    /**
     * Mettre à jour un producteur
     */
    public function update(Request $request, $id)
    {
        $producteur = Producteur::where('id', $id)
            ->where('agent_terrain_id', $request->user()->id)
            ->firstOrFail();

        $validated = $request->validate([
            'nom_complet' => 'sometimes|string|max:255',
            'contact' => 'sometimes|string|unique:producteurs,contact,' . $id,
            'email' => 'nullable|email|unique:producteurs,email,' . $id,
            'localisation' => 'sometimes|string',
            'region' => 'sometimes|in:Centrale,Kara,Savanes',
            'culture_pratiquee' => 'sometimes|string',
            'superficie_totale' => 'sometimes|numeric|min:0',
            'cooperative_id' => 'nullable|exists:cooperatives,id',
            'notes' => 'nullable|string',
            'statut' => 'sometimes|in:actif,inactif,en_attente'
        ]);

        $producteur->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Producteur mis à jour',
            'data' => $producteur
        ]);
    }

    /**
     * Synchronisation en batch (pour mode hors-ligne)
     */
    public function syncBatch(Request $request)
    {
        $producteurs = $request->input('producteurs', []);
        $results = [];
        
        foreach ($producteurs as $data) {
            try {
                // Vérifier si le producteur existe déjà
                $existing = Producteur::where('contact', $data['contact'])->first();
                
                if (!$existing) {
                    $data['agent_terrain_id'] = $request->user()->id;
                    $data['code_producteur'] = 'PRD-' . str_pad(Producteur::max('id') + 1, 6, '0', STR_PAD_LEFT);
                    $data['date_enregistrement'] = now();
                    $data['statut'] = 'actif';
                    
                    $producteur = Producteur::create($data);
                    $results[] = [
                        'temp_id' => $data['temp_id'],
                        'producteur' => $producteur,
                        'status' => 'created'
                    ];
                    
                    $request->user()->increment('producteurs_enregistres');
                } else {
                    $results[] = [
                        'temp_id' => $data['temp_id'],
                        'status' => 'already_exists',
                        'existing_id' => $existing->id
                    ];
                }
            } catch (\Exception $e) {
                $results[] = [
                    'temp_id' => $data['temp_id'],
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
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
        $producteurs = Producteur::where('agent_terrain_id', $request->user()->id)
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'producteurs' => $producteurs,
            'last_sync' => now()->toIso8601String()
        ]);
    }
}