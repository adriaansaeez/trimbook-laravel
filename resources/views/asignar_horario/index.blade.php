@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <h1 class="text-2xl font-semibold mb-4">Asignar Horarios a Estilistas</h1>

    <div class="bg-white rounded-lg shadow p-6">
        {{-- Selector de estilista --}}
        <div class="mb-6">
            <label for="estilista_id" class="block mb-2 font-medium text-gray-700">Selecciona un estilista:</label>
            <select name="estilista_id" id="estilista_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">-- Elegir estilista --</option>
                @foreach($estilistas as $estilista)
                    <option value="{{ $estilista->id }}">{{ $estilista->nombre }}</option>
                @endforeach
            </select>
        </div>

        {{-- Botón para mostrar el formulario de asignación --}}
        <button id="mostrar-formulario" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg mb-4 hidden">
            <i class="fas fa-plus mr-2"></i>Asignar nuevo horario
        </button>

        {{-- Contenedor para los horarios asignados --}}
        <div id="horarios-asignados" class="mt-6 space-y-4">
            {{-- Aquí se cargarán dinámicamente los horarios --}}
        </div>

        {{-- Formulario de asignación de horario --}}
        <div id="formulario-asignacion" class="hidden mt-6 bg-gray-50 p-6 rounded-lg border">
            <h3 class="text-lg font-semibold mb-4">Asignar nuevo horario</h3>
            <form id="form-asignar-horario" class="space-y-4">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="form-estilista-id" name="estilista_id">
                
                <div class="mb-4">
                    <label for="horario_id" class="block mb-2 font-medium text-gray-700">Selecciona un horario:</label>
                    <select name="horario_id" id="horario_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Seleccionar horario --</option>
                        @foreach($horarios as $horario)
                            <option value="{{ $horario->id }}">{{ $horario->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="fecha_inicio" class="block mb-2 font-medium text-gray-700">Fecha de inicio:</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" 
                               class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="fecha_fin" class="block mb-2 font-medium text-gray-700">Fecha de fin:</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" 
                               class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" id="cancelar-asignacion" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Guardar asignación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script iniciado');

    const estilistasSelect = document.getElementById('estilista_id');
    const horariosContainer = document.getElementById('horarios-asignados');
    const mostrarFormularioBtn = document.getElementById('mostrar-formulario');
    const formularioAsignacion = document.getElementById('formulario-asignacion');
    const formEstilistaId = document.getElementById('form-estilista-id');
    const formAsignarHorario = document.getElementById('form-asignar-horario');
    const cancelarAsignacionBtn = document.getElementById('cancelar-asignacion');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Verificar que todos los elementos existen
    console.log('Elementos encontrados:', {
        estilistasSelect: !!estilistasSelect,
        horariosContainer: !!horariosContainer,
        mostrarFormularioBtn: !!mostrarFormularioBtn,
        formularioAsignacion: !!formularioAsignacion,
        csrfToken: !!csrfToken
    });

    // Cargar horarios cuando se selecciona un estilista
    estilistasSelect.addEventListener('change', async function() {
        const estilistaId = this.value;
        console.log('Estilista seleccionado:', estilistaId);

        if (estilistaId) {
            mostrarFormularioBtn.classList.remove('hidden');
            formEstilistaId.value = estilistaId;
            await cargarHorarios(estilistaId);
        } else {
            horariosContainer.innerHTML = '';
            mostrarFormularioBtn.classList.add('hidden');
            formularioAsignacion.classList.add('hidden');
        }
    });

    // Mostrar formulario de asignación
    mostrarFormularioBtn.addEventListener('click', function() {
        console.log('Mostrando formulario');
        formularioAsignacion.classList.remove('hidden');
        this.classList.add('hidden');
    });

    // Cancelar asignación
    cancelarAsignacionBtn.addEventListener('click', function() {
        formularioAsignacion.classList.add('hidden');
        mostrarFormularioBtn.classList.remove('hidden');
        formAsignarHorario.reset();
    });

    // Manejar envío del formulario
    formAsignarHorario.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Enviando formulario');
        
        try {
            const formData = new FormData(this);
            const jsonData = Object.fromEntries(formData.entries());
            console.log('Datos a enviar:', jsonData);

            const response = await fetch('/dashboard/asignar-horario/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(jsonData)
            });

            if (!response.ok) {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const data = await response.json();
                    throw new Error(data.message || 'Error al asignar el horario');
                } else {
                    throw new Error('Error en la respuesta del servidor');
                }
            }

            const data = await response.json();
            console.log('Respuesta del servidor:', data);

            await cargarHorarios(formEstilistaId.value);
            formularioAsignacion.classList.add('hidden');
            mostrarFormularioBtn.classList.remove('hidden');
            this.reset();
            alert('Horario asignado correctamente');
        } catch (error) {
            console.error('Error:', error);
            alert(error.message || 'Error al procesar la solicitud');
        }
    });
});

// Función para cargar horarios
async function cargarHorarios(estilistaId) {
    console.log('Iniciando carga de horarios para estilista:', estilistaId);
    const horariosContainer = document.getElementById('horarios-asignados');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    try {
        const response = await fetch(`/dashboard/asignar-horario/obtener-horarios/${estilistaId}`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                const data = await response.json();
                throw new Error(data.message || 'Error al cargar los horarios');
            } else {
                throw new Error('Error en la respuesta del servidor');
            }
        }

        const data = await response.json();
        console.log('Datos recibidos:', data);

        if (!data.horarios || data.horarios.length === 0) {
            horariosContainer.innerHTML = `
                <div class="text-center py-4">
                    <p class="text-gray-600">Este estilista no tiene horarios asignados.</p>
                </div>`;
            return;
        }

        const horariosHTML = data.horarios.map(horario => `
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex justify-between items-center">
                <div>
                    <h4 class="font-semibold text-lg">${horario.nombre}</h4>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Desde:</span> ${formatearFecha(horario.pivot.fecha_inicio)} 
                        <span class="font-medium ml-2">Hasta:</span> ${formatearFecha(horario.pivot.fecha_fin)}
                    </p>
                </div>
                <button 
                    onclick="eliminarHorario(${horario.pivot.id})"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Eliminar
                </button>
            </div>
        `).join('');

        horariosContainer.innerHTML = horariosHTML;
        console.log('Horarios cargados correctamente');
    } catch (error) {
        console.error('Error al cargar horarios:', error);
        horariosContainer.innerHTML = `
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <p>Error al cargar los horarios: ${error.message}</p>
            </div>`;
    }
}

// Función para eliminar horario
async function eliminarHorario(pivotId) {
    if (!confirm('¿Estás seguro de que deseas eliminar esta asignación?')) return;

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const response = await fetch(`/dashboard/asignar-horario/eliminar/${pivotId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                const data = await response.json();
                throw new Error(data.message || 'Error al eliminar la asignación');
            } else {
                throw new Error('Error en la respuesta del servidor');
            }
        }

        const data = await response.json();
        console.log('Respuesta de eliminación:', data);

        const estilistaId = document.getElementById('estilista_id').value;
        await cargarHorarios(estilistaId);
        alert('Horario eliminado correctamente');
    } catch (error) {
        console.error('Error:', error);
        alert(error.message || 'Error al procesar la solicitud');
    }
}

// Función auxiliar para formatear fechas
function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}
</script>
@endsection
