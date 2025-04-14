<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            [
                'nombre' => 'Corte de Cabello',
                'descripcion' => 'Servicio básico de corte de cabello',
                'precio' => 20.00,
                'duracion' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Tinte',
                'descripcion' => 'Aplicación de tinte profesional',
                'precio' => 50.00,
                'duracion' => 90,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Peinado',
                'descripcion' => 'Peinado para ocasiones especiales',
                'precio' => 35.00,
                'duracion' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('servicios')->insert($servicios);
    }
} 