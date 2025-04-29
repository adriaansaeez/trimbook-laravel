@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Editar Estilista</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-md mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('estilistas.update', $estilista) }}" method="POST" class="bg-white p-6 shadow-md rounded-lg">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Estilista</label>
            <input type="text" name="nombre" value="{{ old('nombre', $estilista->nombre) }}" 
                   class="w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Servicios Disponibles</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($servicios as $servicio)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                               {{ $estilista->servicios->contains($servicio->id) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">{{ $servicio->nombre }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Horarios de Trabajo</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($horarios as $horario)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="horarios[]" value="{{ $horario->id }}"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                               {{ $estilista->horarios->contains($horario->id) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">
                            {{ $horario->dia }} ({{ $horario->hora_inicio }} - {{ $horario->hora_fin }})
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('estilistas.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
