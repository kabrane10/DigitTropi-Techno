<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nom' => 'Administrateur', 'slug' => 'admin'],
            ['nom' => 'Animateur', 'slug' => 'animateur'],
            ['nom' => 'Contrôleur', 'slug' => 'controleur'],
            ['nom' => 'Agent de Terrain', 'slug' => 'agent_terrain'],
        ];
        
        foreach ($roles as $role) {
            // firstOrCreate évite les erreurs de doublons si vous relancez le seeder
            Role::firstOrCreate(
                ['slug' => $role['slug']], // On cherche par le slug
                ['nom' => $role['nom']]    // On crée avec le nom s'il n'existe pas
            );
        }
    }
}