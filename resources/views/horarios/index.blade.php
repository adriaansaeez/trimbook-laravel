@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Listado de Horarios</h1>
    <div class="mb-4">
        <a href="{{ route('horarios.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Crear Nuevo Horario</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($horarios->count())
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow rounded text-center">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">ID</th>
                        <th class="py-2 px-4 border-b">Horas Semanales</th>
                        <th class="py-2 px-4 border-b">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($horarios as $horario)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $horario->id }}</td>
                            <td class="py-2 px-4 border-b">{{ $horario->registro_horas_semanales }}</td>
                            <td class="py-2 px-4 border-b">
                                <a href="{{ route('horarios.show', $horario->id) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white py-1 px-3 rounded">Mostrar Horario</a>
                                <a href="{{ route('horarios.edit', $horario->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded">Editar</a>
                                <form action="{{ route('horarios.destroy', $horario->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Seguro?')" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-700">No se han creado horarios.</p>
    @endif
</div>
@endsection
