<aside x-data="{ openDropdown: '' }"
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
            <!-- Ejemplo de dropdown CRUDs -->
            <li class="relative">
                <button @click="openDropdown === 'cruds' ? openDropdown = '' : openDropdown = 'cruds'"
                        class="w-full flex items-center py-2 text-gray-700 hover:bg-gray-200 rounded-md"
                        :class="expanded ? 'justify-start px-4' : 'justify-center'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <span x-show="expanded" x-transition class="ml-2">CRUDs</span>
                    <svg x-show="expanded" class="w-5 h-5 ml-auto transition-transform transform"
                         :class="openDropdown === 'cruds' ? 'rotate-180' : ''"
                         xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <!-- Submenu -->
                <ul x-show="openDropdown === 'cruds'"
                    x-collapse 
                    class="bg-white mt-1 z-10">
                    <li>
                        <a href="{{ route('users.index') }}"
                           class="flex items-center py-2 text-gray-700 hover:bg-gray-100"
                           :class="expanded ? 'px-4 justify-start' : 'justify-center'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M5.121 17.804A9 9 0 1117.804 5.121
                                         M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span x-show="expanded" x-transition class="ml-2">Usuarios</span>
                        </a>
                    </li>
                    <!-- ... resto de ítems ... -->
                </ul>
            </li>
            
            <!-- Otro dropdown: Asignaciones -->
            <li class="relative mt-2">
                <button @click="openDropdown === 'asignaciones' 
                                ? openDropdown = '' 
                                : openDropdown = 'asignaciones'"
                        class="w-full flex items-center py-2 text-gray-700 hover:bg-gray-200 rounded-md"
                        :class="expanded ? 'justify-start px-4' : 'justify-center'">
                    <svg class="w-6 h-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 12h6m2 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="expanded" x-transition class="ml-2">Asignaciones</span>
                    <svg x-show="expanded" class="w-5 h-5 ml-auto transition-transform transform"
                         :class="openDropdown === 'asignaciones' ? 'rotate-180' : ''"
                         xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul x-show="openDropdown === 'asignaciones'"
                    x-collapse
                    class="bg-white mt-1 z-10">
                    <li>
                        <a href="{{ route('asignar_horario.index') }}"
                           class="flex items-center py-2 text-gray-700 hover:bg-gray-100"
                           :class="expanded ? 'px-4 justify-start' : 'justify-center'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" 
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 
                                         9 9 0 0118 0z" />
                            </svg>
                            <span x-show="expanded" x-transition class="ml-2">
                                Asignar Horario Estilista
                            </span>
                        </a>
                    </li>
                    <!-- ... resto de ítems ... -->
                </ul>
            </li>
        </ul>
    </nav>
</aside>
