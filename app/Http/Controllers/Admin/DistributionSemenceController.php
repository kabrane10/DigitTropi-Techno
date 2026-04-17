<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DistributionSemence;
use App\Models\Producteur;
use App\Models\Semence;
use App\Models\CreditAgricole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistributionSemenceController extends Controller
{
    /**
     * Liste des distributions
     */
    public function index(Request $request)
    {
        $query = DistributionSemence::with(['producteur', 'semence']);

        if ($request->filled('saison')) {
            $query->where('saison', $request->saison);
        }
        if ($request->filled('producteur_id')) {
            $query->where('producteur_id', $request->producteur_id);
        }

        $distributions = $query->orderBy('date_distribution', 'desc')->paginate(15);
        $producteurs = Producteur::where('statut', 'actif')->get();
        
        return view('admin.distributions.index', compact('distributions', 'producteurs'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $producteurs = Producteur::where('statut', 'actif')->get();
        $semences = Semence::where('stock_disponible', '>', 0)->get();
        $credits = CreditAgricole::where('statut', 'actif')->get();
        
        return view('admin.distributions.create', compact('producteurs', 'semences', 'credits'));
    }

    /**
     * Enregistrer une distribution
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'producteur_id' => 'required|exists:producteurs,id',
            'semence_id' => 'required|exists:semences,id',
            'quantite' => 'required|numeric|min:1',
            'superficie_emblevee' => 'required|numeric|min:0',
            'date_distribution' => 'required|date',
            'saison' => 'required|in:principale,contre-saison,hivernage',
            'credit_id' => 'nullable|exists:credits_agricoles,id',
            'observations' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $semence = Semence::find($validated['semence_id']);
            
            // Vérifier le stock
            if ($semence->stock_disponible < $validated['quantite']) {
                return back()->with('error', 'Stock insuffisant. Disponible : ' . $semence->stock_disponible . ' ' . $semence->unite);
            }
            
            $validated['code_distribution'] = 'DIST-' . str_pad(DistributionSemence::max('id') + 1, 6, '0', STR_PAD_LEFT);
            
            DistributionSemence::create($validated);
            
            // Mettre à jour le stock
            $semence->stock_disponible -= $validated['quantite'];
            $semence->save();
            
            DB::commit();
            
            return redirect()->route('admin.distributions.index')
                ->with('success', 'Distribution de semences enregistrée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de l\'enregistrement');
        }
    }

    /**
     * Afficher les détails
     */
    public function show($id)
    {
        $distribution = DistributionSemence::with(['producteur', 'semence', 'credit'])->findOrFail($id);
        return view('admin.distributions.show', compact('distribution'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $distribution = DistributionSemence::findOrFail($id);
        $producteurs = Producteur::where('statut', 'actif')->get();
        $semences = Semence::all();
        $credits = CreditAgricole::where('statut', 'actif')->get();
        
        return view('admin.distributions.edit', compact('distribution', 'producteurs', 'semences', 'credits'));
    }

    /**
     * Mettre à jour
     */
    public function update(Request $request, $id)
    {
        $distribution = DistributionSemence::findOrFail($id);

        $validated = $request->validate([
            'semence_id' => 'required|exists:semences,id',
            'quantite' => 'required|numeric|min:1',
            'superficie_emblevee' => 'required|numeric|min:0',
            'date_distribution' => 'required|date',
            'saison' => 'required|in:principale,contre-saison,hivernage',
            'credit_id' => 'nullable|exists:credits_agricoles,id',
            'observations' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Restaurer l'ancienne quantité dans le stock
            $oldSemence = Semence::find($distribution->semence_id);
            $oldSemence->stock_disponible += $distribution->quantite;
            $oldSemence->save();
            
            // Vérifier le nouveau stock
            $newSemence = Semence::find($validated['semence_id']);
            if ($newSemence->stock_disponible < $validated['quantite']) {
                return back()->with('error', 'Stock insuffisant');
            }
            
            $distribution->update($validated);
            
            // Mettre à jour le nouveau stock
            $newSemence->stock_disponible -= $validated['quantite'];
            $newSemence->save();
            
            DB::commit();
            
            return redirect()->route('admin.distributions.index')
                ->with('success', 'Distribution mise à jour avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la mise à jour');
        }
    }

    /**
     * Supprimer
     */
    public function destroy($id)
    {
        $distribution = DistributionSemence::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Restaurer le stock
            $semence = Semence::find($distribution->semence_id);
            $semence->stock_disponible += $distribution->quantite;
            $semence->save();
            
            $distribution->delete();
            DB::commit();
            
            return redirect()->route('admin.distributions.index')
                ->with('success', 'Distribution supprimée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la suppression');
        }
    }

    /**
     * Dashboard des distributions
     */
    public function dashboard()
    {
        $stats = [
            'total_distributions' => DistributionSemence::count(),
            'total_quantite' => DistributionSemence::sum('quantite'),
            'total_superficie' => DistributionSemence::sum('superficie_emblevee'),
            'distributions_par_saison' => DistributionSemence::select('saison', DB::raw('count(*) as total'))
                ->groupBy('saison')
                ->get(),
            'top_semences' => DistributionSemence::select('semence_id', DB::raw('SUM(quantite) as total'))
                ->with('semence')
                ->groupBy('semence_id')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get(),
            'distributions_recentes' => DistributionSemence::with(['producteur', 'semence'])
                ->orderBy('date_distribution', 'desc')
                ->limit(10)
                ->get()
        ];
        
        return view('admin.distributions.dashboard', compact('stats'));
    }
}