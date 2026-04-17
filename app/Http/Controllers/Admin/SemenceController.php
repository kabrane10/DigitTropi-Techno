<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SemenceController extends Controller
{
    /**
     * Liste des semences
     */
    public function index(Request $request)
    {
        $query = Semence::query();
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('variete', 'like', '%' . $request->search . '%')
                  ->orWhere('code_semence', 'like', '%' . $request->search . '%');
            });
        }
        
        $semences = $query->orderBy('nom')->paginate(15);
        $types = Semence::distinct()->pluck('type');
        
        return view('admin.semences.index', compact('semences', 'types'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('admin.semences.create');
    }

    /**
     * Enregistrer une semence
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'variete' => 'required|string|max:255',
            'type' => 'required|in:soja,arachide,sesame,mais,riz,gombo,autres',
            'prix_unitaire' => 'required|numeric|min:0',
            'stock_disponible' => 'required|numeric|min:0',
            'unite' => 'required|string|max:50',
            'description' => 'nullable|string'
        ]);

        // Générer le code semence
        $lastId = Semence::max('id') + 1;
        $validated['code_semence'] = 'SEM-' . str_pad($lastId, 6, '0', STR_PAD_LEFT);

        Semence::create($validated);

        return redirect()->route('admin.semences.index')
            ->with('success', 'Semence ajoutée avec succès');
    }

    /**
     * Afficher les détails
     */
    public function show($id)
    {
        $semence = Semence::with('distributions.producteur')->findOrFail($id);
        
        $stats = [
            'total_distribue' => $semence->distributions->sum('quantite'),
            'nb_distributions' => $semence->distributions->count(),
            'nb_producteurs' => $semence->distributions->pluck('producteur_id')->count('producteur_id'),
        ];
        
        return view('admin.semences.show', compact('semence', 'stats'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $semence = Semence::findOrFail($id);
        return view('admin.semences.edit', compact('semence'));
    }

    /**
     * Mettre à jour
     */
    public function update(Request $request, $id)
    {
        $semence = Semence::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'variete' => 'required|string|max:255',
            'type' => 'required|in:soja,arachide,sesame,mais,riz,gombo,autres',
            'prix_unitaire' => 'required|numeric|min:0',
            'unite' => 'required|string|max:50',
            'description' => 'nullable|string'
        ]);

        $semence->update($validated);

        return redirect()->route('admin.semences.index')
            ->with('success', 'Semence mise à jour avec succès');
    }

    /**
     * Supprimer
     */
    public function destroy($id)
    {
        $semence = Semence::findOrFail($id);
        
        // Vérifier si la semence a été distribuée
        if ($semence->distributions()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une semence qui a déjà été distribuée.');
        }
        
        $semence->delete();
        
        return redirect()->route('admin.semences.index')
            ->with('success', 'Semence supprimée avec succès');
    }

    /**
     * Ajouter du stock
     */
    public function ajouterStock(Request $request, $id)
    {
        $semence = Semence::findOrFail($id);
        
        $validated = $request->validate([
            'quantite' => 'required|numeric|min:1'
        ]);
        
        $semence->stock_disponible += $validated['quantite'];
        $semence->save();
        
        // Mettre à jour ou créer le stock dans la table stocks
        $stock = \App\Models\Stock::firstOrCreate(
            ['produit' => $semence->nom, 'zone' => 'Centrale'],
            ['unite' => $semence->unite, 'seuil_alerte' => 100]
        );
        $stock->stock_actuel += $validated['quantite'];
        $stock->quantite_entree += $validated['quantite'];
        $stock->dernier_mouvement = now();
        $stock->save();
        
        return back()->with('success', 'Stock ajouté avec succès. Nouveau stock: ' . $semence->stock_disponible . ' ' . $semence->unite);
    }
}