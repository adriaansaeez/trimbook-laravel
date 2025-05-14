@extends('layouts.app')

@section('content')

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold mb-6">Dashboard de Reservas</h2>

                <!-- Formulario para filtrar por rango de fechas -->
                <div class="mb-6 bg-gray-50 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-3">Consultar estadísticas por rango de fechas</h3>
                    <form action="{{ route('reservas.estadisticas.rango') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/3">
                            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha inicio</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" required 
                                value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="w-full md:w-1/3">
                            <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Fecha fin</label>
                            <input type="date" id="fecha_fin" name="fecha_fin" required 
                                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="w-full md:w-1/3 flex items-end">
                            <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Consultar estadísticas
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Banner de Período Actual -->
                <div class="mb-6 bg-gray-100 p-4 rounded-lg border-l-4 border-indigo-500">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Período actual: <span class="font-bold">{{ $estadisticas['periodo_actual'] }}</span></h3>
                        </div>
                        <div class="mt-2 md:mt-0">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                Mes actual
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Comparativa con Período Anterior -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Comparativa con Mes Anterior
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Variación de Reservas Totales -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 rounded-md p-3 {{ $comparativas['variacion_total'] >= 0 ? 'bg-green-100' : 'bg-red-100' }}">
                                        <svg class="h-6 w-6 {{ $comparativas['variacion_total'] >= 0 ? 'text-green-600' : 'text-red-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            @if($comparativas['variacion_total'] >= 0)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Variación de Reservas</dt>
                                            <dd>
                                                <div class="text-lg font-medium {{ $comparativas['variacion_total'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ number_format($comparativas['variacion_total'], 1) }}%
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="text-sm">
                                    <span class="font-medium text-gray-700">Diferencia: </span>
                                    <span class="{{ $comparativas['diferencia_total'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                        {{ $comparativas['diferencia_total'] >= 0 ? '+' : '' }}{{ $comparativas['diferencia_total'] }} reservas
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Variación de Reservas Pendientes -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 rounded-md p-3 {{ $comparativas['variacion_pendientes'] >= 0 ? 'bg-yellow-100' : 'bg-blue-100' }}">
                                        <svg class="h-6 w-6 {{ $comparativas['variacion_pendientes'] >= 0 ? 'text-yellow-600' : 'text-blue-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            @if($comparativas['variacion_pendientes'] >= 0)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Variación de Pendientes</dt>
                                            <dd>
                                                <div class="text-lg font-medium {{ $comparativas['variacion_pendientes'] >= 0 ? 'text-yellow-600' : 'text-blue-600' }}">
                                                    {{ number_format($comparativas['variacion_pendientes'], 1) }}%
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="text-sm">
                                    <span class="font-medium text-gray-700">Tendencia: </span>
                                    <span class="font-semibold text-gray-900">
                                        {{ $comparativas['variacion_pendientes'] > 0 ? 'Incremento en reservas pendientes' : 'Disminución en reservas pendientes' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Variación de Reservas Confirmadas -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 rounded-md p-3 {{ $comparativas['variacion_confirmadas'] >= 0 ? 'bg-green-100' : 'bg-red-100' }}">
                                        <svg class="h-6 w-6 {{ $comparativas['variacion_confirmadas'] >= 0 ? 'text-green-600' : 'text-red-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            @if($comparativas['variacion_confirmadas'] >= 0)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Variación de Confirmadas</dt>
                                            <dd>
                                                <div class="text-lg font-medium {{ $comparativas['variacion_confirmadas'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ number_format($comparativas['variacion_confirmadas'], 1) }}%
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="text-sm">
                                    <span class="font-medium text-gray-700">Tendencia: </span>
                                    <span class="font-semibold text-gray-900">
                                        {{ $comparativas['variacion_confirmadas'] > 0 ? 'Incremento en confirmaciones' : 'Disminución en confirmaciones' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evolución Intermensual -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                        Evolución Intermensual (Últimos 6 meses)
                    </h3>
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="h-80">
                            <x-chartjs-component :chart="$evolucionMensualChart" />
                        </div>
                    </div>
                </div>

                <!-- Estadísticas de Hoy -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Hoy
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- Total de Reservas -->
                        <div class="bg-purple-600 rounded-lg shadow-lg p-6">
                            <div class="text-white">
                                <p class="text-sm font-medium uppercase">Total de Reservas</p>
                                <p class="text-3xl font-bold mt-2">{{ $estadisticasHoy['total_reservas'] }}</p>
                            </div>
                        </div>

                        <!-- Reservas Pendientes -->
                        <div class="bg-yellow-500 rounded-lg shadow-lg p-6">
                            <div class="text-white">
                                <p class="text-sm font-medium uppercase">Pendientes</p>
                                <p class="text-3xl font-bold mt-2">{{ $estadisticasHoy['pendientes'] }}</p>
                            </div>
                        </div>

                        <!-- Reservas Confirmadas -->
                        <div class="bg-green-600 rounded-lg shadow-lg p-6">
                            <div class="text-white">
                                <p class="text-sm font-medium uppercase">Confirmadas</p>
                                <p class="text-3xl font-bold mt-2">{{ $estadisticasHoy['confirmadas'] }}</p>
                            </div>
                        </div>

                        <!-- Reservas Completadas -->
                        <div class="bg-blue-600 rounded-lg shadow-lg p-6">
                            <div class="text-white">
                                <p class="text-sm font-medium uppercase">Completadas</p>
                                <p class="text-3xl font-bold mt-2">{{ $estadisticasHoy['completadas'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Reservas de Hoy -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estilista</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($reservasHoy as $reserva)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reserva->hora }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reserva->user->username }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reserva->servicio->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reserva->estilista->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($reserva->estado === 'PENDIENTE')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pendiente
                                            </span>
                                        @elseif($reserva->estado === 'CONFIRMADA')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Confirmada
                                            </span>
                                        @elseif($reserva->estado === 'COMPLETADA')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Completada
                                            </span>
                                        @elseif($reserva->estado === 'CANCELADA')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelada
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No hay reservas programadas para hoy
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top 5 Estilistas -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Top 5 Estilistas por Número de Reservas
                    </h3>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estilista</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Reservas</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($topEstilistas as $index => $estilista)
                                <tr class="{{ $index === 0 ? 'bg-yellow-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                            #{{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $estilista->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $estilista->total_reservas }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No hay datos de estilistas
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top 10 Clientes -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Top 10 Clientes por Número de Reservas
                    </h3>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Reservas</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($reservasPorCliente as $index => $cliente)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            #{{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $cliente->username }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $cliente->total_reservas }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No hay datos de clientes
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <h3 class="text-xl font-semibold mb-4">Resumen del Mes Actual</h3>

                <!-- Estadísticas Generales -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Total de Reservas -->
                    <div class="bg-blue-600 rounded-lg shadow-lg p-6">
                        <div class="text-white">
                            <p class="text-sm font-medium uppercase">Total de Reservas</p>
                            <p class="text-3xl font-bold mt-2">{{ $estadisticas['total_reservas'] }}</p>
                        </div>
                    </div>

                    <!-- Tasa de Cancelación -->
                    <div class="bg-red-600 rounded-lg shadow-lg p-6">
                        <div class="text-white">
                            <p class="text-sm font-medium uppercase">Tasa de Cancelación</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($estadisticas['tasa_cancelacion'], 1) }}%</p>
                        </div>
                    </div>

                    <!-- Tiempo Medio de Antelación -->
                    <div class="bg-green-600 rounded-lg shadow-lg p-6">
                        <div class="text-white">
                            <p class="text-sm font-medium uppercase">Tiempo Medio de Antelación</p>
                            <p class="text-3xl font-bold mt-2">{{ $estadisticas['tiempo_medio_antelacion'] }} días</p>
                        </div>
                    </div>

                    <!-- Reservas Pendientes -->
                    <div class="bg-yellow-500 rounded-lg shadow-lg p-6">
                        <div class="text-white">
                            <p class="text-sm font-medium uppercase">Reservas Pendientes</p>
                            <p class="text-3xl font-bold mt-2">{{ $estadisticas['pendientes'] }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Gráficos -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Gráfico: Reservas por día -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h4 class="text-lg font-medium mb-4">Reservas por día</h4>
                        <div class="h-80">
                            <x-chartjs-component :chart="$reservasPorDiaChart" />
                        </div>
                    </div>
                    
                    <!-- Gráfico: Reservas por estado -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h4 class="text-lg font-medium mb-4">Reservas por estado</h4>
                        <div class="h-80">
                            <x-chartjs-component :chart="$reservasPorEstadoChart" />
                        </div>
                    </div>
                    
                    <!-- Gráfico: Reservas por estilista -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h4 class="text-lg font-medium mb-4">Reservas por estilista</h4>
                        <div class="h-80">
                            <x-chartjs-component :chart="$reservasPorEstilistaChart" />
                        </div>
                    </div>
                    
                    <!-- Gráfico: Reservas por servicio -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h4 class="text-lg font-medium mb-4">Reservas por servicio</h4>
                        <div class="h-80">
                            <x-chartjs-component :chart="$reservasPorServicioChart" />
                        </div>
                    </div>
                </div>

                <!-- Reservas Próximas -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Próximos 7 días
                    </h3>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estilista</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($reservasProximas as $reserva)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reserva->hora }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reserva->user->username }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reserva->servicio->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reserva->estilista->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($reserva->estado === 'PENDIENTE')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pendiente
                                            </span>
                                        @elseif($reserva->estado === 'CONFIRMADA')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Confirmada
                                            </span>
                                        @elseif($reserva->estado === 'COMPLETADA')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Completada
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No hay reservas programadas para los próximos 7 días
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scripts adicionales que pudieras necesitar
});
</script>
@endpush
@endsection 