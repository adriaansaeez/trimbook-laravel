@extends('layouts.app')

@section('content')
@include('layouts.pagos-navbar')

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Estadísticas de pagos por rango de fechas</h2>
                    <a href="{{ route('pagos.home') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>
                </div>

                <!-- Mensaje Flash -->
                @if(session('info'))
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center text-blue-800">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="font-medium">{{ session('info') }}</p>
                    </div>
                </div>
                @endif

                <!-- Información del rango seleccionado -->
                <div class="mb-8 p-4 bg-indigo-50 rounded-lg">
                    <p class="text-lg text-indigo-900">
                        <strong>Período:</strong> {{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }}
                    </p>
                </div>
                
                @if($totalPagos == 0)
                <div class="mb-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center text-yellow-800">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="font-medium">No hay datos de pagos para el período seleccionado.</p>
                    </div>
                </div>
                @endif
                
                <!-- Estadísticas Generales -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold mb-4">Resumen General</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Ingresos Totales -->
                        <div class="bg-gradient-to-br from-purple-600 to-indigo-600 rounded-xl shadow-xl p-6">
                            <div class="text-white">
                                <p class="text-sm font-medium uppercase">Ingresos Totales</p>
                                <p class="text-3xl font-bold mt-2">€{{ number_format($ingresosTotales, 2) }}</p>
                            </div>
                        </div>

                        <!-- Número Total de Pagos -->
                        <div class="bg-gradient-to-br from-blue-600 to-cyan-600 rounded-xl shadow-xl p-6">
                            <div class="text-white">
                                <p class="text-sm font-medium uppercase">Número Total de Pagos</p>
                                <p class="text-3xl font-bold mt-2">{{ $totalPagos }}</p>
                            </div>
                        </div>

                        <!-- Importe Medio por Pago -->
                        <div class="bg-gradient-to-br from-green-600 to-emerald-600 rounded-xl shadow-xl p-6">
                            <div class="text-white">
                                <p class="text-sm font-medium uppercase">Importe Medio por Pago</p>
                                <p class="text-3xl font-bold mt-2">€{{ number_format($importeMedio, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top 5 Estilistas -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold mb-4">Top 5 Estilistas por Ingresos</h3>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puesto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estilista</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ingresos</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nº Pagos</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket Medio</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($topEstilistas as $index => $estilista)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $estilista->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        €{{ number_format($estilista->total_ingresos, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $estilista->cantidad_pagos }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        €{{ number_format($estilista->ticket_medio, 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No hay datos de estilistas en este período
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Métodos de Pago -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold mb-4">Distribución de Métodos de Pago</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-lg shadow p-4 h-80">
                                <x-chartjs-component :chart="$metodosPagoChart" />
                            </div>
                        </div>
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-lg shadow overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">%</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($pagosPorMetodo as $metodo)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $metodo->metodo_pago }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $metodo->cantidad }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                €{{ number_format($metodo->total, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($metodo->porcentaje, 1) }}%
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                No hay datos de métodos de pago en este período
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ingresos por Estilista -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold mb-4">Ingresos por Estilista</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <div class="bg-white rounded-lg shadow p-4 h-80">
                                <x-chartjs-component :chart="$ingresosEstilistaChart" />
                            </div>
                        </div>
                        <div>
                            <div class="bg-white rounded-lg shadow p-4 h-80">
                                <x-chartjs-component :chart="$ticketMedioEstilistaChart" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evolución de Pagos -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold mb-4">Evolución de Pagos</h3>
                    <div class="bg-white rounded-lg shadow p-4 h-80">
                        <x-chartjs-component :chart="$evolucionPagosChart" />
                    </div>
                </div>

                <!-- Servicios más demandados -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold mb-4">Servicios más demandados</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <div class="bg-white rounded-lg shadow p-4 h-80">
                                <x-chartjs-component :chart="$serviciosChart" />
                            </div>
                        </div>
                        <div>
                            <div class="bg-white rounded-lg shadow overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($pagosPorServicio as $servicio)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $servicio->nombre }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $servicio->cantidad }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                €{{ number_format($servicio->total, 2) }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                No hay datos de servicios en este período
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
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Comprueba si hay datos antes de renderizar los gráficos
    const hayDatos = {{ $totalPagos > 0 ? 'true' : 'false' }};
    
    if (!hayDatos) {
        // Si no hay datos, añadimos mensajes en los contenedores de gráficos
        const containers = document.querySelectorAll('div.h-80');
        containers.forEach(container => {
            const canvas = container.querySelector('canvas');
            if (canvas) {
                canvas.style.display = 'none';
            }
            
            const mensaje = document.createElement('div');
            mensaje.className = 'flex items-center justify-center h-full text-gray-500';
            mensaje.innerHTML = '<p>No hay datos disponibles para este período</p>';
            container.appendChild(mensaje);
        });
    }
});
</script>
@endpush
@endsection 