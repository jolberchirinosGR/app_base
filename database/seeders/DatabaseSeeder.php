<?php

namespace Database\Seeders;

use App\Models\Issue;
use App\Models\LostObject;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //USUARIOS POR DEFECTO PRECARGADOS EN EL SISTEMA
        User::factory()->create([
            'name' => 'Jolber Chirinos',
            'email' => 'jrchirinos@gruporuiz.com',
            'password' => bcrypt(12345678),
        ]);
        User::factory()->create([
            'name' => 'Operario',
            'email' => 'test@gruporuiz.com',
            'password' => bcrypt(12345678),
        ]);

        //INCIENCIAS
        Issue::factory(10)->create();

        //INCIENCIAS
        LostObject::factory(10)->create();
    }
}
