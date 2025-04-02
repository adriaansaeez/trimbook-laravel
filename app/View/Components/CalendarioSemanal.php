<?php

namespace App\View\Components;

// app/View/Components/CalendarioSemanal.php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Reserva;
use Carbon\Carbon;

class CalendarioSemanal extends Component
{
    public $reservas;
    public $inicioSemana;

    public function __construct($estilistaId)
    {
        $this->inicioSemana = Carbon::now()->startOfWeek();
        $finSemana = $this->inicioSemana->copy()->endOfWeek();

        $this->reservas = Reserva::with('servicio')
            ->where('estilista_id', $estilistaId)
            ->whereBetween('fecha', [$this->inicioSemana, $finSemana])
            ->get();
    }

    public function render()
    {
        return view('components.calendario-semanal');
    }
}
