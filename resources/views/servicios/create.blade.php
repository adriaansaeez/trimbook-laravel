@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Crear Servicio</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-md mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('servicios.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium">Nombre</label>
            <input type="text" name="nombre" class="w-full p-2 border rounded-md" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Descripción</label>
            <textarea name="descripcion" class="w-full p-2 border rounded-md" required></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Precio (€)</label>
            <input type="number" step="0.01" name="precio" class="w-full p-2 border rounded-md" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Duración (min)</label>
            <input type="number" name="duracion" class="w-full p-2 border rounded-md" required>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('servicios.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Guardar
            </button>
        </div>
    </form>
</div>
@endsection
