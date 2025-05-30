<nav x-data="{ open: false, estadisticasOpen: false }" class="bg-white border-b border-gray-100 w-full">
    <!-- Contenedor principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- LOGO + LINKS IZQUIERDA -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-blue-500">
                        <img src="{{ asset('images/trimbook-logo-light-removebg.png') }}" 
                             alt="Logo TrimBook" class="w-32 h-auto">
                    </a>
                </div>

                <!-- Enlaces de navegación (ocultos en mobile) -->
                <!-- Sustitución de sm:ms-10 -> sm:ml-10 y eliminación de márgenes negativos -->
                <div class="hidden space-x-8 sm:ml-10 sm:flex">
                    <!-- Home -->
                    @role('estilista|cliente')
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    @endrole
                    
                    @role('admin')
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @endrole
                    
                    @role('admin')
                    <x-nav-link :href="route('pagos.index')" :active="request()->routeIs('pagos.index')">
                        {{ __('Pagos') }}
                    </x-nav-link>
                    @endrole
                    
                    <!-- Nuevo botón: Mis Reservas -->
                    <x-nav-link href="/reservas">
                        {{ __('Mis Reservas') }}
                    </x-nav-link>
                    
                    <!-- Nuevo dropdown: Estadísticas y Datos -->
                    @role('admin')
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-blue-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out">
                            {{ __('Estadísticas y Datos') }}
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <a href="{{ route('pagos.home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('Dashboard de Pagos') }}
                                </a>
                                <a href="{{ route('reservas.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('Dashboard de Reservas') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endrole
                   
                    

                </div>
            </div>

            <!-- BOTONES DERECHA (BOOK + PERFIL) -->
            <!-- Reemplazar sm:ms-6 por sm:ml-6, etc. -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Botón de Book now -->
                @role('cliente')
                <div class="shrink-0 flex items-center md:ml-5 md:mr-3">
                    <a href="{{ route('reservas.create') }}"
                        class="inline-flex items-center space-x-2 rounded-md bg-indigo-500 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        <!-- Icono de calendario (Heroicon) -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 
                                00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Book now</span>
                    </a>
                </div>
                @endrole

                <!-- Separador vertical -->
                <div class="w-px h-6 bg-gray-300 mx-2"></div>

                <!-- Dropdown de usuario -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent
                                       text-sm leading-4 font-medium rounded-md text-gray-500 bg-white
                                       hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center">
                                <!-- Icono de usuario -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke-width="1.5"
                                     stroke="currentColor" class="w-5 h-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15.75 6a3.75 3.75 0 1 1-7.5 0 
                                             3.75 3.75 0 0 1 7.5 0Z
                                             M4.501 20.118a7.5 7.5 0 0 1 14.998 0
                                             A17.933 17.933 0 0 1 12 21.75
                                             c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                </svg>
                                <span>{{ Auth::user()->username }}</span>
                            </div>
                            <div class="ml-2">
                                <!-- Flecha dropdown -->
                                <svg class="fill-current w-4 h-4"
                                     xmlns="http://www.w3.org/2000/svg" 
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293
                                             a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4
                                             a1 1 0 010-1.414z"
                                          clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('perfil.index')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                         this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburguesa (mobile) -->
            <!-- Reemplazar la clase me-2 por mr-2 y quitar cualquier margen negativo -->
            <div class="mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md 
                               text-gray-400 hover:text-gray-500 hover:bg-gray-100 
                               focus:outline-none focus:bg-gray-100 focus:text-gray-500 
                               transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <!-- Icono de menú hamburguesa -->
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                              class="inline-flex" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <!-- Icono de cerrar -->
                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                              class="hidden" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú de navegación responsivo (mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @role('estilista|cliente')
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            @endrole
            
            @role('admin')
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @endrole
            
            @role('admin')
            <x-responsive-nav-link :href="route('pagos.index')" :active="request()->routeIs('pagos.index')">
                {{ __('Pagos') }}
            </x-responsive-nav-link>
            @endrole
            
            @role('admin')
            <div class="pt-2 pb-1 border-t border-gray-200">
                <div class="font-medium text-base text-gray-800 pl-3">
                    {{ __('Estadísticas y Datos') }}
                </div>
                <div class="mt-1 pl-3">
                    <x-responsive-nav-link :href="route('pagos.home')" :active="request()->routeIs('pagos.home')">
                        {{ __('Dashboard de Pagos') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('reservas.dashboard')" :active="request()->routeIs('reservas.dashboard')">
                        {{ __('Dashboard de Reservas') }}
                    </x-responsive-nav-link>
                </div>
            </div>
            @endrole
            
            <x-responsive-nav-link href="/reservas">
                {{ __('Mis Reservas') }}
            </x-responsive-nav-link>
        </div>

        <!-- Ajustes responsivos (mobile) -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">
                    {{ Auth::user()->name }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('perfil.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                 this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
