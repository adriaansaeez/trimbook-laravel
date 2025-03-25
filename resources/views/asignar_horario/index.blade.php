@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Asignar Horarios a Estilistas</h1>

    {{-- Formulario para seleccionar un estilista --}}
    <form method="GET" action="{{ route('asignar_horario.index') }}" class="mb-6">
        <label for="estilista_id" class="block mb-1">Selecciona un estilista:</label>
        <select name="estilista_id" id="estilista_id" class="w-full p-2 border rounded">
            <option value="">-- Elegir estilista --</option>
            @foreach($estilistas as $estilista)
                <option value="{{ $estilista->id }}" {{ request('estilista_id') == $estilista->id ? 'selected' : '' }}>
                    {{ $estilista->nombre }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="mt-3 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Ver horarios
        </button>
    </form>

    @if($estilistaSeleccionado)
        <h2 class="text-lg font-bold mb-3">Horarios asignados a {{ $estilistaSeleccionado->nombre }}</h2>

        @if($horarios->count())
            <ul class="mb-4 space-y-2">
            @foreach($horarios as $horario)
                <li class="border rounded p-3 bg-gray-50">
                    {{ $horario->nombre }} - {{ $horario->hora_inicio }} a {{ $horario->hora_fin }}<br>
                    <span class="text-sm text-gray-600">Del {{ $horario->pivot->fecha_inicio }} al {{ $horario->pivot->fecha_fin }}</span>
                </li>
            @endforeach

            </ul>
        @else
            <p class="text-gray-600 mb-4">Este estilista no tiene horarios asignados.</p>
        @endif

        <a href="{{ route('asignar_horario.create', $estilistaSeleccionado->id) }}"
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Asignar nuevo horario
        </a>
    @endif
</div>
@endsection
