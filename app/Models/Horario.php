<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Horario extends Model
{
    protected $table = 'horarios';

    protected $fillable = ['horario', 'registro_horas_semanales'];

    // Se indica que el campo horario se debe castear a array
    protected $casts = [
        'horario' => 'array'
    ];

    // Definición del trigger a través del evento "saving"
    protected static function booted()
    {
        static::saving(function ($model) {
            $model->registro_horas_semanales = $model->calcularHorasSemanales();
        });
    }

    /**
     * Calcula el total de horas semanales a partir del campo "horario".
     *
     * Se asume que "horario" es un array con la siguiente estructura:
     * [
     *   {
     *     "dia": "Lunes",
     *     "intervalos": [
     *       {"start": "10:00", "end": "12:00"},
     *       {"start": "15:00", "end": "16:00"}
     *     ]
     *   },
     *   // ... resto de días
     * ]
     *
     * @return float Total de horas semanales.
     */
    public function calcularHorasSemanales()
    {
        $totalHoras = 0;

        foreach ($this->horario as $dia) {
            if (isset($dia['intervalos']) && is_array($dia['intervalos'])) {
                foreach ($dia['intervalos'] as $intervalo) {
                    if (isset($intervalo['start'], $intervalo['end'])) {
                        // Convertir las horas en objetos Carbon para calcular la diferencia
                        $start = Carbon::createFromFormat('H:i', $intervalo['start']);
                        $end = Carbon::createFromFormat('H:i', $intervalo['end']);
                        $diffInMinutes = $start->diffInMinutes($end);
                        // Convertir minutos a horas
                        $totalHoras += $diffInMinutes / 60;
                    }
                }
            }
        }

        return $totalHoras;
    }
}
