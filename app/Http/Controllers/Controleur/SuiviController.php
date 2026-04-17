<?php

namespace App\Http\Controllers\Controleur;

use App\Http\Controllers\Controller;
use App\Models\SuiviParcellaire;
use App\Models\Producteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuiviController extends Controller
{
    /**
     * Liste des suivis parcellaires (lecture seule)
     */
    public function index(Request $request)
    {
        $query = SuiviParcellaire::with(['producteur', 'animateur']);
        
        if ($request->filled('producteur_id')) {
            $query->where('producteur_id', $request->producteur_id);
        }
        if ($request->filled('sante')) {
            $query->where('sante_cultures', $request->sante);
        }
        
        $suivis = $query->orderBy('date_suivi', 'desc')->paginate(20);
        
        $producteurs = Producteur::all();
        
        $stats = [
            'total_suivis' => SuiviParcellaire::count(),
            'suivis_mois' => SuiviParcellaire::whereMonth('date_suivi', now()->month)->count(),
            'sante_stats' => SuiviParcellaire::select('sante_cultures', DB::raw('count(*) as total'))
                ->groupBy('sante_cultures')
                ->get(),
        ];
        
        return view('controleur.suivi.index', compact('suivis', 'producteurs', 'stats'));
    }
    
    /**
     * Détails d'un suivi (lecture seule)
     */
    public function show($id)
    {
        $suivi = SuiviParcellaire::with(['producteur', 'animateur', 'distributionSemence.semence'])
            ->findOrFail($id);
        
        return view('controleur.suivi.show', compact('suivi'));
    }
    
    /**
     * Suivis par producteur
     */
    public function byProducteur($producteurId)
    {
        $producteur = Producteur::findOrFail($producteurId);
        $suivis = SuiviParcellaire::where('producteur_id', $producteurId)
            ->with('animateur')
            ->orderBy('date_suivi', 'desc')
            ->get();
        
        return view('controleur.suivi.by-producteur', compact('producteur', 'suivis'));
    }
}