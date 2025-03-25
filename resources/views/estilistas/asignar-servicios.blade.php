@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-4 bg-white shadow rounded">
    <h2 class="text-xl font-semibold mb-4">Asignar servicios a estilista</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('estilistas.asignar.servicios') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="estilista_id" class="block font-medium">Selecciona un estilista:</label>
            <select name="estilista_id" id="estilista_id" class="w-full border rounded p-2">
                @foreach($estilistas as $estilista)
                    <option value="{{ $estilista->id }}">{{ $estilista->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-2">Servicios:</label>
            @foreach($servicios as $servicio)
                <label class="flex items-center space-x-2 mb-1">
                    <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}">
                    <span>{{ $servicio->nombre }}</span>
                </label>
            @endforeach
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Asignar Servicios</button>
    </form>
</div>
@endsection
