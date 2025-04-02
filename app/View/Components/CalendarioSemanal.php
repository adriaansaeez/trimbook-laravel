<?php

// app/View/Components/CalendarioSemanal.php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Reserva;
use App\Models\HorariosEstilista;
use Carbon\Carbon;

class CalendarioSemanal extends Component
{
    public $reservas;
    public $inicioSemana;
    public $horasDisponibles;

    public function __construct($estilistaId, $semana = null)
    {
        $baseDate = $semana ? Carbon::parse($semana) : Carbon::now();
        $this->inicioSemana = $baseDate->copy()->startOfWeek();
        $finSemana = $this->inicioSemana->copy()->endOfWeek();

        $this->reservas = Reserva::with('servicio')
            ->where('estilista_id', $estilistaId)
            ->whereBetween('fecha', [$this->inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')])
            ->get();

        $this->horasDisponibles = [];

        $horarioEstilista = HorariosEstilista::with('horario')
            ->where('estilista_id', $estilistaId)
            ->orderByDesc('fecha_inicio')
            ->first();

        if ($horarioEstilista && $horarioEstilista->horario) {
            $this->horasDisponibles = $horarioEstilista->horario->horario;
        }
    }

    public function render()
    {
        return view('components.calendario-semanal');
    }
}
