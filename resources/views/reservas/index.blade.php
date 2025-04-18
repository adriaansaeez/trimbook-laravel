@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 overflow-x-hidden">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Mis Reservas</h1>
        
        <div class="flex space-x-3">
            @if(auth()->user()->hasRole('estilista') || auth()->user()->hasRole('admin'))
                <a href="{{ route('reservas.exportar-excel') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exportar a Excel
                </a>
            @endif
            
            <a href="{{ route('reservas.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nueva Reserva
            </a>
        </div>
    </div>

    <!-- Mensajes de Éxito o Error -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white shadow rounded-lg mb-6 p-4">
        <form action="{{ route('reservas.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="servicio" class="block text-sm font-medium text-gray-700">Servicio</label>
                <select id="servicio" name="servicio" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Todos los servicios</option>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}" {{ request('servicio') == $servicio->id ? 'selected' : '' }}>
                            {{ $servicio->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="estilista" class="block text-sm font-medium text-gray-700">Estilista</label>
                <select id="estilista" name="estilista" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Todos los estilistas</option>
                    @foreach($estilistas as $estilista)
                        <option value="{{ $estilista->id }}" {{ request('estilista') == $estilista->id ? 'selected' : '' }}>
                            {{ $estilista->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                <select id="estado" name="estado" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Todos los estados</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado }}" {{ request('estado') == $estado ? 'selected' : '' }}>
                            {{ $estado }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="ordenar_por" class="block text-sm font-medium text-gray-700">Ordenar por</label>
                <select id="ordenar_por" name="ordenar_por" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="fecha" {{ request('ordenar_por') == 'fecha' ? 'selected' : '' }}>Fecha</option>
                    <option value="hora" {{ request('ordenar_por') == 'hora' ? 'selected' : '' }}>Hora</option>
                    <option value="estado" {{ request('ordenar_por') == 'estado' ? 'selected' : '' }}>Estado</option>
                </select>
            </div>
            
            <div>
                <label for="orden" class="block text-sm font-medium text-gray-700">Orden</label>
                <select id="orden" name="orden" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="desc" {{ request('orden') == 'desc' ? 'selected' : '' }}>Descendente</option>
                    <option value="asc" {{ request('orden') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                </select>
            </div>
            
            <div>
                <label for="fecha_desde" class="block text-sm font-medium text-gray-700">Fecha desde</label>
                <input type="date" id="fecha_desde" name="fecha_desde" value="{{ request('fecha_desde') }}" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            </div>
            
            <div>
                <label for="fecha_hasta" class="block text-sm font-medium text-gray-700">Fecha hasta</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    @if($reservas->count() > 0)
        <div class="bg-white shadow sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="w-1/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Fecha Reservada
                            </th>
                            <th scope="col" class="w-1/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Fecha de Creación
                            </th>
                            <th scope="col" class="w-1/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Hora
                            </th>
                            <th scope="col" class="w-2/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Servicio
                            </th>
                            @if(auth()->user()->hasRole('cliente'))
                                <th scope="col" class="w-2/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Estilista
                                </th>
                            @elseif(auth()->user()->hasRole('estilista'))
                                <th scope="col" class="w-2/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Cliente
                                </th>
                            @elseif(auth()->user()->hasRole('admin'))
                                <th scope="col" class="w-2/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Cliente
                                </th>
                                <th scope="col" class="w-2/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Estilista
                                </th>
                            @endif
                            <th scope="col" class="w-1/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Estado
                            </th>
                            <th scope="col" class="w-1/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Precio
                            </th>
                            <th scope="col" class="w-1/12 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Pago
                            </th>
                            <th scope="col" class="w-1/12 px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($reservas as $reserva)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900 truncate">
                                    {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 truncate">
                                    {{ \Carbon\Carbon::parse($reserva->created_at)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 truncate">
                                    {{ \Carbon\Carbon::parse($reserva->hora)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 truncate">
                                    {{ $reserva->servicio->nombre }}
                                </td>
                                @if(auth()->user()->hasRole('cliente'))
                                    <td class="px-6 py-4 text-sm text-gray-900 truncate">
                                        {{ $reserva->estilista->nombre }}
                                    </td>
                                @elseif(auth()->user()->hasRole('estilista'))
                                    <td class="px-6 py-4 text-sm text-gray-900 truncate">
                                        {{ $reserva->user->perfil->nombre }}
                                    </td>
                                @elseif(auth()->user()->hasRole('admin'))
                                    <td class="px-6 py-4 text-sm text-gray-900 truncate">
                                        {{ $reserva->user->perfil->nombre }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 truncate">
                                        {{ $reserva->estilista->nombre }}
                                    </td>
                                @endif
                                <td class="px-6 py-4 text-sm text-gray-900 truncate">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($reserva->estado == 'PENDIENTE') bg-yellow-100 text-yellow-800
                                        @elseif($reserva->estado == 'CONFIRMADA') bg-blue-100 text-blue-800
                                        @elseif($reserva->estado == 'CANCELADA') bg-red-100 text-red-800
                                        @elseif($reserva->estado == 'COMPLETADA') bg-green-100 text-green-800
                                        @endif">
                                        {{ $reserva->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 truncate">
                                    {{ number_format($reserva->precio, 2) }} €
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 truncate">
                                    @if($reserva->pagada)
                                        <span class="text-green-600">Pagado</span>
                                    @else
                                        <span class="text-red-600">No pagado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('reservas.show', $reserva) }}" class="text-indigo-600 hover:text-indigo-900" title="Ver detalles">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        
                                        <a href="{{ route('reservas.exportar-pdf', $reserva) }}" class="text-red-600 hover:text-red-900" title="Exportar a PDF">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </a>
                                        
                                        @if(auth()->user()->hasRole('admin'))
                                            <a href="{{ route('reservas.edit', $reserva) }}" class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        @endif
                                        
                                        @if($reserva->estado == 'PENDIENTE' && (auth()->id() == $reserva->user_id || auth()->user()->hasRole('admin')))
                                            <form action="{{ route('reservas.cancelar', $reserva) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Cancelar" onclick="return confirm('¿Estás seguro de cancelar esta reserva?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if((auth()->user()->hasRole('estilista') && auth()->user()->estilista->id == $reserva->estilista_id) || auth()->user()->hasRole('admin'))
                                            @if($reserva->estado == 'PENDIENTE')
                                                <form action="{{ route('reservas.confirmar', $reserva) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Confirmar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($reserva->estado == 'CONFIRMADA')
                                                <button type="button" class="text-blue-600 hover:text-blue-900" title="Completar y Pagar" onclick="abrirModalCompletarPago({{ $reserva->id }}, {{ $reserva->precio }}, '{{ $reserva->servicio->nombre }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            @endif
                                            
                                            @if($reserva->estado == 'COMPLETADA' && !$reserva->pagada)
                                                <button type="button" class="text-purple-600 hover:text-purple-900" title="Registrar Pago" onclick="abrirModalPago({{ $reserva->id }}, {{ $reserva->precio }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-4">
            {{ $reservas->links() }}
        </div>
    @else
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6 text-center">
            <p class="text-gray-500 text-lg">No se encontraron reservas con los filtros seleccionados.</p>
        </div>
    @endif
</div>

<!-- Modal de Pago -->
<div id="modalPago" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="formPago" action="" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Registrar Pago
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Complete los datos del pago para la reserva.
                                </p>
                            </div>
                            <div class="mt-4">
                                <div class="mb-4">
                                    <label for="metodo_pago" class="block text-sm font-medium text-gray-700">Método de Pago</label>
                                    <select id="metodo_pago" name="metodo_pago" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="EFECTIVO">Efectivo</option>
                                        <option value="TARJETA">Tarjeta</option>
                                        <option value="BIZUM">Bizum</option>
                                        <option value="TRANSFERENCIA">Transferencia</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="importe" class="block text-sm font-medium text-gray-700">Importe</label>
                                    <input type="number" step="0.01" id="importe" name="importe" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Registrar Pago
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="cerrarModalPago()">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Completar y Pago -->
<div id="modalCompletarPago" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="formCompletarPago" action="" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Completar Reserva y Registrar Pago
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="servicio-nombre-modal"></p>
                            </div>
                            <div class="mt-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Precio del Servicio</label>
                                    <input type="number" step="0.01" id="precio-servicio-modal" readonly class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 bg-gray-100 focus:outline-none sm:text-sm rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="metodo_pago_completar" class="block text-sm font-medium text-gray-700">Método de Pago</label>
                                    <select id="metodo_pago_completar" name="metodo_pago" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="EFECTIVO">Efectivo</option>
                                        <option value="TARJETA">Tarjeta</option>
                                        <option value="BIZUM">Bizum</option>
                                        <option value="TRANSFERENCIA">Transferencia</option>
                                    </select>
                                </div>
                                <div id="contenedor-efectivo" class="mb-4">
                                    <label for="importe_pagado" class="block text-sm font-medium text-gray-700">Importe Pagado</label>
                                    <input type="number" step="0.01" id="importe_pagado" name="importe" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <div class="mt-2">
                                        <label class="block text-sm font-medium text-gray-700">Cambio</label>
                                        <input type="number" step="0.01" id="cambio-modal" readonly class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 bg-gray-100 focus:outline-none sm:text-sm rounded-md">
                                    </div>
                                </div>
                                <div id="contenedor-otro-pago" class="mb-4 hidden">
                                    <label for="importe_otro" class="block text-sm font-medium text-gray-700">Importe</label>
                                    <input type="number" step="0.01" id="importe_otro" name="importe" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Completar y Pagar
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="cerrarModalCompletarPago()">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function abrirModalPago(reservaId, precio) {
        document.getElementById('modalPago').classList.remove('hidden');
        document.getElementById('formPago').action = `/reservas/${reservaId}/pago`;
        document.getElementById('importe').value = precio;
    }
    
    function cerrarModalPago() {
        document.getElementById('modalPago').classList.add('hidden');
    }

    function abrirModalCompletarPago(reservaId, precio, nombreServicio) {
        document.getElementById('modalCompletarPago').classList.remove('hidden');
        document.getElementById('formCompletarPago').action = `/reservas/${reservaId}/completar`;
        document.getElementById('precio-servicio-modal').value = precio;
        document.getElementById('servicio-nombre-modal').textContent = `Servicio: ${nombreServicio}`;
        document.getElementById('importe_pagado').value = precio;
        document.getElementById('importe_otro').value = precio;
        document.getElementById('cambio-modal').value = '0.00';
    }
    
    function cerrarModalCompletarPago() {
        document.getElementById('modalCompletarPago').classList.add('hidden');
    }

    // Manejar el cambio de método de pago
    document.getElementById('metodo_pago_completar').addEventListener('change', function() {
        const metodoPago = this.value;
        const contenedorEfectivo = document.getElementById('contenedor-efectivo');
        const contenedorOtroPago = document.getElementById('contenedor-otro-pago');
        
        if (metodoPago === 'EFECTIVO') {
            contenedorEfectivo.classList.remove('hidden');
            contenedorOtroPago.classList.add('hidden');
        } else {
            contenedorEfectivo.classList.add('hidden');
            contenedorOtroPago.classList.remove('hidden');
        }
    });

    // Calcular el cambio cuando se ingresa el importe pagado
    document.getElementById('importe_pagado').addEventListener('input', function() {
        const precioServicio = parseFloat(document.getElementById('precio-servicio-modal').value) || 0;
        const importePagado = parseFloat(this.value) || 0;
        const cambio = importePagado - precioServicio;
        
        document.getElementById('cambio-modal').value = cambio.toFixed(2);
    });
</script>
@endsection
