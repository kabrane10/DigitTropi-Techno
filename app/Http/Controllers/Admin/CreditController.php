<?php

namespace App\Http\Controllers\Admin;

use App\Traits\NotifiableTrait;
use App\Http\Controllers\Controller;
use App\Models\CreditAgricole;
use App\Models\Producteur;
use App\Models\Cooperative;
use App\Traits\SignatureTrait;
use App\Models\Remboursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditController extends Controller
{
    use NotifiableTrait;
    use SignatureTrait;

    /**
     * Calculer la mensualité avec intérêts
     */
    private function calculerMensualite($montant, $tauxAnnuel, $dureeMois)
    {
        if ($tauxAnnuel == 0 || $dureeMois == 0) {
            return $dureeMois > 0 ? $montant / $dureeMois : 0;
        }
        
        $tauxMensuel = $tauxAnnuel / 12 / 100;
        $mensualite = $montant * $tauxMensuel * pow(1 + $tauxMensuel, $dureeMois) / (pow(1 + $tauxMensuel, $dureeMois) - 1);
        
        return round($mensualite, 2);
    }
    
    /**
     * Calculer le montant total avec intérêts
     */
    private function calculerMontantTotal($montant, $tauxAnnuel, $dureeMois)
    {
        if ($tauxAnnuel == 0) {
            return $montant;
        }
        
        $mensualite = $this->calculerMensualite($montant, $tauxAnnuel, $dureeMois);
        return round($mensualite * $dureeMois, 2);
    }
    
    /**
     * Calculer le tableau d'amortissement
     */
    private function calculerAmortissement($montant, $tauxAnnuel, $dureeMois)
    {
        $amortissement = [];
        $tauxMensuel = $tauxAnnuel / 12 / 100;
        $mensualite = $this->calculerMensualite($montant, $tauxAnnuel, $dureeMois);
        $capitalRestant = $montant;
        
        for ($i = 1; $i <= $dureeMois; $i++) {
            $interets = round($capitalRestant * $tauxMensuel, 2);
            $capital = round($mensualite - $interets, 2);
            $capitalRestant -= $capital;
            
            $amortissement[] = [
                'mois' => $i,
                'date' => now()->addMonths($i)->format('d/m/Y'),
                'mensualite' => $mensualite,
                'interets' => $interets,
                'capital' => $capital,
                'capital_restant' => max(0, round($capitalRestant, 2))
            ];
        }
        
        return $amortissement;
    }
    
    /**
     * Afficher la liste des crédits
     */
    public function index(Request $request)
    {
        $query = CreditAgricole::with(['producteur', 'cooperative']);
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('producteur_id')) {
            $query->where('producteur_id', $request->producteur_id);
        }
        if ($request->filled('beneficiaire_type')) {
            if ($request->beneficiaire_type == 'producteur') {
                $query->whereNotNull('producteur_id');
            } else {
                $query->whereNotNull('cooperative_id');
            }
        }
        
        $credits = $query->orderBy('created_at', 'desc')->paginate(15);
        $producteurs = Producteur::where('statut', 'actif')->get();
        $cooperatives = Cooperative::where('statut', 'active')->get();
        
        return view('admin.credits.index', compact('credits', 'producteurs', 'cooperatives'));
    }
    
    /**
     * Formulaire de création
     */
    public function create()
    {
        $producteurs = Producteur::where('statut', 'actif')->get();
        $cooperatives = Cooperative::where('statut', 'active')->get();

        // Récupérer les paramètres d'URL
        $cooperative_id = request()->input('cooperative_id');
        $producteur_id = request()->input('producteur_id');
        $montant_total = request()->input('montant_total');
        $estimation_id = request()->input('estimation_id');
        $type_intrant = request()->input('type_intrant', 'semences');
        $quantite_intrant = request()->input('quantite_intrant');
        $unite_intrant = request()->input('unite_intrant', 'kg');
        $taux_interet = request()->input('taux_interet', 5);
        $duree_mois = request()->input('duree_mois', 12);
    
        return view('admin.credits.create', compact(
            'producteurs', 
            'cooperatives', 
            'cooperative_id',  
            'producteur_id',   
            'montant_total',
            'estimation_id',
            'type_intrant',
            'quantite_intrant',
            'unite_intrant',
            'taux_interet',
            'duree_mois'
        ));
    }
    
    /**
     * Enregistrer un nouveau crédit (CORRIGÉ)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'beneficiaire_type' => 'required|in:producteur,cooperative',
            'producteur_id' => 'required_if:beneficiaire_type,producteur|nullable|exists:producteurs,id',
            'cooperative_id' => 'required_if:beneficiaire_type,cooperative|nullable|exists:cooperatives,id',
            'montant_total' => 'required|numeric|min:1000',
            'type_intrant' => 'required|string|max:255',  
            'quantite_intrant' => 'required|numeric|min:0',  
            'unite_intrant' => 'required|string|max:50',  
            'taux_interet' => 'required|numeric|min:0|max:100',
            'duree_mois' => 'required|integer|min:1|max:60',
            'date_octroi' => 'required|date',
            'conditions' => 'nullable|string'
        ]);

        // ✅ CORRIGÉ : Déterminer le bénéficiaire SANS écraser les variables
        if ($validated['beneficiaire_type'] === 'producteur') {
            $producteurId = $validated['producteur_id'];
            $cooperativeId = null;
            $beneficiaireType = 'App\\Models\\Producteur';
            $beneficiaireId = $validated['producteur_id'];
        } else {
            $producteurId = null;
            $cooperativeId = $validated['cooperative_id'];
            $beneficiaireType = 'App\\Models\\Cooperative';
            $beneficiaireId = $validated['cooperative_id'];
        }
        
        // Calculer le montant total avec intérêts
        $montantAvecInterets = $this->calculerMontantTotal(
            $validated['montant_total'],
            $validated['taux_interet'],
            $validated['duree_mois']
        );
        
        DB::beginTransaction();
        try {
            $credit = CreditAgricole::create([
                'code_credit' => 'CRD-' . str_pad(CreditAgricole::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'producteur_id' => $producteurId,
                'cooperative_id' => $cooperativeId,
                'beneficiaire_type' => $beneficiaireType,
                'beneficiaire_id' => $beneficiaireId,
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
                'conditions' => $validated['conditions'] ?? null,
                'montant_sans_interets' => $validated['montant_total'],
                'montant_interets' => $montantAvecInterets - $validated['montant_total']
            ]);
            // ✅ SAUVEGARDER LES SIGNATURES APRÈS CRÉATION
            $this->saveSignatures($request, 'credit', $credit);

            DB::commit();

            return redirect()->route('admin.credits.index')
                ->with('success', 'Crédit agricole accordé avec succès');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la création du crédit: ' . $e->getMessage());
        }
    }
    
    /**
     * Afficher les détails d'un crédit
     */
    public function show($id)
    {
        $credit = CreditAgricole::with(['producteur', 'cooperative', 'remboursements'])
            ->findOrFail($id);
        
        // Calculer les montants réels avec intérêts
        $montantAvecInterets = $this->calculerMontantTotal(
            $credit->montant_total,
            $credit->taux_interet,
            $credit->duree_mois
        );
        
        $montantRembourse = $credit->remboursements->sum('montant');
        $resteAPayer = $montantAvecInterets - $montantRembourse;
        $tauxRemboursement = $montantAvecInterets > 0 ? ($montantRembourse / $montantAvecInterets) * 100 : 0;
        
        // Calculer le tableau d'amortissement
        $amortissement = $this->calculerAmortissement(
            $credit->montant_total,
            $credit->taux_interet,
            $credit->duree_mois
        );
        
        // Calculer la mensualité
        $mensualite = $this->calculerMensualite(
            $credit->montant_total,
            $credit->taux_interet,
            $credit->duree_mois
        );
        
        // ✅ CONFIGURER LES SIGNATURES
        $signatureData = $this->configureSignatures('credit', $credit);

        $data = array_merge([
            'credit' => $credit,
            'montantAvecInterets' => $montantAvecInterets,
            'montantRembourse' => $montantRembourse,
            'resteAPayer' => $resteAPayer,
            'tauxRemboursement' => $tauxRemboursement,
            'mensualite' => $mensualite,
            'amortissement' => $amortissement,
        ], $signatureData);
        
        return view('admin.credits.show', $data);
      
    }
    
    /**
     * Formulaire d'édition (CORRIGÉ)
     */
    public function edit($id)
    {
        $credit = CreditAgricole::with(['producteur', 'cooperative'])->findOrFail($id);
        $producteurs = Producteur::where('statut', 'actif')->get();
        $cooperatives = Cooperative::where('statut', 'active')->get();
        
        return view('admin.credits.edit', compact('credit', 'producteurs', 'cooperatives'));
    }

    /**
     * Mettre à jour un crédit (CORRIGÉ)
     */
    public function update(Request $request, $id)
    {
        $credit = CreditAgricole::findOrFail($id);

        $validated = $request->validate([
            'beneficiaire_type' => 'required|in:producteur,cooperative',
            'producteur_id' => 'required_if:beneficiaire_type,producteur|nullable|exists:producteurs,id',
            'cooperative_id' => 'required_if:beneficiaire_type,cooperative|nullable|exists:cooperatives,id',
            'montant_total' => 'required|numeric|min:1000',
            'type_intrant' => 'required|string|max:255',  
            'quantite_intrant' => 'required|numeric|min:0',  
            'unite_intrant' => 'required|string|max:50',  
            'taux_interet' => 'required|numeric|min:0|max:100',
            'duree_mois' => 'required|integer|min:1|max:60',
            'date_octroi' => 'required|date',
            'conditions' => 'nullable|string',
            'statut' => 'required|in:actif,rembourse,annule,retard',
        ]);

        // ✅ CORRIGÉ : Déterminer le bénéficiaire
        if ($validated['beneficiaire_type'] === 'producteur') {
            $producteurId = $validated['producteur_id'];
            $cooperativeId = null;
            $beneficiaireType = 'App\\Models\\Producteur';
            $beneficiaireId = $validated['producteur_id'];
        } else {
            $producteurId = null;
            $cooperativeId = $validated['cooperative_id'];
            $beneficiaireType = 'App\\Models\\Cooperative';
            $beneficiaireId = $validated['cooperative_id'];
        }

        // Recalculer les montants
        $montantAvecInterets = $this->calculerMontantTotal(
            $validated['montant_total'],
            $validated['taux_interet'],
            $validated['duree_mois']
        );
        
        $montantRembourse = $credit->remboursements->sum('montant');
        $montantRestant = $montantAvecInterets - $montantRembourse;
        
        $updateData = [
            'producteur_id' => $producteurId,
            'cooperative_id' => $cooperativeId,
            'beneficiaire_type' => $beneficiaireType,
            'beneficiaire_id' => $beneficiaireId,
            'montant_total' => $validated['montant_total'],
            'type_intrant' => $validated['type_intrant'],
            'quantite_intrant' => $validated['quantite_intrant'],
            'unite_intrant' => $validated['unite_intrant'],
            'montant_restant' => max(0, $montantRestant),
            'taux_interet' => $validated['taux_interet'],
            'duree_mois' => $validated['duree_mois'],
            'date_octroi' => $validated['date_octroi'],
            'date_echeance' => date('Y-m-d', strtotime($validated['date_octroi'] . ' + ' . $validated['duree_mois'] . ' months')),
            'conditions' => $validated['conditions'] ?? null,
            'statut' => $montantRestant <= 0 ? 'rembourse' : $validated['statut'],
            'montant_sans_interets' => $validated['montant_total'],
            'montant_interets' => $montantAvecInterets - $validated['montant_total']
        ];

        $credit->update($updateData);
        
        // ✅ SAUVEGARDER LES SIGNATURES SI NOUVELLES
        $this->saveSignatures($request, 'credit', $credit);

        return redirect()->route('admin.credits.index')
            ->with('success', 'Crédit mis à jour avec succès.');
    }

    /**
     * Supprimer un crédit
     */
    public function destroy($id)
    {
        $credit = CreditAgricole::findOrFail($id);
        
        $montantTotalAvecInterets = $this->calculerMontantTotal(
            $credit->montant_total,
            $credit->taux_interet,
            $credit->duree_mois
        );
        $montantRembourse = $credit->remboursements->sum('montant');
        $resteAPayer = $montantTotalAvecInterets - $montantRembourse;
        
        if ($resteAPayer <= 0 && $credit->statut != 'rembourse') {
            $credit->statut = 'rembourse';
            $credit->save();
        }
        
        if ($resteAPayer > 0) {
            return redirect()->route('admin.credits.index')
                ->with('error', sprintf(
                    'Suppression impossible ! Ce crédit n\'est pas encore entièrement remboursé.<br><br>
                     Reste à payer : <strong>%s CFA</strong>',
                    number_format($resteAPayer, 0, ',', ' ')
                ));
        }
        
        $hasRemboursements = $credit->remboursements()->count() > 0;
        $hasCollectes = $credit->collectes()->count() > 0;
        $hasDistributions = $credit->distributionsSemences()->count() > 0;
        
        if ($hasRemboursements || $hasCollectes || $hasDistributions) {
            $dependances = [];
            if ($hasRemboursements) $dependances[] = $credit->remboursements()->count() . " remboursement(s)";
            if ($hasCollectes) $dependances[] = $credit->collectes()->count() . " collecte(s)";
            if ($hasDistributions) $dependances[] = $credit->distributionsSemences()->count() . " distribution(s)";
            
            return redirect()->route('admin.credits.index')
                ->with('error', 'Suppression impossible ! Ce crédit est lié à : ' . implode(', ', $dependances));
        }
        
        try {
            $credit->delete();
            return redirect()->route('admin.credits.index')
                ->with('success', 'Crédit supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('admin.credits.index')
                ->with('error', 'Erreur technique lors de la suppression.');
        }
    }
    
    /**
     * Enregistrer un remboursement
     */
    public function remboursement(Request $request, $id)
    {
        $credit = CreditAgricole::findOrFail($id);
        
        $montantTotalAvecInterets = $this->calculerMontantTotal(
            $credit->montant_total,
            $credit->taux_interet,
            $credit->duree_mois
        );
        
        $montantDejaRembourse = $credit->remboursements->sum('montant');
        $resteAPayer = $montantTotalAvecInterets - $montantDejaRembourse;
        
        $validated = $request->validate([
            'montant' => 'required|numeric|min:100|max:' . $resteAPayer,
            'mode_paiement' => 'required|in:especes,prelevement_sur_collecte,virement,mobile_money',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        try {
            Remboursement::create([
                'code_remboursement' => 'RMB-' . str_pad(Remboursement::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'credit_id' => $credit->id,
                'date_remboursement' => now(),
                'montant' => $validated['montant'],
                'type' => $validated['montant'] == $resteAPayer ? 'total' : 'mensuel',
                'mode_paiement' => $validated['mode_paiement'],
                'reference' => $validated['reference'] ?? null,
                'notes' => $validated['notes'] ?? null
            ]);
            
            $nouveauMontantRestant = $resteAPayer - $validated['montant'];
            $credit->montant_restant = max(0, $nouveauMontantRestant);
            
            if ($credit->montant_restant <= 0) {
                $credit->statut = 'rembourse';
            }
            
            $credit->save();
            DB::commit();
            
            $message = $credit->statut == 'rembourse' 
                ? 'Crédit entièrement remboursé !' 
                : 'Remboursement enregistré avec succès. Reste à payer : ' . number_format($credit->montant_restant, 0, ',', ' ') . ' CFA';
            
            return redirect()->route('admin.credits.show', $credit)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'enregistrement du remboursement : ' . $e->getMessage());
        }
    }
    
    /**
     * Dashboard des crédits
     */
    public function dashboard()
    {
        $credits = CreditAgricole::all();
        $totalAccorde = 0;
        $totalRembourse = 0;
        
        foreach ($credits as $credit) {
            $montantAvecInterets = $this->calculerMontantTotal(
                $credit->montant_total,
                $credit->taux_interet,
                $credit->duree_mois
            );
            $totalAccorde += $montantAvecInterets;
            $totalRembourse += $credit->remboursements->sum('montant');
        }
        
        $stats = [
            'total_credits' => $totalAccorde,
            'credits_actifs' => CreditAgricole::where('statut', 'actif')->sum('montant_restant'),
            'nb_credits' => CreditAgricole::count(),
            'taux_remboursement' => $totalAccorde > 0 ? ($totalRembourse / $totalAccorde) * 100 : 0,
        ];
        
        $credits_par_statut = CreditAgricole::select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->get();
        
        return view('admin.credits.dashboard', compact('stats', 'credits_par_statut'));
    }

    /**
     * Imprimer la fiche de crédit
     */
    public function print($id)
    {
        $credit = CreditAgricole::with(['producteur', 'cooperative', 'remboursements'])->findOrFail($id);
        
        $montantAvecInterets = $this->calculerMontantTotal(
            $credit->montant_total,
            $credit->taux_interet,
            $credit->duree_mois
        );
        
        $montantRembourse = $credit->remboursements->sum('montant');
        $resteAPayer = $montantAvecInterets - $montantRembourse;
        $tauxRemboursement = $montantAvecInterets > 0 ? ($montantRembourse / $montantAvecInterets) * 100 : 0;
        $mensualite = $this->calculerMensualite(
            $credit->montant_total,
            $credit->taux_interet,
            $credit->duree_mois
        );
        $amortissement = $this->calculerAmortissement(
            $credit->montant_total,
            $credit->taux_interet,
            $credit->duree_mois
        );
        
        return view('admin.credits.print', compact(
            'credit', 
            'montantRembourse', 
            'resteAPayer', 
            'tauxRemboursement',
            'mensualite',
            'montantAvecInterets',
            'amortissement'
        ));
    }
    
    /**
     * Corriger tous les statuts des crédits
     */
    public function fixAllStatus()
    {
        $credits = CreditAgricole::all();
        $count = 0;
        
        foreach ($credits as $credit) {
            $montantTotalAvecInterets = $this->calculerMontantTotal(
                $credit->montant_total,
                $credit->taux_interet,
                $credit->duree_mois
            );
            $montantRembourse = $credit->remboursements->sum('montant');
            $resteAPayer = $montantTotalAvecInterets - $montantRembourse;
            
            if ($resteAPayer <= 0 && $credit->statut != 'rembourse') {
                $credit->statut = 'rembourse';
                $credit->save();
                $count++;
            }
        }
        
        return redirect()->route('admin.credits.index')
            ->with('success', "$count crédit(s) marqué(s) comme remboursé(s)");
    }
}