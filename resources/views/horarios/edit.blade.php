@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-2xl font-semibold mb-4">Editar Horario</h1>
  <form method="POST" action="{{ route('horarios.update', $horario->id) }}" class="bg-white shadow rounded p-6">
    @csrf
    @method('PUT')

    <!-- Campo para el nombre del Horario -->
    <div class="mb-6">
      <label for="nombre" class="block text-gray-700 font-medium mb-2">Nombre</label>
      <input type="text" name="nombre" id="nombre" value="{{ $horario->nombre }}" class="w-full border-gray-300 rounded p-2" required>
    </div>

    <!-- Contenedor de bloques de días -->
    <div id="dias-container">
      @foreach($horario->horario as $diaIndex => $dia)
      <div class="dia-group mb-6 border p-4 rounded" data-index="{{ $diaIndex }}">
        <div class="flex items-center justify-between mb-4">
          <label class="block text-gray-700 font-medium">Día de la semana</label>
          <button type="button" class="btn-remove-day bg-red-500 hover:bg-red-600 text-white py-1 px-2 rounded">
            Eliminar Bloque
          </button>
        </div>
        <select name="horario[{{ $diaIndex }}][dia]" class="dia-select w-full border-gray-300 rounded mb-4">
          <!-- Se cargarán las opciones en el script -->
          <option value="{{ $dia['dia'] }}" selected>{{ $dia['dia'] }}</option>
        </select>
        <!-- Contenedor para los intervalos -->
        <div class="intervalos-container mb-4">
          <h4 class="text-lg font-medium mb-2">Intervalos</h4>
          @if(isset($dia['intervalos']) && count($dia['intervalos']))
            @foreach($dia['intervalos'] as $intervaloIndex => $intervalo)
              <div class="intervalo mb-4">
                <div class="flex flex-wrap -mx-2">
                  <div class="w-full md:w-5/12 px-2 mb-4 md:mb-0">
                    <label class="block text-gray-700 font-medium mb-2">Inicio</label>
                    <input type="time" name="horario[{{ $diaIndex }}][intervalos][{{ $intervaloIndex }}][start]" class="w-full border-gray-300 rounded" value="{{ $intervalo['start'] }}" required>
                  </div>
                  <div class="w-full md:w-5/12 px-2 mb-4 md:mb-0">
                    <label class="block text-gray-700 font-medium mb-2">Fin</label>
                    <input type="time" name="horario[{{ $diaIndex }}][intervalos][{{ $intervaloIndex }}][end]" class="w-full border-gray-300 rounded" value="{{ $intervalo['end'] }}" required>
                  </div>
                  <div class="w-full md:w-2/12 px-2 flex items-end">
                    <button type="button" class="btn-remove-intervalo bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
                      Eliminar
                    </button>
                  </div>
                </div>
              </div>
            @endforeach
          @endif
        </div>
        <button type="button" class="btn-add-intervalo bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded mb-4">
          Añadir Intervalo
        </button>
        <hr class="border-gray-200">
      </div>
      @endforeach
    </div>

    <!-- Botón para agregar un nuevo bloque de día -->
    <button type="button" id="add-day-block" class="mb-4 bg-indigo-500 hover:bg-indigo-600 text-white py-2 px-4 rounded">
      Añadir Bloque de Día
    </button>

    <div>
      <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">
        Actualizar Horario
      </button>
    </div>
  </form>
</div>

<!-- Plantilla oculta para un bloque de día -->
<div id="day-block-template" class="hidden">
  <div class="dia-group mb-6 border p-4 rounded" data-index="__DAY_INDEX__">
    <div class="flex items-center justify-between mb-4">
      <label class="block text-gray-700 font-medium">Día de la semana</label>
      <button type="button" class="btn-remove-day bg-red-500 hover:bg-red-600 text-white py-1 px-2 rounded">
        Eliminar Bloque
      </button>
    </div>
    <select name="horario[__DAY_INDEX__][dia]" class="dia-select w-full border-gray-300 rounded mb-4">
      <!-- Opciones se cargarán dinámicamente -->
    </select>
    <div class="intervalos-container mb-4">
      <h4 class="text-lg font-medium mb-2">Intervalos</h4>
    </div>
    <button type="button" class="btn-add-intervalo bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded mb-4">
      Añadir Intervalo
    </button>
    <hr class="border-gray-200">
  </div>
