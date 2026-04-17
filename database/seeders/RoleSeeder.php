<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nom' => 'Super Administrateur', 'slug' => 'super_admin'],
            ['nom' => 'Rédacteur', 'slug' => 'redacteur'],
            ['nom' => 'Manager', 'slug' => 'manager'],
            ['nom' => 'Agent Terrain', 'slug' => 'agent_terrain'],
        ];
        
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}