@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Estilistas</h1>

    <a href="{{ route('estilistas.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 lg:mb-3">Crear Estilista</a>
    <x-listado-dinamico 
        :items="$estilistas" 
        :columns="['id', 'nombre']" 
        editRoute="estilistas.edit" 
        deleteRoute="estilistas.destroy" 
    />
</div>
@endsection
