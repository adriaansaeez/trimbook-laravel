@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Editar Servicio</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-md mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('servicios.update', $servicio) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre', $servicio->nombre) }}" class="w-full p-2 border rounded-md" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Descripción</label>
            <textarea name="descripcion" class="w-full p-2 border rounded-md" required>{{ old('descripcion', $servicio->descripcion) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium">Precio (€)</label>
                <input type="number" step="0.01" name="precio" value="{{ old('precio', $servicio->precio ?? '') }}" class="w-full p-2 border rounded-md" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Duración (min)</label>
                <input type="number" name="duracion" value="{{ old('duracion', $servicio->duracion ?? '') }}" class="w-full p-2 border rounded-md" required>
            </div>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('servicios.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const interval = setInterval(function () {
        if (window.$) {
            clearInterval(interval);

            const form = $('form');
            const precioInput = $('input[name="precio"]');
            const duracionInput = $('input[name="duracion"]');

            function validarCampoNumerico(input, permitirDecimal = true) {
                const valor = input.val().trim();
                const regex = permitirDecimal ? /^\d+(\.\d{1,2})?$/ : /^\d+$/;
                if (!regex.test(valor) || Number(valor) <= 0) {
                    input.addClass('border-red-500 border-2');
                    return false;
                } else {
                    input.removeClass('border-red-500 border-2');
                    return true;
                }
            }

            precioInput.on('change', function () {
                validarCampoNumerico(precioInput, true);
            });

            duracionInput.on('change', function () {
                validarCampoNumerico(duracionInput, false);
            });

            form.submit(function (e) {
                let valido = true;

                if (!validarCampoNumerico(precioInput, true)) {
                    valido = false;
                }

                if (!validarCampoNumerico(duracionInput, false)) {
                    valido = false;
                }

                if (!valido) {
                    e.preventDefault();
                    alert('Por favor, corrige los campos resaltados en rojo.');
                }
            });
        }
    }, 50);
});
</script>
@endsection

@endsection
