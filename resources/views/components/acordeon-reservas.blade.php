@props(['reservas'])

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Mis Reservas</h3>
    </div>
    
    @forelse ($reservas as $reserva)
        <div x-data="{ open: false }" class="border-b border-gray-200 last:border-b-0">
            <button @click="open = !open" 
                class="w-full flex justify-between items-center px-4 py-3 text-left hover:bg-gray-50 transition-colors duration-200">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded-full mr-3
                        @if($reserva->estado === 'PENDIENTE') bg-yellow-400
                        @elseif($reserva->estado === 'CANCELADA') bg-red-400
                        @elseif($reserva->estado === 'CONFIRMADA') bg-green-400
                        @endif
                    "></div>
                    <div>
                        <span class="font-medium text-gray-800">{{ $reserva->servicio->nombre }}</span>
                        <span class="text-sm text-gray-500 ml-2">{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($reserva->hora)->format('H:i') }}</span>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium
                        @if($reserva->estado === 'PENDIENTE') text-yellow-600
                        @elseif($reserva->estado === 'CANCELADA') text-red-600
                        @elseif($reserva->estado === 'CONFIRMADA') text-green-600
                        @endif
                    ">
                        {{ $reserva->estado }}
                    </span>
                    <svg x-show="!open" class="w-5 h-5 ml-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    <svg x-show="open" class="w-5 h-5 ml-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                </div>
            </button>
            
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-4 pb-4 pt-2 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600"><span class="font-medium">Servicio:</span> {{ $reserva->servicio->nombre }}</p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Fecha:</span> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Hora:</span> {{ \Carbon\Carbon::parse($reserva->hora)->format('H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600"><span class="font-medium">Estilista:</span> {{ $reserva->estilista->user->name ?? 'No asignado' }}</p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Estado:</span>
                            <span class="
                                @if($reserva->estado === 'PENDIENTE') text-yellow-600
                                @elseif($reserva->estado === 'CANCELADA') text-red-600
                                @elseif($reserva->estado === 'CONFIRMADA') text-green-600
                                @endif
                            ">
                                {{ $reserva->estado }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-600"><span class="font-medium">Duración:</span> {{ $reserva->servicio->duracion ?? '30' }} minutos</p>
                    </div>
                </div>
                
                @if ($reserva->estado !== 'CANCELADA')
                    <div class="mt-4 flex space-x-2">
                        <form method="POST" action="{{ route('reservas.cancelar', $reserva->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded transition-colors duration-200">
                                Cancelar Reserva
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="p-6 text-center text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p>No tienes reservas programadas</p>
        </div>
    @endforelse
</div> 