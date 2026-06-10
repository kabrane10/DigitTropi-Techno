<?php

namespace App\Http\Controllers\Agent;

use App\Traits\NotifiableTrait;
use App\Http\Controllers\Controller;
use App\Models\Collecte;
use App\Models\Producteur;
use App\Models\CreditAgricole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CollecteController extends Controller
{
    use NotifiableTrait;

    /**
     * Liste des collectes
     */
    public function index(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        
        $query = Collecte::whereHas('producteur', function($q) use ($agent) {
            $q->where('agent_terrain_id', $agent->id);
        })->with(['producteur', 'credit']);
        
        if ($request->filled('produit')) {
            $query->where('produit', $request->produit);
        }
        
        if ($request->filled('date_debut')) {
            $query->whereDate('date_collecte', '>=', $request->date_debut);
        }
        
        if ($request->filled('date_fin')) {
            $query->whereDate('date_collecte', '<=', $request->date_fin);
        }
        
        $collectes = $query->orderBy('date_collecte', 'desc')->paginate(15);
        $produits = Collecte::distinct()->pluck('produit');
        
        return view('agent.collectes.index', compact('collectes', 'produits'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $agent = Auth::guard('agent')->user();
        
        $producteurs = Producteur::where('agent_terrain_id', $agent->id)
            ->where('statut', 'actif')
            ->with(['credits' => function($q) {
                $q->where('statut', 'actif')->where('montant_restant', '>', 0);
            }])
            ->get();
        
        return view('agent.collectes.create', compact('producteurs'));
    }

    /**
     * Enregistrer une nouvelle collecte (CORRIGÉ)
     */
    public function store(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        
        $validated = $request->validate([
            'beneficiaire_id' => 'required|exists:producteurs,id',
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

        // Vérifier que le producteur appartient bien à l'agent
        $producteur = Producteur::where('id', $validated['beneficiaire_id'])
            ->where('agent_terrain_id', $agent->id)
            ->firstOrFail();
        
        DB::beginTransaction();
        try {
            $montantTotal = $validated['quantite_nette'] * $validated['prix_unitaire'];
            $montantDeduit = $validated['montant_deduict'] ?? 0;
            $montantAPayer = $montantTotal - $montantDeduit;
            
            // Créer la collecte
            $collecte = Collecte::create([
                'code_collecte' => 'COL-' . str_pad(Collecte::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'producteur_id' => $producteur->id,
                'beneficiaire_type' => 'App\\Models\\Producteur',
                'beneficiaire_id' => $producteur->id,
                'credit_id' => $validated['credit_id'] ?? null,
                'date_collecte' => $validated['date_collecte'],
                'produit' => $validated['produit'],
                'quantite_brute' => $validated['quantite_brute'],
                'quantite_nette' => $validated['quantite_nette'],
                'prix_unitaire' => $validated['prix_unitaire'],
                'montant_total' => $montantTotal,
                'montant_deduict' => $montantDeduit,
                'montant_a_payer' => $montantAPayer,
                'statut_paiement' => $montantAPayer == 0 ? 'paye' : 'en_attente',
                'zone_collecte' => $validated['zone_collecte'],
                'observations' => $validated['observations'] ?? null
            ]);
            
            // Mettre à jour le crédit si déduction
            if (!empty($validated['credit_id']) && $montantDeduit > 0) {
                $credit = CreditAgricole::find($validated['credit_id']);
                if ($credit) {
                    $credit->montant_restant -= $montantDeduit;
                    if ($credit->montant_restant <= 0) {
                        $credit->statut = 'rembourse';
                    }
                    $credit->save();
                }
            }
            
            DB::commit();
            
            // 🔔 Déclencher la notification
            $this->notifyNewCollecte($collecte);
            
            return redirect()->route('agent.collectes.index')
                ->with('success', 'Collecte enregistrée avec succès. Montant à payer: ' . number_format($montantAPayer, 0, ',', ' ') . ' CFA');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les détails d'une collecte
     */
    public function show($id)
    {
        $agent = Auth::guard('agent')->user();
        
        $collecte = Collecte::whereHas('producteur', function($q) use ($agent) {
            $q->where('agent_terrain_id', $agent->id);
        })->with(['producteur', 'credit'])->findOrFail($id);
        
        return view('agent.collectes.show', compact('collecte'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $agent = Auth::guard('agent')->user();
        
        $collecte = Collecte::whereHas('producteur', function($q) use ($agent) {
            $q->where('agent_terrain_id', $agent->id);
        })->with(['producteur', 'credit'])->findOrFail($id);
        
        $producteurs = Producteur::where('agent_terrain_id', $agent->id)
            ->where('statut', 'actif')
            ->with(['credits' => function($q) {
                $q->where('statut', 'actif')->where('montant_restant', '>', 0);
            }])
            ->get();
        
        // Récupérer les crédits actifs du producteur pour l'édition
        $credits = CreditAgricole::where('producteur_id', $collecte->producteur_id)
            ->where('statut', 'actif')
            ->where('montant_restant', '>', 0)
            ->get();
        
        return view('agent.collectes.edit', compact('collecte', 'producteurs', 'credits'));
    }

    /**
     * Mettre à jour une collecte
     */
    public function update(Request $request, $id)
    {
        $agent = Auth::guard('agent')->user();
        
        $collecte = Collecte::whereHas('producteur', function($q) use ($agent) {
            $q->where('agent_terrain_id', $agent->id);
        })->findOrFail($id);
        
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
            'observations' => 'nullable|string',
            'statut_paiement' => 'nullable|in:en_attente,partiel,paye'
        ]);

        // Vérifier que le producteur appartient bien à l'agent
        Producteur::where('id', $validated['producteur_id'])
            ->where('agent_terrain_id', $agent->id)
            ->firstOrFail();
        
        DB::beginTransaction();
        try {
            // Restaurer l'ancienne déduction sur le crédit si nécessaire
            if ($collecte->credit_id && $collecte->montant_deduict > 0) {
                $oldCredit = CreditAgricole::find($collecte->credit_id);
                if ($oldCredit) {
                    $oldCredit->montant_restant += $collecte->montant_deduict;
                    if ($oldCredit->montant_restant > 0 && $oldCredit->statut == 'rembourse') {
                        $oldCredit->statut = 'actif';
                    }
                    $oldCredit->save();
                }
            }
            
            // Calculer les nouveaux montants
            $montantTotal = $validated['quantite_nette'] * $validated['prix_unitaire'];
            $montantDeduit = $validated['montant_deduict'] ?? 0;
            $montantAPayer = $montantTotal - $montantDeduit;
            
            // Mettre à jour la collecte
            $collecte->update([
                'producteur_id' => $validated['producteur_id'],
                'date_collecte' => $validated['date_collecte'],
                'produit' => $validated['produit'],
                'quantite_brute' => $validated['quantite_brute'],
                'quantite_nette' => $validated['quantite_nette'],
                'prix_unitaire' => $validated['prix_unitaire'],
                'montant_total' => $montantTotal,
                'montant_deduict' => $montantDeduit,
                'montant_a_payer' => $montantAPayer,
                'zone_collecte' => $validated['zone_collecte'],
                'credit_id' => $validated['credit_id'] ?? null,
                'observations' => $validated['observations'] ?? null,
                'statut_paiement' => $validated['statut_paiement'] ?? $collecte->statut_paiement
            ]);
            
            // Appliquer la nouvelle déduction sur le crédit
            if (!empty($validated['credit_id']) && $montantDeduit > 0) {
                $newCredit = CreditAgricole::find($validated['credit_id']);
                if ($newCredit) {
                    $newCredit->montant_restant -= $montantDeduit;
                    if ($newCredit->montant_restant <= 0) {
                        $newCredit->statut = 'rembourse';
                    }
                    $newCredit->save();
                }
            }
            
            DB::commit();
            
            return redirect()->route('agent.collectes.index')
                ->with('success', 'Collecte mise à jour avec succès');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une collecte
     */
    public function destroy($id)
    {
        $agent = Auth::guard('agent')->user();
        
        $collecte = Collecte::whereHas('producteur', function($q) use ($agent) {
            $q->where('agent_terrain_id', $agent->id);
        })->findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Restaurer le crédit si déduction
            if ($collecte->credit_id && $collecte->montant_deduict > 0) {
                $credit = CreditAgricole::find($collecte->credit_id);
                if ($credit) {
                    $credit->montant_restant += $collecte->montant_deduict;
                    if ($credit->montant_restant > 0 && $credit->statut == 'rembourse') {
                        $credit->statut = 'actif';
                    }
                    $credit->save();
                }
            }
            
            $collecte->delete();
            DB::commit();
            
            return redirect()->route('agent.collectes.index')
                ->with('success', 'Collecte supprimée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
    
    /**
     * Dashboard des collectes pour agent
     */
    public function dashboard()
    {
        $agent = Auth::guard('agent')->user();
        
        $stats = [
            'total_collecte' => Collecte::whereHas('producteur', function($q) use ($agent) {
                $q->where('agent_terrain_id', $agent->id);
            })->sum('quantite_nette'),
            'valeur_totale' => Collecte::whereHas('producteur', function($q) use ($agent) {
                $q->where('agent_terrain_id', $agent->id);
            })->sum('montant_total'),
            'collecte_mois' => Collecte::whereHas('producteur', function($q) use ($agent) {
                $q->where('agent_terrain_id', $agent->id);
            })->whereMonth('date_collecte', now()->month)->sum('quantite_nette'),
            'nb_collectes' => Collecte::whereHas('producteur', function($q) use ($agent) {
                $q->where('agent_terrain_id', $agent->id);
            })->count(),
            'nb_producteurs' => Collecte::whereHas('producteur', function($q) use ($agent) {
                $q->where('agent_terrain_id', $agent->id);
            })->distinct('producteur_id')->count('producteur_id'),
        ];
        
        $collectes_par_produit = Collecte::select('produit', DB::raw('SUM(quantite_nette) as total'))
            ->whereHas('producteur', function($q) use ($agent) {
                $q->where('agent_terrain_id', $agent->id);
            })
            ->groupBy('produit')
            ->get();
        
        $collectes_recentes = Collecte::with('producteur')
            ->whereHas('producteur', function($q) use ($agent) {
                $q->where('agent_terrain_id', $agent->id);
            })
            ->orderBy('date_collecte', 'desc')
            ->limit(10)
            ->get();
        
        return view('agent.collectes.dashboard', compact('stats', 'collectes_par_produit', 'collectes_recentes'));
    }
}