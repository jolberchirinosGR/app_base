<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear roles
        $admin = Role::factory()->create([
            'name' => 'Admin',
        ]);
        $userRole = Role::factory()->create([
            'name' => 'Usuario',
        ]);

        // Crear usuarios
        $adminUser = User::factory()->create([
            'name' => 'Jolber Chirinos',
            'email' => 'jrchirinos@gruporuiz.com',
            'password' => bcrypt(12345678),
            'id_role' => $admin->id,
        ]);
        $operario = User::factory()->create([
            'name' => 'Operario',
            'email' => 'test@gruporuiz.com',
            'password' => bcrypt(12345678),
            'id_role' => $userRole->id, // Asignar role de usuario
        ]);

        // Crear más usuarios
        $users = User::factory()->count(30)->create();

        // Crear tareas
        $tasks = Task::factory()->count(25)->create();

        // Relacionar usuarios y tareas
        // Incluye al usuario admin y operario en la colección de usuarios para las relaciones
        $allUsers = $users->concat([$adminUser, $operario]);

        foreach ($tasks as $task) {
            // Relacionar cada tarea con 1 a 3 usuarios aleatorios
            $task->users()->attach(
                $allUsers->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
