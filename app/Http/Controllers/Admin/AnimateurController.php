<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Animateur;
use App\Models\AgentTerrain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AnimateurController extends Controller
{
    /**
     * Afficher la liste des animateurs
     */
    public function index()
    {
        $animateurs = Animateur::withCount('agents')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.animateurs.index', compact('animateurs'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.animateurs.create');
    }

    /**
     * Enregistrer un nouvel animateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'email' => 'required|email|unique:animateurs',
            'contact' => 'required|string|max:20',
            'zone_responsabilite' => 'required|string',
            'region' => 'required|in:Centrale,Kara,Savanes',
            'date_embauche' => 'required|date',
            'qualifications' => 'nullable|string',
            'statut' => 'required|in:actif,inactif,en_conge'
        ]);

        $validated['code_animateur'] = 'ANIM-' . str_pad(Animateur::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validated['password'] = Hash::make('password123');
        $validated['nombre_producteurs_suivis'] = 0;

        Animateur::create($validated);

        return redirect()->route('admin.animateurs.index')
            ->with('success', 'Animateur ajouté avec succès.Mot de passe par défaut: password123');
    }

    /**
     * Afficher les détails d'un animateur
     */
    public function show($id)
    {
        $animateur = Animateur::with('agents')->findOrFail($id);
        
        // Récupérer les statistiques
        $stats = [
            'agents_count' => $animateur->agents->count(),
            'agents_actifs' => $animateur->agents->where('statut', 'actif')->count(),
            'total_producteurs' => $animateur->agents->sum('producteurs_enregistres'),
        ];
        
        return view('admin.animateurs.show', compact('animateur', 'stats'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $animateur = Animateur::findOrFail($id);
        return view('admin.animateurs.edit', compact('animateur'));
    }

    /**
     * Mettre à jour un animateur
     */
    public function update(Request $request, $id)
    {
        $animateur = Animateur::findOrFail($id);

        $validated = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'email' => 'required|email|unique:animateurs,email,' . $id,
            'contact' => 'required|string|max:20',
            'zone_responsabilite' => 'required|string',
            'region' => 'required|in:Centrale,Kara,Savanes',
            'date_embauche' => 'required|date',
            'qualifications' => 'nullable|string',
            'statut' => 'required|in:actif,inactif,en_conge'
        ]);

        $animateur->update($validated);

        return redirect()->route('admin.animateurs.index')
            ->with('success', 'Animateur mis à jour avec succès');
    }

    /**
     * Supprimer un animateur
     */
    public function destroy($id)
    {
        $animateur = Animateur::findOrFail($id);
        
        // Vérifier si l'animateur a des agents associés
        if ($animateur->agents()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer cet animateur car il a ' . $animateur->agents()->count() . ' agents associés.');
        }
        
        $animateur->delete();
        
        return redirect()->route('admin.animateurs.index')
            ->with('success', 'Animateur supprimé avec succès');
    }
/***********************************
 * Réinitialiser le mot de passe
 ****************************************/
public function resetPassword(Request $request, $id)
{
    $validated = $request->validate([
        'password' => 'required|min:6|confirmed'
    ]);

    $animateur = Animateur::findOrFail($id);
    $animateur->update([
        'password' => Hash::make($validated['password'])
    ]);

    return back()->with('success', 'Mot de passe réinitialisé avec succès. Nouveau mot de passe : ' . $validated['password']);
}

    /**
     * Changer le statut
     */
    public function toggleStatus($id)
    {
        $animateur = Animateur::findOrFail($id);
        $statuses = ['actif' => 'inactif', 'inactif' => 'actif', 'en_conge' => 'actif'];
        $animateur->update([
            'statut' => $statuses[$animateur->statut] ?? 'actif'
        ]);

        return back()->with('success', 'Statut modifié avec succès');
    }

    /**
     * Exporter la liste
     */
    public function export()
    {
        $animateurs = Animateur::withCount('agents')->get();
        
        $csv = "ID,Code,Nom,Email,Contact,Région,Zone,Nombre d'agents,Statut,Date embauche\n";
        
        foreach ($animateurs as $a) {
            $csv .= "{$a->id},{$a->code_animateur},{$a->nom_complet},{$a->email},{$a->contact},{$a->region},{$a->zone_responsabilite},{$a->agents_count},{$a->statut},{$a->date_embauche}\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="animateurs.csv"');
    }
}