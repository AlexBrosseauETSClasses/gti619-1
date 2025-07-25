<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Création des rôles
        $adminRole = Role::firstOrCreate(['name' => 'Administrateur']);
        $resRole = Role::firstOrCreate(['name' => 'Préposé aux clients résidentiels']);
        $affRole = Role::firstOrCreate(['name' => 'Préposé aux clients d’affaire']);

        // Création des utilisateurs avec vérification
        if (!User::where('email', 'admin@example.com')->exists()) {
            $admin = User::create([
                'name' => 'Administrateur',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
            $admin->assignRole($adminRole);
        }

        if (!User::where('email', 'res@example.com')->exists()) {
            $res = User::create([
                'name' => 'Utilisateur1',
                'email' => 'res@example.com',
                'password' => bcrypt('password'),
            ]);
            $res->assignRole($resRole);
        }

        if (!User::where('email', 'aff@example.com')->exists()) {
            $aff = User::create([
                'name' => 'Utilisateur2',
                'email' => 'aff@example.com',
                'password' => bcrypt('password'),
            ]);
            $aff->assignRole($affRole);
        }
    }
}
