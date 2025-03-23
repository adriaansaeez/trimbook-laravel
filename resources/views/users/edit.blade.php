@extends('layouts.app')

@section('content')
<div class=" mx-auto p-6 w-auto">
    <h1 class="text-2xl font-bold mb-4">Editar Usuario y Perfil</h1>

    {{-- Div para mostrar errores --}}
    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-md mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Columna Izquierda (Datos del Usuario) -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold mb-2">Datos del Usuario</h2>

                <div>
                    <label class="block text-gray-700">Nombre Usuario</label>
                    <input type="text" name="name" value="{{ old('username', $user->username) }}" class="w-full px-4 py-2 border rounded" required>
                </div>

                <div>
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border rounded" required>
                </div>

                <div>
                    <label class="block text-gray-700">Rol</label>
                    <select name="role" class="w-full px-4 py-2 border rounded" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Columna Derecha (Datos del Perfil) -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold mb-2">Datos del Perfil</h2>

                <div>
                    <label class="block text-gray-700">Nombre del perfil</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $perfil->nombre) }}" class="w-full px-4 py-2 border rounded">
                </div>

                <div>
                    <label class="block text-gray-700">Apellidos</label>
                    <input type="text" name="apellidos" value="{{ old('apellidos', $perfil->apellidos) }}" class="w-full px-4 py-2 border rounded">
                </div>

                <div>
                    <label class="block text-gray-700">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $perfil->telefono) }}" class="w-full px-4 py-2 border rounded">
                </div>

                <div>
                    <label class="block text-gray-700">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $perfil->direccion) }}" class="w-full px-4 py-2 border rounded">
                </div>

                <div>
                    <label class="block text-gray-700">Instagram URL</label>
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $perfil->instagram_url) }}" class="w-full px-4 py-2 border rounded">
                </div>
            </div>
        </div>

        <div class="flex space-x-2 mt-6">
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Actualizar</button>
            <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancelar</a>
        </div>
    </form>
</div>

@endsection

