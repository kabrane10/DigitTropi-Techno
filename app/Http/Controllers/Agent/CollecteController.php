<?php

namespace App\Http\Controllers\Agent;

use App\Traits\NotifiableTrait;
use App\Http\Controllers\Controller;
use App\Models\Collecte;
use App\Models\Producteur;
use App\Models\CreditAgricole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollecteController extends Controller
{
    use NotifiableTrait;

    public function index(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        
        $query = Collecte::whereHas('producteur', function($q) use ($agent) {
            $q->where('agent_terrain_id', $agent->id);
        })->with('producteur');
        
        if ($request->filled('produit')) {
            $query->where('produit', $request->produit);
        }
        
        $collectes = $query->orderBy('date_collecte', 'desc')->paginate(15);
        $produits = Collecte::distinct()->pluck('produit');
        
        return view('agent.collectes.index', compact('collectes', 'produits'));
    }

    public function create()
    {
        $agent = Auth::guard('agent')->user();
        
        $producteurs = Producteur::where('agent_terrain_id', $agent->id)
            ->where('statut', 'actif')
            ->get();
        
        return view('agent.collectes.create', compact('producteurs'));
    }

    public function store(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        
        $validated = $request->validate([
            'producteur_id' => 'required|exists:producteurs,id',
            'date_collecte' => 'required|date',
            'produit' => 'required|string',
            'quantite_brute' => 'required|numeric|min:0',
            'quantite_nette' => 'required|numeric|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
            'zone_collecte' => 'required|string',
            'observations' => 'nullable|string'
        ]);

        // Vérifier que le producteur appartient bien à l'agent
        $producteur = Producteur::where('id', $validated['producteur_id'])
            ->where('agent_terrain_id', $agent->id)
            ->firstOrFail();
        
        $validated['montant_total'] = $validated['quantite_nette'] * $validated['prix_unitaire'];
        $validated['montant_a_payer'] = $validated['montant_total'];
        $validated['code_collecte'] = 'COL-' . str_pad(Collecte::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validated['statut_paiement'] = 'en_attente';

        Collecte::create($validated);
        
         // 🔔 Déclencher la notification
         $this->notifyNewCollecte($collecte);

        return redirect()->route('agent.collectes.index')
            ->with('success', 'Collecte enregistrée avec succès');
    }

    public function show($id)
    {
        $agent = Auth::guard('agent')->user();
        
        $collecte = Collecte::whereHas('producteur', function($q) use ($agent) {
            $q->where('agent_terrain_id', $agent->id);
        })->with('producteur')->findOrFail($id);
        
        return view('agent.collectes.show', compact('collecte'));
    }

    /**
 * Afficher le formulaire d'édition d'une collecte
 */
public function edit($id)
{
    $agent = Auth::guard('agent')->user();
    
    $collecte = Collecte::whereHas('producteur', function($q) use ($agent) {
        $q->where('agent_terrain_id', $agent->id);
    })->with('producteur')->findOrFail($id);
    
    $producteurs = Producteur::where('agent_terrain_id', $agent->id)
        ->where('statut', 'actif')
        ->get();
    
    return view('agent.collectes.edit', compact('collecte', 'producteurs'));
}

/**
 * Mettre à jour une collecte
 */
public function update(Request $request, $id)
{
    $agent = Auth::guard('agent')->user();
    
    $collecte = Collecte::whereHas('producteur', function($q) use ($agent) {
        $q->where('agent_terrain_id', $agent->id);
    })->findOrFail($id);
    
    $validated = $request->validate([
        'producteur_id' => 'required|exists:producteurs,id',
        'date_collecte' => 'required|date',
        'produit' => 'required|string',
        'quantite_brute' => 'required|numeric|min:0',
        'quantite_nette' => 'required|numeric|min:0',
        'prix_unitaire' => 'required|numeric|min:0',
        'zone_collecte' => 'required|string',
        'observations' => 'nullable|string'
    ]);

    // Vérifier que le producteur appartient bien à l'agent
    Producteur::where('id', $validated['producteur_id'])
        ->where('agent_terrain_id', $agent->id)
        ->firstOrFail();
    
    $validated['montant_total'] = $validated['quantite_nette'] * $validated['prix_unitaire'];
    $validated['montant_a_payer'] = $validated['montant_total'];

    $collecte->update($validated);

    return redirect()->route('agent.collectes.index')
        ->with('success', 'Collecte modifiée avec succès');
}

}