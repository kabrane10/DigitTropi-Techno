<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. D'abord, on crée tous les rôles
        $this->call(RoleSeeder::class);

        // On récupère les rôles pour lier les utilisateurs
        $roleAdmin = Role::where('slug', 'admin')->first();
        $roleAnimateur = Role::where('slug', 'animateur')->first();
        $roleControleur = Role::where('slug', 'controleur')->first();
        $roleAgent = Role::where('slug', 'agent_terrain')->first();

        // 2. Création de l'Administrateur (via AdminSeeder ou directement ici)
        Admin::updateOrCreate(
            ['email' => 'admin1@tropitechno.com'],
            [
                'nom' => 'Administrateur Principal',
                'password' => Hash::make('@dmin1234@'),
                'role_id' => $roleAdmin->id,
                'est_actif' => true,
            ]
        );

        // 3. Création de l'Animateur
        User::updateOrCreate(
            ['email' => 'animateur1@tropitechno.com'],
            [
                'name' => 'Animateur Tropi',
                'password' => Hash::make('@anmt1234@'),
                'role_id' => $roleAnimateur->id,
                //'est_actif' => true, // Ajoutez cette ligne si votre table users a cette colonne
            ]
        );

        // 4. Création du Contrôleur
        User::updateOrCreate(
            ['email' => 'controleur1@tropitechno.com'],
            [
                'name' => 'Contrôleur Tropi',
                'password' => Hash::make('@ctrl123@'),
                'role_id' => $roleControleur->id,
            ]
        );

        // 5. Création de l'Agent de Terrain
        User::updateOrCreate(
            ['email' => 'agent1@tropitechno.com'],
            [
                'name' => 'Agent Terrain Tropi',
                'password' => Hash::make('@agt1234@'),
                'role_id' => $roleAgent->id,
            ]
        );
    }
}