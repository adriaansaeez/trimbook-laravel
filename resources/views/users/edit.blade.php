@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Editar Usuario</h1>

    <form action="{{ route('users.update', $user) }}" method="POST" class="bg-white p-6 rounded shadow-md w-full max-w-lg">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Nombre</label>
            <input type="text" id="name" name="name" value="{{ $user->name }}" class="w-full px-4 py-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}" class="w-full px-4 py-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label for="role" class="block text-gray-700">Rol</label>
            <select id="role" name="role" class="w-full px-4 py-2 border rounded" required>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex space-x-2">
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Actualizar</button>
            <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancelar</a>
        </div>
    </form>
</div>
@endsection
