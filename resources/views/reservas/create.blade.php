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

  <!-- Barra de Progreso -->
  <div id="progress-bar" class="flex justify-between mb-6">
    <div class="step active" data-step="1">1. Servicio y Estilista</div>
    <div class="step" data-step="2">2. Fecha y Hora</div>
    <div class="step" data-step="3">3. Confirmar Reserva</div>
  </div>

  <form id="reserva-form" action="{{ route('reservas.store') }}" method="POST">
    @csrf

    <!-- Paso 1: Seleccionar Servicio y Estilista -->
    <div class="form-step" id="step-1">
      <div class="flex gap-6">
        <!-- Columna de Inputs -->
        <div class="w-1/2">
          <div class="mb-4">
            <label class="block text-sm font-medium">Servicio</label>
            <select name="servicio_id" id="servicio" class="w-full p-2 border rounded-md">
              <option value="">Seleccione un servicio</option>
              @foreach ($servicios as $servicio)
              <option value="{{ $servicio->id }}"
                data-nombre="{{ $servicio->nombre }}"
                data-descripcion="{{ $servicio->descripcion }}"
                data-precio="{{ $servicio->precio }}"
                data-duracion="{{ $servicio->duracion }}">
                {{ $servicio->nombre }}
              </option>
              @endforeach
            </select>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium">Estilista</label>
            <select name="estilista_id" id="estilista" class="w-full p-2 border rounded-md" disabled>
              <option value="">Seleccione un estilista</option>
            </select>
          </div>
        </div>

        <!-- Columna de Detalles -->
        <div class="w-1/2 border p-4 rounded-md">
          <!-- Detalles del Estilista -->
          <div id="estilista-detalles" class="mb-4">
            <h3 class="text-lg font-bold mb-2">Perfil del Estilista</h3>
            <div id="perfil-estilista">
              <p class="text-sm">Seleccione un estilista para ver su perfil.</p>
            </div>
          </div>

          <!-- Detalles del Servicio -->
          <div id="servicio-detalles">
            <h3 class="text-lg font-bold mb-2">Detalles del Servicio</h3>
            <p id="detalle-nombre" class="font-semibold"></p>
            <p id="detalle-descripcion" class="text-sm"></p>
            <p id="detalle-precio" class="text-sm"></p>
            <p id="detalle-duracion" class="text-sm"></p>
          </div>
        </div>
      </div>
      <div class="flex justify-end mt-4">
        <button type="button" id="next-1" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Siguiente</button>
      </div>
    </div>

    <!-- Paso 2: Seleccionar Fecha y Hora -->
    <div class="form-step" id="step-2" style="display:none;">
      <div class="mb-4 flex gap-4">
        <div class="w-1/2">
          <label class="block text-sm font-medium">Fecha</label>
          <!-- Input para Pikaday: readonly y sin autocomplete para evitar que se escriba manualmente -->
          <input readonly type="text" name="fecha" id="fecha" class="w-full p-2 border rounded-md" disabled autocomplete="off">
        </div>

        <div class="w-1/2" id="horarios-container" style="display: none; position: relative; z-index: 1; margin-top: 20px;">
          <label class="block text-sm font-medium">Horas Disponibles</label>
          <div id="horas-lista" class="mt-1 space-y-1"></div>
        </div>
      </div>
      <div class="flex justify-between mt-4">
        <button type="button" id="prev-2" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Atrás</button>
        <button type="button" id="next-2" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Siguiente</button>
      </div>
    </div>

    <!-- Paso 3: Confirmación -->
    <div class="form-step" id="step-3" style="display:none;">
      <h2 class="text-2xl font-semibold mb-4">Confirma tu Reserva</h2>
      <div class="mb-4">
        <p><strong>Servicio:</strong> <span id="confirm-servicio"></span></p>
        <p><strong>Estilista:</strong> <span id="confirm-estilista"></span></p>
        <p><strong>Fecha:</strong> <span id="confirm-fecha"></span></p>
        <p><strong>Hora:</strong> <span id="confirm-hora"></span></p>
      </div>
      <div class="flex justify-between mt-4">
        <button type="button" id="prev-3" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Atrás</button>
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Confirmar Reserva</button>
      </div>
    </div>
  </form>
</div>

