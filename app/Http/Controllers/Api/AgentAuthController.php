<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgentTerrain;
use App\Models\Cooperative;
use App\Models\Semence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class AgentAuthController extends Controller
{
    /**
     * Connexion de l'agent terrain
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required|string'
        ]);

        $agent = AgentTerrain::where('email', $request->email)->first();

        if (!$agent || !Hash::check($request->password, $agent->password)) {
            return response()->json([
                'message' => 'Identifiants incorrects'
            ], 401);
        }

        // Vérifier que l'agent est actif
        if ($agent->statut !== 'actif') {
            return response()->json([
                'message' => 'Votre compte est désactivé'
            ], 403);
        }

        // Créer le token
        $token = $agent->createToken($request->device_name)->plainTextToken;

        // Données essentielles pour le mode hors-ligne
        $offlineData = [
            'zones' => ['Centrale', 'Kara', 'Savanes'],
            'cooperatives' => Cooperative::where('statut', 'active')->get(['id', 'nom', 'region']),
            'semences' => Semence::where('est_actif', true)->get(['id', 'nom', 'variete', 'unite']),
            'cultures' => ['Maïs', 'Soja', 'Arachide', 'Sésame', 'Riz', 'Gombo', 'Igname', 'Manioc', 'Autre']
        ];

        // Mettre à jour la dernière connexion
        $agent->update(['last_login' => now()]);

        return response()->json([
            'success' => true,
            'token' => $token,
            'agent' => [
                'id' => $agent->id,
                'nom_complet' => $agent->nom_complet,
                'code_agent' => $agent->code_agent,
                'email' => $agent->email,
                'telephone' => $agent->telephone,
                'zone_affectation' => $agent->zone_affectation,
                'superviseur_id' => $agent->superviseur_id
            ],
            'offline_data' => $offlineData
        ]);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnecté avec succès']);
    }

    /**
     * Informations de l'agent connecté
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Mettre à jour le token FCM pour notifications push (optionnel)
     */
    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string'
        ]);

        $request->user()->update(['fcm_token' => $request->fcm_token]);

        return response()->json(['message' => 'Token FCM mis à jour']);
    }
}