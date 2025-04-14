<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PagoController extends Controller
{

    public function store(Request $request)
    {
        try {
            $request->validate([
                'reserva_id' => 'required|exists:reservas,id',
                'metodo_pago' => 'required|in:EFECTIVO,TARJETA,BIZUM,TRANSFERENCIA',
                'importe' => 'required|numeric|min:0',
            ]);

            $reserva = Reserva::findOrFail($request->reserva_id);

            // Verificar si ya fue completada
            if ($reserva->estado === 'COMPLETADA') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta reserva ya ha sido completada'
                ], 400);
            }

            // Verificar si está en estado válido
            if (!in_array($reserva->estado, ['PENDIENTE', 'CONFIRMADA'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'El estado de la reserva no permite procesar el pago'
                ], 400);
            }

            // Crear el pago
            $pago = Pago::create([
                'reserva_id' => $request->reserva_id,
                'estilista_id' => $reserva->estilista_id,
                'metodo_pago' => $request->metodo_pago,
                'importe' => $request->importe,
                'fecha_pago' => now(),
            ]);

            // Marcar reserva como completada
            $reserva->estado = 'COMPLETADA';
            $reserva->save();

            return response()->json([
                'success' => true,
                'message' => 'Pago procesado correctamente',
                'pago' => $pago
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al procesar pago: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pago: ' . $e->getMessage()
            ], 500);
        }
    }
}
