@extends('layouts.app')

@section('content')

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold mb-6">Dashboard de Pagos</h2>

                <!-- Formulario para filtrar por rango de fechas -->
                <div class="mb-6 bg-gray-50 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-3">Consultar estadísticas por rango de fechas</h3>
                    <form action="{{ route('pagos.estadisticas.rango') }}" method="GET" class="flex flex-col md:flex-row gap-4">
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
                        <!-- Variación de Ingresos -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 rounded-md p-3 {{ $comparativas['variacion_ingresos'] >= 0 ? 'bg-green-100' : 'bg-red-100' }}">
                                        <svg class="h-6 w-6 {{ $comparativas['variacion_ingresos'] >= 0 ? 'text-green-600' : 'text-red-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            @if($comparativas['variacion_ingresos'] >= 0)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Variación de Ingresos</dt>
                                            <dd>
                                                <div class="text-lg font-medium {{ $comparativas['variacion_ingresos'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ number_format($comparativas['variacion_ingresos'], 1) }}%
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="text-sm">
                                    <span class="font-medium text-gray-700">Diferencia: </span>
                                    <span class="{{ $comparativas['diferencia_ingresos'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                        {{ $comparativas['diferencia_ingresos'] >= 0 ? '+' : '' }}€{{ number_format($comparativas['diferencia_ingresos'], 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Variación de Cantidad -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 rounded-md p-3 {{ $comparativas['variacion_cantidad'] >= 0 ? 'bg-green-100' : 'bg-red-100' }}">
                                        <svg class="h-6 w-6 {{ $comparativas['variacion_cantidad'] >= 0 ? 'text-green-600' : 'text-red-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            @if($comparativas['variacion_cantidad'] >= 0)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Variación de Transacciones</dt>
                                            <dd>
                                                <div class="text-lg font-medium {{ $comparativas['variacion_cantidad'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ number_format($comparativas['variacion_cantidad'], 1) }}%
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="text-sm">
                                    <span class="font-medium text-gray-700">Diferencia: </span>
                                    <span class="{{ $comparativas['diferencia_cantidad'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                        {{ $comparativas['diferencia_cantidad'] >= 0 ? '+' : '' }}{{ $comparativas['diferencia_cantidad'] }} pagos
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Variación Ticket Medio -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 rounded-md p-3 {{ $comparativas['variacion_promedio'] >= 0 ? 'bg-green-100' : 'bg-red-100' }}">
                                        <svg class="h-6 w-6 {{ $comparativas['variacion_promedio'] >= 0 ? 'text-green-600' : 'text-red-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            @if($comparativas['variacion_promedio'] >= 0)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Variación Ticket Medio</dt>
                                            <dd>
                                                <div class="text-lg font-medium {{ $comparativas['variacion_promedio'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ number_format($comparativas['variacion_promedio'], 1) }}%
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="text-sm">
                                    <span class="font-medium text-gray-700">Diferencia: </span>
                                    <span class="{{ $comparativas['diferencia_promedio'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                        {{ $comparativas['diferencia_promedio'] >= 0 ? '+' : '' }}€{{ number_format($comparativas['diferencia_promedio'], 2) }}
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
                        <!-- Total del Día -->
                        <div class="bg-purple-600 rounded-lg shadow-lg p-6">
                            <div class="text-white">
                                <p class="text-sm font-medium uppercase">Total del Día</p>
                                <p class="text-3xl font-bold mt-2">€{{ number_format($estadisticasHoy['total_dia'], 2) }}</p>
                            </div>
                        </div>

                        <!-- Cantidad de Pagos -->
                        <div class="bg-pink-600 rounded-lg shadow-lg p-6">
                            <div class="text-white">
                                <p class="text-sm font-medium uppercase">Cantidad de Pagos</p>
                                <p class="text-3xl font-bold mt-2">{{ $estadisticasHoy['cantidad_pagos'] }}</p>
                            </div>
                        </div>

                        <!-- Promedio por Pago -->
                        <div class="bg-orange-600 rounded-lg shadow-lg p-6">
                            <div class="text-white">
                                <p class="text-sm font-medium uppercase">Promedio por Pago</p>
                                <p class="text-3xl font-bold mt-2">€{{ number_format($estadisticasHoy['promedio_pago'], 2) }}</p>
                            </div>
                        </div>

                        <!-- Último Pago -->
                        <div class="bg-teal-600 rounded-lg shadow-lg p-6">
                            <div class="text-white">
                                <p class="text-sm font-medium uppercase">Último Pago</p>
                                <p class="text-3xl font-bold mt-2">{{ $estadisticasHoy['hora_ultimo_pago'] ? \Carbon\Carbon::parse($estadisticasHoy['hora_ultimo_pago'])->format('H:i') : '--:--' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Pagos de Hoy -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estilista</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Importe</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pagosDiaActual as $pago)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $pago->reserva->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $pago->reserva->servicio->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $pago->estilista->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $pago->metodo_pago }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        €{{ number_format($pago->importe, 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No hay pagos registrados hoy
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
                        Top 5 Estilistas por Ingresos
                    </h3>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estilista</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pagos</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ingresos</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket Medio</th>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $estilista->cantidad_pagos }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        €{{ number_format($estilista->total_ingresos, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        €{{ number_format($estilista->ticket_medio, 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No hay datos de estilistas
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
                    <!-- Total del Mes -->
                    <div class="bg-blue-600 rounded-lg shadow-lg p-6">
                        <div class="text-white">
                            <p class="text-sm font-medium uppercase">Total del Mes</p>
                            <p class="text-3xl font-bold mt-2">€{{ number_format($estadisticas['total_mes'], 2) }}</p>
                        </div>
                    </div>

                    <!-- Promedio Diario -->
                    <div class="bg-green-600 rounded-lg shadow-lg p-6">
                        <div class="text-white">
                            <p class="text-sm font-medium uppercase">Promedio por Pago</p>
                            <p class="text-3xl font-bold mt-2">€{{ number_format($estadisticas['promedio_diario'], 2) }}</p>
                        </div>
                    </div>

                    <!-- Total Transacciones -->
                    <div class="bg-indigo-600 rounded-lg shadow-lg p-6">
                        <div class="text-white">
                            <p class="text-sm font-medium uppercase">Total Transacciones</p>
                            <p class="text-3xl font-bold mt-2">{{ $estadisticas['total_transacciones'] }}</p>
                        </div>
                    </div>

                    <!-- Método Más Usado -->
                    <div class="bg-yellow-500 rounded-lg shadow-lg p-6">
                        <div class="text-white">
                            <p class="text-sm font-medium uppercase">Método Más Usado</p>
                            <p class="text-3xl font-bold mt-2">{{ $estadisticas['metodo_mas_usado']->metodo_pago ?? 'N/A' }}</p>
                            @if($estadisticas['metodo_mas_usado'])
                            <p class="text-sm mt-1">{{ $estadisticas['metodo_mas_usado']->cantidad }} pagos ({{ number_format($estadisticas['metodo_mas_usado']->porcentaje, 1) }}%)</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Gráficos -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Gráfico: Pagos por día -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h4 class="text-lg font-medium mb-4">Pagos por día</h4>
                        <div class="h-80">
                            <x-chartjs-component :chart="$pagosPorDiaChart" />
                        </div>
                    </div>
                    
                    <!-- Gráfico: Pagos por método -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h4 class="text-lg font-medium mb-4">Pagos por método</h4>
                        <div class="h-80">
                            <x-chartjs-component :chart="$pagosPorMetodoChart" />
                        </div>
                    </div>
                    
                    <!-- Gráfico: Pagos por estilista -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h4 class="text-lg font-medium mb-4">Pagos por estilista</h4>
                        <div class="h-80">
                            <x-chartjs-component :chart="$pagosPorEstilistaChart" />
                        </div>
                    </div>
                    
                    <!-- Gráfico: Pagos por servicio -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h4 class="text-lg font-medium mb-4">Pagos por servicio</h4>
                        <div class="h-80">
                            <x-chartjs-component :chart="$pagosPorServicioChart" />
                        </div>
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