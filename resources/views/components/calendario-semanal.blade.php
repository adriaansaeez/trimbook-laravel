@props(['estilistaId' => null, 'inicioSemana' => null, 'reservas' => [], 'horasDisponibles' => [], 'esEstilista' => false, 'esCliente' => false, 'esAdmin' => false])

<div class="relative w-full" id="calendario-container">
    <!-- Calendario (ocupa todo el ancho) -->
    <div class="bg-white rounded-lg shadow-lg p-6 w-full">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Calendario Semanal</h2>
            <div class="flex space-x-2">
                <button id="btn-semana-anterior" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="btn-semana-actual" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">
                    <i class="fas fa-calendar-day"></i> Hoy
                </button>
                <button id="btn-semana-siguiente" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <div class="overflow-y-auto" style="max-height: 80vh;">
                <table class="min-w-full">
                    <thead class="sticky top-0 bg-white">
                        <tr>
                            <th class="py-2 px-4 border-b text-left font-semibold text-gray-700">Hora</th>
                            @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $index => $dia)
                                <th class="py-2 px-4 border-b text-center font-semibold text-gray-700">
                                    {{ $dia }}
                                    <div class="text-xs font-normal text-gray-500" id="fecha-{{ strtolower($dia) }}">
                                        {{ $inicioSemana->copy()->addDays($index)->format('d/m') }}
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody id="calendario-body">
                        @foreach($horasDisponibles['lunes'] as $hora)
                            <tr>
                                <td class="py-2 px-4 border-b text-sm font-medium text-gray-700">{{ $hora }}</td>
                                @foreach(['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'] as $index => $dia)
                                    @php
                                        $fechaActual = $inicioSemana->copy()->addDays($index);
                                        $reserva = $reservas->first(function($r) use ($fechaActual, $hora) {
                                            $fechaReserva = Carbon\Carbon::parse($r->fecha);
                                            $horaReserva = Carbon\Carbon::parse($r->hora)->format('H:i');
                                            return $fechaReserva->isSameDay($fechaActual) && $horaReserva === $hora;
                                        });
                                        
                                        // Calcular la altura de la celda según la duración del servicio
                                        $altura = 'h-16'; // Altura por defecto (1 hora)
                                        if ($reserva && $reserva->servicio) {
                                            $duracion = $reserva->servicio->duracion ?? 60; // Duración en minutos, por defecto 60
                                            $altura = 'h-' . ($duracion / 30 * 8); // 8px por cada 30 minutos
                                        }
                                        
                                        // Obtener el nombre del cliente
                                        $nombreCliente = 'Cliente';
                                        if ($reserva && $reserva->user && $reserva->user->perfil) {
                                            $nombreCliente = $reserva->user->perfil->nombre . ' ' . $reserva->user->perfil->apellidos;
                                        } else if ($reserva && $reserva->user) {
                                            $nombreCliente = $reserva->user->username;
                                        }
                                        
                                        // Obtener el nombre del estilista
                                        $nombreEstilista = 'Estilista';
                                        if ($reserva && $reserva->estilista && $reserva->estilista->user && $reserva->estilista->user->perfil) {
                                            $nombreEstilista = $reserva->estilista->user->perfil->nombre . ' ' . $reserva->estilista->user->perfil->apellidos;
                                        } else if ($reserva && $reserva->estilista && $reserva->estilista->user) {
                                            $nombreEstilista = $reserva->estilista->user->username;
                                        } else if ($reserva && $reserva->estilista) {
                                            $nombreEstilista = $reserva->estilista->nombre ?? 'Estilista';
                                        }

                                        // Determinar el color según el estado
                                        $colorClase = '';
                                        if ($reserva) {
                                            switch(strtoupper($reserva->estado)) {
                                                case 'CONFIRMADA':
                                                    $colorClase = 'bg-blue-100 hover:bg-blue-200';
                                                    break;
                                                case 'CANCELADA':
                                                    $colorClase = 'bg-red-100 hover:bg-red-200';
                                                    break;
                                                case 'COMPLETADA':
                                                    $colorClase = 'bg-green-100 hover:bg-green-200';
                                                    break;
                                                default:
                                                    $colorClase = 'bg-gray-100 hover:bg-gray-200';
                                            }
                                        }
                                    @endphp
                                    <td class="py-2 px-4 border-b text-center relative {{ $reserva ? $colorClase : '' }} cursor-pointer" 
                                        data-dia="{{ $dia }}" 
                                        data-hora="{{ $hora }}"
                                        @if($reserva)
                                        data-reserva="{!! htmlspecialchars(json_encode([
                                            'id' => $reserva->id,
                                            'cliente' => $nombreCliente,
                                            'estilista' => $nombreEstilista,
                                            'servicio' => $reserva->servicio->nombre ?? 'Servicio',
                                            'fecha' => Carbon\Carbon::parse($reserva->fecha)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
                                            'hora' => Carbon\Carbon::parse($reserva->hora)->format('H:i'),
                                            'estado' => $reserva->estado,
                                            'precio' => $reserva->servicio ? number_format($reserva->servicio->precio, 2, '.', '') : '0.00'
                                        ]), ENT_QUOTES, 'UTF-8') !!}"
                                        @endif
                                    >
                                        @if($reserva)
                                            <div class="text-xs {{ $altura }} flex flex-col justify-center">
                                                @if($esEstilista)
                                                    <p class="font-medium">{{ $nombreCliente }}</p>
                                                    <p class="text-gray-600">{{ $reserva->servicio->nombre ?? 'Servicio' }}</p>
                                                @elseif($esCliente)
                                                    <p class="font-medium">{{ $nombreEstilista }}</p>
                                                    <p class="text-gray-600">{{ $reserva->servicio->nombre ?? 'Servicio' }}</p>
                                                @elseif($esAdmin)
                                                    <p class="font-medium">Cliente: {{ $nombreCliente }}</p>
                                                    <p class="font-medium">Estilista: {{ $nombreEstilista }}</p>
                                                    <p class="text-gray-600">{{ $reserva->servicio->nombre ?? 'Servicio' }}</p>
                                                @endif
                                                
                                                @if($reserva->estado === 'CONFIRMADA')
                                                    <div class="mt-1 flex justify-center space-x-1">
                                                        <form action="{{ route('reservas.cancelar', $reserva) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-xs bg-red-500 text-white px-1 py-0.5 rounded hover:bg-red-600">
                                                                Cancelar
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel de detalles flotante (inicialmente oculto) -->
    <div class="fixed top-0 right-0 h-full w-full md:w-96 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-50" id="detalles-panel">
        <div class="relative h-full p-6 overflow-y-auto">
            <!-- Botón de cerrar -->
            <button id="cerrar-detalles" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div id="detalles-reserva" class="hidden mt-8">
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
                                <select name="metodo_pago" id="metodo-pago" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="EFECTIVO">Efectivo</option>
                                    <option value="TARJETA">Tarjeta</option>
                                    <option value="BIZUM">Bizum</option>
                                    <option value="TRANSFERENCIA">Transferencia</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Precio del Servicio</label>
                                <input type="number" step="0.01" id="precio-servicio" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                            </div>
                            <div id="importe-efectivo-container" class="hidden">
                                <label class="block text-sm font-medium text-gray-700">Importe Pagado</label>
                                <input type="number" step="0.01" id="importe-pagado" name="importe" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <div class="mt-2">
                                    <label class="block text-sm font-medium text-gray-700">Cambio</label>
                                    <input type="number" step="0.01" id="cambio" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                </div>
                            </div>
                            <div id="importe-otro-container">
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
            <div id="sin-seleccion" class="text-center text-gray-500 mt-8">
                <p>Selecciona una reserva para ver los detalles</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Obtener la fecha actual del sistema
    let fechaActual = new Date();
    fechaActual.setHours(0, 0, 0, 0);
    
    // Variable para almacenar el desplazamiento de semanas
    let desplazamientoSemanas = 0;
    
    const esAdmin = @json($esAdmin);
    const esEstilista = @json($esEstilista);
    const esCliente = @json($esCliente);

    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

    const dias = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'];

    function getColorClase(estado) {
        const colores = {
            PENDIENTE: 'bg-yellow-100 hover:bg-yellow-200',
            CONFIRMADA: 'bg-blue-100 hover:bg-blue-200',
            CANCELADA: 'bg-red-100 hover:bg-red-200',
            COMPLETADA: 'bg-green-100 hover:bg-green-200'
        };
        return colores[estado?.toUpperCase()] || 'bg-gray-100 hover:bg-gray-200';
    }

    function crearBotonAccion(texto, clase, onClick) {
        const btn = document.createElement('button');
        btn.className = `text-xs ${clase} text-white px-1 py-0.5 rounded hover:opacity-90`;
        btn.textContent = texto;
        btn.onclick = onClick;
        return btn;
    }

    function actualizarCalendario() {
        // Calcular la fecha actual considerando el desplazamiento
        let fechaCalculada = new Date(fechaActual);
        fechaCalculada.setDate(fechaCalculada.getDate() + (desplazamientoSemanas * 7));
        
        // Formatear la fecha para la API
        const fechaFormateada = fechaCalculada.toISOString().split('T')[0];
        
        axios.get(`/calendario-data?fecha=${fechaFormateada}&desplazamiento=${desplazamientoSemanas}`)
            .then(response => {
                const data = response.data;
                
                // Actualizar los días en el calendario
                dias.forEach((dia, index) => {
                    const fechaElement = document.getElementById(`fecha-${dia}`);
                    if (fechaElement) {
                        const fecha = new Date(data.inicioSemana);
                        fecha.setDate(fecha.getDate() + index);
                        fechaElement.textContent = fecha.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit' });
                    }
                });
                
                // Actualizar los eventos
                if (data.calendario) {
                    actualizarEventos(data.calendario);
                }
            })
            .catch(error => {
                console.error('Error al actualizar el calendario:', error);
            });
    }

    function confirmarReserva(id) {
        axios.post(`/reservas/${id}/confirmar`)
            .then(() => {
                alert('Reserva confirmada');
                actualizarCalendario();
            })
            .catch(err => alert('Error al confirmar reserva'));
    }

    function cancelarReserva(id) {
        axios.post(`/reservas/${id}/cancelar`)
            .then(() => {
                alert('Reserva cancelada');
                actualizarCalendario();
            })
            .catch(err => alert('Error al cancelar reserva'));
    }

    function actualizarEventos(eventos) {
        const tbody = document.getElementById('calendario-body');
        tbody.innerHTML = '';

        eventos.forEach(hora => {
            const tr = document.createElement('tr');
            const tdHora = document.createElement('td');
            tdHora.className = 'py-2 px-4 border-b text-sm font-medium text-gray-700';
            tdHora.textContent = hora.hora;
            tr.appendChild(tdHora);

            dias.forEach(dia => {
                const td = document.createElement('td');
                td.className = 'py-2 px-4 border-b text-center relative';
                td.dataset.dia = dia;
                td.dataset.hora = hora.hora;

                const reserva = hora.reservas[dia];
                if (reserva) {
                    const clase = getColorClase(reserva.estado);
                    td.classList.add(...clase.split(' '), 'cursor-pointer');
                    td.dataset.reserva = JSON.stringify(reserva);

                    const div = document.createElement('div');
                    div.className = 'text-xs flex flex-col justify-center';

                    if (esEstilista) {
                        div.innerHTML = `<p class="font-medium">${reserva.cliente}</p><p class="text-gray-600">${reserva.servicio}</p>`;
                    } else if (esCliente) {
                        div.innerHTML = `<p class="font-medium">${reserva.estilista}</p><p class="text-gray-600">${reserva.servicio}</p>`;
                    } else if (esAdmin) {
                        div.innerHTML = `
                            <p class="font-medium">Cliente: ${reserva.cliente}</p>
                            <p class="font-medium">Estilista: ${reserva.estilista}</p>
                            <p class="text-gray-600">${reserva.servicio}</p>`;
                    }

                    if (reserva.estado === 'PENDIENTE') {
                        const acciones = document.createElement('div');
                        acciones.className = 'mt-1 flex justify-center space-x-1';

                        if (esEstilista) {
                            acciones.appendChild(crearBotonAccion('Confirmar', 'bg-green-500', () => confirmarReserva(reserva.id)));
                        }

                        acciones.appendChild(crearBotonAccion('Cancelar', 'bg-red-500', () => cancelarReserva(reserva.id)));
                        div.appendChild(acciones);
                    }

                    td.appendChild(div);
                }

                tr.appendChild(td);
            });

            tbody.appendChild(tr);
        });
    }

    document.getElementById('btn-semana-anterior').addEventListener('click', () => {
        desplazamientoSemanas--;
        actualizarCalendario();
    });

    document.getElementById('btn-semana-actual').addEventListener('click', () => {
        desplazamientoSemanas = 0;
        actualizarCalendario();
    });

    document.getElementById('btn-semana-siguiente').addEventListener('click', () => {
        desplazamientoSemanas++;
        actualizarCalendario();
    });

    // Inicializar el calendario
    actualizarCalendario();

    document.addEventListener('click', function (e) {
        const td = e.target.closest('td[data-reserva]');
        if (!td) return;

        const reserva = JSON.parse(td.dataset.reserva);
        console.log('Datos de la reserva:', reserva);
        
        const detalles = document.getElementById('detalles-reserva');
        const sinSeleccion = document.getElementById('sin-seleccion');
        const formPago = document.getElementById('formulario-pago');
        const detallesPanel = document.getElementById('detalles-panel');

        document.getElementById('cliente-nombre').textContent = reserva.cliente;
        document.getElementById('servicio-nombre').textContent = reserva.servicio;
        
        // Usar directamente los valores formateados desde el backend
        document.getElementById('fecha-hora').textContent = 
            `${reserva.fecha} a las ${reserva.hora}`;
        
        document.getElementById('estado-reserva').textContent = reserva.estado;

        if ((['CONFIRMADA', 'PENDIENTE'].includes(reserva.estado)) && (esAdmin || esEstilista)) {
            formPago.classList.remove('hidden');
            document.getElementById('reserva-id').value = reserva.id;
            
            // Establecer el precio del servicio
            const precioServicio = reserva.precio;
            console.log('Precio del servicio:', precioServicio);
            document.getElementById('precio-servicio').value = precioServicio;
            
            // Inicializar el formulario de pago
            const metodoPago = document.getElementById('metodo-pago').value;
            const importeEfectivoContainer = document.getElementById('importe-efectivo-container');
            const importeOtroContainer = document.getElementById('importe-otro-container');
            
            if (metodoPago === 'EFECTIVO') {
                importeEfectivoContainer.classList.remove('hidden');
                importeOtroContainer.classList.add('hidden');
                document.getElementById('importe-pagado').value = precioServicio;
                document.getElementById('cambio').value = '0.00';
            } else {
                importeEfectivoContainer.classList.add('hidden');
                importeOtroContainer.classList.remove('hidden');
                document.querySelector('#importe-otro-container input[name="importe"]').value = precioServicio;
            }
        } else {
            formPago.classList.add('hidden');
        }

        detalles.classList.remove('hidden');
        sinSeleccion.classList.add('hidden');
        
        // Mostrar el panel de detalles con animación
        detallesPanel.classList.remove('translate-x-full');
    });

    // Manejar el cambio de método de pago
    document.getElementById('metodo-pago').addEventListener('change', function() {
        const metodoPago = this.value;
        const importeEfectivoContainer = document.getElementById('importe-efectivo-container');
        const importeOtroContainer = document.getElementById('importe-otro-container');
        
        if (metodoPago === 'EFECTIVO') {
            importeEfectivoContainer.classList.remove('hidden');
            importeOtroContainer.classList.add('hidden');
        } else {
            importeEfectivoContainer.classList.add('hidden');
            importeOtroContainer.classList.remove('hidden');
        }
    });

    // Calcular el cambio cuando se ingresa el importe pagado
    document.getElementById('importe-pagado').addEventListener('input', function() {
        const precioServicio = parseFloat(document.getElementById('precio-servicio').value) || 0;
        const importePagado = parseFloat(this.value) || 0;
        const cambio = importePagado - precioServicio;
        
        document.getElementById('cambio').value = cambio.toFixed(2);
    });

    document.getElementById('pago-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const metodoPago = document.getElementById('metodo-pago').value;
        
        // Si es efectivo, usar el importe pagado
        if (metodoPago === 'EFECTIVO') {
            formData.set('importe', document.getElementById('importe-pagado').value);
        }

        axios.post('/pagos', formData)
            .then(({ data }) => {
                if (data.success) {
                    const reservaId = formData.get('reserva_id');
                    const td = document.querySelector(`td[data-reserva*='"id":${reservaId}']`);
                    if (td) {
                        const reserva = JSON.parse(td.dataset.reserva);
                        reserva.estado = 'COMPLETADA';
                        td.dataset.reserva = JSON.stringify(reserva);
                        td.classList.remove('bg-blue-100', 'hover:bg-blue-200');
                        td.classList.add('bg-green-100', 'hover:bg-green-200');
                    }

                    document.getElementById('estado-reserva').textContent = 'COMPLETADA';
                    document.getElementById('formulario-pago').classList.add('hidden');
                    alert('Pago procesado correctamente');
                } else {
                    alert('Error al procesar el pago: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error al procesar el pago');
            });
    });

    // Añadir evento para cerrar el panel de detalles
    document.getElementById('cerrar-detalles').addEventListener('click', function() {
        const detallesPanel = document.getElementById('detalles-panel');
        detallesPanel.classList.add('translate-x-full');
    });
});
</script>

