@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
        
        {{-- Foto de perfil --}}
        <div class="flex-shrink-0">
            <img src="{{ asset('storage/perfiles/' . $perfil->foto_perfil) }}" alt="Foto de perfil"
                 class="w-40 h-40 rounded-full object-cover border-4 border-blue-500">
        </div>

        {{-- Información del perfil --}}
        <div class="flex-1 w-full">
            <h2 class="text-2xl font-bold text-gray-800">{{ $perfil->nombre }} {{ $perfil->apellidos }}</h2>
            <p class="text-gray-600 mt-1">📞 {{ $perfil->telefono }}</p>
            <p class="text-gray-600 mt-1">📍 {{ $perfil->direccion }}</p>
            
            @if ($perfil->instagram_url && $perfil->instagram_url !== 'No especificado')
                <p class="mt-2 text-gray-700">
                    <a href="https://www.instagram.com/{{ $perfil->instagram_url }}" target="_blank" class="inline-flex items-center gap-2 text-pink-600 hover:underline">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7.75 2C4.85 2 2.5 4.35 2.5 7.25v9.5C2.5 19.65 4.85 22 7.75 22h8.5c2.9 0 5.25-2.35 5.25-5.25v-9.5C21.5 4.35 19.15 2 16.25 2h-8.5zM12 7.5a4.5 4.5 0 1 1 0 9a4.5 4.5 0 0 1 0-9zm6-1a1 1 0 1 1-2 0a1 1 0 0 1 2 0zM12 9a3 3 0 1 0 0 6a3 3 0 0 0 0-6z"/>
                        </svg>
                        @{{ $perfil->instagram_url }}
                    </a>
                </p>
            @endif

            {{-- Botón de edición --}}
            <div class="mt-6">
                <a href="{{ route('perfil.edit') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Editar Perfil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
