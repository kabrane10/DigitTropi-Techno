<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use App\Models\Semence;
use App\Models\EstimationBesoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstimationBesoinController extends Controller
{
    public function index()
    {
        $estimations = EstimationBesoin::with(['producteur', 'semence'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.estimations.index', compact('estimations'));
    }

    public function create()
    {
        $producteurs = Producteur::where('statut', 'actif')->get();
        $semences = Semence::all();
        
        return view('admin.estimations.create', compact('producteurs', 'semences'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'producteur_id' => 'required|exists:producteurs,id',
            'semence_id' => 'required|exists:semences,id',
            'quantite_estimee' => 'required|numeric|min:0',
            'superficie_prevue' => 'required|numeric|min:0',
            'credit_montant' => 'nullable|numeric|min:0',
            'intrants' => 'nullable|array',
            'date_estimation' => 'required|date',
            'statut' => 'required|in:en_attente,approuve,rejete',
            'observations' => 'nullable|string',
            'cout_semences' => 'nullable|numeric|min:0',
            'cout_intrants' => 'nullable|numeric|min:0',
            'autres_frais' => 'nullable|numeric|min:0',
            'total_estimation' => 'nullable|numeric|min:0',
        ]);

        // Traiter les intrants
        if ($request->has('intrants')) {
            $validated['intrants'] = json_encode(array_values($request->intrants));
        }

        $validated['code_estimation'] = 'EST-' . str_pad(EstimationBesoin::max('id') + 1, 6, '0', STR_PAD_LEFT);
        
        EstimationBesoin::create($validated);

        return redirect()->route('admin.estimations.index')
            ->with('success', 'Estimation de besoin enregistrée avec succès');
    }

    public function show($id)
    {
        $estimation = EstimationBesoin::with(['producteur', 'semence'])->findOrFail($id);
        return view('admin.estimations.show', compact('estimation'));
    }

    public function edit($id)
    {
        $estimation = EstimationBesoin::findOrFail($id);
        $producteurs = Producteur::where('statut', 'actif')->get();
        $semences = Semence::all();
        
        return view('admin.estimations.edit', compact('estimation', 'producteurs', 'semences'));
    }

    public function update(Request $request, $id)
    {
        $estimation = EstimationBesoin::findOrFail($id);

        $validated = $request->validate([
            'semence_id' => 'required|exists:semences,id',
            'quantite_estimee' => 'required|numeric|min:0',
            'superficie_prevue' => 'required|numeric|min:0',
            'credit_montant' => 'nullable|numeric|min:0',
            'intrants' => 'nullable|array',
            'date_estimation' => 'required|date',
            'statut' => 'required|in:en_attente,approuve,rejete',
            'observations' => 'nullable|string',
            'cout_semences' => 'nullable|numeric|min:0',
            'cout_intrants' => 'nullable|numeric|min:0',
            'autres_frais' => 'nullable|numeric|min:0',
            'total_estimation' => 'nullable|numeric|min:0',
        ]);

        // Traiter les intrants
        $intrants = $request->input('intrants');
        if ($intrants && is_array($intrants) && count($intrants) > 0) {
            $validated['intrants'] = json_encode(array_values($intrants));
        } else {
            $validated['intrants'] = null;
        }

        $estimation->update($validated);

        return redirect()->route('admin.estimations.index')
            ->with('success', 'Estimation mise à jour avec succès');
    }

    public function destroy($id)
    {
        $estimation = EstimationBesoin::findOrFail($id);
        $estimation->delete();

        return redirect()->route('admin.estimations.index')
            ->with('success', 'Estimation supprimée avec succès');
    }

    /**
     * Convertir une estimation en crédit
     */
    public function convertToCredit($id)
    {
        try {
            $estimation = EstimationBesoin::with(['producteur', 'semence'])->findOrFail($id);
            
            // Vérifications
            if (!$estimation->credit_montant || $estimation->credit_montant <= 0) {
                return redirect()->route('admin.estimations.index')
                    ->with('error', 'Cette estimation n\'a pas de montant de crédit défini.');
            }
            
            // Vérifier que le producteur existe
            if (!$estimation->producteur) {
                return redirect()->route('admin.estimations.index')
                    ->with('error', 'Le producteur associé à cette estimation n\'existe plus.');
            }
            
            // Construction des paramètres
            $params = [
                'producteur_id' => $estimation->producteur_id,
                'montant_total' => $estimation->credit_montant,
                'estimation_id' => $estimation->id,
                'type_intrant' => 'semences',
                'quantite_intrant' => $estimation->quantite_estimee,
                'unite_intrant' => $estimation->semence->unite ?? 'kg'
            ];
            
            return redirect()->route('admin.credits.create', $params)
                ->with('success', 'Formulaire de crédit pré-rempli à partir de l\'estimation N° ' . $estimation->code_estimation);
                
        } catch (\Exception $e) {
            return redirect()->route('admin.estimations.index')
                ->with('error', 'Erreur lors de la conversion: ' . $e->getMessage());
        }
    }

    public function print($id)
    {
        $estimation = EstimationBesoin::with(['producteur', 'semence'])->findOrFail($id);
        return view('admin.estimations.print', compact('estimation'));
    }

}
