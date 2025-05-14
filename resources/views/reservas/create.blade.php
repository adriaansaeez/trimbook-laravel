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
            <input type="hidden" name="estilista_id" id="estilista_id">
            <div id="estilistas-grid" class="grid grid-cols-2 gap-4 mt-2">
              <!-- Los estilistas se cargarán aquí dinámicamente -->
            </div>
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
      <div class="mb-4 flex flex-col items-center gap-4">
        <div class="w-full flex flex-col">
          <label class="block text-sm font-medium text-left mb-1">Fecha</label>
          <!-- Ajustamos el ancho del input de fecha a la mitad (o un tamaño fijo) -->
          <input 
            type="date" 
            name="fecha" 
            id="fecha" 
            class="p-2 border rounded-md max-w-sm" 
            style="width: 50%;" 
            autocomplete="off"
          >
        </div>
        <div class="w-full" id="horarios-container" style="display: none;">
          <label class="block text-sm font-medium text-center mb-2">Horas Disponibles</label>
          <!-- Reducimos el gap a gap-2 para menos espacio -->
          <div id="horas-lista" class="grid grid-cols-3 gap-2 justify-items-center"></div>
        </div>
      </div>
      <div class="flex justify-between mt-4 w-full">
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
  // Función para formatear fecha a "YYYY-MM-DD"
  function formatDate(date) {
    const d = date.getDate();
    const m = date.getMonth() + 1;
    const y = date.getFullYear();
    return y + '-' + (m < 10 ? '0' + m : m) + '-' + (d < 10 ? '0' + d : d);
  }

  let currentStep = 1;
  let picker;
  let availableDates = []; // Fechas en las que el estilista trabaja

  function updateProgressBar(step) {
    document.querySelectorAll('#progress-bar .step').forEach(el => {
      el.classList.remove('active');
      if (parseInt(el.getAttribute('data-step')) === step) {
        el.classList.add('active');
      }
    });
  }

  function showStep(step) {
    document.querySelectorAll('.form-step').forEach(el => {
      el.style.display = 'none';
    });
    document.getElementById('step-' + step).style.display = 'block';
    updateProgressBar(step);
  }

  // Carga de horarios
  function loadHorarios() {
    const estilistaId = document.getElementById('estilista_id').value;
    const servicioId = document.getElementById('servicio').value;
    const fecha = document.getElementById('fecha').value;
    
    if (!estilistaId || !servicioId || !fecha) {
      console.error('Faltan datos para cargar horarios:', { estilistaId, servicioId, fecha });
      return;
    }

    const horariosContainer = document.getElementById('horarios-container');
    const horasLista = document.getElementById('horas-lista');
    horasLista.innerHTML = '<p class="text-center text-gray-500">Cargando horarios disponibles...</p>';
    horariosContainer.style.display = 'block';

    axios.get(`/api/v1/reservas/horarios/${estilistaId}/${fecha}/${servicioId}`)
      .then(response => {
        horasLista.innerHTML = '';
        
        if (!response.data || response.data.length === 0) {
          horasLista.innerHTML = '<p class="text-center text-red-500">No hay horarios disponibles para esta fecha.</p>';
          return;
        }
        
        // Separar horas en mañana y tarde
        const horasMañana = response.data.filter(hora => {
          const hour = parseInt(hora.split(':')[0]);
          return hour < 14;
        });
        
        const horasTarde = response.data.filter(hora => {
          const hour = parseInt(hora.split(':')[0]);
          return hour >= 14;
        });
        
        // Crear contenedor para los bloques
        horasLista.innerHTML = `
          <div class="col-span-3 w-full">
            <h3 class="text-lg font-semibold mb-2 text-center">Mañana</h3>
            <div class="grid grid-cols-3 gap-2 mb-6" id="horas-mañana">
              ${horasMañana.map(hora => `
                <label class="flex items-center justify-center p-2 border rounded-full cursor-pointer hover:bg-blue-100 transition-colors">
                  <input type="radio" name="hora" value="${hora}" class="hidden" />
                  <span class="text-sm font-medium">${hora}</span>
                </label>
              `).join('')}
            </div>
            
            <h3 class="text-lg font-semibold mb-2 text-center">Tarde</h3>
            <div class="grid grid-cols-3 gap-2" id="horas-tarde">
              ${horasTarde.map(hora => `
                <label class="flex items-center justify-center p-2 border rounded-full cursor-pointer hover:bg-blue-100 transition-colors">
                  <input type="radio" name="hora" value="${hora}" class="hidden" />
                  <span class="text-sm font-medium">${hora}</span>
                </label>
              `).join('')}
            </div>
          </div>
        `;

        // Evento para resaltar la hora seleccionada
        document.querySelectorAll('input[name="hora"]').forEach(input => {
          input.addEventListener('change', function() {
            document.querySelectorAll('#horas-lista label').forEach(label => label.classList.remove('selected'));
            this.parentElement.classList.add('selected');
          });
        });
      })
      .catch(error => {
        console.error('Error al obtener horarios:', error);
        horasLista.innerHTML = '<p class="text-center text-red-500">Error al cargar los horarios. Por favor, intente nuevamente.</p>';
      });
  }

  // Paso 1: Seleccionar Servicio y Estilista
  document.getElementById('servicio').addEventListener('change', function() {
    let servicioId = this.value;
    let opcionSeleccionada = this.options[this.selectedIndex];
    document.getElementById('detalle-nombre').innerText = opcionSeleccionada.getAttribute('data-nombre') || '';
    document.getElementById('detalle-descripcion').innerText = opcionSeleccionada.getAttribute('data-descripcion') || '';
    document.getElementById('detalle-precio').innerText = opcionSeleccionada.getAttribute('data-precio') ? 'Precio: $' + opcionSeleccionada.getAttribute('data-precio') : '';
    document.getElementById('detalle-duracion').innerText = opcionSeleccionada.getAttribute('data-duracion') ? 'Duración: ' + opcionSeleccionada.getAttribute('data-duracion') + ' minutos' : '';

    const estilistaGrid = document.getElementById('estilistas-grid');
    estilistaGrid.innerHTML = '<p class="col-span-2 text-center text-gray-500">Cargando estilistas...</p>';
    document.getElementById('fecha').disabled = true;
    document.getElementById('perfil-estilista').innerHTML = '<p class="text-sm">Seleccione un estilista para ver su perfil.</p>';
    document.getElementById('estilista_id').value = '';

    axios.get(`/api/v1/reservas/estilistas/${servicioId}`)
      .then(response => {
        estilistaGrid.innerHTML = '';
        response.data.forEach(estilista => {
          const card = document.createElement('div');
          card.className = 'estilista-card border rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-colors';
          card.setAttribute('data-estilista-id', estilista.id);
          card.innerHTML = `
            <div class="flex items-center gap-3">
              <img src="/storage/fotos/${estilista.foto_perfil || 'default.jpg'}" 
                   alt="Foto de ${estilista.nombre}" 
                   class="w-16 h-16 rounded-full object-cover">
              <div>
                <h4 class="font-medium">${estilista.nombre}</h4>
              </div>
            </div>
          `;
          
          card.addEventListener('click', function() {
            // Remover selección previa
            document.querySelectorAll('.estilista-card').forEach(c => {
              c.classList.remove('selected-estilista');
              c.classList.remove('border-blue-500');
              c.classList.add('border-gray-200');
            });
            
            // Aplicar selección
            this.classList.add('selected-estilista');
            this.classList.remove('border-gray-200');
            this.classList.add('border-blue-500');
            
            // Actualizar input hidden
            document.getElementById('estilista_id').value = this.getAttribute('data-estilista-id');
            
            // Cargar perfil del estilista
            const estilistaId = this.getAttribute('data-estilista-id');
            document.getElementById('fecha').disabled = false;
            cargarPerfilEstilista(estilistaId);
          });
          
          estilistaGrid.appendChild(card);
        });
      })
      .catch(error => {
        console.error('Error al obtener estilistas:', error);
        estilistaGrid.innerHTML = '<p class="col-span-2 text-center text-red-500">Error al cargar estilistas</p>';
      });
  });

  function cargarPerfilEstilista(estilistaId) {
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

    // Consultar los días disponibles del estilista
    axios.get(`/api/v1/horarios-estilista/dias-disponibles/${estilistaId}`)
      .then(response => {
        availableDates = response.data;
        if (picker) {
          picker.destroy();
        }
        picker = new Pikaday({
          field: document.getElementById('fecha'),
          format: 'YYYY-MM-DD',
          minDate: new Date(),
          isInvalidDate: function(date) {
            let dateStr = formatDate(date);
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
  }

  // Botones de navegación
  document.getElementById('next-1').addEventListener('click', function() {
    let servicio = document.getElementById('servicio').value;
    let estilista = document.getElementById('estilista_id').value;
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
    let estilistaText = document.getElementById('estilistas-grid').querySelector('.selected-estilista').querySelector('h4').innerText;
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
  /* Barra de progreso */
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
  /* Fecha con z-index */
  #fecha {
    position: relative;
    z-index: 1000;
  }
  /* Días deshabilitados en Pikaday */
  .pikaday .is-disabled {
    background-color: #f5f5f5 !important;
    color: #aaa !important;
    pointer-events: none;
  }
  /* Estilos para horas */
  #horas-lista label {
    padding: 0.4rem 0.8rem; /* Más pequeño que antes */
    border: 1px solid #d1d5db;
    border-radius: 9999px;
    cursor: pointer;
    transition: background-color 0.2s, color 0.2s;
    text-align: center;
    width: max-content;
  }
  #horas-lista label:hover {
    background-color: #e0f2fe;
  }
  #horas-lista label.selected {
    background-color: #3490dc;
    color: white;
    border-color: #3490dc;
  }
  /* Estilos para las tarjetas de estilistas */
  .estilista-card {
    background-color: white;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
  }
  
  .estilista-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  }
  
  .selected-estilista {
    background-color: #f0f9ff;
    border-color: #3490dc !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  }
</style>
@endsection
