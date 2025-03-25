@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Detalle del Horario #{{ $horario->id }}</h1>
    <div class="bg-white shadow rounded p-6">
        <h2 class="text-xl font-medium mb-4">Horas Semanales: {{ $horario->registro_horas_semanales }}</h2>
        @if(is_array($horario->horario))
            @foreach($horario->horario as $dia)
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $dia['dia'] }}</h3>
                    @if(isset($dia['intervalos']) && count($dia['intervalos']))
                        <ul class="ml-4 list-disc">
                            @foreach($dia['intervalos'] as $intervalo)
                                <li>{{ $intervalo['start'] }} - {{ $intervalo['end'] }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 ml-4">No hay intervalos</p>
                    @endif
                </div>
                <hr class="border-gray-200">
            @endforeach
        @else
            <p class="text-gray-700">Estructura de horario inválida.</p>
        @endif
    </div>
    <div class="mt-4">
        <a href="{{ route('horarios.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">Volver al listado</a>
    </div>
</div>
@endsection
