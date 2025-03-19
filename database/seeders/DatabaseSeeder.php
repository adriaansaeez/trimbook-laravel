<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear el rol ADMIN si no existe
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Verificar si el usuario admin ya existe antes de crearlo
        if (!User::where('email', 'super@admin.com')->exists()) {
            $admin = User::factory()->create([
                'username' => 'Admin User',
                'email' => 'super@admin.com',
                'password' => Hash::make('password'),
            ]);
            
            // Asignar el rol ADMIN al usuario administrador
            $admin->assignRole($adminRole);
        }
        

        // Crear el rol CLIENTE si no existe
        $clienteRole = Role::firstOrCreate(['name' => 'cliente']);

        // Verificar si el usuario cliente ya existe antes de crearlo
        if (!User::where('email', 'super@cliente.com')->exists()) {
            $cliente = User::factory()->create([
                'username' => 'Cliente User',
                'email' => 'super@cliente.com',
                'password' => Hash::make('password'),
            ]);
            
            // Asignar el rol CLIENTE al usuario cliente
            $cliente->assignRole($clienteRole);
        }


        // Crear el rol CLIENTE si no existe
        $estilistaRole = Role::firstOrCreate(['name' => 'estilista']);

        // Verificar si el usuario cliente ya existe antes de crearlo
        if (!User::where('email', 'super@estilista.com')->exists()) {
            $estilista = User::factory()->create([
                'username' => 'Estilista User',
                'email' => 'super@estilista.com',
                'password' => Hash::make('password'),
            ]);
            
            // Asignar el rol CLIENTE al usuario cliente
            $estilista->assignRole($estilistaRole);
        }
    }
}
