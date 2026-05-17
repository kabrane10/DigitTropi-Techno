<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collecte;
use App\Models\Producteur;
use App\Models\Cooperative;
use App\Models\CreditAgricole;
use App\Models\Stock;
use App\Traits\SignatureTrait; 
use App\Traits\DatabaseCompatibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollecteController extends Controller
{
    use DatabaseCompatibility;
    use SignatureTrait;
    /**
     * Afficher la liste des collectes
     */
    public function index(Request $request)
    {
        $query = Collecte::with(['producteur', 'cooperative', 'credit']);

        if ($request->filled('produit')) {
            $query->where('produit', $request->produit);
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('date_collecte', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_collecte', '<=', $request->date_fin);
        }
        if ($request->filled('beneficiaire_type')) {
            if ($request->beneficiaire_type == 'producteur') {
                $query->whereNotNull('producteur_id');
            } else {
                $query->whereNotNull('cooperative_id');
            }
        }

        $collectes = $query->orderBy('date_collecte', 'desc')->paginate(15);
        $produits = Collecte::distinct()->pluck('produit');
        $producteurs = Producteur::where('statut', 'actif')->get();
        $cooperatives = Cooperative::where('statut', 'active')->get();
        
        return view('admin.collectes.index', compact('collectes', 'produits', 'producteurs', 'cooperatives'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $producteurs = Producteur::where('statut', 'actif')->get();
        $cooperatives = Cooperative::where('statut', 'active')->get(); 
        $credits = CreditAgricole::where('statut', 'actif')
            ->where('montant_restant', '>', 0)
            ->get();
        
         // Récupérer les paramètres d'URL
        $cooperative_id = request()->input('cooperative_id');
        $producteur_id = request()->input('producteur_id');
    
        return view('admin.collectes.create', compact(
        'producteurs', 
        'cooperatives', 
        'credits',
        'cooperative_id',  
        'producteur_id'   
        ));
    }

    /**
    * /Mettre à jour une collecte
    */
   public function update(Request $request, $id)
   {
       $collecte = Collecte::findOrFail($id);
       
       $validated = $request->validate([
           'date_collecte' => 'required|date',
           'produit' => 'required|string',
           'quantite_brute' => 'required|numeric|min:0',
           'quantite_nette' => 'required|numeric|min:0',
           'prix_unitaire' => 'required|numeric|min:0',
           'zone_collecte' => 'required|string',
           'statut_paiement' => 'required|in:en_attente,partiel,paye',
           'montant_deduict' => 'nullable|numeric|min:0',
           'observations' => 'nullable|string'
       ]);
       
       $validated['montant_total'] = $validated['quantite_nette'] * $validated['prix_unitaire'];
       $validated['montant_a_payer'] = $validated['montant_total'] - ($validated['montant_deduict'] ?? 0);
       
       $collecte->update($validated);
       
       return redirect()->route('admin.collectes.index')
           ->with('success', 'Collecte mise à jour avec succès');
   }

    /**
     * Enregistrer une nouvelle collecte
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'beneficiaire_type' => 'required|in:producteur,cooperative',
            'producteur_id' => 'required_if:beneficiaire_type,producteur|nullable|exists:producteurs,id',
            'cooperative_id' => 'required_if:beneficiaire_type,cooperative|nullable|exists:cooperatives,id',
            'date_collecte' => 'required|date',
            'produit' => 'required|string',
            'quantite_brute' => 'required|numeric|min:0',
            'quantite_nette' => 'required|numeric|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
            'credit_id' => 'nullable|exists:credits_agricoles,id',
            'montant_deduict' => 'nullable|numeric|min:0',
            'zone_collecte' => 'required|string',
            'observations' => 'nullable|string'
        ]);

        DB::transaction(function () use ($validated) {
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

            $montantTotal = $validated['quantite_nette'] * $validated['prix_unitaire'];
            $montantDeduit = $validated['montant_deduict'] ?? 0;
            $montantAPayer = $montantTotal - $montantDeduit;

            $collecte = Collecte::create([
                'code_collecte' => 'COL-' . str_pad(Collecte::max('id') + 1, 6, '0', STR_PAD_LEFT),
                'producteur_id' => $producteurId,
                'cooperative_id' => $cooperativeId,
                'beneficiaire_type' => $beneficiaireType,
                'beneficiaire_id' => $beneficiaireId,
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

             // ✅ SAUVEGARDER LES SIGNATURES
             $this->saveSignatures($request, 'collecte', $collecte);

            // Mettre à jour le stock
            $this->mettreAJourStock($validated['produit'], $validated['quantite_nette'], $validated['zone_collecte']);

            // Si déduction sur crédit
            if (!empty($validated['credit_id']) && $montantDeduit > 0) {
                $credit = CreditAgricole::find($validated['credit_id']);
                $credit->montant_restant -= $montantDeduit;
                if ($credit->montant_restant <= 0) {
                    $credit->statut = 'rembourse';
                }
                $credit->save();
            }
        });

        return redirect()->route('admin.collectes.index')
            ->with('success', 'Collecte enregistrée avec succès');
    }

    
    public function destroy($id)
{
    $collecte = Collecte::findOrFail($id);

    DB::transaction(function () use ($collecte) {
        // 1. Inverser la mise à jour du stock
        $stock = Stock::where('produit', $collecte->produit)
                      ->where('zone', $collecte->zone_collecte)
                      ->first();

        if ($stock) {
            $stock->quantite_entree -= $collecte->quantite_nette;
            $stock->stock_actuel -= $collecte->quantite_nette;
            $stock->dernier_mouvement = now();
            $stock->save();
        }

        // 2. Inverser la déduction sur le crédit (si applicable)
        if ($collecte->credit_id && $collecte->montant_deduict > 0) {
            $credit = CreditAgricole::find($collecte->credit_id);
            if ($credit) {
                $credit->montant_restant += $collecte->montant_deduict;
                
                // Si le crédit était marqué comme remboursé, il repasse en actif
                if ($credit->statut === 'rembourse' && $credit->montant_restant > 0) {
                    $credit->statut = 'actif';
                }
                $credit->save();
            }
        }

        // 3. Supprimer la collecte
        $collecte->delete();
    });

    return redirect()->route('admin.collectes.index')
        ->with('success', 'Collecte supprimée et stocks/crédits mis à jour.');
}

    /**
     * Afficher les détails d'une collecte
     */
    public function show($id)
    {
        $collecte = Collecte::with(['producteur', 'credit'])->findOrFail($id);

         // ✅ CONFIGURER LES SIGNATURES
         $signatureData = $this->configureSignatures('collecte', $collecte);
        
         return view('admin.collectes.show', array_merge([
             'collecte'
         ], $signatureData));
        
    }

    /**
     * Mettre à jour le statut de paiement
     */
    public function updatePaiement(Request $request, $id)
    {
        $collecte = Collecte::findOrFail($id);
        $collecte->update(['statut_paiement' => $request->statut_paiement]);
        
        return back()->with('success', 'Statut de paiement mis à jour');
    }

    /**
     * Mettre à jour le stock
     */
    private function mettreAJourStock($produit, $quantite, $zone)
    {
        $stock = Stock::firstOrCreate(
            ['produit' => $produit, 'zone' => $zone],
            ['quantite_entree' => 0, 'quantite_sortie' => 0, 'stock_actuel' => 0, 'unite' => 'kg', 'seuil_alerte' => 100]
        );

        $stock->quantite_entree += $quantite;
        $stock->stock_actuel += $quantite;
        $stock->dernier_mouvement = now();
        $stock->save();
    }

   /**
 * Dashboard des collectes
 */
public function dashboard()
{
    $stats = [
        'total_collecte' => Collecte::sum('quantite_nette'),
        'valeur_totale' => Collecte::sum('montant_total'),
        'collecte_mois' => Collecte::whereMonth('date_collecte', now()->month)->sum('quantite_nette'),
        'valeur_mois' => Collecte::whereMonth('date_collecte', now()->month)->sum('montant_total'),
        'nb_collectes' => Collecte::count(),
        'nb_producteurs' => Collecte::whereNotNull('producteur_id')->distinct('producteur_id')->count('producteur_id'),
        'nb_cooperatives' => Collecte::whereNotNull('cooperative_id')->distinct('cooperative_id')->count('cooperative_id'),
        'collectes_producteurs' => Collecte::whereNotNull('producteur_id')->sum('quantite_nette'),
        'collectes_cooperatives' => Collecte::whereNotNull('cooperative_id')->sum('quantite_nette'),
    ];
    
    $collectes_par_produit = Collecte::select('produit', DB::raw('SUM(quantite_nette) as total'))
        ->groupBy('produit')
        ->get();
        
    $collectes_recentes = Collecte::with(['producteur', 'cooperative'])
        ->orderBy('date_collecte', 'desc')
        ->limit(10)
        ->get();
        
    $collectes_par_mois = Collecte::select(
        DB::raw($this->getDateFormatFunction('date_collecte', '%Y-%m') . ' as mois'),
        DB::raw('SUM(quantite_nette) as total')
    )
    ->groupBy('mois')
    ->orderBy('mois', 'desc')
    ->limit(6)
    ->get();
    
    return view('admin.collectes.dashboard', compact('stats', 'collectes_par_produit', 'collectes_recentes', 'collectes_par_mois'));
}
}