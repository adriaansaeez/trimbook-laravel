@extends('layouts.app')

@section('content')

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Estadísticas de reservas por rango de fechas</h2>
                    <a href="{{ route('reservas.dashboard') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
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
                
                @if($totalReservas == 0)
                <div class="mb-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center text-yellow-800">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="font-medium">No hay datos de reservas para el período seleccionado.</p>
                    </div>
                </div>
                @endif
                
                <!-- Estadísticas Generales -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold mb-4">Resumen General</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Reservas Totales -->
                        <div class="bg-gradient-to-br from-purple-600 to-indigo-600 rounded-xl shadow-xl p-6">
                            <div class="text-white">
                                <p class="text-sm font-medium uppercase">Total de Reservas</p>
                                <p class="text-3xl font-bold mt-2">{{ $totalReservas }}</p>
                            </div>
                        </div>

                        <!-- Tasa de Cancelación -->
                        <div class="bg-gradient-to-br from-red-600 to-pink-600 rounded-xl shadow-xl p-6">
                            <div class="text-white">
                                <p class="text-sm font-medium uppercase">Tasa de Cancelación</p>
                                <p class="text-3xl font-bold mt-2">{{ number_format($tasaCancelacion, 1) }}%</p>
                            </div>
                        </div>

                        <!-- Tiempo Medio de Antelación -->
                        <div class="bg-gradient-to-br from-green-600 to-emerald-600 rounded-xl shadow-xl p-6">
                            <div class="text-white">
                                <p class="text-sm font-medium uppercase">Tiempo Medio de Antelación</p>
                                <p class="text-3xl font-bold mt-2">{{ number_format($tiempoMedioAntelacion, 1) }} días</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top 5 Estilistas -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold mb-4">Top 5 Estilistas por Reservas</h3>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puesto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estilista</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Reservas</th>
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
                                        {{ $estilista->total_reservas }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No hay datos de estilistas en este período
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Estados de Reserva -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold mb-4">Distribución de Estados de Reserva</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-lg shadow p-4 h-80">
                                <x-chartjs-component :chart="$reservasPorEstadoChart" />
                            </div>
                        </div>
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-lg shadow overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">%</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($reservasPorEstado as $estado)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $estado->estado }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $estado->cantidad }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($estado->porcentaje, 1) }}%
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                No hay datos de estados en este período
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reservas por Estilista -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold mb-4">Reservas por Estilista</h3>
                    <div class="bg-white rounded-lg shadow p-4 h-80">
                        <x-chartjs-component :chart="$reservasPorEstilistaChart" />
                    </div>
                </div>

                <!-- Evolución de Reservas -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold mb-4">Evolución de Reservas</h3>
                    <div class="bg-white rounded-lg shadow p-4 h-80">
                        <x-chartjs-component :chart="$evolucionReservasChart" />
                    </div>
                </div>

                <!-- Servicios más demandados -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold mb-4">Servicios más demandados</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <div class="bg-white rounded-lg shadow p-4 h-80">
                                <x-chartjs-component :chart="$reservasPorServicioChart" />
                            </div>
                        </div>
                        <div>
                            <div class="bg-white rounded-lg shadow overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($reservasPorServicio as $servicio)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $servicio->nombre }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $servicio->cantidad }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
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

                <!-- Top 10 Clientes -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold mb-4">Clientes más frecuentes</h3>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Reservas</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($reservasPorCliente as $cliente)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $cliente->username }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $cliente->total_reservas }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No hay datos de clientes en este período
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
    // Comprueba si hay datos antes de renderizar los gráficos
    const hayDatos = {{ $totalReservas > 0 ? 'true' : 'false' }};
    
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