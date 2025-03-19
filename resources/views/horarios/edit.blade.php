@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Editar Horario</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-md mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('horarios.update', $horario) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium">Día de la Semana</label>
            <select name="dia" class="w-full p-2 border rounded-md" required>
                @foreach (['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO'] as $dia)
                    <option value="{{ $dia }}" {{ $horario->dia == $dia ? 'selected' : '' }}>
                        {{ ucfirst(strtolower($dia)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-4">
            <div class="w-1/2">
                <label class="block text-sm font-medium">Hora de Inicio</label>
                <input type="time" name="hora_inicio" value="{{ $horario->hora_inicio }}" class="w-full p-2 border rounded-md" required>
            </div>
            <div class="w-1/2">
                <label class="block text-sm font-medium">Hora de Fin</label>
                <input type="time" name="hora_fin" value="{{ $horario->hora_fin }}" class="w-full p-2 border rounded-md" required>
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('horarios.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
