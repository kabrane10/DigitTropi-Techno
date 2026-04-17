<?php

namespace App\Http\Controllers\Agent;
use App\Traits\NotifiableTrait;
use App\Http\Controllers\Controller;
use App\Models\Producteur;
use App\Models\Cooperative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProducteurController extends Controller
{
    use NotifiableTrait;

    public function index(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        
        $query = Producteur::where('agent_terrain_id', $agent->id);
        
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
        
        return view('agent.producteurs.index', compact('producteurs'));
    }

    public function create()
    {
        $cooperatives = Cooperative::where('statut', 'active')->get();
        return view('agent.producteurs.create', compact('cooperatives'));
    }

    public function store(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        
        // Vérifier que l'agent est connecté
        if (!$agent) {
            return redirect()->route('agent.login')->with('error', 'Veuillez vous connecter');
        }
        
        $validated = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'contact' => 'required|string|unique:producteurs',
            'email' => 'nullable|email|unique:producteurs',
            'localisation' => 'required|string',
            'region' => 'required|in:Centrale,Kara,Savanes',
            'culture_pratiquee' => 'required|string',
            'superficie_totale' => 'required|numeric|min:0',
            'cooperative_id' => 'nullable|exists:cooperatives,id',
            'notes' => 'nullable|string'
        ]);

        // Générer le code producteur
        $lastId = Producteur::max('id') + 1;
        $validated['code_producteur'] = 'PRD-' . str_pad($lastId, 6, '0', STR_PAD_LEFT);
        $validated['date_enregistrement'] = now();
        $validated['statut'] = 'actif';
        $validated['agent_terrain_id'] = $agent->id;

        try {
            $producteur = Producteur::create($validated);

             // 🔔 Déclencher la notification
        $this->notifyNewProducteur($producteur);
            
            // Mettre à jour le compteur de l'agent
            $agent->increment('producteurs_enregistres');
            
            return redirect()->route('agent.producteurs.index')
                ->with('success', 'Producteur enregistré avec succès. Code: ' . $producteur->code_producteur);
                
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $agent = Auth::guard('agent')->user();
        
        $producteur = Producteur::where('id', $id)
            ->where('agent_terrain_id', $agent->id)
            ->firstOrFail();
        
        $credits = $producteur->credits()->orderBy('created_at', 'desc')->get();
        $collectes = $producteur->collectes()->orderBy('date_collecte', 'desc')->limit(10)->get();
        
        return view('agent.producteurs.show', compact('producteur', 'credits', 'collectes'));
    }

    public function edit($id)
    {
        $agent = Auth::guard('agent')->user();
        
        $producteur = Producteur::where('id', $id)
            ->where('agent_terrain_id', $agent->id)
            ->firstOrFail();
        
        $cooperatives = Cooperative::where('statut', 'active')->get();
        
        return view('agent.producteurs.edit', compact('producteur', 'cooperatives'));
    }

    public function update(Request $request, $id)
    {
        $agent = Auth::guard('agent')->user();
        
        $producteur = Producteur::where('id', $id)
            ->where('agent_terrain_id', $agent->id)
            ->firstOrFail();

        $validated = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'contact' => 'required|string|unique:producteurs,contact,' . $id,
            'email' => 'nullable|email|unique:producteurs,email,' . $id,
            'localisation' => 'required|string',
            'region' => 'required|in:Centrale,Kara,Savanes',
            'culture_pratiquee' => 'required|string',
            'superficie_totale' => 'required|numeric|min:0',
            'cooperative_id' => 'nullable|exists:cooperatives,id',
            'statut' => 'required|in:actif,inactif,en_attente',
            'notes' => 'nullable|string'
        ]);

        $producteur->update($validated);

        return redirect()->route('agent.producteurs.index')
            ->with('success', 'Producteur mis à jour avec succès');
    }

    public function destroy($id)
    {
        $agent = Auth::guard('agent')->user();
        
        $producteur = Producteur::where('id', $id)
            ->where('agent_terrain_id', $agent->id)
            ->firstOrFail();
        
        if ($producteur->credits()->where('statut', 'actif')->exists()) {
            return back()->with('error', 'Impossible de supprimer un producteur avec des crédits actifs');
        }
        
        $producteur->delete();
        $agent->decrement('producteurs_enregistres');

        return redirect()->route('agent.producteurs.index')
            ->with('success', 'Producteur supprimé avec succès');
    }
}