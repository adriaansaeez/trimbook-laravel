@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Usuarios</h1>

    <x-listado-dinamico 
        :items="$users" 
        :columns="['id', 'name', 'email']" 
        editRoute="users.edit" 
        deleteRoute="users.destroy" 
    />
</div>
@endsection
