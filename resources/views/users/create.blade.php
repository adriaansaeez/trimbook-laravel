@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Crear Usuario</h1>

    <form action="{{ route('users.store') }}" method="POST" class="bg-white p-6 shadow-md rounded-lg">
        @csrf

        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" id="username" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input type="password" name="password" id="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
            <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
            <select name="role" id="role" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                <option value="admin">Admin</option>
                <option value="estilista">Estilista</option>
                <option value="cliente">Cliente</option>
            </select>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500 mr-2">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Guardar</button>
        </div>
    </form>
</div>
@endsection
