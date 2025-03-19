<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="w-full table-auto border-collapse border border-gray-300">
        <thead class="bg-gray-200">
            <tr>
                @foreach($columns as $column)
                    <th class="px-4 py-2 border border-gray-300">{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                @endforeach
                @if($editRoute || $deleteRoute)
                    <th class="px-4 py-2 border border-gray-300">Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr class="border-b">
                    @foreach($columns as $column)
                        <td class="px-4 py-2 border border-gray-300">{{ $item->$column }}</td>
                    @endforeach
                    @if($editRoute || $deleteRoute)
                        <td class="px-4 py-2 border border-gray-300 flex gap-2">
                            @if($editRoute)
                                <a href="{{ route($editRoute, $item->id) }}" class="text-blue-500 hover:underline">Editar</a>
                            @endif
                            @if($deleteRoute)
                                <form action="{{ route($deleteRoute, $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline ml-2">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
