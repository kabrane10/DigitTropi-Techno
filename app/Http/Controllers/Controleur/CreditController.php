<?php

namespace App\Http\Controllers\Controleur;

use App\Http\Controllers\Controller;
use App\Models\CreditAgricole;
use App\Models\Producteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
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
        
        return view('controleur.credits.index', compact('credits', 'producteurs'));
    }

    public function show($id)
    {
        $credit = CreditAgricole::with(['producteur', 'cooperative', 'remboursements'])->findOrFail($id);
        
        $montantRembourse = $credit->remboursements->sum('montant');
        $resteAPayer = $credit->montant_total - $montantRembourse;
        $tauxRemboursement = $credit->montant_total > 0 ? ($montantRembourse / $credit->montant_total) * 100 : 0;
        
        return view('controleur.credits.show', compact('credit', 'montantRembourse', 'resteAPayer', 'tauxRemboursement'));
    }
}