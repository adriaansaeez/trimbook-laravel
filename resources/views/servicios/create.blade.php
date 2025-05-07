@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-3xl font-semibold text-gray-900">Crear Servicio</h1>
            <p class="mt-2 text-sm text-gray-700">Añade un nuevo servicio al sistema.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('servicios.index') }}"
               class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Volver a Servicios
            </a>
        </div>
    </div>

    {{-- Errores de validación --}}
    @if ($errors->any())
    <div class="rounded-md bg-red-50 p-4 mt-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M8.257 3.099c.366-.772 1.41-.772 1.777 0l6.518 13.748A1 1 0 0115.777
                             18H4.223a1 1 0 01-.775-1.653L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0
                             002 0zm-1-9a1 1 0 00-.894.553l-2 4a1 1 0
                             00.223 1.118l2 2a1 1 0 001.342 0l2-2a1 1 0
                             00.223-1.118l-2-4A1 1 0 0010 5z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <ul class="text-sm font-medium text-red-800 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    {{-- Formulario de creación --}}
    <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('servicios.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input
                    type="text"
                    name="nombre"
                    id="nombre"
                    required
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                />
            </div>

            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                <textarea
                    name="descripcion"
                    id="descripcion"
                    required
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                ></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="precio" class="block text-sm font-medium text-gray-700">Precio (€)</label>
                    <input
                        type="number"
                        step="0.01"
                        name="precio"
                        id="precio"
                        value="{{ old('precio') }}"
                        required
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>
                <div>
                    <label for="duracion" class="block text-sm font-medium text-gray-700">Duración (min)</label>
                    <input
                        type="number"
                        name="duracion"
                        id="duracion"
                        value="{{ old('duracion') }}"
                        required
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('servicios.index') }}"
                   class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Cancelar
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const precio = document.getElementById('precio');
    const duracion = document.getElementById('duracion');

    function validar(input, regex) {
        const val = input.value.trim();
        const ok = regex.test(val) && Number(val) > 0;
        input.classList.toggle('border-red-500', !ok);
        input.classList.toggle('border-2', !ok);
        return ok;
    }

    precio.addEventListener('change', () => validar(precio, /^\d+(\.\d{1,2})?$/));
    duracion.addEventListener('change', () => validar(duracion, /^\d+$/));

    document.querySelector('form').addEventListener('submit', function (e) {
        const vp = validar(precio, /^\d+(\.\d{1,2})?$/);
        const vd = validar(duracion, /^\d+$/);
        if (!vp || !vd) {
            e.preventDefault();
            alert('Por favor, corrige los campos resaltados en rojo.');
        }
    });
});
</script>
@endsection
