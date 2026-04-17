<?php

namespace App\Http\Controllers\Controleur;

use App\Http\Controllers\Controller;
use App\Models\Collecte;
use App\Models\Producteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollecteController extends Controller
{
    public function index(Request $request)
    {
        $query = Collecte::with('producteur');
        
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
        
        return view('controleur.collectes.index', compact('collectes', 'produits'));
    }

    public function show($id)
    {
        $collecte = Collecte::with(['producteur', 'credit'])->findOrFail($id);
        return view('controleur.collectes.show', compact('collecte'));
    }
}