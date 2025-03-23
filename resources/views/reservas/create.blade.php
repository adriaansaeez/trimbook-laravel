@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Crear Reserva</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-md mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reservas.store') }}" method="POST">
        @csrf

        <div class="mb-4 flex gap-4">
            <div class="w-1/2">
                <label class="block text-sm font-medium">Servicio</label>
                <select name="servicio_id" id="servicio" class="w-full p-2 border rounded-md">
                    <option value="">Seleccione un servicio</option>
                    @foreach ($servicios as $servicio)
                        <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-1/2">
                <label class="block text-sm font-medium">Estilista</label>
                <select name="estilista_id" id="estilista" class="w-full p-2 border rounded-md" disabled>
                    <option value="">Seleccione un estilista</option>
                </select>
            </div>
        </div>


        <div class="mb-4 flex gap-4">
            <div class="w-1/2">
                <label class="block text-sm font-medium">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="w-full p-2 border rounded-md" disabled>
            </div>

            <div class="w-1/2" id="horarios-container" style="display: none;">
                <label class="block text-sm font-medium">Horas Disponibles</label>
                <div id="horas-lista" class="mt-1 space-y-1"></div>
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('reservas.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Confirmar Reserva
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('servicio').addEventListener('change', function() {
    let servicioId = this.value;
    let estilistaSelect = document.getElementById('estilista');
    let fechaInput = document.getElementById('fecha'); // 🟢 Seleccionamos el campo fecha

    estilistaSelect.innerHTML = '<option value="">Cargando...</option>';
    estilistaSelect.disabled = true;
    fechaInput.disabled = true; // 🟠 Deshabilitamos la fecha temporalmente

    axios.get(`/reservas/estilistas/${servicioId}`)
        .then(response => {
            estilistaSelect.innerHTML = '<option value="">Seleccione un estilista</option>';
            response.data.forEach(estilista => {
                estilistaSelect.innerHTML += `<option value="${estilista.id}">${estilista.nombre}</option>`;
            });
            estilistaSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error al obtener estilistas:', error);
        });
});

// 🟢 Habilitar la fecha cuando se selecciona un estilista
document.getElementById('estilista').addEventListener('change', function() {
    let fechaInput = document.getElementById('fecha');
    fechaInput.disabled = false; // ✅ Habilitamos el campo de fecha
});


document.getElementById('fecha').addEventListener('change', function() {
    let estilistaId = document.getElementById('estilista').value;
    let fecha = this.value;

    axios.get(`/reservas/horarios/${estilistaId}/${fecha}`)
        .then(response => {
            let horariosContainer = document.getElementById('horarios-container');
            let horasLista = document.getElementById('horas-lista');
            horasLista.innerHTML = '';
            response.data.forEach(hora => {
                horasLista.innerHTML += `<label><input type="radio" name="hora" value="${hora}"> ${hora}</label><br>`;
            });
            horariosContainer.style.display = 'block';
        });
});
</script>
@endsection
