@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Editar Estilista</h1>

    <form action="{{ route('estilistas.update', $estilista) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium">Nombre del Estilista</label>
            <input type="text" name="nombre" value="{{ old('nombre', $estilista->nombre) }}" class="w-full p-2 border rounded-md" required>
        </div>

        <h2 class="text-lg font-bold mt-6">Horarios Disponibles</h2>
        <div class="grid grid-cols-2 gap-4">
            @foreach ($horarios as $horario)
                <label class="flex items-center">
                    <input type="checkbox" name="horarios[]" value="{{ $horario->id }}"
                        {{ $estilista->horarios->contains($horario->id) ? 'checked' : '' }}>
                    <span class="ml-2">{{ $horario->dia }} - {{ $horario->hora_inicio }} a {{ $horario->hora_fin }}</span>
                </label>
            @endforeach
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('estilistas.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
