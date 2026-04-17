<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cooperative;
use App\Models\Producteur;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CooperativesExport;


class CooperativeController extends Controller
{
    /**
     * Afficher la liste des coopératives
     */
    public function index(Request $request)
    {
        $query = Cooperative::query();

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $cooperatives = $query->orderBy('nom')->paginate(15);
        return view('admin.cooperatives.index', compact('cooperatives'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('admin.cooperatives.create');
    }

    /**
     * Enregistrer une coopérative
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:cooperatives',
            'contact' => 'required|string|max:20',
            'email' => 'nullable|email|unique:cooperatives',
            'localisation' => 'required|string',
            'region' => 'required|in:Centrale,Kara,Savanes',
            'date_creation' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $validated['code_cooperative'] = 'COOP-' . str_pad(Cooperative::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validated['nombre_membres'] = 0;
        $validated['statut'] = 'active';

        Cooperative::create($validated);

        return redirect()->route('admin.cooperatives.index')
            ->with('success', 'Coopérative créée avec succès');
    }

    /**
     * Afficher les détails
     */
    public function show($id)
    {
        $cooperative = Cooperative::with('producteurs')->findOrFail($id);
        
        $stats = [
            'nb_membres' => $cooperative->producteurs->count(),
            'superficie_totale' => $cooperative->producteurs->sum('superficie_totale'),
            'total_credits' => $cooperative->credits()->sum('montant_total'),
            'credits_restants' => $cooperative->credits()->where('statut', 'actif')->sum('montant_restant'),
        ];
        
        return view('admin.cooperatives.show', compact('cooperative', 'stats'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $cooperative = Cooperative::findOrFail($id);
        return view('admin.cooperatives.edit', compact('cooperative'));
    }

    /**
     * Mettre à jour
     */
    public function update(Request $request, $id)
    {
        $cooperative = Cooperative::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:cooperatives,nom,' . $id,
            'contact' => 'required|string|max:20',
            'email' => 'nullable|email|unique:cooperatives,email,' . $id,
            'localisation' => 'required|string',
            'region' => 'required|in:Centrale,Kara,Savanes',
            'date_creation' => 'required|date',
            'statut' => 'required|in:active,suspendue',
            'description' => 'nullable|string'
        ]);

        $cooperative->update($validated);

        return redirect()->route('admin.cooperatives.index')
            ->with('success', 'Coopérative mise à jour avec succès');
    }

    /**
     * Supprimer
     */
    public function destroy($id)
    {
        $cooperative = Cooperative::findOrFail($id);
        
        if ($cooperative->producteurs()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une coopérative qui a des membres');
        }
        
        $cooperative->delete();
        
        return redirect()->route('admin.cooperatives.index')
            ->with('success', 'Coopérative supprimée avec succès');
    }

    /**
 * Exporter les coopératives en Excel
 */
public function export()
{
    return Excel::download(new CooperativesExport, 'cooperatives-' . date('Y-m-d') . '.xlsx');
}
}