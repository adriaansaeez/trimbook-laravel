<aside x-data="{ expanded: true, showDropdown: false }"
       class="bg-white shadow-md h-screen px-4 py-6 transition-all duration-300"
       :class="expanded ? 'w-64' : 'w-16'">

    <!-- Botón para expandir/contraer -->
    <button @click="expanded = !expanded" class="absolute top-4 left-4 text-gray-600">
        <svg class="w-6 h-6 transition-transform transform" :class="expanded ? '' : 'rotate-180'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>

    <h2 class="text-lg font-semibold text-gray-700 text-center mb-4" x-show="expanded">Panel de Control</h2>

    <nav class="mt-6">
        <ul>
            <!-- CRUDs Dropdown -->
            <li class="relative">
                <button @click="showDropdown = !showDropdown" 
                        class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md focus:outline-none">
                    <span x-show="expanded">CRUDs</span>
                    <svg class="w-5 h-5 transition-transform transform ml-auto" :class="showDropdown ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <ul x-show="showDropdown" x-collapse class="bg-white shadow-lg mt-1 z-10">
                    <li><a href="{{ route('users.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Usuarios</a></li>
                    <li><a href="{{ route('estilistas.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Estilistas</a></li>
                    <li><a href="{{ route('servicios.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Servicios</a></li>
                    <li><a href="{{ route('horarios.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Horarios</a></li>
                    <li><a href="{{ route('reservas.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Reservas</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</aside>
