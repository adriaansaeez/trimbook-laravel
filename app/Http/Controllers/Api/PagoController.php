<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Reserva;
use Illuminate\Http\Request;
use App\Http\Resources\PagoResource;
use Carbon\Carbon;

class PagoController extends Controller
{
    /**
     * Muestra un listado de pagos.
     *
     * GET /api/v1/pagos
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = Pago::with(['reserva', 'reserva.estilista', 'reserva.servicio']);

        // Filtros opcionales
        if ($request->has('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        if ($request->has('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->has('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        return PagoResource::collection($query->paginate(15));
    }

    /**
     * Muestra los detalles de un pago específico.
     *
     * GET /api/v1/pagos/{pago}
     *
     * @param Pago $pago
     * @return PagoResource
     */
    public function show(Pago $pago)
    {
        return new PagoResource($pago->load(['reserva', 'reserva.estilista', 'reserva.servicio']));
    }

    /**
     * Registra un nuevo pago para una reserva.
     *
     * POST /api/v1/pagos
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'reserva_id' => 'required|exists:reservas,id',
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string|in:efectivo,tarjeta,transferencia',
            'estado' => 'required|string|in:pendiente,completado,cancelado'
        ]);

        $reserva = Reserva::findOrFail($data['reserva_id']);
        
        // Verificar si ya existe un pago completado para esta reserva
        if ($reserva->pagos()->where('estado', 'completado')->exists()) {
            return response()->json(['message' => 'Esta reserva ya tiene un pago registrado'], 422);
        }

        $pago = $reserva->pagos()->create($data);

        if ($data['estado'] === 'completado') {
            $reserva->update(['estado' => 'completada']);
        }

        return response()->json([
            'message' => 'Pago registrado correctamente',
            'pago' => new PagoResource($pago)
        ], 201);
    }

    /**
     * Actualiza el estado de un pago existente.
     *
     * PUT /api/v1/pagos/{pago}
     *
     * @param Request $request
     * @param Pago $pago
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Pago $pago)
    {
        $data = $request->validate([
            'estado' => 'required|string|in:pendiente,completado,cancelado',
            'metodo_pago' => 'sometimes|string|in:efectivo,tarjeta,transferencia',
            'monto' => 'sometimes|numeric|min:0'
        ]);

        $pago->update($data);

        if ($data['estado'] === 'completado') {
            $pago->reserva->update(['estado' => 'completada']);
        }

        return response()->json([
            'message' => 'Pago actualizado correctamente',
            'pago' => new PagoResource($pago)
        ]);
    }

    /**
     * Obtiene un resumen de pagos por período.
     *
     * GET /api/v1/pagos/resumen
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resumen(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $resumen = Pago::where('estado', 'completado')
            ->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
            ->selectRaw('
                COUNT(*) as total_pagos,
                SUM(monto) as monto_total,
                metodo_pago,
                DATE(created_at) as fecha
            ')
            ->groupBy('metodo_pago', 'fecha')
            ->get();

        return response()->json([
            'resumen' => $resumen,
            'total_general' => $resumen->sum('monto_total')
        ]);
    }
} 