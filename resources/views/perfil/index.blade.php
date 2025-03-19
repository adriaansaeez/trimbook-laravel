@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Mi Perfil</h1>

    <div class="bg-white p-6 shadow-md rounded-lg">
        <p><strong>Nombre:</strong> {{ $perfil->nombre }}</p>
        <p><strong>Apellidos:</strong> {{ $perfil->apellidos ?? 'No especificado' }}</p>
        <p><strong>Teléfono:</strong> {{ $perfil->telefono ?? 'No especificado' }}</p>
        <p><strong>Dirección:</strong> {{ $perfil->direccion ?? 'No especificado' }}</p>
        <p><strong>Instagram:</strong> <a href="https://www.instagram.com/{{ $perfil->instagram_url }}" target="_blank" class="text-blue-600">{{ $perfil->instagram_url ?? 'No especificado' }}</a></p>

        <div class="mt-4">
            <a href="{{ route('perfil.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Editar Perfil</a>
        </div>
    </div>
</div>
@endsection
