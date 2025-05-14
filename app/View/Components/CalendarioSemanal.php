<?php

// app/View/Components/CalendarioSemanal.php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Reserva;
use App\Models\HorariosEstilista;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarioSemanal extends Component
{
    public $reservas;
    public $inicioSemana;
    public $horasDisponibles;
    public $estilistaId;
    public $userId;
    public $esEstilista;
    public $esCliente;
    public $esAdmin;

    public function __construct($estilistaId = null, $inicioSemana = null, $reservas = null, $horasDisponibles = null)
    {
        $this->estilistaId = $estilistaId;
        $this->userId = Auth::id();
        $this->esEstilista = Auth::user()->hasRole('estilista');
        $this->esCliente = Auth::user()->hasRole('cliente');
        $this->esAdmin = Auth::user()->hasRole('admin');
        
        // Calcular siempre la fecha de inicio de la semana actual
        $baseDate = $inicioSemana ? Carbon::parse($inicioSemana) : Carbon::now();
        $this->inicioSemana = $baseDate->copy()->startOfWeek(Carbon::MONDAY);
        
        // Si se proporcionan los parámetros, usarlos directamente
        if ($inicioSemana && $reservas && $horasDisponibles) {
            $this->reservas = $reservas;
            $this->horasDisponibles = $horasDisponibles;
            return;
        }
        
        // Si no se proporcionan los parámetros, calcularlos como antes
        $finSemana = $this->inicioSemana->copy()->endOfWeek();

        // Obtener reservas según el rol del usuario
        if ($this->esEstilista && $estilistaId) {
            // Si es estilista, mostrar sus propias reservas
            $this->reservas = Reserva::with('servicio', 'user')
                ->where('estilista_id', $estilistaId)
                ->whereNotIn('estado', ['CANCELADA'])
                ->whereBetween('fecha', [$this->inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')])
                ->get();
        } else if ($this->esCliente) {
            // Si es cliente, mostrar sus propias reservas
            $this->reservas = Reserva::with('servicio', 'estilista.user')
                ->where('user_id', $this->userId)
                ->whereNotIn('estado', ['CANCELADA'])
                ->whereBetween('fecha', [$this->inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')])
                ->get();
        } else if ($this->esAdmin) {
            // Si es admin, mostrar todas las reservas
            $this->reservas = Reserva::with('servicio', 'user', 'estilista.user')
                ->whereNotIn('estado', ['CANCELADA'])
                ->whereBetween('fecha', [$this->inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')])
                ->get();
        } else {
            // Por defecto, mostrar las reservas del usuario actual
            $this->reservas = Reserva::with('servicio', 'estilista.user')
                ->where('user_id', $this->userId)
                ->whereNotIn('estado', ['CANCELADA'])
                ->whereBetween('fecha', [$this->inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')])
                ->get();
        }

        $this->horasDisponibles = [];

        // Solo obtener horarios disponibles si es estilista
        if ($this->esEstilista && $estilistaId) {
            $horarioEstilista = HorariosEstilista::with('horario')
                ->where('estilista_id', $estilistaId)
                ->orderByDesc('fecha_inicio')
                ->first();

            if ($horarioEstilista && $horarioEstilista->horario) {
                $this->horasDisponibles = $horarioEstilista->horario->horario;
            }
        } else {
            // Horas disponibles por defecto para clientes
            $this->horasDisponibles = [
                'lunes' => ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'],
                'martes' => ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'],
                'miércoles' => ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'],
                'jueves' => ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'],
                'viernes' => ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'],
                'sábado' => ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'],
                'domingo' => [],
            ];
        }
    }

    public function render()
    {
        return view('components.calendario-semanal');
    }
}
