<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cooperative;
use App\Models\Semence;
use App\Models\Intrant;
use App\Models\CreditAgricole;
use App\Models\DistributionSemence;
use App\Models\DistributionIntrantCooperative;
use App\Models\CollecteCooperative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CooperativeOperationController extends Controller
{
    /**
     * Dashboard des opérations coopératives
     */
    public function dashboard($id)
    {
        $cooperative = Cooperative::with([
            'producteurs', 
            'distributionsSemences.semence',
            'distributionsIntrants.intrant',
            'collectes',
            'credits'
        ])->findOrFail($id);
        
        // Statistiques globales
        $stats = [
            'nb_membres' => $cooperative->producteurs->count(),
            'total_semences' => $cooperative->total_semences_distribuees,
            'valeur_semences' => $cooperative->valeur_semences_distribuees,
            'total_intrants' => $cooperative->total_intrants_distribues,
            'valeur_intrants' => $cooperative->valeur_intrants_distribues,
            'total_collectes' => $cooperative->total_collectes,
            'valeur_collectes' => $cooperative->valeur_collectes,
            'credits_actifs' => $cooperative->credits_actifs,
            'credits_total' => $cooperative->credits_total,
        ];
        
        // Graphiques : évolution des collectes (6 derniers mois)
        $collectesParMois = CollecteCooperative::where('cooperative_id', $id)
            ->selectRaw('DATE_FORMAT(date_collecte, "%Y-%m") as mois, SUM(quantite_nette) as total')
            ->where('date_collecte', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();
        
        // Top produits collectés
        $topProduits = CollecteCooperative::where('cooperative_id', $id)
            ->select('produit', DB::raw('SUM(quantite_nette) as total'))
            ->groupBy('produit')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.cooperatives.operations.dashboard', compact(
            'cooperative', 'stats', 'collectesParMois', 'topProduits'
        ));
    }
    
    /**
     * Formulaire distribution semences
     */
    public function createDistributionSemence($id)
    {
        $cooperative = Cooperative::findOrFail($id);
        $semences = Semence::where('stock_disponible', '>', 0)->get();
        $credits = CreditAgricole::where('cooperative_id', $id)
            ->where('statut', 'actif')
            ->get();
        
        return view('admin.cooperatives.operations.distribution-semence', compact('cooperative', 'semences', 'credits'));
    }
    
    /**
     * Enregistrer distribution semences
     */
    public function storeDistributionSemence(Request $request, $id)
    {
        $cooperative = Cooperative::findOrFail($id);
        
        $validated = $request->validate([
            'semence_id' => 'required|exists:semences,id',
            'quantite' => 'required|numeric|min:0.01',
            'prix_unitaire' => 'required|numeric|min:0',
            'date_distribution' => 'required|date',
            'saison' => 'required|in:principale,contre-saison,hivernage',
            'credit_id' => 'nullable|exists:credits_agricoles,id',
            'notes' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        try {
            $semence = Semence::find($validated['semence_id']);
            
            // Vérifier stock
            if ($semence->stock_disponible < $validated['quantite']) {
                return back()->with('error', "Stock insuffisant. Disponible: {$semence->stock_disponible} {$semence->unite}");
            }
            
            // Créer la distribution
            $distribution = DistributionSemence::create([
                'code_distribution' => 'DIST-COOP-' . str_pad(DistributionSemence::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'cooperative_id' => $cooperative->id,
                'semence_id' => $validated['semence_id'],
                'credit_id' => $validated['credit_id'] ?? null,
                'quantite' => $validated['quantite'],
                'prix_unitaire' => $validated['prix_unitaire'],
                'montant_total' => $validated['quantite'] * $validated['prix_unitaire'],
                'date_distribution' => $validated['date_distribution'],
                'saison' => $validated['saison'],
                'observations' => $validated['notes'] ?? null
            ]);
            
            // Mettre à jour le stock de semences
            $semence->stock_disponible -= $validated['quantite'];
            $semence->save();
            
            // Mettre à jour le crédit si utilisé
            if ($validated['credit_id']) {
                $credit = CreditAgricole::find($validated['credit_id']);
                $credit->montant_restant -= $distribution->montant_total;
                if ($credit->montant_restant <= 0) {
                    $credit->statut = 'rembourse';
                }
                $credit->save();
            }
            
            DB::commit();
            
            return redirect()->route('admin.cooperatives.operations.dashboard', $cooperative)
                ->with('success', "Distribution de {$validated['quantite']} {$semence->unite} de {$semence->nom} effectuée avec succès");
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la distribution: ' . $e->getMessage());
        }
    }
    
    /**
     * Formulaire distribution intrants
     */
    public function createDistributionIntrant($id)
    {
        $cooperative = Cooperative::findOrFail($id);
        $intrants = Intrant::where('est_actif', true)->get();
        $credits = CreditAgricole::where('cooperative_id', $id)
            ->where('statut', 'actif')
            ->get();
        
        // Récupérer les stocks par zone
        $stocks = [];
        $zones = ['Centrale', 'Kara', 'Savanes'];
        foreach ($intrants as $intrant) {
            foreach ($zones as $zone) {
                $stock = $intrant->stocks()->where('zone', $zone)->first();
                $stocks[$intrant->id][$zone] = $stock ? $stock->stock_actuel : 0;
            }
        }
        
        return view('admin.cooperatives.operations.distribution-intrant', compact('cooperative', 'intrants', 'credits', 'stocks', 'zones'));
    }
    
    /**
     * Enregistrer distribution intrants
     */
    public function storeDistributionIntrant(Request $request, $id)
    {
        $cooperative = Cooperative::findOrFail($id);
        
        $validated = $request->validate([
            'intrant_id' => 'required|exists:intrants,id',
            'quantite' => 'required|numeric|min:0.01',
            'zone' => 'required|string',
            'date_distribution' => 'required|date',
            'credit_id' => 'nullable|exists:credits_agricoles,id',
            'notes' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        try {
            $intrant = Intrant::find($validated['intrant_id']);
            $prixUnitaire = $intrant->prix_unitaire;
            $montantTotal = $validated['quantite'] * $prixUnitaire;
            
            // Vérifier le stock dans la zone
            $stock = $intrant->stocks()->where('zone', $validated['zone'])->first();
            if (!$stock || $stock->stock_actuel < $validated['quantite']) {
                $disponible = $stock ? $stock->stock_actuel : 0;
                return back()->with('error', "Stock insuffisant pour {$intrant->nom} dans la zone {$validated['zone']}. Disponible: {$disponible} {$intrant->unite}");
            }
            
            // Créer la distribution
            $distribution = DistributionIntrantCooperative::create([
                'code_distribution' => 'DIST-INT-' . str_pad(DistributionIntrantCooperative::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'cooperative_id' => $cooperative->id,
                'intrant_id' => $validated['intrant_id'],
                'credit_id' => $validated['credit_id'] ?? null,
                'quantite' => $validated['quantite'],
                'prix_unitaire' => $prixUnitaire,
                'montant_total' => $montantTotal,
                'date_distribution' => $validated['date_distribution'],
                'zone' => $validated['zone'],
                'notes' => $validated['notes'] ?? null
            ]);
            
            // Mettre à jour le stock
            $stock->stock_actuel -= $validated['quantite'];
            $stock->save();
            
            // Enregistrer le mouvement
            \App\Models\IntrantMouvement::create([
                'intrant_stock_id' => $stock->id,
                'type' => 'sortie',
                'quantite' => $validated['quantite'],
                'motif' => 'Distribution à coopérative',
                'reference' => $distribution->code_distribution,
                'user_id' => auth()->guard('admin')->id(),
                'notes' => "Distribution à: {$cooperative->nom}"
            ]);
            
            // Mettre à jour le crédit si utilisé
            if ($validated['credit_id']) {
                $credit = CreditAgricole::find($validated['credit_id']);
                $credit->montant_restant -= $montantTotal;
                if ($credit->montant_restant <= 0) {
                    $credit->statut = 'rembourse';
                }
                $credit->save();
            }
            
            DB::commit();
            
            return redirect()->route('admin.cooperatives.operations.dashboard', $cooperative)
                ->with('success', "Distribution de {$validated['quantite']} {$intrant->unite} de {$intrant->nom} effectuée avec succès");
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la distribution: ' . $e->getMessage());
        }
    }
    
    /**
     * Formulaire de collecte
     */
    public function createCollecte($id)
    {
        $cooperative = Cooperative::findOrFail($id);
        $credits = CreditAgricole::where('cooperative_id', $id)
            ->where('statut', 'actif')
            ->where('montant_restant', '>', 0)
            ->get();
        
        $produits = ['Soja', 'Maïs', 'Riz', 'Arachide', 'Sésame', 'Gombo', 'Autre'];
        
        return view('admin.cooperatives.operations.collecte', compact('cooperative', 'credits', 'produits'));
    }
    
    /**
     * Enregistrer la collecte
     */
    public function storeCollecte(Request $request, $id)
    {
        $cooperative = Cooperative::findOrFail($id);
        
        $validated = $request->validate([
            'produit' => 'required|string',
            'quantite_brute' => 'required|numeric|min:0.01',
            'quantite_nette' => 'required|numeric|min:0.01',
            'prix_unitaire' => 'required|numeric|min:0',
            'date_collecte' => 'required|date',
            'zone_collecte' => 'required|string',
            'credit_id' => 'nullable|exists:credits_agricoles,id',
            'observations' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        try {
            $montantTotal = $validated['quantite_nette'] * $validated['prix_unitaire'];
            $montantDeduit = 0;
            
            // Si crédit, vérifier le montant à déduire
            if ($validated['credit_id']) {
                $credit = CreditAgricole::find($validated['credit_id']);
                $montantDeduit = min($montantTotal, $credit->montant_restant);
            }
            
            $montantAPayer = $montantTotal - $montantDeduit;
            
            // Créer la collecte
            $collecte = CollecteCooperative::create([
                'code_collecte' => 'COL-COOP-' . str_pad(CollecteCooperative::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'cooperative_id' => $cooperative->id,
                'credit_id' => $validated['credit_id'] ?? null,
                'date_collecte' => $validated['date_collecte'],
                'produit' => $validated['produit'],
                'quantite_brute' => $validated['quantite_brute'],
                'quantite_nette' => $validated['quantite_nette'],
                'prix_unitaire' => $validated['prix_unitaire'],
                'montant_total' => $montantTotal,
                'montant_deduit' => $montantDeduit,
                'montant_a_payer' => $montantAPayer,
                'statut_paiement' => $montantAPayer == 0 ? 'paye' : 'en_attente',
                'zone_collecte' => $validated['zone_collecte'],
                'observations' => $validated['observations']
            ]);
            
            // Mettre à jour le crédit si déduction
            if ($validated['credit_id'] && $montantDeduit > 0) {
                $credit = CreditAgricole::find($validated['credit_id']);
                $credit->montant_restant -= $montantDeduit;
                if ($credit->montant_restant <= 0) {
                    $credit->statut = 'rembourse';
                }
                $credit->save();
            }
            
            DB::commit();
            
            return redirect()->route('admin.cooperatives.operations.dashboard', $cooperative)
                ->with('success', "Collecte de {$validated['quantite_nette']} kg de {$validated['produit']} enregistrée. Montant à payer: " . number_format($montantAPayer, 0, ',', ' ') . " CFA");
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }
    
    /**
     * Crédit pour coopérative
     */
    public function createCredit($id)
    {
        $cooperative = Cooperative::findOrFail($id);
        return view('admin.cooperatives.operations.credit', compact('cooperative'));
    }
    
    /**
     * Octroyer un crédit à une coopérative
     */
    public function storeCredit(Request $request, $id)
    {
        $cooperative = Cooperative::findOrFail($id);
        
        $validated = $request->validate([
            'montant_total' => 'required|numeric|min:1000',
            'type_intrant' => 'required|string',
            'quantite_intrant' => 'required|numeric|min:0',
            'unite_intrant' => 'required|string',
            'taux_interet' => 'required|numeric|min:0|max:100',
            'duree_mois' => 'required|integer|min:1|max:60',
            'date_octroi' => 'required|date',
            'conditions' => 'nullable|string'
        ]);
        
        // Calculer le montant avec intérêts
        $montantAvecInterets = $this->calculerMontantTotal(
            $validated['montant_total'],
            $validated['taux_interet'],
            $validated['duree_mois']
        );
        
        $credit = CreditAgricole::create([
            'code_credit' => 'CRD-COOP-' . str_pad(CreditAgricole::max('id') + 1, 6, '0', STR_PAD_LEFT),
            'cooperative_id' => $cooperative->id,
            'producteur_id' => null, // Pas de producteur individuel
            'montant_total' => $validated['montant_total'],
            'type_intrant' => $validated['type_intrant'],
            'quantite_intrant' => $validated['quantite_intrant'],
            'unite_intrant' => $validated['unite_intrant'],
            'montant_restant' => $montantAvecInterets,
            'taux_interet' => $validated['taux_interet'],
            'duree_mois' => $validated['duree_mois'],
            'date_octroi' => $validated['date_octroi'],
            'date_echeance' => date('Y-m-d', strtotime($validated['date_octroi'] . " + {$validated['duree_mois']} months")),
            'statut' => 'actif',
            'conditions' => $validated['conditions']
        ]);
        
        return redirect()->route('admin.cooperatives.operations.dashboard', $cooperative)
            ->with('success', "Crédit de " . number_format($validated['montant_total'], 0, ',', ' ') . " CFA accordé à {$cooperative->nom}");
    }
    
    /**
     * Calculer le montant total avec intérêts
     */
    private function calculerMontantTotal($montant, $tauxAnnuel, $dureeMois)
    {
        if ($tauxAnnuel == 0) {
            return $montant;
        }
        
        $tauxMensuel = $tauxAnnuel / 12 / 100;
        $mensualite = $montant * $tauxMensuel * pow(1 + $tauxMensuel, $dureeMois) / (pow(1 + $tauxMensuel, $dureeMois) - 1);
        
        return round($mensualite * $dureeMois, 2);
    }
}