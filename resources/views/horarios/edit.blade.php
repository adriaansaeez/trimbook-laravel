@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-3xl font-semibold text-gray-900">Editar Horario</h1>
            <p class="mt-2 text-sm text-gray-700">Modifica los intervalos y días de este horario.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('horarios.index') }}"
               class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Volver a Horarios
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
                          d="M8.257 3.099c.366-.772 1.41-.772 1.777 0l6.518 13.748A1 1 0 
                             0115.777 18H4.223a1 1 0 01-.775-1.653L8.257 3.1zM11 14a1 1 0 
                             10-2 0 1 1 0 002 0zm-1-9a1 1 0 00-.894.553l-2 4a1 1 0 
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

    {{-- Formulario de edición --}}
    <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
        <form method="POST" action="{{ route('horarios.update', $horario->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Nombre --}}
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input
                    type="text"
                    name="nombre"
                    id="nombre"
                    value="{{ old('nombre', $horario->nombre) }}"
                    required
                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                />
            </div>

            {{-- Bloques de día --}}
            <div id="dias-container" class="space-y-6">
                @foreach($horario->horario as $diaIndex => $dia)
                <div class="dia-group border p-4 rounded-lg" data-index="{{ $diaIndex }}">
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-medium text-gray-700">Día de la semana</label>
                        <button type="button" class="btn-remove-day inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-sm font-medium text-red-700 hover:bg-red-100">
                            Eliminar
                        </button>
                    </div>
                    <select name="horario[{{ $diaIndex }}][dia]" class="dia-select block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mb-4">
                        <option value="{{ $dia['dia'] }}" selected>{{ $dia['dia'] }}</option>
                    </select>

                    <div class="intervalos-container space-y-4 mb-4">
                        <h4 class="text-sm font-medium text-gray-700">Intervalos</h4>
                        @foreach($dia['intervalos'] as $intervaloIndex => $intervalo)
                        <div class="intervalo flex flex-wrap gap-4 items-end">
                            <div class="flex-1 min-w-[120px]">
                                <label class="block text-sm font-medium text-gray-700">Inicio</label>
                                <input
                                    type="time"
                                    name="horario[{{ $diaIndex }}][intervalos][{{ $intervaloIndex }}][start]"
                                    value="{{ $intervalo['start'] }}"
                                    required
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                            </div>
                            <div class="flex-1 min-w-[120px]">
                                <label class="block text-sm font-medium text-gray-700">Fin</label>
                                <input
                                    type="time"
                                    name="horario[{{ $diaIndex }}][intervalos][{{ $intervaloIndex }}][end]"
                                    value="{{ $intervalo['end'] }}"
                                    required
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                            </div>
                            <button type="button" class="btn-remove-intervalo inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-sm font-medium text-red-700 hover:bg-red-100">
                                Quitar
                            </button>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn-add-intervalo inline-flex items-center rounded-md bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-700 hover:bg-indigo-100">
                        Añadir Intervalo
                    </button>
                </div>
                @endforeach
            </div>

            {{-- Añadir nuevo día --}}
            <button
                type="button"
                id="add-day-block"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Añadir Bloque de Día
            </button>

            {{-- Enviar --}}
            <div class="pt-6 border-t border-gray-200">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                >
                    Actualizar Horario
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
            $(this).find('.dia-select').attr('name', `horario[${i}][dia]`);
            $(this).find('.intervalo').each(function(j){
                $(this).find('input').first().attr('name', `horario[${i}][intervalos][${j}][start]`);
                $(this).find('input').last().attr('name',  `horario[${i}][intervalos][${j}][end]`);
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
        refreshIndices();
    });

    $(document).on('click', '.btn-remove-intervalo', function(){
        $(this).closest('.intervalo').remove();
        refreshIndices();
    });

    $(document).on('change', '.dia-select', updateDaySelects);

    // Init
    refreshIndices();
});
</script>

{{-- Plantillas ocultas --}}
<div id="day-block-template" class="hidden">
  <div class="dia-group border p-4 rounded-lg" data-index="__DAY_INDEX__">
    <div class="flex items-center justify-between mb-4">
      <label class="block text-sm font-medium text-gray-700">Día de la semana</label>
      <button type="button" class="btn-remove-day inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-sm font-medium text-red-700 hover:bg-red-100">
        Eliminar
      </button>
    </div>
    <select class="dia-select block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mb-4"></select>
    <div class="intervalos-container space-y-4 mb-4">
      <h4 class="text-sm font-medium text-gray-700">Intervalos</h4>
    </div>
    <button type="button" class="btn-add-intervalo inline-flex items-center rounded-md bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-700 hover:bg-indigo-100">
      Añadir Intervalo
    </button>
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
