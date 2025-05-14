@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-3xl font-semibold text-gray-900">Asignar Servicios a Estilista</h1>
            <p class="mt-2 text-sm text-gray-700">Selecciona un estilista y asigna los servicios disponibles.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('estilistas.index') }}"
               class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Volver a Estilistas
            </a>
        </div>
    </div>

    {{-- Alerta de éxito --}}
    @if(session('success'))
    <div class="rounded-md bg-green-50 p-4 mt-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0
                             00-1.414-1.414L9 10.586 7.707 9.293a1 1 0
                             00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Formulario de asignación --}}
    <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('estilistas.asignar.servicios') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Selección de estilista --}}
            <div>
                <label for="estilista_id" class="block text-sm font-medium text-gray-700">Selecciona un estilista</label>
                <select
                    name="estilista_id"
                    id="estilista_id"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="">-- Elige un estilista --</option>
                    @foreach($estilistas as $estilista)
                        <option value="{{ $estilista->id }}">{{ $estilista->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Lista de servicios --}}
            <div>
                <span class="block text-sm font-medium text-gray-700 mb-2">Servicios</span>
                <div class="space-y-2">
                    @foreach($servicios as $servicio)
                        <label class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                name="servicios[]"
                                value="{{ $servicio->id }}"
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                {{ $estilista->servicios->contains($servicio->id) ? 'checked' : '' }}
                            >
                            <span class="text-gray-700">{{ $servicio->nombre }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Botón de enviar --}}
            <div>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Asignar Servicios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const estilistaSelect = document.getElementById('estilista_id');
        const checkboxes = document.querySelectorAll('input[name="servicios[]"]');

        estilistaSelect.addEventListener('change', function () {
            const estilistaId = this.value;

            if (estilistaId) {
                axios.get(`/api/v1/estilistas/${estilistaId}/servicios`)
                    .then(response => {
                        const assignedServices = response.data;

                        checkboxes.forEach(checkbox => {
                            checkbox.checked = assignedServices.includes(parseInt(checkbox.value));
                        });
                    })
                    .catch(error => {
                        console.error('Error al obtener los servicios del estilista:', error);
                    });
            } else {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        });
    });
</script>
@endsection
