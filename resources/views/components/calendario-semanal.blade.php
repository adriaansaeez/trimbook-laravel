@php
    $semanaAnterior = $inicioSemana->copy()->subWeek()->toDateString();
    $semanaSiguiente = $inicioSemana->copy()->addWeek()->toDateString();
@endphp

<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <a href="?semana={{ $semanaAnterior }}" class="text-blue-600 hover:underline">&laquo; Semana anterior</a>
        <span class="font-bold text-lg">
            Semana del {{ $inicioSemana->format('d/m/Y') }}
        </span>
        <a href="?semana={{ $semanaSiguiente }}" class="text-blue-600 hover:underline">Semana siguiente &raquo;</a>
    </div>

    <div class="overflow-auto">
        <table class="table-auto border border-gray-400 w-full text-sm text-center">
            <thead>
                <tr>
                    <th class="border p-2 bg-gray-100">Hora</th>
                    @for ($i = 0; $i < 7; $i++)
                        <th class="border p-2 bg-gray-100">
                            {{ $inicioSemana->copy()->addDays($i)->locale('es')->isoFormat('dddd D/M') }}
                        </th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @for ($h = 8; $h <= 20; $h++)
                    <tr>
                        <td class="border p-2 font-medium">{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00</td>
                        @for ($d = 0; $d < 7; $d++)
                            @php
                                $diaCarbon = $inicioSemana->copy()->addDays($d);
                                $dia = $diaCarbon->format('Y-m-d');
                                $horaActual = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00:00';

                                $reserva = $reservas->first(fn($r) => $r->fecha === $dia && $r->hora === $horaActual);

                                $bg = match($reserva?->estado) {
                                    'CONFIRMADA' => 'bg-green-300',
                                    'CANCELADA' => 'bg-gray-300',
                                    default => 'bg-red-300',
                                };

                                $diaNombre = strtolower($diaCarbon->locale('es')->dayName);
                                $horaDisponible = in_array(str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', $horasDisponibles[$diaNombre] ?? []);
                            @endphp
                            <td class="border h-16 align-top 
                                {{ $reserva ? $bg : ($horaDisponible ? 'bg-yellow-100' : 'bg-gray-100') }}">
                                @if($reserva)
                                    <div class="font-semibold">{{ $reserva->servicio->nombre }}</div>
                                    <div class="text-xs">{{ $reserva->estado }}</div>
                                @endif
                            </td>
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
