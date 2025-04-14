@extends('layouts.app')

@section('content')    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Mis Reservas</h3>
                    
                    @if($reservasSemana->isEmpty())
                        <p class="text-gray-500">No tienes reservas para esta semana.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b text-left">Fecha</th>
                                        <th class="py-2 px-4 border-b text-left">Hora</th>
                                        <th class="py-2 px-4 border-b text-left">Servicio</th>
                                        @if($esEstilista)
                                            <th class="py-2 px-4 border-b text-left">Cliente</th>
                                        @elseif($esCliente)
                                            <th class="py-2 px-4 border-b text-left">Estilista</th>
                                        @elseif($esAdmin)
                                            <th class="py-2 px-4 border-b text-left">Cliente</th>
                                            <th class="py-2 px-4 border-b text-left">Estilista</th>
                                        @endif
                                        <th class="py-2 px-4 border-b text-left">Estado</th>
                                        <th class="py-2 px-4 border-b text-left">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservasSemana as $reserva)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</td>
                                            <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($reserva->hora)->format('H:i') }}</td>
                                            <td class="py-2 px-4 border-b">{{ $reserva->servicio->nombre }}</td>
                                            @if($esEstilista)
                                                <td class="py-2 px-4 border-b">{{ $reserva->user->name }}</td>
                                            @elseif($esCliente)
                                                <td class="py-2 px-4 border-b">{{ $reserva->estilista->user->name }}</td>
                                            @elseif($esAdmin)
                                                <td class="py-2 px-4 border-b">{{ $reserva->user->name }}</td>
                                                <td class="py-2 px-4 border-b">{{ $reserva->estilista->user->name }}</td>
                                            @endif
                                            <td class="py-2 px-4 border-b">
                                                <span class="px-2 py-1 rounded text-xs 
                                                    @if(strtoupper($reserva->estado) === 'CONFIRMADA') bg-green-100 text-green-800
                                                    @elseif(strtoupper($reserva->estado) === 'PENDIENTE') bg-yellow-100 text-yellow-800
                                                    @elseif(strtoupper($reserva->estado) === 'CANCELADA') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $reserva->estado }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                @if(strtoupper($reserva->estado) === 'PENDIENTE')
                                                    @if($esEstilista)
                                                        <form action="{{ route('reservas.confirmar', $reserva) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                                                Confirmar
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('reservas.cancelar', $reserva) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                                            Cancelar
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-2">
                    <x-calendario-semanal 
                        :estilistaId="$estilistaId" 
                        :inicioSemana="$inicioSemana" 
                        :reservas="$reservasSemana" 
                        :horasDisponibles="$horasDisponibles"
                        :esEstilista="$esEstilista"
                        :esCliente="$esCliente"
                        :esAdmin="$esAdmin"
                    />
                </div>
                <div class="col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 text-gray-900">
                            <div id="detalles-reserva" class="hidden">
                                <h3 class="text-lg font-semibold mb-4">Detalles de la Reserva</h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Cliente:</p>
                                        <p class="font-medium" id="cliente-nombre"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Servicio:</p>
                                        <p class="font-medium" id="servicio-nombre"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Fecha y Hora:</p>
                                        <p class="font-medium" id="fecha-hora"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Estado:</p>
                                        <p class="font-medium" id="estado-reserva"></p>
                                    </div>
                                    
                                    <div id="formulario-pago" class="hidden">
                                        <h4 class="text-md font-semibold mb-3">Procesar Pago</h4>
                                        <form id="pago-form" class="space-y-4">
                                            @csrf
                                            <input type="hidden" id="reserva-id" name="reserva_id">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                                                <select name="metodo_pago" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="EFECTIVO">Efectivo</option>
                                                    <option value="TARJETA">Tarjeta</option>
                                                    <option value="BIZUM">Bizum</option>
                                                    <option value="TRANSFERENCIA">Transferencia</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Importe</label>
                                                <input type="number" step="0.01" name="importe" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                                Procesar Pago
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div id="sin-seleccion" class="text-center text-gray-500">
                                <p>Selecciona una reserva para ver los detalles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection