@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-3xl font-semibold text-gray-900">Crear Horario</h1>
            <p class="mt-2 text-sm text-gray-700">Define los bloques de días e intervalos para un nuevo horario.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('horarios.index') }}"
               class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Volver a Horarios
            </a>
        </div>
    </div>

    {{-- Formulario --}}
    <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
        <form method="POST" action="{{ route('horarios.store') }}" class="space-y-6">
            @csrf

            {{-- Nombre --}}
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

            {{-- Contenedor de bloques de día --}}
            <div id="dias-container" class="space-y-6"></div>

            {{-- Botón de añadir bloque de día --}}
            <button
                type="button"
                id="add-day-block"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Añadir Bloque de Día
            </button>

            {{-- Submit --}}
            <div class="pt-6 border-t border-gray-200">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                >
                    Guardar Horario
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
    const fullDays = ["LUNES","MARTES","MIERCOLES","JUEVES","VIERNES","SABADO","DOMINGO"];

    function updateDaySelects() {
        let used = $('.dia-select').map((_,el)=>el.value).get();
        $('.dia-select').each(function(){
            const cur = this.value;
            $(this).empty();
            fullDays.forEach(day=>{
                if(day===cur||!used.includes(day)){
                    $(this).append(new Option(day, day, day===cur, day===cur));
                }
            });
        });
    }

    function refreshIndices(){
        $('#dias-container .dia-group').each(function(i){
            $(this).attr('data-index', i);
            $(this).find('.dia-select')
                .attr('name', `horario[${i}][dia]`)
                .attr('id', `horario_${i}_dia`);
            $(this).find('.intervalo').each(function(j){
                $(this).find('input[type="time"]').first()
                    .attr('name', `horario[${i}][intervalos][${j}][start]`);
                $(this).find('input[type="time"]').last()
                    .attr('name', `horario[${i}][intervalos][${j}][end]`);
            });
        });
        updateDaySelects();
    }

    $('#add-day-block').click(function(){
        const idx = $('#dias-container .dia-group').length;
        let tpl = $('#day-block-template').html().replace(/__DAY_INDEX__/g, idx);
        $('#dias-container').append(tpl);
        refreshIndices();
    });

    $(document).on('click', '.btn-remove-day', function(){
        $(this).closest('.dia-group').remove();
        refreshIndices();
    });

    $(document).on('click', '.btn-add-intervalo', function(){
        const grp = $(this).closest('.dia-group');
        const i = grp.data('index'), n = grp.find('.intervalo').length;
        let tpl = $('#intervalo-template').html()
            .replace(/__START__/g, `horario[${i}][intervalos][${n}][start]`)
            .replace(/__END__/g,   `horario[${i}][intervalos][${n}][end]`);
        grp.find('.intervalos-container').append(tpl);
    });

    $(document).on('click', '.btn-remove-intervalo', function(){
        $(this).closest('.intervalo').remove();
        refreshIndices();
    });

    $(document).on('change', '.dia-select', updateDaySelects);
});
</script>

{{-- Plantillas ocultas --}}
<div id="day-block-template" class="hidden">
  <div class="dia-group border p-4 rounded-lg space-y-4" data-index="__DAY_INDEX__">
    <div class="flex items-center justify-between">
      <label class="block text-sm font-medium text-gray-700">Día de la semana</label>
      <button type="button" class="btn-remove-day inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-sm font-medium text-red-700 hover:bg-red-100">
        Eliminar
      </button>
    </div>
    <select class="dia-select block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mb-4"></select>
    <div class="intervalos-container space-y-4">
      <h4 class="text-sm font-medium text-gray-700">Intervalos</h4>
    </div>
    <button type="button" class="btn-add-intervalo inline-flex items-center rounded-md bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-700 hover:bg-indigo-100">
      Añadir Intervalo
    </button>
    <hr class="border-gray-200 mt-4">
  </div>
</div>
<div id="intervalo-template" class="hidden">
  <div class="intervalo flex flex-wrap gap-4 items-end">
    <div class="flex-1 min-w-[120px]">
      <label class="block text-sm font-medium text-gray-700">Inicio</label>
      <input type="time" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
    </div>
    <div class="flex-1 min-w-[120px]">
      <label class="block text-sm font-medium text-gray-700">Fin</label>
      <input type="time" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
    </div>
    <button type="button" class="btn-remove-intervalo inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-sm font-medium text-red-700 hover:bg-red-100">
      Quitar
    </button>
  </div>
</div>
@endsection