</div>

<!-- Plantilla oculta para un intervalo -->
<div id="intervalo-template" class="hidden">
  <div class="intervalo mb-4">
    <div class="flex flex-wrap -mx-2">
      <div class="w-full md:w-5/12 px-2 mb-4 md:mb-0">
        <label class="block text-gray-700 font-medium mb-2">Inicio</label>
        <input type="time" name="__START__" class="w-full border-gray-300 rounded" required>
      </div>
      <div class="w-full md:w-5/12 px-2 mb-4 md:mb-0">
        <label class="block text-gray-700 font-medium mb-2">Fin</label>
        <input type="time" name="__END__" class="w-full border-gray-300 rounded" required>
      </div>
      <div class="w-full md:w-2/12 px-2 flex items-end">
        <button type="button" class="btn-remove-intervalo bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
          Eliminar
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
  const fullDays = ["LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO", "DOMINGO"];

  function updateDaySelects() {
    let selectedDays = [];
    $('.dia-select').each(function() {
      const val = $(this).val();
      if(val) { selectedDays.push(val); }
    });
    $('.dia-select').each(function() {
      const currentVal = $(this).val();
      $(this).empty();
      $.each(fullDays, function(index, day) {
        if(day === currentVal || selectedDays.indexOf(day) === -1) {
          const option = $('<option>', { value: day, text: day });
          if(day === currentVal) option.prop('selected', true);
          $(this).append(option);
        }
      }.bind(this));
    });
  }

  function actualizarIndices() {
    $('#dias-container .dia-group').each(function(i) {
      $(this).attr('data-index', i);
      $(this).find('.dia-select').attr('name', 'horario[' + i + '][dia]');
      $(this).find('.intervalos-container .intervalo').each(function(j) {
        $(this).find('input[type="time"]').each(function(k) {
          if(k === 0) {
            $(this).attr('name', 'horario[' + i + '][intervalos][' + j + '][start]');
          } else {
            $(this).attr('name', 'horario[' + i + '][intervalos][' + j + '][end]');
          }
        });
      });
    });
    updateDaySelects();
  }

  $('#add-day-block').click(function(){
    var dayIndex = $('#dias-container .dia-group').length;
    var template = $('#day-block-template').html();
    template = template.replace(/__DAY_INDEX__/g, dayIndex);
    $('#dias-container').append(template);
    updateDaySelects();
  });

  $(document).on('click', '.btn-remove-day', function(){
    $(this).closest('.dia-group').remove();
    actualizarIndices();
  });

  $(document).on('click', '.btn-add-intervalo', function(){
    var diaGroup = $(this).closest('.dia-group');
    var dayIndex = diaGroup.data('index');
    var intervalosCount = diaGroup.find('.intervalo').length;
    var startName = "horario[" + dayIndex + "][intervalos][" + intervalosCount + "][start]";
    var endName = "horario[" + dayIndex + "][intervalos][" + intervalosCount + "][end]";
    var template = $('#intervalo-template').html();
    template = template.replace('__START__', startName).replace('__END__', endName);
    diaGroup.find('.intervalos-container').append(template);
  });

  $(document).on('click', '.btn-remove-intervalo', function(){
    $(this).closest('.intervalo').remove();
    actualizarIndices();
  });

  $(document).on('change', '.dia-select', function(){
    updateDaySelects();
  });

  // Inicializar selects en la edición
  updateDaySelects();
});
</script>
@endsection
