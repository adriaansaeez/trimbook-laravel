@extends('layouts.app')

@section('content')

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                                    <button type="button" class="text-indigo-600 hover:text-indigo-900 mr-3" onclick="mostrarDetallesPago({{ $pago->id }})">
                                        Ver detalles
                                    </button>
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

// Función para mostrar los detalles del pago en un modal
function mostrarDetallesPago(pagoId) {
    // Realizar una petición AJAX para obtener los detalles del pago
    fetch(`/pagos/${pagoId}/detalles`)
        .then(response => response.json())
        .then(data => {
            // Llenar el modal con los datos
            document.getElementById('pago-id').textContent = data.pago.id;
            document.getElementById('pago-fecha').textContent = data.pago.fecha_pago;
            document.getElementById('pago-metodo').textContent = data.pago.metodo_pago;
            document.getElementById('pago-importe').textContent = data.pago.importe + ' €';
            
            document.getElementById('reserva-id').textContent = data.reserva.id;
            document.getElementById('reserva-fecha').textContent = data.reserva.fecha;
            document.getElementById('reserva-hora').textContent = data.reserva.hora;
            document.getElementById('reserva-estado').textContent = data.reserva.estado;
            
            document.getElementById('servicio-nombre').textContent = data.servicio.nombre;
            document.getElementById('servicio-precio').textContent = data.servicio.precio + ' €';
            document.getElementById('servicio-duracion').textContent = data.servicio.duracion + ' minutos';
            
            document.getElementById('cliente-nombre').textContent = data.cliente.name;
            document.getElementById('cliente-email').textContent = data.cliente.email;
            document.getElementById('estilista-nombre').textContent = data.estilista.nombre;
            
            // Calcular la diferencia entre el precio del servicio y el importe pagado
            const diferencia = parseFloat(data.pago.importe) - parseFloat(data.servicio.precio);
            document.getElementById('diferencia-precio').textContent = diferencia.toFixed(2) + ' €';
            
            // Mostrar el modal
            document.getElementById('modal-detalles-pago').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error al obtener los detalles del pago:', error);
        });
}

// Función para cerrar el modal
function cerrarModal() {
    document.getElementById('modal-detalles-pago').classList.add('hidden');
}

// Función para descargar el PDF
function descargarPDF(pagoId) {
    window.location.href = `/pagos/${pagoId}/pdf`;
}
</script>


<!-- Modal para mostrar los detalles del pago -->
<div id="modal-detalles-pago" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-4/5 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Detalles del Pago</h3>
            <button type="button" class="text-gray-400 hover:text-gray-500" onclick="cerrarModal()">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Información del pago -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4 text-gray-900">Información del pago</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">ID del pago</p>
                        <p class="mt-1 text-lg text-gray-900" id="pago-id"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Fecha de pago</p>
                        <p class="mt-1 text-lg text-gray-900" id="pago-fecha"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Método de pago</p>
                        <p class="mt-1 text-lg text-gray-900" id="pago-metodo"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Importe</p>
                        <p class="mt-1 text-lg font-bold text-gray-900" id="pago-importe"></p>
                    </div>
                </div>
            </div>

            <!-- Información de la reserva -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4 text-gray-900">Información de la reserva</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">ID de la reserva</p>
                        <p class="mt-1 text-lg text-gray-900" id="reserva-id"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Fecha de la reserva</p>
                        <p class="mt-1 text-lg text-gray-900" id="reserva-fecha"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Hora</p>
                        <p class="mt-1 text-lg text-gray-900" id="reserva-hora"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Estado</p>
                        <p class="mt-1 text-lg text-gray-900" id="reserva-estado"></p>
                    </div>
                </div>
            </div>

            <!-- Información del servicio -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4 text-gray-900">Información del servicio</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Servicio</p>
                        <p class="mt-1 text-lg text-gray-900" id="servicio-nombre"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Precio del servicio</p>
                        <p class="mt-1 text-lg text-gray-900" id="servicio-precio"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Duración</p>
                        <p class="mt-1 text-lg text-gray-900" id="servicio-duracion"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Diferencia (Pago - Servicio)</p>
                        <p class="mt-1 text-lg font-bold text-gray-900" id="diferencia-precio"></p>
                    </div>
                </div>
            </div>

            <!-- Información del cliente y estilista -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4 text-gray-900">Información del cliente y estilista</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Cliente</p>
                        <p class="mt-1 text-lg text-gray-900" id="cliente-nombre"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Email del cliente</p>
                        <p class="mt-1 text-lg text-gray-900" id="cliente-email"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Estilista</p>
                        <p class="mt-1 text-lg text-gray-900" id="estilista-nombre"></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="descargarPDF(document.getElementById('pago-id').textContent)">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Descargar PDF
            </button>
        </div>
    </div>
</div>

@endsection