<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Permisos de usuarios
        $admin = Role::factory()->create([
            'name' => 'Admin',
        ]);
        $driver = Role::factory()->create([
            'name' => 'Usuario',
        ]);

        //USUARIOS POR DEFECTO PRECARGADOS EN EL SISTEMA
        User::factory()->create([
            'name' => 'Jolber Chirinos',
            'email' => 'jrchirinos@gruporuiz.com',
            'password' => bcrypt(12345678),
            'id_role' => $admin->id,
        ]);
        User::factory()->create([
            'name' => 'Operario',
            'email' => 'test@gruporuiz.com',
            'password' => bcrypt(12345678),
        ]);

        User::factory()->count(30)->create();
    }
}
