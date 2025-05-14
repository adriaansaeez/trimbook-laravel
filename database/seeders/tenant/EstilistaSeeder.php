<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class EstilistaSeeder extends Seeder
{
    public function run(): void
    {
        $estilistaUser = User::where('email', 'super@estilista.com')->first();
        
        // Verificar si ya existe un estilista para este usuario
        if (!DB::table('estilistas')->where('user_id', $estilistaUser->id)->exists()) {
            DB::table('estilistas')->insert([
                'user_id' => $estilistaUser->id,
                'nombre' => $estilistaUser->username,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 