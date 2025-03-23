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

    <form action="{{ route('estilistas.update', $estilista) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium">Nombre del Estilista</label>
            <input type="text" name="nombre" value="{{ old('nombre', $estilista->nombre) }}" class="w-full p-2 border rounded-md" required>
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

    <div class="mt-8">
        <a href="{{ route('estilistas.horarios.edit', $estilista) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
            Asignar Horarios Disponibles
        </a>
    </div>
</div>
@endsection
