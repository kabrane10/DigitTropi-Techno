<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producteur;
use App\Models\SuiviParcellaire;
use App\Models\Animateur;
use App\Models\DistributionSemence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuiviController extends Controller
{
    /**
     * Dashboard de suivi
     */
    public function index()
    {
        $stats = [
            'total_producteurs' => Producteur::count(),
            'producteurs_avec_suivi' => SuiviParcellaire::distinct('producteur_id')->count('producteur_id'),
            'taux_couverture' => $this->calculerTauxCouverture(),
            'suivis_mois' => SuiviParcellaire::whereMonth('date_suivi', now()->month)->count(),
        ];
        
        $suivis_recents = SuiviParcellaire::with(['producteur', 'animateur'])
            ->orderBy('date_suivi', 'desc')
            ->limit(10)
            ->get();
        
        $producteurs_sans_suivi = Producteur::whereDoesntHave('suivisParcellaires')
            ->limit(10)
            ->get();
        
        $sante_cultures = SuiviParcellaire::select('sante_cultures', DB::raw('count(*) as total'))
            ->groupBy('sante_cultures')
            ->get();
        
        return view('admin.suivis.index', compact('stats', 'suivis_recents', 'producteurs_sans_suivi', 'sante_cultures'));
    }

    /**
     * Liste des suivis
     */
    public function liste(Request $request)
    {
        $query = SuiviParcellaire::with(['producteur', 'animateur', 'distributionSemence']);
        
        if ($request->filled('producteur_id')) {
            $query->where('producteur_id', $request->producteur_id);
        }
        if ($request->filled('animateur_id')) {
            $query->where('animateur_id', $request->animateur_id);
        }
        
        $suivis = $query->orderBy('date_suivi', 'desc')->paginate(20);
        $producteurs = Producteur::where('statut', 'actif')->get();
        $animateurs = Animateur::where('statut', 'actif')->get();
        
        return view('admin.suivis.liste', compact('suivis', 'producteurs', 'animateurs'));
    }

    public function create(Request $request)
{
    $producteurs = Producteur::where('statut', 'actif')->get();
    $animateurs = Animateur::where('statut', 'actif')->get();
    $distributions = DistributionSemence::with('semence')
        ->orderBy('date_distribution', 'desc')
        ->get();
    
    $producteur_selectionne = null;
    if ($request->producteur_id) {
        $producteur_selectionne = Producteur::find($request->producteur_id);
        // Charger les distributions du producteur sélectionné
        $distributions = DistributionSemence::where('producteur_id', $request->producteur_id)
            ->with('semence')
            ->orderBy('date_distribution', 'desc')
            ->get();
    }

    return view('admin.suivis.create', compact('producteurs', 'animateurs', 'distributions', 'producteur_selectionne'));
}
    /**
     * Enregistrer un suivi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'producteur_id' => 'required|exists:producteurs,id',
            'animateur_id' => 'required|exists:animateurs,id',
            'date_suivi' => 'required|date',
            'superficie_actuelle' => 'required|numeric|min:0',
            'hauteur_plantes' => 'nullable|numeric',
            'stade_croissance' => 'required|string',
            'sante_cultures' => 'required|in:excellente,bonne,moyenne,mauvaise,critique',
            'taux_levée' => 'nullable|integer|min:0|max:100',
            'problemes_constates' => 'nullable|string',
            'recommandations' => 'nullable|string',
            'actions_prises' => 'nullable|string',
            'distribution_semence_id' => 'nullable|exists:distributions_semences,id'
        ]);

          // Si distribution_semence_id n'est pas fourni, le mettre à null
    if (!isset($validated['distribution_semence_id'])) {
        $validated['distribution_semence_id'] = null;
    }

    $validated['code_suivi'] = 'SUIVI-' . str_pad(SuiviParcellaire::max('id') + 1, 6, '0', STR_PAD_LEFT);
    
    SuiviParcellaire::create($validated);

    return redirect()->route('admin.suivi.liste')
        ->with('success', 'Suivi enregistré avec succès');
}

    /**
     * Détails d'un suivi
     */
    public function show($id)
    {
        $suivi = SuiviParcellaire::with(['producteur', 'animateur', 'distributionSemence.semence'])->findOrFail($id);
        return view('admin.suivis.show', compact('suivi'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $suivi = SuiviParcellaire::findOrFail($id);
        $producteurs = Producteur::where('statut', 'actif')->get();
        $animateurs = Animateur::where('statut', 'actif')->get();
        
        return view('admin.suivis.edit', compact('suivi', 'producteurs', 'animateurs'));
    }

    /**
     * Mettre à jour un suivi
     */
    public function update(Request $request, $id)
    {
        $suivi = SuiviParcellaire::findOrFail($id);
        
        $validated = $request->validate([
            'date_suivi' => 'required|date',
            'superficie_actuelle' => 'required|numeric|min:0',
            'hauteur_plantes' => 'nullable|numeric',
            'stade_croissance' => 'required|string',
            'sante_cultures' => 'required|in:excellente,bonne,moyenne,mauvaise,critique',
            'taux_levée' => 'nullable|integer|min:0|max:100',
            'problemes_constates' => 'nullable|string',
            'recommandations' => 'nullable|string',
            'actions_prises' => 'nullable|string',
            'distribution_semence_id' => 'nullable|exists:distributions_semences,id'
        ]);

        $suivi->update($validated);
        
        return redirect()->route('admin.suivi.liste')
            ->with('success', 'Suivi mis à jour avec succès');
    }

    /**
     * Supprimer un suivi
     */
    public function destroy($id)
    {
        $suivi = SuiviParcellaire::findOrFail($id);
        $suivi->delete();
        
        return redirect()->route('admin.suivi.liste')
            ->with('success', 'Suivi supprimé avec succès');
    }

    /**
     * Suivi par producteur
     */
    public function producteur($id)
    {
        $producteur = Producteur::findOrFail($id);
        $suivis = SuiviParcellaire::where('producteur_id', $id)
            ->with('animateur')
            ->orderBy('date_suivi', 'desc')
            ->get();
        
        $evolution = $this->calculerEvolution($suivis);
        
        return view('admin.suivi.producteur', compact('producteur', 'suivis', 'evolution'));
    }

    /**
 * Imprimer un suivi parcellaire
 */
   public function print($id)
   {
    $suivi = SuiviParcellaire::with(['producteur', 'animateur', 'distributionSemence.semence'])->findOrFail($id);
    return view('admin.suivis.print', compact('suivi'));
   }
    /**
     * Export des suivis
     */
    public function export()
    {
        $suivis = SuiviParcellaire::with(['producteur', 'animateur'])->get();
        
        $csv = "Code,Date,Producteur,Animateur,Superficie,Hauteur,Stade,Santé,Taux levée,Problèmes,Recommandations\n";
        
        foreach ($suivis as $s) {
            $csv .= "{$s->code_suivi},{$s->date_suivi},{$s->producteur->nom_complet},{$s->animateur->nom_complet},{$s->superficie_actuelle},{$s->hauteur_plantes},{$s->stade_croissance},{$s->sante_cultures},{$s->taux_levée},\"{$s->problemes_constates}\",\"{$s->recommandations}\"\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="suivis.csv"');
    }

    private function calculerTauxCouverture()
    {
        $total = Producteur::count();
        $suivis = SuiviParcellaire::distinct('producteur_id')->count('producteur_id');
        
        return $total > 0 ? ($suivis / $total) * 100 : 0;
    }

    private function calculerEvolution($suivis)
    {
        if ($suivis->count() < 2) return null;
        
        $premier = $suivis->last();
        $dernier = $suivis->first();
        
        return [
            'superficie' => $dernier->superficie_actuelle - $premier->superficie_actuelle,
            'hauteur' => $dernier->hauteur_plantes - ($premier->hauteur_plantes ?? 0),
        ];
    }
}