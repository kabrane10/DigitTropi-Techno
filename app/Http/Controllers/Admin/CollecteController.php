<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collecte;
use App\Models\Producteur;
use App\Models\CreditAgricole;
use App\Models\Stock;
use App\Traits\DatabaseCompatibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollecteController extends Controller
{
    use DatabaseCompatibility;
    /**
     * Afficher la liste des collectes
     */
    public function index(Request $request)
    {
        $query = Collecte::with(['producteur', 'credit']);

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
        
        return view('admin.collectes.index', compact('collectes', 'produits'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $producteurs = Producteur::where('statut', 'actif')->get();
        $credits = CreditAgricole::where('statut', 'actif')
            ->where('montant_restant', '>', 0)
            ->get();
        return view('admin.collectes.create', compact('producteurs', 'credits'));
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
            'producteur_id' => 'required|exists:producteurs,id',
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
            $validated['montant_total'] = $validated['quantite_nette'] * $validated['prix_unitaire'];
            $validated['montant_a_payer'] = $validated['montant_total'] - ($validated['montant_deduict'] ?? 0);
            $validated['code_collecte'] = 'COL-' . str_pad(Collecte::max('id') + 1, 6, '0', STR_PAD_LEFT);
            
            if ($validated['montant_a_payer'] == 0) {
                $validated['statut_paiement'] = 'paye';
            }

            $collecte = Collecte::create($validated);

            // Mettre à jour le stock
            $this->mettreAJourStock($validated['produit'], $validated['quantite_nette'], $validated['zone_collecte']);

            // Si déduction sur crédit
            if (!empty($validated['credit_id']) && ($validated['montant_deduict'] ?? 0) > 0) {
                $credit = CreditAgricole::find($validated['credit_id']);
                $credit->montant_restant -= $validated['montant_deduict'];
                if ($credit->montant_restant <= 0) {
                    $credit->statut = 'rembourse';
                }
                $credit->save();
            }
        });

        return redirect()->route('admin.collectes.index')
            ->with('success', 'Collecte enregistrée avec succès');
    }

    /**
     * Afficher les détails d'une collecte
     */
    public function show($id)
    {
        $collecte = Collecte::with(['producteur', 'credit'])->findOrFail($id);
        return view('admin.collectes.show', compact('collecte'));
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
    // dd("Je suis dans la méthode dashboard du contrôleur CollecteController");
    $stats = [
        'total_collecte' => Collecte::sum('quantite_nette'),
        'valeur_totale' => Collecte::sum('montant_total'),
        'collecte_mois' => Collecte::whereMonth('date_collecte', now()->month)->sum('quantite_nette'),
        'valeur_mois' => Collecte::whereMonth('date_collecte', now()->month)->sum('montant_total'),
        'nb_collectes' => Collecte::count(),
        'nb_producteurs' => Collecte::distinct('producteur_id')->count('producteur_id'),
    ];
    
    $collectes_par_produit = Collecte::select('produit', \DB::raw('SUM(quantite_nette) as total'))
        ->groupBy('produit')
        ->get();
        
    $collectes_recentes = Collecte::with('producteur')
        ->orderBy('date_collecte', 'desc')
        ->limit(10)
        ->get();
        
     // 🔴 CORRECTION: Utiliser DATE_FORMAT pour MySQL
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