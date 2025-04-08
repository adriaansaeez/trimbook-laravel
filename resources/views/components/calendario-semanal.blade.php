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
                                @endphp
                                <td class="py-2 px-4 border-b text-center relative {{ $reserva ? 'bg-blue-100' : '' }}" data-dia="{{ $dia }}" data-hora="{{ $hora }}">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    let fechaActual = new Date('{{ $inicioSemana }}');
    
    function actualizarCalendario() {
        const fechaFormateada = fechaActual.toISOString().split('T')[0];
        
        fetch(`/calendario-data?fecha=${fechaFormateada}`)
            .then(response => response.json())
            .then(data => {
                // Actualizar las fechas en los encabezados
                const dias = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'];
                dias.forEach((dia, index) => {
                    const fecha = new Date(data.inicioSemana);
                    fecha.setDate(fecha.getDate() + index);
                    document.getElementById(`fecha-${dia}`).textContent = 
                        fecha.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit' });
                });
                
                // Actualizar el contenido del calendario
                const tbody = document.getElementById('calendario-body');
                tbody.innerHTML = '';
                
                data.calendario.forEach(hora => {
                    const tr = document.createElement('tr');
                    
                    // Celda de hora
                    const tdHora = document.createElement('td');
                    tdHora.className = 'py-2 px-4 border-b text-sm font-medium text-gray-700';
                    tdHora.textContent = hora.hora;
                    tr.appendChild(tdHora);
                    
                    // Celdas para cada día
                    dias.forEach(dia => {
                        const td = document.createElement('td');
                        td.className = 'py-2 px-4 border-b text-center relative';
                        td.setAttribute('data-dia', dia);
                        td.setAttribute('data-hora', hora.hora);
                        
                        const reserva = hora.reservas[dia];
                        if (reserva) {
                            td.classList.add('bg-blue-100');
                            
                            const div = document.createElement('div');
                            div.className = 'text-xs flex flex-col justify-center';
                            
                            if (data.esEstilista) {
                                div.innerHTML = `
                                    <p class="font-medium">${reserva.cliente}</p>
                                    <p class="text-gray-600">${reserva.servicio}</p>
                                `;
                            } else if (data.esCliente) {
                                div.innerHTML = `
                                    <p class="font-medium">${reserva.estilista}</p>
                                    <p class="text-gray-600">${reserva.servicio}</p>
                                `;
                            } else if (data.esAdmin) {
                                div.innerHTML = `
                                    <p class="font-medium">Cliente: ${reserva.cliente}</p>
                                    <p class="font-medium">Estilista: ${reserva.estilista}</p>
                                    <p class="text-gray-600">${reserva.servicio}</p>
                                `;
                            }
                            
                            if (reserva.estado === 'PENDIENTE') {
                                const accionesDiv = document.createElement('div');
                                accionesDiv.className = 'mt-1 flex justify-center space-x-1';
                                
                                if (data.esEstilista) {
                                    const formConfirmar = document.createElement('form');
                                    formConfirmar.action = `/reservas/${reserva.id}/confirmar`;
                                    formConfirmar.method = 'POST';
                                    formConfirmar.className = 'inline';
                                    
                                    const csrfToken = document.createElement('input');
                                    csrfToken.type = 'hidden';
                                    csrfToken.name = '_token';
                                    csrfToken.value = '{{ csrf_token() }}';
                                    
                                    const btnConfirmar = document.createElement('button');
                                    btnConfirmar.type = 'submit';
                                    btnConfirmar.className = 'text-xs bg-green-500 text-white px-1 py-0.5 rounded hover:bg-green-600';
                                    btnConfirmar.textContent = 'Confirmar';
                                    
                                    formConfirmar.appendChild(csrfToken);
                                    formConfirmar.appendChild(btnConfirmar);
                                    accionesDiv.appendChild(formConfirmar);
                                }
                                
                                const formCancelar = document.createElement('form');
                                formCancelar.action = `/reservas/${reserva.id}/cancelar`;
                                formCancelar.method = 'POST';
                                formCancelar.className = 'inline';
                                
                                const csrfTokenCancelar = document.createElement('input');
                                csrfTokenCancelar.type = 'hidden';
                                csrfTokenCancelar.name = '_token';
                                csrfTokenCancelar.value = '{{ csrf_token() }}';
                                
                                const btnCancelar = document.createElement('button');
                                btnCancelar.type = 'submit';
                                btnCancelar.className = 'text-xs bg-red-500 text-white px-1 py-0.5 rounded hover:bg-red-600';
                                btnCancelar.textContent = 'Cancelar';
                                
                                formCancelar.appendChild(csrfTokenCancelar);
                                formCancelar.appendChild(btnCancelar);
                                accionesDiv.appendChild(formCancelar);
                                
                                div.appendChild(accionesDiv);
                            }
                            
                            td.appendChild(div);
                        }
                        
                        tr.appendChild(td);
                    });
                    
                    tbody.appendChild(tr);
                });
            })
            .catch(error => console.error('Error al cargar el calendario:', error));
    }
    
    // Eventos para los botones de navegación
    document.getElementById('btn-semana-anterior').addEventListener('click', function() {
        fechaActual.setDate(fechaActual.getDate() - 7);
        actualizarCalendario();
    });
    
    document.getElementById('btn-semana-siguiente').addEventListener('click', function() {
        fechaActual.setDate(fechaActual.getDate() + 7);
        actualizarCalendario();
    });
    
    // Cargar el calendario inicial
    actualizarCalendario();
});
</script>
