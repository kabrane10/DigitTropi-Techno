<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier que le rôle existe
        $role = Role::where('slug', 'super_admin')->first();
        
        if (!$role) {
            $role = Role::create([
                'nom' => 'Super Administrateur',
                'slug' => 'super_admin'
            ]);
        }
        
        // Créer l'admin principal
        Admin::updateOrCreate(
            ['email' => 'admin@tropitechno.com'],
            [
                'nom' => 'Administrateur',
                'password' => Hash::make('admin123'),
                'role_id' => $role->id,
                'est_actif' => true,
            ]
        );
    }
}