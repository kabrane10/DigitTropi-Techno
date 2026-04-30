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
        // 1. Récupérer le rôle Administrateur créé par le RoleSeeder
        // On utilise 'admin' car c'est le slug défini dans notre RoleSeeder
        $role = Role::where('slug', 'admin')->first();
        
        // Sécurité au cas où le RoleSeeder n'aurait pas été lancé
        if (!$role) {
            $role = Role::create([
                'nom' => 'Administrateur',
                'slug' => 'admin'
            ]);
        }
        
        // 2. Créer ou mettre à jour l'admin principal
        Admin::updateOrCreate(
            ['email' => 'admin1@tropitechno.com'], // On cherche par l'email
            [
                'nom' => 'Administrateur',
                'password' => Hash::make('@dmin1234@'), // Pensez à changer ce mot de passe plus tard
                'role_id' => $role->id,
                'est_actif' => true,
            ]
        );
    }
}