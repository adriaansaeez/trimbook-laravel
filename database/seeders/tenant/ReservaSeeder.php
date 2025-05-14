<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Estilista;
use App\Models\Servicio;

class ReservaSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = User::where('email', 'super@cliente.com')->first();
        $estilista = Estilista::first();
        $servicio = Servicio::first();

        $reservas = [
            [
                'user_id' => $cliente->id,
                'estilista_id' => $estilista->id,
                'servicio_id' => $servicio->id,
                'fecha' => now()->addDays(1),
                'hora' => '10:00:00',
                'estado' => 'PENDIENTE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $cliente->id,
                'estilista_id' => $estilista->id,
                'servicio_id' => $servicio->id,
                'fecha' => now()->addDays(2),
                'hora' => '15:00:00',
                'estado' => 'CONFIRMADA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('reservas')->insert($reservas);
    }
} 