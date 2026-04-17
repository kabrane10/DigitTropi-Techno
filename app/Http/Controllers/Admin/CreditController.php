<?php

namespace App\Http\Controllers\Admin;
use App\Traits\NotifiableTrait;
use App\Http\Controllers\Controller;
use App\Models\CreditAgricole;
use App\Models\Producteur;
use App\Models\Cooperative;
use App\Models\Remboursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditController extends Controller
{
    use NotifiableTrait;
    /**
     * Calculer la mensualité avec intérêts
     */
    private function calculerMensualite($montant, $tauxAnnuel, $dureeMois)
    {
        if ($tauxAnnuel == 0) {
            return $montant / $dureeMois;
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
     * Calculer le tableau d\'amortissement
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
        
        $credits = $query->orderBy('created_at', 'desc')->paginate(15);
        $producteurs = Producteur::where('statut', 'actif')->get();
        
        return view('admin.credits.index', compact('credits', 'producteurs'));
    }
    
    /**
     * Formulaire de création
     */
    public function create()
    {
        $producteurs = Producteur::where('statut', 'actif')->get();
        $cooperatives = Cooperative::where('statut', 'active')->get();
        return view('admin.credits.create', compact('producteurs', 'cooperatives'));
    }
    
    /**
     * Enregistrer un nouveau crédit
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'producteur_id' => 'required|exists:producteurs,id',
            'cooperative_id' => 'required|exists:cooperatives,id',
            'montant_total' => 'required|numeric|min:1000',
            'taux_interet' => 'required|numeric|min:0|max:100',
            'duree_mois' => 'required|integer|min:1|max:60',
            'date_octroi' => 'required|date',
            'conditions' => 'nullable|string'
        ]);
        
        // Calculer le montant total avec intérêts
        $montantAvecInterets = $this->calculerMontantTotal(
            $validated['montant_total'],
            $validated['taux_interet'],
            $validated['duree_mois']
        );
        
        $validated['code_credit'] = 'CRD-' . str_pad(CreditAgricole::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validated['montant_restant'] = $montantAvecInterets;
        $validated['date_echeance'] = date('Y-m-d', strtotime($validated['date_octroi'] . ' + ' . $validated['duree_mois'] . ' months'));
        $validated['statut'] = 'actif';
        
        // Sauvegarder également le montant sans intérêts pour référence
        $validated['montant_sans_interets'] = $validated['montant_total'];
        $validated['montant_interets'] = $montantAvecInterets - $validated['montant_total'];
        
        CreditAgricole::create($validated);
        
        return redirect()->route('admin.credits.index')
            ->with('success', 'Crédit agricole accordé avec succès');
    }
    
    /**
     * Afficher les détails d\'un crédit
     */
    public function show($id)
    {
        $credit = CreditAgricole::with(['producteur', 'cooperative', 'remboursements'])->findOrFail($id);
        
        // Calculer les montants réels avec intérêts
        $montantAvecInterets = $this->calculerMontantTotal(
            $credit->montant_total,
            $credit->taux_interet,
            $credit->duree_mois
        );
        
        $montantRembourse = $credit->remboursements->sum('montant');
        $resteAPayer = $montantAvecInterets - $montantRembourse;
        $tauxRemboursement = $montantAvecInterets > 0 ? ($montantRembourse / $montantAvecInterets) * 100 : 0;
        
        // Calculer le tableau d\'amortissement
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
        
        return view('admin.credits.show', compact(
            'credit', 
            'montantRembourse', 
            'resteAPayer', 
            'tauxRemboursement',
            'amortissement',
            'mensualite',
            'montantAvecInterets'
        ));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $credit = CreditAgricole::findOrFail($id);
        $producteurs = Producteur::where('statut', 'actif')->get();
        $cooperatives = Cooperative::where('statut', 'active')->get();
        return view('admin.credits.edit', compact('credit', 'producteurs', 'cooperatives'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $credit = CreditAgricole::findOrFail($id);

        $validated = $request->validate([
            'producteur_id' => 'required|exists:producteurs,id',
            'cooperative_id' => 'required|exists:cooperatives,id',
            'montant_total' => 'required|numeric|min:1000',
            'taux_interet' => 'required|numeric|min:0|max:100',
            'duree_mois' => 'required|integer|min:1|max:60',
            'date_octroi' => 'required|date',
            'conditions' => 'nullable|string',
            'statut' => 'required|in:actif,rembourse,annule,retard',
        ]);

        // Recalculate amounts if necessary
        $montantAvecInterets = $this->calculerMontantTotal(
            $validated['montant_total'],
            $validated['taux_interet'],
            $validated['duree_mois']
        );
        
        $montantRembourse = $credit->remboursements->sum('montant');
        $validated['montant_restant'] = $montantAvecInterets - $montantRembourse;
        $validated['date_echeance'] = date('Y-m-d', strtotime($validated['date_octroi'] . ' + ' . $validated['duree_mois'] . ' months'));
        $validated['montant_sans_interets'] = $validated['montant_total'];
        $validated['montant_interets'] = $montantAvecInterets - $validated['montant_total'];
        
        //  Si le montant restant est 0, statut = rembourse
        if ($validated['montant_restant'] <= 0) {
            $validated['statut'] = 'rembourse';
        }

        $credit->update($validated);

        return redirect()->route('admin.credits.index')->with('success', 'Crédit mis à jour avec succès.');
    }

  
    /**
 * Supprimer un crédit
 */
public function destroy($id)
{
    $credit = CreditAgricole::findOrFail($id);
    
    // 🔴 CORRECTION : Vérifier le montant restant, pas seulement le statut
    $montantTotalAvecInterets = $this->calculerMontantTotal(
        $credit->montant_total,
        $credit->taux_interet,
        $credit->duree_mois
    );
    $montantRembourse = $credit->remboursements->sum('montant');
    $resteAPayer = $montantTotalAvecInterets - $montantRembourse;
    
    // Si le montant restant est 0, forcer le statut à rembourse
    if ($resteAPayer <= 0 && $credit->statut != 'rembourse') {
        $credit->statut = 'rembourse';
        $credit->save();
    }
    
    // VÉRIFICATION : Le crédit est-il totalement remboursé ?
    if ($resteAPayer > 0) {
        return redirect()->route('admin.credits.index')
            ->with('error', sprintf(
                '❌ Suppression impossible ! Ce crédit n\'est pas encore entièrement remboursé.<br><br>
                💰 Reste à payer : <strong>%s CFA</strong><br><br>
                💡 Veuillez d\'abord compléter le remboursement.',
                number_format($resteAPayer, 0, ',', ' ')
            ));
    }
    
    // Vérifier les autres dépendances
    $hasRemboursements = $credit->remboursements()->count() > 0;
    $hasCollectes = $credit->collectes()->count() > 0;
    $hasDistributions = $credit->distributionsSemences()->count() > 0;
    
    if ($hasRemboursements || $hasCollectes || $hasDistributions) {
        $dependances = [];
        if ($hasRemboursements) $dependances[] = $credit->remboursements()->count() . " remboursement(s)";
        if ($hasCollectes) $dependances[] = $credit->collectes()->count() . " collecte(s)";
        if ($hasDistributions) $dependances[] = $credit->distributionsSemences()->count() . " distribution(s)";
        
        return redirect()->route('admin.credits.index')
            ->with('error', '❌ Suppression impossible ! Ce crédit est lié à : ' . implode(', ', $dependances));
    }
    
    // Suppression
    try {
        $credit->delete();
        return redirect()->route('admin.credits.index')
            ->with('success', '✅ Crédit supprimé avec succès.');
    } catch (\Exception $e) {
        return redirect()->route('admin.credits.index')
            ->with('error', '❌ Erreur technique lors de la suppression.');
    }
}
   /**
 * Enregistrer un remboursement
 */
public function remboursement(Request $request, $id)
{
    $credit = CreditAgricole::findOrFail($id);
    
    // Recalculer le montant total avec intérêts
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
        // Créer le remboursement
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
        
        // Mettre à jour le montant restant
        $nouveauMontantRestant = $resteAPayer - $validated['montant'];
        $credit->montant_restant = max(0, $nouveauMontantRestant);
        
        // 🔴 CORRECTION IMPORTANTE : Mettre à jour le statut
        if ($credit->montant_restant <= 0) {
            $credit->statut = 'rembourse';
        } elseif ($credit->montant_restant < $montantTotalAvecInterets && $credit->statut == 'actif') {
            $credit->statut = 'actif'; // reste actif
        }
        
        $credit->save();
        DB::commit();
        
        $message = $credit->statut == 'rembourse' 
            ? '✅ Crédit entièrement remboursé !' 
            : '✅ Remboursement enregistré avec succès. Reste à payer : ' . number_format($credit->montant_restant, 0, ',', ' ') . ' CFA';
        
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
    
    // Recalculer les montants avec intérêts
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
