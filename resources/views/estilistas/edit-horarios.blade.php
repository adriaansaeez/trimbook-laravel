@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6" x-data="{ openDay: null }">
    <h1 class="text-3xl font-bold mb-6">Asignar Horarios a {{ $estilista->nombre }}</h1>

    {{-- Mostrar mensaje de éxito --}}
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Mostrar errores del controlador --}}
    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-md mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('estilistas.horarios.update', $estilista) }}" method="POST" id="form-horarios">
        @csrf
        @method('PUT')

        <div class="space-y-2">
            @php
                $diasSemana = ['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO','DOMINGO'];
            @endphp

            @foreach ($diasSemana as $dia)
                @php
                    $horariosDia = $horarios->where('dia', $dia);
                    $primerHorarioDelDia = $estilista->horarios->where('dia', $dia)->first();
                    $fechaInicio = optional(optional($primerHorarioDelDia)->pivot)->fecha_inicio;
                    $fechaFin = optional(optional($primerHorarioDelDia)->pivot)->fecha_fin;
                @endphp

                <div class="border rounded-md">
                    <button type="button" @click="openDay = (openDay === '{{ $dia }}' ? null : '{{ $dia }}')"
                        class="w-full px-4 py-2 bg-gray-200 text-left font-semibold rounded-md">
                        {{ ucfirst(strtolower($dia)) }}
                    </button>

                    <div x-show="openDay === '{{ $dia }}'" class="px-4 py-2">
                        @if ($horariosDia->isEmpty())
                            <p class="text-sm text-gray-600">No hay horarios disponibles.</p>
                        @else
                            <div class="grid grid-cols-1 gap-2 mb-2">
                                @foreach ($horariosDia as $horario)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox"
                                            name="horarios[{{ $dia }}][]"
                                            value="{{ $horario->id }}"
                                            {{ $estilista->horarios->contains($horario->id) ? 'checked' : '' }}>
                                        <span>{{ $horario->hora_inicio }} - {{ $horario->hora_fin }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-sm">Fecha inicio</label>
                                    <input type="date" name="fechas[{{ $dia }}][inicio]" class="border p-1 rounded-md w-full"
                                        value="{{ $fechaInicio }}">
                                </div>
                                <div>
                                    <label class="text-sm">Fecha fin</label>
                                    <input type="date" name="fechas[{{ $dia }}][fin]" class="border p-1 rounded-md w-full"
                                        value="{{ $fechaFin }}">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('estilistas.edit', $estilista) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Volver
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Guardar Horarios
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-horarios');

    form.addEventListener('submit', function (e) {
        const dias = ['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO','DOMINGO'];
        let valido = true;
        let errores = [];

        dias.forEach(dia => {
            const checkboxes = document.querySelectorAll(`input[name='horarios[${dia}][]']:checked`);
            const horarios = Array.from(checkboxes).map(cb => {
                const label = cb.nextElementSibling.textContent.trim();
                const [inicio, fin] = label.split(' - ');
                return {
                    inicio: inicio.trim(),
                    fin: fin.trim()
                };
            });

            // Comprobar solapamientos
            horarios.sort((a, b) => a.inicio.localeCompare(b.inicio));

            for (let i = 0; i < horarios.length - 1; i++) {
                if (horarios[i].fin > horarios[i + 1].inicio) {
                    errores.push(`Conflicto de horarios el ${dia.toLowerCase()}: ${horarios[i].inicio}-${horarios[i].fin} se solapa con ${horarios[i + 1].inicio}-${horarios[i + 1].fin}`);
                    valido = false;
                }
            }
        });

        if (!valido) {
            e.preventDefault();
            alert(errores.join('\n'));
        }
    });
});
</script>
@endsection