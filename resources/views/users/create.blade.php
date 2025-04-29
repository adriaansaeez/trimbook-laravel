@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Crear Usuario</h1>
        <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            Volver al listado
        </a>
    </div>

    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Se encontraron los siguientes errores:</h3>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST" class="bg-white p-6 shadow-md rounded-lg">
        @csrf

        <div class="grid grid-cols-1 gap-6">
            <div class="col-span-1">
                <label for="username" class="block text-sm font-medium text-gray-700">Nombre de usuario</label>
                <input type="text" 
                       name="username" 
                       id="username" 
                       value="{{ old('username') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('username') border-red-500 @enderror"
                       required
                       minlength="3"
                       maxlength="255">
                @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-1">
                <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('email') border-red-500 @enderror"
                       required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-1">
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('password') border-red-500 @enderror"
                       required
                       minlength="8">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">La contraseña debe tener al menos 8 caracteres.</p>
            </div>

            <div class="col-span-1">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                <input type="password" 
                       name="password_confirmation" 
                       id="password_confirmation" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                       required
                       minlength="8">
            </div>

            <div class="col-span-1">
                <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                <select name="role" 
                        id="role" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('role') border-red-500 @enderror"
                        required>
                    <option value="">Selecciona un rol</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('users.index') }}" 
               class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Cancelar
            </a>
            <button type="submit" 
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Crear usuario
            </button>
        </div>
    </form>
</div>
@endsection
