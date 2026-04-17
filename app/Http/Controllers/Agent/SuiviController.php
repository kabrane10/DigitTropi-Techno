<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\SuiviParcellaire;
use App\Models\Producteur;
use App\Models\DistributionSemence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuiviController extends Controller
{
    public function index()
    {
        $agent = Auth::guard('agent')->user();
        
        $suivis = SuiviParcellaire::whereHas('producteur', function($q) use ($agent) {
            $q->where('agent_terrain_id', $agent->id);
        })->with(['producteur', 'animateur'])
        ->orderBy('date_suivi', 'desc')
        ->paginate(15);
        
        return view('agent.suivi.index', compact('suivis'));
    }

    public function create(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        
        $producteurs = Producteur::where('agent_terrain_id', $agent->id)
            ->where('statut', 'actif')
            ->get();
        
        $producteur_selectionne = null;
        if ($request->producteur_id) {
            $producteur_selectionne = Producteur::find($request->producteur_id);
        }
        
        return view('agent.suivi.create', compact('producteurs', 'producteur_selectionne'));
    }

    public function store(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        
        $validated = $request->validate([
            'producteur_id' => 'required|exists:producteurs,id',
            'date_suivi' => 'required|date',
            'superficie_actuelle' => 'required|numeric|min:0',
            'hauteur_plantes' => 'nullable|numeric',
            'stade_croissance' => 'required|string',
            'sante_cultures' => 'required|in:excellente,bonne,moyenne,mauvaise,critique',
            'taux_levée' => 'nullable|integer|min:0|max:100',
            'problemes_constates' => 'nullable|string',
            'recommandations' => 'nullable|string',
            'actions_prises' => 'nullable|string',
        ]);

        // Vérifier que le producteur appartient à l'agent
        Producteur::where('id', $validated['producteur_id'])
            ->where('agent_terrain_id', $agent->id)
            ->firstOrFail();

        $validated['code_suivi'] = 'SUIVI-' . str_pad(SuiviParcellaire::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validated['animateur_id'] = $agent->superviseur_id;

        SuiviParcellaire::create($validated);

        return redirect()->route('agent.suivi.index')
            ->with('success', 'Suivi enregistré avec succès');
    }

    public function show($id)
    {
        $agent = Auth::guard('agent')->user();
        
        $suivi = SuiviParcellaire::whereHas('producteur', function($q) use ($agent) {
            $q->where('agent_terrain_id', $agent->id);
        })->with(['producteur', 'animateur'])->findOrFail($id);
        
        return view('agent.suivi.show', compact('suivi'));
    }

    /**
 * Afficher le formulaire d'édition d'un suivi
 */
public function edit($id)
{
    $agent = Auth::guard('agent')->user();
    
    $suivi = SuiviParcellaire::whereHas('producteur', function($q) use ($agent) {
        $q->where('agent_terrain_id', $agent->id);
    })->with('producteur')->findOrFail($id);
    
    $producteurs = Producteur::where('agent_terrain_id', $agent->id)
        ->where('statut', 'actif')
        ->get();
    
    return view('agent.suivi.edit', compact('suivi', 'producteurs'));
}

/**
 * Mettre à jour un suivi
 */
public function update(Request $request, $id)
{
    $agent = Auth::guard('agent')->user();
    
    $suivi = SuiviParcellaire::whereHas('producteur', function($q) use ($agent) {
        $q->where('agent_terrain_id', $agent->id);
    })->findOrFail($id);
    
    $validated = $request->validate([
        'producteur_id' => 'required|exists:producteurs,id',
        'date_suivi' => 'required|date',
        'superficie_actuelle' => 'required|numeric|min:0',
        'hauteur_plantes' => 'nullable|numeric',
        'stade_croissance' => 'required|string',
        'sante_cultures' => 'required|in:excellente,bonne,moyenne,mauvaise,critique',
        'taux_levée' => 'nullable|integer|min:0|max:100',
        'problemes_constates' => 'nullable|string',
        'recommandations' => 'nullable|string',
        'actions_prises' => 'nullable|string',
    ]);

    // Vérifier que le producteur appartient à l'agent
    Producteur::where('id', $validated['producteur_id'])
        ->where('agent_terrain_id', $agent->id)
        ->firstOrFail();

    $suivi->update($validated);

    return redirect()->route('agent.suivi.index')
        ->with('success', 'Suivi modifié avec succès');
}

}