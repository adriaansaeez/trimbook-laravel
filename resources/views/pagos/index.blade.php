@extends('layouts.app')

@section('content')
@include('layouts.pagos-navbar')

<div class="py-6">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold mb-4">Listado de Pagos</h2>

                <!-- Filtros -->
                <form id="filtrosForm" action="{{ route('pagos.index') }}" method="GET">
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="fecha_desde" class="block text-sm font-medium text-gray-700">Fecha Desde</label>
                            <input type="date" id="fecha_desde" name="fecha_desde" 
                                   value="{{ request('fecha_desde') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="fecha_hasta" class="block text-sm font-medium text-gray-700">Fecha Hasta</label>
                            <input type="date" id="fecha_hasta" name="fecha_hasta" 
                                   value="{{ request('fecha_hasta') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="metodo_pago" class="block text-sm font-medium text-gray-700">Método de Pago</label>
                            <select id="metodo_pago" name="metodo_pago" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos</option>
                                @foreach($metodosPago as $metodo)
                                    <option value="{{ $metodo }}" {{ request('metodo_pago') == $metodo ? 'selected' : '' }}>
                                        {{ $metodo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="estilista_id" class="block text-sm font-medium text-gray-700">Estilista</label>
                            <select id="estilista_id" name="estilista_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos</option>
                                @foreach($estilistas as $estilista)
                                    <option value="{{ $estilista->id }}" {{ request('estilista_id') == $estilista->id ? 'selected' : '' }}>
                                        {{ $estilista->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-between mb-4">
                        <div class="flex space-x-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Filtrar
                            </button>
                            <button type="button" id="limpiarFiltros" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Limpiar Filtros
                            </button>
                        </div>
                        
                        <a href="{{ route('pagos.export.excel') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Exportar Excel
                        </a>
                    </div>
                </form>

                <!-- Tabla de Pagos -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estilista</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Importe</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pagos as $pago)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pago->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pago->fecha_pago->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pago->reserva->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pago->estilista->nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pago->reserva->servicio->nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pago->metodo_pago }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€{{ number_format($pago->importe, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('pagos.edit', $pago) }}" class="text-blue-600 hover:text-blue-900 mr-3">Editar</a>
                                    <form action="{{ route('pagos.destroy', $pago) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de que deseas eliminar este pago?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $pagos->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar el botón de limpiar filtros
    document.getElementById('limpiarFiltros').addEventListener('click', function(e) {
        e.preventDefault(); // Prevenir el comportamiento por defecto del botón
        
        // Obtener todos los campos del formulario
        const form = document.getElementById('filtrosForm');
        const inputs = form.querySelectorAll('input, select');
        
        // Limpiar cada campo
        inputs.forEach(input => {
            input.value = '';
        });
        
        // Enviar el formulario
        form.submit();
    });

    // Validación de fechas
    const fechaDesde = document.getElementById('fecha_desde');
    const fechaHasta = document.getElementById('fecha_hasta');

    if (fechaDesde && fechaHasta) {
        fechaDesde.addEventListener('change', function() {
            if (this.value) {
                fechaHasta.min = this.value;
            } else {
                fechaHasta.min = '';
            }
        });

        fechaHasta.addEventListener('change', function() {
            if (this.value) {
                fechaDesde.max = this.value;
            } else {
                fechaDesde.max = '';
            }
        });
    }
});
</script>
@endpush

@endsection