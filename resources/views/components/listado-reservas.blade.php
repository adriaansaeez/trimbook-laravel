<div>
    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 border border-green-300 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 border border-red-300 rounded">
            {{ session('error') }}
        </div>
    @endif

    @forelse ($reservas as $reserva)
        <div x-data="{ open: false }" class="border-b">
            <button @click="open = !open"
                class="w-full text-left px-4 py-3 font-semibold bg-gray-100 hover:bg-gray-200">
                {{ $reserva->servicio->nombre }} |
                {{ $reserva->fecha }} {{ $reserva->hora }} |
                Estado:
                <span class="
                    @if($reserva->estado === 'PENDIENTE') text-yellow-600
                    @elseif($reserva->estado === 'CANCELADA') text-red-600
                    @elseif($reserva->estado === 'CONFIRMADA') text-green-600
                    @endif
                ">
                    {{ $reserva->estado }}
                </span>
            </button>

            <div x-show="open" x-transition class="px-4 pb-4 pt-2 bg-white">
                <p><strong>Servicio:</strong> {{ $reserva->servicio->nombre }}</p>
                <p><strong>Fecha:</strong> {{ $reserva->fecha }}</p>
                <p><strong>Hora:</strong> {{ $reserva->hora }}</p>
                <p>
                    <strong>Estado:</strong>
                    <span class="
                        @if($reserva->estado === 'PENDIENTE') text-yellow-600
                        @elseif($reserva->estado === 'CANCELADA') text-red-600
                        @elseif($reserva->estado === 'CONFIRMADA') text-green-600
                        @endif
                    ">
                        {{ $reserva->estado }}
                    </span>
                </p>

                @if ($esEstilista)
                    <p><strong>Cliente:</strong> {{ $reserva->cliente->username }}</p>
                @else
                    <p><strong>Estilista:</strong> {{ $reserva->estilista->user->username }}</p>
                @endif

                @if ($reserva->estado !== 'CANCELADA')
                    <div class="pt-2 space-x-2">
                        @if ($esEstilista)
                            <form method="POST" action="{{ route('reservas.cambiarEstado', $reserva->id) }}" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="estado" value="CONFIRMADA">
                                <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded">Confirmar</button>
                            </form>
                            <form method="POST" action="{{ route('reservas.cambiarEstado', $reserva->id) }}" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="estado" value="CANCELADA">
                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded">Cancelar</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('reservas.cancelar', $reserva->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded">Cancelar</button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @empty
        <p class="text-gray-500 p-4">No hay reservas registradas.</p>
    @endforelse
</div>
