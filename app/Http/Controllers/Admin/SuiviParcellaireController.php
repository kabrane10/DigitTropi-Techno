<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuiviParcellaire;
use App\Models\Producteur;
use App\Models\Animateur;
use App\Models\DistributionSemence;
use Illuminate\Http\Request;

class SuiviParcellaireController extends Controller
{
    /**
     * Afficher la liste des suivis parcellaires
     */
    public function index()
    {
        $suivis = SuiviParcellaire::with(['producteur', 'animateur'])
            ->orderBy('date_suivi', 'desc')
            ->paginate(20);
        
        return view('admin.suivi-parcellaire.index', compact('suivis'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $producteurs = Producteur::where('statut', 'actif')->get();
        $animateurs = Animateur::where('statut', 'actif')->get();
        
        return view('admin.suivi-parcellaire.create', compact('producteurs', 'animateurs'));
    }

    /**
     * Enregistrer un nouveau suivi
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

        $validated['code_suivi'] = 'SUIVI-' . str_pad(SuiviParcellaire::max('id') + 1, 6, '0', STR_PAD_LEFT);
        
        SuiviParcellaire::create($validated);
        
        return redirect()->route('admin.suivi-parcellaire.index')
            ->with('success', 'Suivi parcellaire enregistré avec succès');
    }

    /**
     * Afficher un suivi
     */
    public function show($id)
    {
        $suivi = SuiviParcellaire::with(['producteur', 'animateur', 'distributionSemence.semence'])
            ->findOrFail($id);
        
        return view('admin.suivi-parcellaire.show', compact('suivi'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $suivi = SuiviParcellaire::findOrFail($id);
        $producteurs = Producteur::where('statut', 'actif')->get();
        $animateurs = Animateur::where('statut', 'actif')->get();
        
        return view('admin.suivi-parcellaire.edit', compact('suivi', 'producteurs', 'animateurs'));
    }

    /**
     * Mettre à jour
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
        ]);

        $suivi->update($validated);
        
        return redirect()->route('admin.suivi-parcellaire.index')
            ->with('success', 'Suivi mis à jour avec succès');
    }

    /**
     * Supprimer
     */
    public function destroy($id)
    {
        $suivi = SuiviParcellaire::findOrFail($id);
        $suivi->delete();
        
        return redirect()->route('admin.suivi-parcellaire.index')
            ->with('success', 'Suivi supprimé avec succès');
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
        
        return view('admin.suivi-parcellaire.by-producteur', compact('producteur', 'suivis'));
    }

    /**
     * Exporter les suivis
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
            ->header('Content-Disposition', 'attachment; filename="suivis_parcellaires.csv"');
    }
}