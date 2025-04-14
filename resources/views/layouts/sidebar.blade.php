<aside x-data="{ openDropdown: '', expanded: true }"
       class="bg-white transition-all duration-300 overflow-y-auto flex-none relative"
       :class="expanded ? 'w-64' : 'w-16'">

    <!-- Botón para expandir/contraer el sidebar -->
    <button @click="expanded = !expanded" 
            class="text-gray-600 p-2 absolute top-4 left-4 z-50" 
            title="Expandir/Contraer">
        <svg class="w-6 h-6 transition-transform"
             :class="expanded ? '' : 'rotate-180'"
             xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5l7 7-7 7" />
        </svg>
    </button>

    <!-- Título del panel -->
    <h2 class="text-lg font-semibold text-gray-700 text-center mb-4 mt-4" x-show="expanded" x-transition>
        Panel de Control
    </h2>

    <!-- Navegación -->
    <nav :class="expanded ? 'mt-10' : 'mt-20'">
        <ul>

            <!-- Gestión de Personas -->
            <li class="relative">
                <button @click="openDropdown === 'personas' ? openDropdown = '' : openDropdown = 'personas'"
                        class="w-full flex items-center py-2 text-gray-700 hover:bg-gray-200 rounded-md"
                        :class="expanded ? 'justify-start px-4' : 'justify-center'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m3-4a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 00-3-3.87" />
                    </svg>
                    <span x-show="expanded" x-transition class="ml-2">Gestión de Personas</span>
                    <svg x-show="expanded" class="w-5 h-5 ml-auto transition-transform transform"
                         :class="openDropdown === 'personas' ? 'rotate-180' : ''"
                         xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul x-show="openDropdown === 'personas'" x-collapse class="bg-white mt-1 z-10">
                    <li>
                        <a href="{{ route('users.index') }}" class="flex items-center py-2 text-gray-700 hover:bg-gray-100"
                           :class="expanded ? 'px-4 justify-start' : 'justify-center'">
                            <span class="ml-2" x-show="expanded" x-transition>Usuarios</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('estilistas.index') }}" class="flex items-center py-2 text-gray-700 hover:bg-gray-100"
                           :class="expanded ? 'px-4 justify-start' : 'justify-center'">
                            <span class="ml-2" x-show="expanded" x-transition>Estilistas</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Servicios y Horarios -->
            <li class="relative mt-2">
                <button @click="openDropdown === 'servicios' ? openDropdown = '' : openDropdown = 'servicios'"
                        class="w-full flex items-center py-2 text-gray-700 hover:bg-gray-200 rounded-md"
                        :class="expanded ? 'justify-start px-4' : 'justify-center'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L15 12.75 9.75 8.5M4.5 12a7.5 7.5 0 1115 0 7.5 7.5 0 01-15 0z" />
                    </svg>
                    <span x-show="expanded" x-transition class="ml-2">Servicios y Horarios</span>
                    <svg x-show="expanded" class="w-5 h-5 ml-auto transition-transform transform"
                         :class="openDropdown === 'servicios' ? 'rotate-180' : ''"
                         xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul x-show="openDropdown === 'servicios'" x-collapse class="bg-white mt-1 z-10">
                    <li>
                        <a href="{{ route('servicios.index') }}" class="flex items-center py-2 text-gray-700 hover:bg-gray-100"
                           :class="expanded ? 'px-4 justify-start' : 'justify-center'">
                            <span class="ml-2" x-show="expanded" x-transition>Servicios</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('horarios.index') }}" class="flex items-center py-2 text-gray-700 hover:bg-gray-100"
                           :class="expanded ? 'px-4 justify-start' : 'justify-center'">
                            <span class="ml-2" x-show="expanded" x-transition>Horarios</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Asignaciones -->
            <li class="relative mt-2">
                <button @click="openDropdown === 'asignaciones' ? openDropdown = '' : openDropdown = 'asignaciones'"
                        class="w-full flex items-center py-2 text-gray-700 hover:bg-gray-200 rounded-md"
                        :class="expanded ? 'justify-start px-4' : 'justify-center'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="expanded" x-transition class="ml-2">Asignaciones</span>
                    <svg x-show="expanded" class="w-5 h-5 ml-auto transition-transform transform"
                         :class="openDropdown === 'asignaciones' ? 'rotate-180' : ''"
                         xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul x-show="openDropdown === 'asignaciones'" x-collapse class="bg-white mt-1 z-10">
                    <li>
                        <a href="{{ route('asignar_horario.index') }}" class="flex items-center py-2 text-gray-700 hover:bg-gray-100"
                           :class="expanded ? 'px-4 justify-start' : 'justify-center'">
                            <span class="ml-2" x-show="expanded" x-transition>Asignar Horarios</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('estilistas.asignar.form') }}" class="flex items-center py-2 text-gray-700 hover:bg-gray-100"
                           :class="expanded ? 'px-4 justify-start' : 'justify-center'">
                            <span class="ml-2" x-show="expanded" x-transition>Asignar Servicios</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Reservas -->
            <li class="relative mt-2">
                <a href="{{ route('reservas.index') }}" class="flex items-center py-2 text-gray-700 hover:bg-gray-200 rounded-md"
                   :class="expanded ? 'justify-start px-4' : 'justify-center'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span x-show="expanded" x-transition class="ml-2">Reservas</span>
                </a>
            </li>

            <!-- Mi Perfil -->
            <li class="relative mt-2">
                <a href="{{ route('perfil.index') }}" class="flex items-center py-2 text-gray-700 hover:bg-gray-200 rounded-md"
                   :class="expanded ? 'justify-start px-4' : 'justify-center'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5.121 17.804A9 9 0 1117.804 5.121M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-show="expanded" x-transition class="ml-2">Mi Perfil</span>
                </a>
            </li>

        </ul>
    </nav>
</aside>
