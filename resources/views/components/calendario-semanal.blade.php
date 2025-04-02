<!-- resources/views/components/calendario-semanal.blade.php -->

<div class="overflow-auto">
    <table class="table-auto border border-gray-400 w-full text-center text-sm">
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
                            $dia = $inicioSemana->copy()->addDays($d)->format('Y-m-d');
                            $horaActual = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00:00';

                            $reserva = $reservas->first(fn($r) =>
                                $r->fecha === $dia && $r->hora === $horaActual
                            );
                        @endphp
                        <td class="border h-16 align-top {{ $reserva ? 'bg-red-300' : '' }}">
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
