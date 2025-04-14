<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        $horarios = [
            [
                'nombre' => 'Horario Laboral',
                'horario' => json_encode([
                    'Lunes' => ['09:00' => '18:00'],
                    'Martes' => ['09:00' => '18:00'],
                    'Miércoles' => ['09:00' => '18:00'],
                    'Jueves' => ['09:00' => '18:00'],
                    'Viernes' => ['09:00' => '18:00'],
                ]),
                'registro_horas_semanales' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('horarios')->insert($horarios);
    }
} 