<!-- Incluir Pikaday desde CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.0/css/pikaday.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.0/pikaday.min.js"></script>

<script>
  // Función auxiliar para formatear una fecha a "YYYY-MM-DD"
  function formatDate(date) {
    const d = date.getDate();
    const m = date.getMonth() + 1; // Los meses comienzan en 0
    const y = date.getFullYear();
    return y + '-' + (m < 10 ? '0' + m : m) + '-' + (d < 10 ? '0' + d : d);
  }

  // Variables globales
  let currentStep = 1;
  let picker; // Instancia de Pikaday
  let availableDates = []; // Array con las fechas en las que el estilista trabaja (en formato "YYYY-MM-DD")

  function updateProgressBar(step) {
    document.querySelectorAll('#progress-bar .step').forEach(function(el) {
      el.classList.remove('active');
      if (parseInt(el.getAttribute('data-step')) === step) {
        el.classList.add('active');
      }
    });
  }

  function showStep(step) {
    document.querySelectorAll('.form-step').forEach(function(el) {
      el.style.display = 'none';
    });
    document.getElementById('step-' + step).style.display = 'block';
    updateProgressBar(step);
  }

  // Función para cargar los horarios disponibles (se llama cuando se selecciona una fecha válida)
  function loadHorarios() {
    const estilistaId = document.getElementById('estilista').value;
    const servicioId = document.getElementById('servicio').value;
    const fecha = document.getElementById('fecha').value;
    if (!fecha) return;
    
    axios.get(`/api/v1/reservas/horarios/${estilistaId}/${fecha}/${servicioId}`)
      .then(response => {
        const horariosContainer = document.getElementById('horarios-container');
        const horasLista = document.getElementById('horas-lista');
        horasLista.innerHTML = '';
        response.data.forEach(hora => {
          horasLista.innerHTML += `<label class="block">
            <input type="radio" name="hora" value="${hora}"> ${hora}
          </label>`;
        });
        horariosContainer.style.display = 'block';
      })
      .catch(error => {
        console.error('Error al obtener horarios:', error);
      });
  }

  // Paso 1: Seleccionar Servicio y Estilista
  document.getElementById('servicio').addEventListener('change', function() {
    let servicioId = this.value;
    let opcionSeleccionada = this.options[this.selectedIndex];
    
    // Actualizar detalles del servicio
    document.getElementById('detalle-nombre').innerText = opcionSeleccionada.getAttribute('data-nombre') || '';
    document.getElementById('detalle-descripcion').innerText = opcionSeleccionada.getAttribute('data-descripcion') || '';
    document.getElementById('detalle-precio').innerText = opcionSeleccionada.getAttribute('data-precio') ? 'Precio: $' + opcionSeleccionada.getAttribute('data-precio') : '';
    document.getElementById('detalle-duracion').innerText = opcionSeleccionada.getAttribute('data-duracion') ? 'Duración: ' + opcionSeleccionada.getAttribute('data-duracion') + ' minutos' : '';

    // Reiniciar select de estilistas y deshabilitar el input de fecha
    let estilistaSelect = document.getElementById('estilista');
    estilistaSelect.innerHTML = '<option value="">Cargando estilistas...</option>';
    estilistaSelect.disabled = true;
    document.getElementById('fecha').disabled = true;

    // Limpiar el área del perfil del estilista
    document.getElementById('perfil-estilista').innerHTML = '<p class="text-sm">Seleccione un estilista para ver su perfil.</p>';

    // Obtener estilistas para el servicio seleccionado
    axios.get(`/api/v1/reservas/estilistas/${servicioId}`)
      .then(response => {
        estilistaSelect.innerHTML = '<option value="">Seleccione un estilista</option>';
        response.data.forEach(estilista => {
          estilistaSelect.innerHTML += `<option value="${estilista.id}">${estilista.nombre}</option>`;
        });
        estilistaSelect.disabled = false;
      })
      .catch(error => {
        console.error('Error al obtener estilistas:', error);
        estilistaSelect.innerHTML = '<option value="">Error al cargar estilistas</option>';
      });
  });

  // Al seleccionar un estilista: cargar perfil y configurar Pikaday
  document.getElementById('estilista').addEventListener('change', function() {
    const estilistaId = this.value;
    document.getElementById('fecha').disabled = false;

    // Cargar perfil del estilista
    axios.get(`/api/v1/perfiles/${estilistaId}`)
      .then(response => {
        const perfil = response.data.data;
        let perfilHTML = `
          <img src="/storage/fotos/${perfil.foto_perfil}" alt="Foto de perfil" class="w-24 h-24 object-cover rounded-full mt-2">
          <p><strong>Nombre:</strong> ${perfil.nombre || 'N/D'} ${perfil.apellidos || ''}</p>
          <p><strong>Instagram:</strong> ${perfil.instagram_url !== 'No especificado' ? `<a href="${perfil.instagram_url}" target="_blank">${perfil.instagram_url}</a>` : 'N/D'}</p>
        `;
        document.getElementById('perfil-estilista').innerHTML = perfilHTML;
      })
      .catch(error => {
        console.error('Error al cargar el perfil del estilista:', error);
        document.getElementById('perfil-estilista').innerHTML = '<p class="text-sm">No se pudo cargar el perfil.</p>';
      });

    // Consultar los días disponibles del estilista mediante el nuevo endpoint
    axios.get(`/api/v1/horarios-estilista/dias-disponibles/${estilistaId}`)
      .then(response => {
        // Se espera un array de fechas disponibles, ej: ["2025-03-31", "2025-04-01", ...]
        availableDates = response.data;
        
        // Si existe una instancia anterior de Pikaday, se destruye para reinicializar
        if (picker) {
          picker.destroy();
        }

        // Inicializar Pikaday: habilitar sólo las fechas disponibles
        picker = new Pikaday({
          field: document.getElementById('fecha'),
          format: 'YYYY-MM-DD',
          minDate: new Date(),
          isInvalidDate: function(date) {
            let dateStr = formatDate(date);
            // La fecha será inválida si NO está en availableDates
            return !availableDates.includes(dateStr);
          },
          onSelect: function(date) {
            document.getElementById('fecha').value = formatDate(date);
            loadHorarios();
          }
        });
      })
      .catch(error => {
        console.error('Error al obtener días de trabajo:', error);
      });
  });

  // Botones de navegación entre pasos
  document.getElementById('next-1').addEventListener('click', function() {
    let servicio = document.getElementById('servicio').value;
    let estilista = document.getElementById('estilista').value;
    if (!servicio || !estilista) {
      alert('Debes seleccionar un servicio y un estilista.');
      return;
    }
    currentStep = 2;
    showStep(currentStep);
  });

  document.getElementById('prev-2').addEventListener('click', function() {
    currentStep = 1;
    showStep(currentStep);
  });

  document.getElementById('next-2').addEventListener('click', function() {
    let fecha = document.getElementById('fecha').value;
    let hora = document.querySelector('input[name="hora"]:checked');
    if (!fecha || !hora) {
      alert('Debes seleccionar una fecha y una hora disponible.');
      return;
    }
    let servicioText = document.getElementById('servicio').selectedOptions[0].text;
    let estilistaText = document.getElementById('estilista').selectedOptions[0].text;
    document.getElementById('confirm-servicio').innerText = servicioText;
    document.getElementById('confirm-estilista').innerText = estilistaText;
    document.getElementById('confirm-fecha').innerText = fecha;
    document.getElementById('confirm-hora').innerText = hora.value;
    currentStep = 3;
    showStep(currentStep);
  });

  document.getElementById('prev-3').addEventListener('click', function() {
    currentStep = 2;
    showStep(currentStep);
  });
</script>

<style>
  /* Estilos para la barra de progreso */
  #progress-bar {
    font-weight: bold;
  }
  #progress-bar .step {
    width: 32%;
    text-align: center;
    padding: 8px;
    background: #e0e0e0;
    border-radius: 4px;
  }
  #progress-bar .active {
    background: #3490dc;
    color: white;
  }
  /* Asegurar que el input de fecha se muestre por encima si es necesario */
  #fecha {
    position: relative;
    z-index: 1000;
  }
  /* Estilos para los días deshabilitados en Pikaday */
  .pikaday .is-disabled {
    background-color: #f5f5f5 !important;
    color: #aaa !important;
    pointer-events: none;
  }
</style>
@endsection
