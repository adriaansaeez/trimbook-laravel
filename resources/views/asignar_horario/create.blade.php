@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-xl">
    <h1 class="text-2xl font-semibold mb-4">Asignar horario a {{ $estilista->nombre }}</h1>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('asignar_horario.store', $estilista->id) }}">
        @csrf

        <label for="horario_id" class="block mb-2">Selecciona un horario disponible:</label>
        <select name="horario_id" id="horario_id" class="w-full p-2 border rounded mb-4">
            @foreach($horariosDisponibles as $horario)
                <option value="{{ $horario->id }}">
                    {{ $horario->nombre }} - {{ $horario->hora_inicio }} a {{ $horario->hora_fin }}
                </option>
            @endforeach
        </select>

        <div class="mb-4">
            <label for="fecha_inicio" class="block mb-1">Fecha de inicio:</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label for="fecha_fin" class="block mb-1">Fecha de fin:</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="w-full p-2 border rounded" required>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Asignar
        </button>
    </form>
</div>
@endsection
