@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-3xl font-semibold text-gray-900">Detalles de: {{$horario->nombre}}</h1>
            <p class="mt-2 text-sm text-gray-700">Visualiza la información completa de este horario.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('horarios.index') }}"
               class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Volver a Horarios
            </a>
        </div>
    </div>

    {{-- Detalles --}}
    <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-medium text-gray-900 mb-4">
            Horas Semanales: {{ $horario->registro_horas_semanales }}
        </h2>

        @if(is_array($horario->horario))
            @foreach($horario->horario as $dia)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $dia['dia'] }}</h3>

                    @if(!empty($dia['intervalos']))
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            @foreach($dia['intervalos'] as $intervalo)
                                <li class="text-gray-700">{{ $intervalo['start'] }} - {{ $intervalo['end'] }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 mt-2">No hay intervalos para este día.</p>
                    @endif
                </div>
                <hr class="border-gray-200">
            @endforeach
        @else
            <p class="text-gray-500">La estructura del horario es inválida.</p>
        @endif
    </div>
</div>
@endsection
