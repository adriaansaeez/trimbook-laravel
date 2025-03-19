@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Editar Perfil</h1>

    <form action="{{ route('perfil.update') }}" method="POST" class="bg-white p-6 shadow-md rounded-lg">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{ $perfil->nombre }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <div class="mb-4">
            <label for="apellidos" class="block text-sm font-medium text-gray-700">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" value="{{ $perfil->apellidos }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <div class="mb-4">
            <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
            <input type="text" name="telefono" id="telefono" value="{{ $perfil->telefono }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <div class="mb-4">
            <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
            <input type="text" name="direccion" id="direccion" value="{{ $perfil->direccion }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <div class="mb-4">
            <label for="instagram_url" class="block text-sm font-medium text-gray-700">Instagram</label>
            <input type="text" name="instagram_url" id="instagram_url" value="{{ $perfil->instagram_url }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Actualizar</button>
        </div>
    </form>
</div>
@endsection
