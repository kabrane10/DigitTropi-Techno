<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DistributionIntrant;
use App\Models\Producteur;
use App\Models\Cooperative;
use App\Models\Intrant;
use App\Models\CreditAgricole;
use App\Models\IntrantStock;
use App\Models\IntrantMouvement;
use App\Traits\SignatureTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistributionIntrantController extends Controller
{
    use SignatureTrait;
    /**
     * Liste des distributions
     */
    public function index(Request $request)
    {
        $query = DistributionIntrant::with(['beneficiaire', 'intrant', 'credit']);
        
        if ($request->filled('beneficiaire_type')) {
            if ($request->beneficiaire_type == 'producteur') {
                $query->whereNotNull('producteur_id');
            } else {
                $query->whereNotNull('cooperative_id');
            }
        }
        
        if ($request->filled('intrant_id')) {
            $query->where('intrant_id', $request->intrant_id);
        }
        
        if ($request->filled('zone')) {
            $query->where('zone', $request->zone);
        }
        
        $distributions = $query->orderBy('date_distribution', 'desc')->paginate(15);
        $intrants = Intrant::all();
        $zones = ['Centrale', 'Kara', 'Savanes'];
        
        return view('admin.distributions-intrants.index', compact('distributions', 'intrants', 'zones'));
    }
    
    /**
     * Formulaire de création
     */
    public function create()
    {
        $producteurs = Producteur::where('statut', 'actif')->get();
        $cooperatives = Cooperative::where('statut', 'active')->get();
        $intrants = Intrant::where('est_actif', true)->get();
        $credits = CreditAgricole::where('statut', 'actif')
            ->where('montant_restant', '>', 0)
            ->get();
        
        $zones = ['Centrale', 'Kara', 'Savanes'];
        
        // Récupérer les stocks par zone
        $stocks = [];
        foreach ($intrants as $intrant) {
            foreach ($zones as $zone) {
                $stock = IntrantStock::where('intrant_id', $intrant->id)
                    ->where('zone', $zone)
                    ->first();
                $stocks[$intrant->id][$zone] = $stock ? $stock->stock_actuel : 0;
            }
        }
        
        // Paramètres d'URL
        $producteur_id = request()->input('producteur_id');
        $cooperative_id = request()->input('cooperative_id');
        $beneficiaire_type = $producteur_id ? 'producteur' : ($cooperative_id ? 'cooperative' : null);
        
        return view('admin.distributions-intrants.create', compact(
            'producteurs', 'cooperatives', 'intrants', 'credits', 'zones', 'stocks',
            'producteur_id', 'cooperative_id', 'beneficiaire_type'
        ));
    }
    
    /**
     * Enregistrer une distribution
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'beneficiaire_type' => 'required|in:producteur,cooperative',
            'producteur_id' => 'required_if:beneficiaire_type,producteur|nullable|exists:producteurs,id',
            'cooperative_id' => 'required_if:beneficiaire_type,cooperative|nullable|exists:cooperatives,id',
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
            $stock = IntrantStock::where('intrant_id', $validated['intrant_id'])
                ->where('zone', $validated['zone'])
                ->first();
            
            if (!$stock || $stock->stock_actuel < $validated['quantite']) {
                $disponible = $stock ? $stock->stock_actuel : 0;
                return back()->with('error', "Stock insuffisant. Disponible: {$disponible} {$intrant->unite}");
            }
            
            // Déterminer le bénéficiaire
            if ($validated['beneficiaire_type'] === 'producteur') {
                $beneficiaireType = 'App\\Models\\Producteur';
                $beneficiaireId = $validated['producteur_id'];
                $producteurId = $validated['producteur_id'];
                $cooperativeId = null;
            } else {
                $beneficiaireType = 'App\\Models\\Cooperative';
                $beneficiaireId = $validated['cooperative_id'];
                $producteurId = null;
                $cooperativeId = $validated['cooperative_id'];
            }
            
            // Créer la distribution
            $distribution = DistributionIntrant::create([
                'code_distribution' => 'DIST-INT-' . str_pad(DistributionIntrant::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'beneficiaire_type' => $beneficiaireType,
                'beneficiaire_id' => $beneficiaireId,
                'producteur_id' => $producteurId,
                'cooperative_id' => $cooperativeId,
                'intrant_id' => $validated['intrant_id'],
                'credit_id' => $validated['credit_id'] ?? null,
                'quantite' => $validated['quantite'],
                'prix_unitaire' => $prixUnitaire,
                'montant_total' => $montantTotal,
                'date_distribution' => $validated['date_distribution'],
                'zone' => $validated['zone'],
                'notes' => $validated['notes'] ?? null
            ]);

              // ✅ SAUVEGARDER LES SIGNATURES
              $this->saveSignatures($request, 'distribution_intrant', $distribution);
            
            // Mettre à jour le stock
            $stock->stock_actuel -= $validated['quantite'];
            $stock->save();
            
            // Enregistrer le mouvement
            IntrantMouvement::create([
                'intrant_stock_id' => $stock->id,
                'type' => 'sortie',
                'quantite' => $validated['quantite'],
                'motif' => 'Distribution à ' . ($validated['beneficiaire_type'] === 'producteur' ? 'producteur' : 'coopérative'),
                'reference' => $distribution->code_distribution,
                'user_id' => auth()->guard('admin')->id(),
                'notes' => $distribution->notes
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
            
            $beneficiaireNom = $validated['beneficiaire_type'] === 'producteur' 
                ? Producteur::find($validated['producteur_id'])->nom_complet 
                : Cooperative::find($validated['cooperative_id'])->nom;
            
            return redirect()->route('admin.distributions-intrants.index')
                ->with('success', "Distribution de {$validated['quantite']} {$intrant->unite} de {$intrant->nom} à {$beneficiaireNom} effectuée avec succès");
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la distribution: ' . $e->getMessage());
        }
    }

    /**
 * Formulaire d'édition d'une distribution d'intrants
 */
   public function edit($id)
   {
    $distribution = DistributionIntrant::with(['beneficiaire', 'intrant', 'credit'])->findOrFail($id);
    
    // Récupérer les données pour les sélecteurs
    $producteurs = Producteur::where('statut', 'actif')->get();
    $cooperatives = Cooperative::where('statut', 'active')->get();
    $intrants = Intrant::where('est_actif', true)->get();
    $credits = CreditAgricole::where('statut', 'actif')
        ->where('montant_restant', '>', 0)
        ->get();
    
    $zones = ['Centrale', 'Kara', 'Savanes'];
    
    // Récupérer les stocks par zone
    $stocks = [];
    foreach ($intrants as $intrant) {
        foreach ($zones as $zone) {
            $stock = IntrantStock::where('intrant_id', $intrant->id)
                ->where('zone', $zone)
                ->first();
            $stocks[$intrant->id][$zone] = $stock ? $stock->stock_actuel : 0;
        }
    }
    
    // Déterminer le type de bénéficiaire actuel
    $beneficiaire_type = $distribution->cooperative_id ? 'cooperative' : 'producteur';
    
    return view('admin.distributions-intrants.edit', compact(
        'distribution', 'producteurs', 'cooperatives', 'intrants', 'credits', 
        'zones', 'stocks', 'beneficiaire_type'
    ));
   }


    /**
 * Mettre à jour une distribution
 */
    public function update(Request $request, $id)
    {
    $distribution = DistributionIntrant::findOrFail($id);
    
    $validated = $request->validate([
        'date_distribution' => 'required|date',
        'quantite' => 'required|numeric|min:0.01',
        'credit_id' => 'nullable|exists:credits_agricoles,id',
        'notes' => 'nullable|string'
    ]);
    
    DB::beginTransaction();
    try {
        $ancienneQuantite = $distribution->quantite;
        $nouvelleQuantite = $validated['quantite'];
        $differenceQuantite = $nouvelleQuantite - $ancienneQuantite;
        
        $intrant = $distribution->intrant;
        $prixUnitaire = $intrant->prix_unitaire;
        $nouveauMontantTotal = $nouvelleQuantite * $prixUnitaire;
        
        // Mettre à jour le stock
        $stock = IntrantStock::where('intrant_id', $distribution->intrant_id)
            ->where('zone', $distribution->zone)
            ->first();
        
        if ($stock) {
            $nouveauStock = $stock->stock_actuel - $differenceQuantite;
            if ($nouveauStock < 0) {
                return back()->with('error', 'Stock insuffisant pour cette modification');
            }
            $stock->stock_actuel = $nouveauStock;
            $stock->save();
        }
        
        // Mettre à jour le crédit si associé
        if ($distribution->credit_id) {
            $credit = CreditAgricole::find($distribution->credit_id);
            if ($credit) {
                $differenceMontant = $nouveauMontantTotal - $distribution->montant_total;
                $credit->montant_restant -= $differenceMontant;
                if ($credit->montant_restant < 0) {
                    return back()->with('error', 'Modification impossible : dépassement du crédit disponible');
                }
                $credit->save();
            }
        }
        
        // Mettre à jour la distribution
        $distribution->update([
            'date_distribution' => $validated['date_distribution'],
            'quantite' => $nouvelleQuantite,
            'prix_unitaire' => $prixUnitaire,
            'montant_total' => $nouveauMontantTotal,
            'credit_id' => $validated['credit_id'] ?? $distribution->credit_id,
            'notes' => $validated['notes'] ?? null
        ]);
        
        DB::commit();
        
        return redirect()->route('admin.distributions-intrants.index')
            ->with('success', 'Distribution mise à jour avec succès');
            
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
    }
    }
    
    /**
     * Afficher les détails
     */
    public function show($id)
    {
        $distribution = DistributionIntrant::with(['beneficiaire', 'intrant', 'credit'])->findOrFail($id);
        
        // ✅ CONFIGURER LES SIGNATURES
        $signatureData = $this->configureSignatures('distribution_intrant', $distribution);
        
        return view('admin.distributions-intrants.show', array_merge([
            'distribution'
        ], $signatureData));
    }
    
    /**
     * Imprimer la fiche
     */
    public function print($id)
    {
        $distribution = DistributionIntrant::with(['beneficiaire', 'intrant', 'credit'])->findOrFail($id);
        return view('admin.distributions-intrants.print', compact('distribution'));
    }
    
    /**
     * Supprimer une distribution
     */
    public function destroy($id)
    {
        $distribution = DistributionIntrant::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Restaurer le stock
            $stock = IntrantStock::where('intrant_id', $distribution->intrant_id)
                ->where('zone', $distribution->zone)
                ->first();
            
            if ($stock) {
                $stock->stock_actuel += $distribution->quantite;
                $stock->save();
            }
            
            // Restaurer le crédit si nécessaire
            if ($distribution->credit_id) {
                $credit = CreditAgricole::find($distribution->credit_id);
                if ($credit) {
                    $credit->montant_restant += $distribution->montant_total;
                    $credit->save();
                }
            }
            
            $distribution->delete();
            DB::commit();
            
            return redirect()->route('admin.distributions-intrants.index')
                ->with('success', 'Distribution supprimée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la suppression');
        }
    }
}