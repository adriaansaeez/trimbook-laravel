<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Reserva;
use App\Models\Estilista;

class PagoSeeder extends Seeder
{
    public function run(): void
    {
        $reserva = Reserva::first();
        $estilista = Estilista::first();

        $pagos = [
            [
                'reserva_id' => $reserva->id,
                'estilista_id' => $estilista->id,
                'metodo_pago' => 'TARJETA',
                'importe' => 50.00,
                'fecha_pago' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'reserva_id' => $reserva->id,
                'estilista_id' => $estilista->id,
                'metodo_pago' => 'EFECTIVO',
                'importe' => 35.00,
                'fecha_pago' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('pagos')->insert($pagos);
    }
} 