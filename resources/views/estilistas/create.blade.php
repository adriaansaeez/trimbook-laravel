@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Crear Estilista</h1>

    <form action="{{ route('estilistas.store') }}" method="POST" class="bg-white p-6 shadow-md rounded-lg">
        @csrf

        <div class="mb-6">
            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
            <select name="user_id" id="user_id" 
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                    required>
                <option value="">Seleccione un usuario</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name }} - {{ $usuario->email }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
            <input type="text" name="nombre" id="nombre" 
                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                   required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Servicios Disponibles</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($servicios as $servicio)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">{{ $servicio->nombre }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('estilistas.index') }}" 
               class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500 transition-colors mr-2">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                Guardar
            </button>
        </div>
    </form>
</div>
@endsection
