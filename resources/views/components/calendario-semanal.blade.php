@props(['estilistaId' => null, 'inicioSemana' => null, 'reservas' => [], 'horasDisponibles' => [], 'esEstilista' => false, 'esCliente' => false, 'esAdmin' => false])

<div class="bg-white rounded-lg shadow-lg p-6" id="calendario-container">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Calendario Semanal</h2>
        <div class="flex space-x-2">
            <button id="btn-semana-anterior" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button id="btn-semana-siguiente" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <div class="overflow-y-auto" style="max-height: 50vh;">
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
                                            case 'PENDIENTE':
                                                $colorClase = 'bg-yellow-100 hover:bg-yellow-200';
                                                break;
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
                                        'fecha' => $reserva->fecha,
                                        'hora' => $reserva->hora,
                                        'estado' => $reserva->estado,
                                        'precio' => $reserva->servicio->precio ?? 0
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
                                            
                                            @if(strtoupper($reserva->estado) === 'PENDIENTE')
                                                <div class="mt-1 flex justify-center space-x-1">
                                                    @if($esEstilista)
                                                        <form action="{{ route('reservas.confirmar', $reserva) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-xs bg-green-500 text-white px-1 py-0.5 rounded hover:bg-green-600">
                                                                Confirmar
                                                            </button>
                                                        </form>
                                                    @endif
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

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
document.addEventListener('DOMContentLoaded', function () {
    let fechaActual = new Date('{{ $inicioSemana }}');
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
        const fechaFormateada = fechaActual.toISOString().split('T')[0];

        axios.get('/calendario-data', { params: { fecha: fechaFormateada } })
            .then(({ data }) => {
                dias.forEach((dia, i) => {
                    const fecha = new Date(data.inicioSemana);
                    fecha.setDate(fecha.getDate() + i);
                    document.getElementById(`fecha-${dia}`).textContent = fecha.toLocaleDateString('es-ES', {
                        day: '2-digit', month: '2-digit'
                    });
                });

                const tbody = document.getElementById('calendario-body');
                tbody.innerHTML = '';

                data.calendario.forEach(hora => {
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
            })
            .catch(err => console.error('Error al cargar el calendario:', err));
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

    document.getElementById('btn-semana-anterior').addEventListener('click', () => {
        fechaActual.setDate(fechaActual.getDate() - 7);
        actualizarCalendario();
    });

    document.getElementById('btn-semana-siguiente').addEventListener('click', () => {
        fechaActual.setDate(fechaActual.getDate() + 7);
        actualizarCalendario();
    });

    document.addEventListener('click', function (e) {
        const td = e.target.closest('td[data-reserva]');
        if (!td) return;

        const reserva = JSON.parse(td.dataset.reserva);
        const detalles = document.getElementById('detalles-reserva');
        const sinSeleccion = document.getElementById('sin-seleccion');
        const formPago = document.getElementById('formulario-pago');

        document.getElementById('cliente-nombre').textContent = reserva.cliente;
        document.getElementById('servicio-nombre').textContent = reserva.servicio;
        document.getElementById('fecha-hora').textContent = `${reserva.fecha} ${reserva.hora}`;
        document.getElementById('estado-reserva').textContent = reserva.estado;

        if ((['CONFIRMADA', 'PENDIENTE'].includes(reserva.estado)) && (esAdmin || esEstilista)) {
            formPago.classList.remove('hidden');
            document.getElementById('reserva-id').value = reserva.id;
            document.querySelector('input[name="importe"]').value = reserva.precio;
        } else {
            formPago.classList.add('hidden');
        }

        detalles.classList.remove('hidden');
        sinSeleccion.classList.add('hidden');
    });

    document.getElementById('pago-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

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

    actualizarCalendario();
});
</script>

