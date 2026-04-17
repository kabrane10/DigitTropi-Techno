<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentTerrain;
use App\Models\Animateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AgentTerrainController extends Controller
{
    /**
     * Afficher la liste des agents terrain
     */
    public function index()
    {
        $agents = AgentTerrain::with('superviseur')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.agents.index', compact('agents'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $animateurs = Animateur::where('statut', 'actif')->get();
        return view('admin.agents.create', compact('animateurs'));
    }

    /**
     * Enregistrer un nouvel agent terrain
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'email' => 'required|email|unique:agents_terrain',
            'telephone' => 'required|string|max:20',
            'zone_affectation' => 'required|string',
            'superviseur_id' => 'nullable|exists:animateurs,id',
            'date_embauche' => 'required|date',
            'statut' => 'required|in:actif,inactif'
        ]);

        $validated['code_agent'] = 'AGT-' . str_pad(AgentTerrain::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validated['password'] = Hash::make('password123');
        $validated['producteurs_enregistres'] = 0;

        AgentTerrain::create($validated);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent terrain ajouté avec succès');
    }

    /**
     * Afficher les détails d'un agent terrain
     */
    public function show($id)
    {
        $agent = AgentTerrain::with('superviseur')->findOrFail($id);
        
        // Récupérer les producteurs enregistrés par cet agent
        $producteurs = [];
        if (Schema::hasTable('producteurs')) {
            $producteurs = \App\Models\Producteur::where('agent_terrain_id', $id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }
        
        return view('admin.agents.show', compact('agent', 'producteurs'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $agent = AgentTerrain::findOrFail($id);
        $animateurs = Animateur::where('statut', 'actif')->get();
        
        return view('admin.agents.edit', compact('agent', 'animateurs'));
    }

    /**
     * Mettre à jour un agent terrain
     */
    public function update(Request $request, $id)
    {
        $agent = AgentTerrain::findOrFail($id);

        $validated = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'email' => 'required|email|unique:agents_terrain,email,' . $id,
            'telephone' => 'required|string|max:20',
            'zone_affectation' => 'required|string',
            'superviseur_id' => 'nullable|exists:animateurs,id',
            'date_embauche' => 'required|date',
            'statut' => 'required|in:actif,inactif'
        ]);

        $agent->update($validated);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent terrain mis à jour avec succès');
    }

    /**
     * Supprimer un agent terrain
     */
    public function destroy($id)
    {
        $agent = AgentTerrain::findOrFail($id);
        
        // Vérifier si l'agent a des producteurs associés
        if (Schema::hasTable('producteurs')) {
            $producteursCount = \App\Models\Producteur::where('agent_terrain_id', $id)->count();
            if ($producteursCount > 0) {
                return back()->with('error', 'Impossible de supprimer cet agent car il a ' . $producteursCount . ' producteurs associés.');
            }
        }
        
        $agent->delete();
        
        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent terrain supprimé avec succès');
    }

    /*************************************************
 * Réinitialiser le mot de passe
 *******************************************************/
public function resetPassword(Request $request, $id)
{
    $validated = $request->validate([
        'password' => 'required|min:6|confirmed'
    ]);

    $agent = AgentTerrain::findOrFail($id);
    $agent->update([
        'password' => Hash::make($validated['password'])
    ]);

    return back()->with('success', 'Mot de passe réinitialisé avec succès.  Nouveau mot de passe : ' . $validated['password']);
}

    /**
     * Changer le statut d'un agent
     */
    public function toggleStatus($id)
    {
        $agent = AgentTerrain::findOrFail($id);
        $agent->update([
            'statut' => $agent->statut === 'actif' ? 'inactif' : 'actif'
        ]);

        return back()->with('success', 'Statut modifié avec succès');
    }

    /**
     * Exporter la liste des agents
     */
    public function export()
    {
        $agents = AgentTerrain::with('superviseur')->get();
        
        // Logique d'export CSV/Excel
        $csv = "ID,Code,Nom,Email,Téléphone,Zone,Statut,Date embauche\n";
        
        foreach ($agents as $agent) {
            $csv .= "{$agent->id},{$agent->code_agent},{$agent->nom_complet},{$agent->email},{$agent->telephone},{$agent->zone_affectation},{$agent->statut},{$agent->date_embauche}\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="agents_terrain.csv"');
    }
}