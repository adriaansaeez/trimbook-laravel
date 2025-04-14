<?php

namespace App\Exports;

use App\Models\Reserva;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReservasExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $estilistaId;

    public function __construct($estilistaId = null)
    {
        $this->estilistaId = $estilistaId;
    }

    public function query()
    {
        $query = Reserva::query()
            ->with(['servicio', 'estilista', 'user', 'pago']);
            
        if ($this->estilistaId) {
            $query->where('estilista_id', $this->estilistaId);
        }
        
        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Cliente',
            'Servicio',
            'Estilista',
            'Fecha',
            'Hora',
            'Estado',
            'Precio',
            'Método de Pago',
            'Fecha de Pago',
        ];
    }

    public function map($reserva): array
    {
        return [
            $reserva->id,
            $reserva->user->name,
            $reserva->servicio->nombre,
            $reserva->estilista->nombre,
            $reserva->fecha,
            $reserva->hora,
            $reserva->estado,
            $reserva->servicio->precio,
            $reserva->pago ? $reserva->pago->metodo_pago : 'No pagado',
            $reserva->pago ? $reserva->pago->fecha_pago : 'N/A',
        ];
    }
} 