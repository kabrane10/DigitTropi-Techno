<?php

namespace App\Http\Controllers\Controleur;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProducteurController extends Controller
{
    public function index(Request $request)
    {
        $query = Producteur::with('cooperative');
        
        if ($request->filled('search')) {
            $query->where('nom_complet', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        $producteurs = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('controleur.producteurs.index', compact('producteurs'));
    }

    public function show($id)
    {
        $producteur = Producteur::with(['cooperative', 'credits', 'collectes'])->findOrFail($id);
        
        $stats = [
            'total_credits' => $producteur->credits->sum('montant_total'),
            'credits_restants' => $producteur->credits->where('statut', 'actif')->sum('montant_restant'),
            'total_production' => $producteur->collectes->sum('quantite_nette'),
            'valeur_totale' => $producteur->collectes->sum('montant_total'),
        ];
        
        return view('controleur.producteurs.show', compact('producteur', 'stats'));
    }
}