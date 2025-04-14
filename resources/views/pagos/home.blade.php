@extends('layouts.app')

@section('content')
@include('layouts.pagos-navbar')

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold mb-6">Dashboard de Pagos</h2>

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
                            <p class="text-sm font-medium uppercase">Promedio Diario</p>
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
                            <p class="text-3xl font-bold mt-2">{{ $estadisticas['metodo_mas_usado']->metodo_pago }}</p>
                        </div>
                    </div>
                </div>

                <!-- Gráficos -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Gráfico 1: Pagos por día -->
                    <div class="bg-white rounded-lg shadow-lg">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Pagos por Día</h3>
                        </div>
                        <div class="p-4 h-[400px] relative">
                            <canvas id="pagosPorDiaChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfico 2: Pagos por método -->
                    <div class="bg-white rounded-lg shadow-lg">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Pagos por Método</h3>
                        </div>
                        <div class="p-4 h-[400px] relative">
                            <canvas id="pagosPorMetodoChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfico 3: Pagos por estilista -->
                    <div class="bg-white rounded-lg shadow-lg">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Pagos por Estilista</h3>
                        </div>
                        <div class="p-4 h-[400px] relative">
                            <canvas id="pagosPorEstilistaChart"></canvas>
                        </div>
                    </div>

                    <!-- Gráfico 4: Pagos por servicio -->
                    <div class="bg-white rounded-lg shadow-lg">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Pagos por Servicio</h3>
                        </div>
                        <div class="p-4 h-[400px] relative">
                            <canvas id="pagosPorServicioChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuración común para todos los gráficos
    Chart.defaults.font.family = '"Inter", sans-serif';
    Chart.defaults.font.size = 13;
    Chart.defaults.plugins.legend.position = 'bottom';
    Chart.defaults.plugins.tooltip.padding = 10;
    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.8)';
    Chart.defaults.plugins.tooltip.titleColor = '#ffffff';
    Chart.defaults.plugins.tooltip.bodyColor = '#ffffff';
    Chart.defaults.plugins.tooltip.borderColor = 'rgba(255, 255, 255, 0.1)';
    Chart.defaults.plugins.tooltip.borderWidth = 1;
    Chart.defaults.plugins.tooltip.displayColors = true;
    Chart.defaults.plugins.tooltip.boxPadding = 3;

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    padding: 20,
                    boxWidth: 15,
                    usePointStyle: true
                }
            }
        }
    };

    // Formatear fechas para el gráfico de línea
    const fechas = {!! json_encode($pagosPorDia->pluck('fecha')) !!};
    const fechasFormateadas = fechas.map(fecha => {
        const date = new Date(fecha);
        return date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit' });
    });

    // Gráfico 1: Pagos por día
    new Chart(document.getElementById('pagosPorDiaChart'), {
        type: 'line',
        data: {
            labels: fechasFormateadas,
            datasets: [{
                label: 'Total de Pagos',
                data: {!! json_encode($pagosPorDia->pluck('total')) !!},
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#3B82F6'
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '€' + value.toFixed(2);
                        }
                    },
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Gráfico 2: Pagos por método
    new Chart(document.getElementById('pagosPorMetodoChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($pagosPorMetodo->pluck('metodo_pago')) !!},
            datasets: [{
                data: {!! json_encode($pagosPorMetodo->pluck('total')) !!},
                backgroundColor: [
                    '#3B82F6', // blue
                    '#10B981', // green
                    '#F59E0B', // yellow
                    '#EF4444'  // red
                ],
                borderWidth: 0
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value * 100) / total).toFixed(1);
                            return `€${value.toFixed(2)} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Gráfico 3: Pagos por estilista
    new Chart(document.getElementById('pagosPorEstilistaChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($pagosPorEstilista->pluck('nombre')) !!},
            datasets: [{
                label: 'Total de Pagos',
                data: {!! json_encode($pagosPorEstilista->pluck('total')) !!},
                backgroundColor: '#4F46E5',
                borderRadius: 6
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '€' + value.toFixed(2);
                        }
                    },
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Gráfico 4: Pagos por servicio
    new Chart(document.getElementById('pagosPorServicioChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($pagosPorServicio->pluck('nombre')) !!},
            datasets: [{
                data: {!! json_encode($pagosPorServicio->pluck('total')) !!},
                backgroundColor: [
                    '#3B82F6', // blue
                    '#10B981', // green
                    '#F59E0B', // yellow
                    '#EF4444', // red
                    '#8B5CF6'  // purple
                ],
                borderWidth: 0
            }]
        },
        options: {
            ...commonOptions,
            cutout: '60%',
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value * 100) / total).toFixed(1);
                            return `€${value.toFixed(2)} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection 