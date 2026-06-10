<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collecte;
use App\Models\Producteur;
use App\Models\CreditAgricole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentCollecteController extends Controller
{
    /**
     * Liste des collectes de l'agent
     */
    public function index(Request $request)
    {
        $collectes = Collecte::whereHas('producteur', function($q) use ($request) {
                $q->where('agent_terrain_id', $request->user()->id);
            })
            ->with('producteur')
            ->orderBy('date_collecte', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $collectes
        ]);
    }

    /**
     * Créer une nouvelle collecte
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'producteur_id' => 'required|exists:producteurs,id',
            'date_collecte' => 'required|date',
            'produit' => 'required|string',
            'quantite_brute' => 'required|numeric|min:0',
            'quantite_nette' => 'required|numeric|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
            'zone_collecte' => 'required|string',
            'credit_id' => 'nullable|exists:credits_agricoles,id',
            'montant_deduict' => 'nullable|numeric|min:0',
            'observations' => 'nullable|string'
        ]);

        // Vérifier que le producteur appartient à l'agent
        $producteur = Producteur::where('id', $validated['producteur_id'])
            ->where('agent_terrain_id', $request->user()->id)
            ->firstOrFail();

        $validated['montant_total'] = $validated['quantite_nette'] * $validated['prix_unitaire'];
        $validated['montant_deduict'] = $validated['montant_deduict'] ?? 0;
        $validated['montant_a_payer'] = $validated['montant_total'] - $validated['montant_deduict'];
        $validated['code_collecte'] = 'COL-' . str_pad(Collecte::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validated['statut_paiement'] = $validated['montant_a_payer'] <= 0 ? 'paye' : 'en_attente';

        DB::beginTransaction();
        try {
            $collecte = Collecte::create($validated);
            
            // Mettre à jour le crédit si déduction
            if (!empty($validated['credit_id']) && $validated['montant_deduict'] > 0) {
                $credit = CreditAgricole::find($validated['credit_id']);
                $credit->montant_restant -= $validated['montant_deduict'];
                if ($credit->montant_restant <= 0) {
                    $credit->statut = 'rembourse';
                }
                $credit->save();
            }
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Collecte enregistrée avec succès',
                'data' => $collecte
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher une collecte
     */
    public function show($id, Request $request)
    {
        $collecte = Collecte::whereHas('producteur', function($q) use ($request) {
                $q->where('agent_terrain_id', $request->user()->id);
            })
            ->with('producteur', 'credit')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $collecte
        ]);
    }

    /**
     * Synchronisation en batch
     */
    public function syncBatch(Request $request)
    {
        $collectes = $request->input('collectes', []);
        $results = [];
        
        foreach ($collectes as $data) {
            try {
                // Vérifier si la collecte existe déjà
                $existing = Collecte::where('code_collecte', $data['code_collecte'] ?? null)->first();
                
                if (!$existing) {
                    $data['code_collecte'] = 'COL-' . str_pad(Collecte::max('id') + 1, 6, '0', STR_PAD_LEFT);
                    $data['montant_total'] = $data['quantite_nette'] * $data['prix_unitaire'];
                    $data['montant_a_payer'] = $data['montant_total'] - ($data['montant_deduict'] ?? 0);
                    
                    $collecte = Collecte::create($data);
                    $results[] = ['temp_id' => $data['temp_id'], 'collecte' => $collecte, 'status' => 'created'];
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
        $collectes = Collecte::whereHas('producteur', function($q) use ($request) {
                $q->where('agent_terrain_id', $request->user()->id);
            })
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'collectes' => $collectes,
            'last_sync' => now()->toIso8601String()
        ]);
    }
}