@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Lista de Servicios</h1>

    <a href="{{ route('servicios.create') }}" class="mb-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
        Crear Servicio
    </a>

    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3 text-left">Nombre</th>
                <th class="p-3 text-left">Precio</th>
                <th class="p-3 text-left">Duración</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($servicios as $servicio)
                <tr class="border-b">
                    <td class="p-3">{{ $servicio->nombre }}</td>
                    <td class="p-3">{{ $servicio->precio }}€</td>
                    <td class="p-3">{{ $servicio->duracion }} min</td>
                    <td class="p-3">
                        <a href="{{ route('servicios.edit', $servicio) }}" class="text-blue-600 hover:underline">Editar</a>
                        <form action="{{ route('servicios.destroy', $servicio) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline ml-2">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $servicios->links() }}
    </div>
</div>
@endsection
