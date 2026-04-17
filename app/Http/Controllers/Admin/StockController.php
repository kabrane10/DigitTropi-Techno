<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Semence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StocksExport;


class StockController extends Controller
{
    /**
     * Liste des stocks
     */
    public function index(Request $request)
    {
        $query = Stock::query();

        if ($request->filled('produit')) {
            $query->where('produit', 'like', '%' . $request->produit . '%');
        }
        if ($request->filled('zone')) {
            $query->where('zone', $request->zone);
        }
        if ($request->filled('alerte')) {
            $query->whereRaw('stock_actuel <= seuil_alerte');
        }

        $stocks = $query->orderBy('produit')->orderBy('zone')->paginate(20);
        $zones = Stock::distinct()->pluck('zone');
        
        return view('admin.stocks.index', compact('stocks', 'zones'));
    }

    /**
     * Formulaire d'ajout de stock
     */
    public function create()
    {
        $semences = Semence::all();
        return view('admin.stocks.create', compact('semences'));
    }

    /**
     * Enregistrer un nouveau stock
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit' => 'required|string',
            'zone' => 'required|string',
            'quantite' => 'required|numeric|min:1',
            'entrepot' => 'nullable|string',
            'unite' => 'required|string',
            'seuil_alerte' => 'nullable|numeric|min:0'
        ]);

        $stock = Stock::firstOrCreate(
            ['produit' => $validated['produit'], 'zone' => $validated['zone']],
            [
                'quantite_entree' => 0,
                'quantite_sortie' => 0,
                'stock_actuel' => 0,
                'seuil_alerte' => $validated['seuil_alerte'] ?? 100,
                'unite' => $validated['unite'],
                'entrepot' => $validated['entrepot'] ?? null
            ]
        );

        $stock->quantite_entree += $validated['quantite'];
        $stock->stock_actuel += $validated['quantite'];
        $stock->dernier_mouvement = now();
        $stock->save();

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Stock ajouté avec succès');
    }

    /**
     * Afficher les détails d'un stock
     */
    public function show($id)
    {
        $stock = Stock::findOrFail($id);
        return view('admin.stocks.show', compact('stock'));
    }

    /**
     * Formulaire d'édition du seuil d'alerte
     */
    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        return view('admin.stocks.edit', compact('stock'));
    }

    /**
     * Mettre à jour le seuil d'alerte
     */
    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        
        $validated = $request->validate([
            'seuil_alerte' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $stock->update([
            'seuil_alerte' => $validated['seuil_alerte'],
            'notes' => $validated['notes'] ?? null
        ]);

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Seuil d\'alerte mis à jour avec succès');
    }

    /**
     * Modifier le seuil d'alerte (méthode alternative)
     */
    public function updateSeuil(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        
        $validated = $request->validate([
            'seuil_alerte' => 'required|numeric|min:0'
        ]);

        $stock->update(['seuil_alerte' => $validated['seuil_alerte']]);

        return back()->with('success', 'Seuil d\'alerte mis à jour');
    }

    /**
     * Supprimer un stock
     */
    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        
        // Vérifier si le stock peut être supprimé
        if ($stock->stock_actuel > 0) {
            return back()->with('error', 'Impossible de supprimer un stock non nul. Veuillez d\'abord retirer le stock.');
        }
        
        $stock->delete();
        
        return redirect()->route('admin.stocks.index')
            ->with('success', 'Stock supprimé avec succès');
    }

    /**
     * Retirer du stock
     */
    public function sortie(Request $request)
    {
        $validated = $request->validate([
            'produit' => 'required|string',
            'zone' => 'required|string',
            'quantite' => 'required|numeric|min:1',
            'motif' => 'required|string'
        ]);

        $stock = Stock::where('produit', $validated['produit'])
            ->where('zone', $validated['zone'])
            ->first();

        if (!$stock) {
            return back()->with('error', 'Produit non trouvé dans cette zone');
        }

        if ($stock->stock_actuel < $validated['quantite']) {
            return back()->with('error', 'Stock insuffisant. Disponible : ' . $stock->stock_actuel . ' ' . $stock->unite);
        }

        $stock->quantite_sortie += $validated['quantite'];
        $stock->stock_actuel -= $validated['quantite'];
        $stock->dernier_mouvement = now();
        $stock->save();

        return redirect()->route('admin.stocks.index')
            ->with('success', 'Retrait de stock effectué avec succès');
    }

    /**
     * Mouvements de stock
     */
    public function mouvements($id)
    {
        $stock = Stock::findOrFail($id);
        
        // Récupérer les entrées (collectes)
        $entrees = \App\Models\Collecte::where('produit', $stock->produit)
            ->where('zone_collecte', $stock->zone)
            ->orderBy('date_collecte', 'desc')
            ->limit(20)
            ->get();
        
        // Récupérer les sorties (distributions)
        $sorties = \App\Models\DistributionSemence::whereHas('semence', function($q) use ($stock) {
            $q->where('nom', $stock->produit);
        })->orderBy('date_distribution', 'desc')->limit(20)->get();
        
        return view('admin.stocks.mouvements', compact('stock', 'entrees', 'sorties'));
    }

    /**
     * Dashboard des stocks
     */
    public function dashboard()
    {
        $stats = [
            'total_produits' => Stock::distinct('produit')->count('produit'),
            'stock_total' => Stock::sum('stock_actuel'),
            'alertes' => Stock::whereRaw('stock_actuel <= seuil_alerte')->count(),
            'valeur_stock' => Stock::sum('stock_actuel') * 500 // Prix moyen estimé
        ];
        
        $stocks_alerte = Stock::whereRaw('stock_actuel <= seuil_alerte')->get();
        $top_produits = Stock::orderBy('stock_actuel', 'desc')->limit(10)->get();
        $stock_par_zone = Stock::select('zone', DB::raw('SUM(stock_actuel) as total'))
            ->groupBy('zone')
            ->get();
        
        return view('admin.stocks.dashboard', compact('stats', 'stocks_alerte', 'top_produits', 'stock_par_zone'));
    }

    /**
 * Exporter les coopératives en Excel
 */
public function export()
{
    return Excel::download(new StocksExport, 'stocks-' . date('Y-m-d') . '.xlsx');
}
}