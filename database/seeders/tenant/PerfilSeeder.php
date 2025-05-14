<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PerfilSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            // Verificar si ya existe un perfil para este usuario
            if (!DB::table('perfiles')->where('usuario_id', $user->id)->exists()) {
                DB::table('perfiles')->insert([
                    'usuario_id' => $user->id,
                    'nombre' => $user->username,
                    'apellidos' => 'Apellido ' . $user->username,
                    'telefono' => '600000000',
                    'direccion' => 'Calle Principal 123',
                    'foto_perfil' => 'default.jpg',
                    'instagram_url' => 'https://instagram.com/' . strtolower(str_replace(' ', '', $user->username)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 