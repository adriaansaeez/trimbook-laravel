@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Listado de Estilistas</h1>
        
        <!-- Botón para mostrar/ocultar el formulario de importación -->
        <button type="button" onclick="toggleImportForm()" 
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
            Importar Estilista
        </button>
    </div>

    <!-- Formulario de importación (oculto por defecto) -->
    <div id="importForm" class="hidden mb-6 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Importar Estilista</h2>
        <form action="{{ route('estilistas.importar') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Seleccionar Usuario
                    </label>
                    <select name="user_id" id="user_id" required
                            class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccione un usuario</option>
                        @foreach($usuariosEstilistas as $usuario)
                            <option value="{{ $usuario->id }}">{{ $usuario->name }} - {{ $usuario->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Estilista
                    </label>
                    <input type="text" name="nombre" id="nombre" required
                           class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Importar
                </button>
            </div>
        </form>
    </div>

    {{-- Mensajes de éxito y error --}}
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-md mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($estilistas as $estilista)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $estilista->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $estilista->nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $estilista->user->name ?? 'Sin usuario' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('estilistas.edit', $estilista) }}" class="text-blue-500 hover:underline">Editar</a>
                            <form action="{{ route('estilistas.destroy', $estilista->id) }}" method="POST" class="inline-block ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este estilista?')" class="text-red-500 hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleImportForm() {
        const form = document.getElementById('importForm');
        form.classList.toggle('hidden');
    }
</script>
@endsection