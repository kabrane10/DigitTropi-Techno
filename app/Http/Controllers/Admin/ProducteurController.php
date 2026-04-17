<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use App\Models\Cooperative;
use App\Models\AgentTerrain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;  
use App\Exports\ProducteursExport;      


class ProducteurController extends Controller
{
    /**
     * Afficher la liste des producteurs
     */
    public function index(Request $request)
    {
        $query = Producteur::with('cooperative');

        // Filtres
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nom_complet', 'like', '%'.$request->search.'%')
                  ->orWhere('code_producteur', 'like', '%'.$request->search.'%')
                  ->orWhere('contact', 'like', '%'.$request->search.'%');
            });
        }

        $producteurs = $query->orderBy('created_at', 'desc')->paginate(15);
        $cooperatives = Cooperative::where('statut', 'active')->get();

        return view('admin.producteurs.index', compact('producteurs', 'cooperatives'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $cooperatives = Cooperative::where('statut', 'active')->get();
        $agents = AgentTerrain::where('statut', 'actif')->get();
        return view('admin.producteurs.create', compact('cooperatives', 'agents'));
    }

    /**
     * Enregistrer un nouveau producteur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'contact' => 'required|string|unique:producteurs',
            'email' => 'nullable|email|unique:producteurs',
            'localisation' => 'required|string',
            'region' => 'required|in:Centrale,Kara,Savanes',
            'culture_pratiquee' => 'required|string',
            'superficie_totale' => 'required|numeric|min:0',
            'cooperative_id' => 'nullable|exists:cooperatives,id',
            'agent_terrain_id' => 'nullable|exists:agents_terrain,id',
            'notes' => 'nullable|string'
        ]);

        $validated['code_producteur'] = 'PRD-' . str_pad(Producteur::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validated['date_enregistrement'] = now();
        $validated['statut'] = 'actif';

        Producteur::create($validated);

        return redirect()->route('admin.producteurs.index')
            ->with('success', 'Producteur enregistré avec succès');
    }

    /**
     * Afficher les détails d'un producteur
     */
    public function show($id)
    {
        $producteur = Producteur::with(['cooperative', 'credits', 'collectes', 'distributions.semence'])->findOrFail($id);
        
        $stats = [
            'total_credits' => $producteur->credits->sum('montant_total'),
            'credits_restants' => $producteur->credits->where('statut', 'actif')->sum('montant_restant'),
            'total_production' => $producteur->collectes->sum('quantite_nette'),
            'valeur_totale' => $producteur->collectes->sum('montant_total'),
            'nb_collectes' => $producteur->collectes->count(),
            'nb_credits' => $producteur->credits->count(),
        ];
        
        return view('admin.producteurs.show', compact('producteur', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $producteur = Producteur::findOrFail($id);
        $cooperatives = Cooperative::where('statut', 'active')->get();
        $agents = AgentTerrain::where('statut', 'actif')->get();
        return view('admin.producteurs.edit', compact('producteur', 'cooperatives', 'agents'));
    }

    /**
     * Mettre à jour un producteur
     */
    public function update(Request $request, $id)
    {
        $producteur = Producteur::findOrFail($id);

        $validated = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'contact' => 'required|string|unique:producteurs,contact,' . $id,
            'email' => 'nullable|email|unique:producteurs,email,' . $id,
            'localisation' => 'required|string',
            'region' => 'required|in:Centrale,Kara,Savanes',
            'culture_pratiquee' => 'required|string',
            'superficie_totale' => 'required|numeric|min:0',
            'cooperative_id' => 'nullable|exists:cooperatives,id',
            'agent_terrain_id' => 'nullable|exists:agents_terrain,id',
            'statut' => 'required|in:actif,inactif,en_attente',
            'notes' => 'nullable|string'
        ]);

        $producteur->update($validated);

        return redirect()->route('admin.producteurs.index')
            ->with('success', 'Producteur mis à jour avec succès');
    }

    /**
     * Supprimer un producteur
     */
    public function destroy($id)
    {
        $producteur = Producteur::findOrFail($id);
        
        if ($producteur->credits()->where('statut', 'actif')->exists()) {
            return back()->with('error', 'Impossible de supprimer un producteur avec des crédits actifs');
        }
        
        $producteur->delete();
        
        return redirect()->route('admin.producteurs.index')
            ->with('success', 'Producteur supprimé avec succès');
    }

    public function export()
{
    return Excel::download(new ProducteursExport, 'producteurs-' . date('Y-m-d') . '.xlsx');
}
}