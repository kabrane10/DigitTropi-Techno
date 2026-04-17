<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Controleur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ControleurController extends Controller
{
    /**
     * Afficher la liste des contrôleurs
     */
    public function index()
    {
        $controleurs = Controleur::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.controleurs.index', compact('controleurs'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.controleurs.create');
    }

    /**
     * Enregistrer un nouveau contrôleur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'email' => 'required|email|unique:controleurs',
            'telephone' => 'required|string|max:20',
            'region_controle' => 'required|string',
            'date_embauche' => 'required|date',
            'statut' => 'required|in:actif,inactif'
        ]);

        $validated['code_controleur'] = 'CTRL-' . str_pad(Controleur::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validated['password'] = Hash::make('password123');
        $validated['nombre_visites'] = 0;

        Controleur::create($validated);

        return redirect()->route('admin.controleurs.index')
            ->with('success', 'Contrôleur ajouté avec succès');
    }

    /**
     * Afficher les détails d'un contrôleur
     */
    public function show($id)
    {
        $controleur = Controleur::findOrFail($id);
        return view('admin.controleurs.show', compact('controleur'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $controleur = Controleur::findOrFail($id);
        return view('admin.controleurs.edit', compact('controleur'));
    }

    /**
     * Mettre à jour un contrôleur
     */
    public function update(Request $request, $id)
    {
        $controleur = Controleur::findOrFail($id);

        $validated = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'email' => 'required|email|unique:controleurs,email,' . $id,
            'telephone' => 'required|string|max:20',
            'region_controle' => 'required|string',
            'date_embauche' => 'required|date',
            'statut' => 'required|in:actif,inactif'
        ]);

        $controleur->update($validated);

        return redirect()->route('admin.controleurs.index')
            ->with('success', 'Contrôleur mis à jour avec succès');
    }

    /**
     * Supprimer un contrôleur
     */
    public function destroy($id)
    {
        $controleur = Controleur::findOrFail($id);
        $controleur->delete();
        
        return redirect()->route('admin.controleurs.index')
            ->with('success', 'Contrôleur supprimé avec succès');
    }

   /************************************************************
 * Réinitialiser le mot de passe
 *************************************************************/
public function resetPassword(Request $request, $id)
{
    $validated = $request->validate([
        'password' => 'required|min:6|confirmed'
    ]);

    $controleur = Controleur::findOrFail($id);
    $controleur->update([
        'password' => Hash::make($validated['password'])
    ]);

    return back()->with('success', 'Mot de passe réinitialisé avec succès. Nouveau mot de passe : ' . $validated['password']);
}

    /**
     * Changer le statut
     */
    public function toggleStatus($id)
    {
        $controleur = Controleur::findOrFail($id);
        $controleur->update([
            'statut' => $controleur->statut === 'actif' ? 'inactif' : 'actif'
        ]);

        return back()->with('success', 'Statut modifié avec succès');
    }

    /**
     * Exporter la liste
     */
    public function export()
    {
        $controleurs = Controleur::all();
        
        $csv = "ID,Code,Nom,Email,Téléphone,Région de contrôle,Nombre visites,Statut,Date embauche\n";
        
        foreach ($controleurs as $c) {
            $csv .= "{$c->id},{$c->code_controleur},{$c->nom_complet},{$c->email},{$c->telephone},{$c->region_controle},{$c->nombre_visites},{$c->statut},{$c->date_embauche}\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="controleurs.csv"');
    }
}