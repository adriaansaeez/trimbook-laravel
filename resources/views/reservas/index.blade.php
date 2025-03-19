@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Mis Reservas</h1>

    <!-- Mensajes de Éxito o Error -->
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-md mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($reservas->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">Fecha</th>
                        <th class="py-3 px-4 text-left">Hora</th>
                        <th class="py-3 px-4 text-left">Servicio</th>
                        <th class="py-3 px-4 text-left">Estilista</th>
                        <th class="py-3 px-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservas as $reserva)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-3 px-4">{{ $reserva->fecha }}</td>
                            <td class="py-3 px-4">{{ $reserva->hora }}</td>
                            <td class="py-3 px-4">{{ $reserva->servicio->nombre }}</td>
                            <td class="py-3 px-4">{{ $reserva->estilista->nombre }}</td>
                            <td class="py-3 px-4 flex justify-center gap-2">
                                

                                <!-- Botón Cancelar -->
                                <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de cancelar esta reserva?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-700">
                                        Cancelar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-center text-gray-600 mt-4">No tienes reservas aún.</p>
    @endif
</div>
@endsection
