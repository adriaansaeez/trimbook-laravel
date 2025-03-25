<aside x-data="{ expanded: true, openDropdown: '' }"
       class="bg-white shadow-md h-screen px-4 py-6 transition-all duration-300"
       :class="expanded ? 'w-64' : 'w-16'">

    <!-- Botón para expandir/contraer -->
    <button @click="expanded = !expanded" class="absolute top-4 left-4 text-gray-600">
        <svg class="w-6 h-6 transition-transform transform" :class="expanded ? '' : 'rotate-180'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    <h2 class="text-lg font-semibold text-gray-700 text-center mb-4" x-show="expanded">Panel de Control</h2>

    <nav class="mt-6">
        <ul>

            <!-- CRUDs Dropdown -->
            <li class="relative">
                <button @click="openDropdown === 'cruds' ? openDropdown = '' : openDropdown = 'cruds'" 
                        class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <span x-show="expanded">CRUDs</span>
                    <svg class="w-5 h-5 ml-auto transition-transform transform"
                         :class="openDropdown === 'cruds' ? 'rotate-180' : ''"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul x-show="openDropdown === 'cruds'" x-collapse class="bg-white shadow-lg mt-1 z-10">
                    <li>
                        <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1117.804 5.121M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Usuarios
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('estilistas.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.34 6.584L12 20l-6.5-2.838a12.083 12.083 0 01.34-6.584L12 14z" />
                            </svg>
                            Estilistas
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('servicios.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                            </svg>
                            Servicios
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('horarios.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Horarios
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reservas.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10M5 11h14M5 19h14M5 15h14" />
                            </svg>
                            Reservas
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Asignaciones Dropdown -->
            <li class="relative mt-2">
                <button @click="openDropdown === 'asignaciones' ? openDropdown = '' : openDropdown = 'asignaciones'"
                        class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="expanded">Asignaciones</span>
                    <svg class="w-5 h-5 ml-auto transition-transform transform"
                         :class="openDropdown === 'asignaciones' ? 'rotate-180' : ''"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul x-show="openDropdown === 'asignaciones'" x-collapse class="bg-white shadow-lg mt-1 z-10">
                    <li>
                        <a href="{{ route('asignar_horario.index') }}"
                           class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Asignar Horario Estilista
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('estilistas.asignar.form') }}"
                           class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-12 0v3c0 .379-.214.725-.553.895L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            Asignar Servicios Estilista
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </nav>
</aside>
