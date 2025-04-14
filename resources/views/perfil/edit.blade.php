@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Editar Perfil</h1>

    {{-- Contenedor de errores --}}
    @if ($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <p class="font-semibold">Se encontraron algunos errores:</p>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 shadow-md rounded-lg">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $perfil->nombre) }}"
                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <div class="mb-4">
            <label for="apellidos" class="block text-sm font-medium text-gray-700">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos', $perfil->apellidos) }}"
                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <div class="mb-4">
            <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
            <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $perfil->telefono) }}"
                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <div class="mb-4">
            <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
            <input type="text" name="direccion" id="direccion" value="{{ old('direccion', $perfil->direccion) }}"
                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <div class="mb-4">
            <label for="instagram_url" class="block text-sm font-medium text-gray-700">Instagram</label>
            <input type="text" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $perfil->instagram_url) }}"
                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <div class="mb-4">
            <label for="foto_perfil" class="block text-sm font-medium text-gray-700">Foto de Perfil</label>
            <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*"
                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Actualizar
            </button>
        </div>
    </form>
</div>
@endsection
