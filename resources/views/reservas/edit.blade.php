@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Reserva</h1>
        
        <a href="{{ route('reservas.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver al listado
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('reservas.update', $reserva) }}" method="POST" class="space-y-6 p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Cliente -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                    <select id="user_id" name="user_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $reserva->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->perfil->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Servicio -->
                <div>
                    <label for="servicio_id" class="block text-sm font-medium text-gray-700">Servicio</label>
                    <select id="servicio_id" name="servicio_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}" {{ $reserva->servicio_id == $servicio->id ? 'selected' : '' }}>
                                {{ $servicio->nombre }} - {{ number_format($servicio->precio, 2) }} €
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Estilista -->
                <div>
                    <label for="estilista_id" class="block text-sm font-medium text-gray-700">Estilista</label>
                    <select id="estilista_id" name="estilista_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        @foreach($estilistas as $estilista)
                            <option value="{{ $estilista->id }}" {{ $reserva->estilista_id == $estilista->id ? 'selected' : '' }}>
                                {{ $estilista->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select id="estado" name="estado" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        @foreach($estados as $estado)
                            <option value="{{ $estado }}" {{ $reserva->estado == $estado ? 'selected' : '' }}>
                                {{ $estado }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha -->
                <div>
                    <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                    <input type="date" name="fecha" id="fecha" value="{{ \Carbon\Carbon::parse($reserva->fecha)->format('Y-m-d') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- Hora -->
                <div>
                    <label for="hora" class="block text-sm font-medium text-gray-700">Hora</label>
                    <input type="time" name="hora" id="hora" value="{{ \Carbon\Carbon::parse($reserva->hora)->format('H:i') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('reservas.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Actualizar estilistas disponibles cuando se selecciona un servicio
    document.getElementById('servicio_id').addEventListener('change', function() {
        const servicioId = this.value;
        const estilistaSelect = document.getElementById('estilista_id');
        
        // Limpiar opciones actuales
        estilistaSelect.innerHTML = '';
        
        // Obtener estilistas para el servicio seleccionado
        fetch(`/reservas/get-estilistas/${servicioId}`)
            .then(response => response.json())
            .then(estilistas => {
                estilistas.forEach(estilista => {
                    const option = document.createElement('option');
                    option.value = estilista.id;
                    option.textContent = estilista.nombre;
                    estilistaSelect.appendChild(option);
                });
            });
    });
</script>
@endpush
@endsection 