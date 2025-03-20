@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Gestión de Horarios</h1>

    @if(session('success'))
        <div class="bg-green-400 text-white p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('horarios.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            + Añadir Horario
        </a>
    </div>

    <div class="bg-white shadow-md rounded-md overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Día</th>
                    <th class="px-4 py-2 text-left">Hora Inicio</th>
                    <th class="px-4 py-2 text-left">Hora Fin</th>
                    <th class="px-4 py-2 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($horarios as $horario)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ ucfirst(strtolower($horario->dia)) }}</td>
                    <td class="px-4 py-2">{{ $horario->hora_inicio }}</td>
                    <td class="px-4 py-2">{{ $horario->hora_fin }}</td>
                    <td class="px-4 py-2 flex justify-center gap-2">
                        <a href="{{ route('horarios.edit', $horario) }}" class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                            Editar
                        </a>
                        <form action="{{ route('horarios.destroy', $horario) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este horario?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
