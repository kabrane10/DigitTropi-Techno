<?php

namespace App\Http\Controllers\Animateur;

use App\Http\Controllers\Controller;
use App\Models\AgentTerrain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    public function index()
    {
        $animateur = Auth::guard('animateur')->user();
        $agents = AgentTerrain::where('superviseur_id', $animateur->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('animateur.agents.index', compact('agents'));
    }
    public function create() { return view('animateur.agents.create'); }
    public function store(Request $request)
    {
        $validated = $request->validate(['nom_complet'=>'required','email'=>'required|email|unique:agents_terrain','telephone'=>'required','zone_affectation'=>'required','date_embauche'=>'required']);
        $validated['code_agent'] = 'AGT-'.str_pad(AgentTerrain::max('id')+1,6,'0',STR_PAD_LEFT);
        $validated['password'] = Hash::make('password123');
        $validated['superviseur_id'] = Auth::guard('animateur')->id();
        $validated['statut'] = 'actif';
        AgentTerrain::create($validated);
        return redirect()->route('animateur.agents.index')->with('success', 'Agent ajouté. Mot de passe: password123');
    }
    public function show($id)
    {
        $animateur = Auth::guard('animateur')->user();
        $agent = AgentTerrain::where('id',$id)->where('superviseur_id',$animateur->id)->firstOrFail();
        $producteurs = \App\Models\Producteur::where('agent_terrain_id',$agent->id)->paginate(10);
        return view('animateur.agents.show', compact('agent','producteurs'));
    }
    public function edit($id)
    {
        $animateur = Auth::guard('animateur')->user();
        $agent = AgentTerrain::where('id',$id)->where('superviseur_id',$animateur->id)->firstOrFail();
        return view('animateur.agents.edit', compact('agent'));
    }
    public function update(Request $request, $id)
    {
        $animateur = Auth::guard('animateur')->user();
        $agent = AgentTerrain::where('id',$id)->where('superviseur_id',$animateur->id)->firstOrFail();
        $validated = $request->validate(['nom_complet'=>'required','email'=>'required|email|unique:agents_terrain,email,'.$id,'telephone'=>'required','zone_affectation'=>'required','statut'=>'required']);
        $agent->update($validated);
        return redirect()->route('animateur.agents.index')->with('success','Agent mis à jour');
    }
    public function destroy($id)
    {
        $animateur = Auth::guard('animateur')->user();
        $agent = AgentTerrain::where('id',$id)->where('superviseur_id',$animateur->id)->firstOrFail();
        if($agent->producteurs()->count()>0) return back()->with('error','Impossible de supprimer un agent avec des producteurs.');
        $agent->delete();
        return redirect()->route('animateur.agents.index')->with('success','Agent supprimé');
    }
    public function resetPassword($id)
    {
        $animateur = Auth::guard('animateur')->user();
        $agent = AgentTerrain::where('id',$id)->where('superviseur_id',$animateur->id)->firstOrFail();
        $newPassword = 'password123';
        $agent->update(['password'=>Hash::make($newPassword)]);
        return back()->with('success','Mot de passe réinitialisé. Nouveau mot de passe: '.$newPassword);
    }
}