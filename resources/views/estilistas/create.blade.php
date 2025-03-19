@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Crear Estilista</h1>

    <form action="{{ route('estilistas.store') }}" method="POST" class="bg-white p-6 shadow-md rounded-lg">
        @csrf

        <div class="mb-4">
            <label for="user_id" class="block text-sm font-medium text-gray-700">Usuario</label>
            <select name="user_id" id="user_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                <option value="">Seleccione un usuario</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name }} - {{ $usuario->email }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('estilistas.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500 mr-2">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Guardar</button>
        </div>
    </form>
</div>
@endsection
