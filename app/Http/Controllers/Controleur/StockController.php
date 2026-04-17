<?php

namespace App\Http\Controllers\Controleur;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Liste des stocks (lecture seule)
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
        
        // Statistiques pour le dashboard
        $stats = [
            'total_stock' => Stock::sum('stock_actuel'),
            'alertes' => Stock::whereRaw('stock_actuel <= seuil_alerte')->count(),
            'nb_produits' => Stock::distinct('produit')->count('produit'),
        ];
        
        return view('controleur.stocks.index', compact('stocks', 'zones', 'stats'));
    }
    
    /**
     * Détails d'un stock (lecture seule)
     */
    public function show($id)
    {
        $stock = Stock::findOrFail($id);
        
        // Historique des mouvements (entrées/sorties)
        $entrees = \App\Models\Collecte::where('produit', $stock->produit)
            ->where('zone_collecte', $stock->zone)
            ->orderBy('date_collecte', 'desc')
            ->limit(20)
            ->get();
        
        $sorties = \App\Models\DistributionSemence::whereHas('semence', function($q) use ($stock) {
            $q->where('nom', $stock->produit);
        })->orderBy('date_distribution', 'desc')->limit(20)->get();
        
        return view('controleur.stocks.show', compact('stock', 'entrees', 'sorties'));
    }
}