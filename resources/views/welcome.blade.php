<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>trimbook</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#FDFDFC] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <!-- Navegación fija que aparece al hacer scroll -->
        <nav id="sticky-nav" class="fixed top-0 left-0 right-0 z-50 transform -translate-y-full transition-transform duration-300 ease-in-out px-6 lg:px-8 pt-4">
            @if (Route::has('login'))
            <div class="lg:max-w-4xl max-w-[335px] mx-auto">
                <div class="flex items-center justify-between bg-white/95 backdrop-blur-sm shadow-lg py-3 px-4 rounded-lg border border-gray-100">
                    {{-- Logo a la izquierda --}}
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center">
                            <img src="{{ asset('images/trimbook-logo-light-removebg.png') }}" alt="TrimBook Logo" class="h-8">
                        </a>
                    </div>

                    {{-- Acciones a la derecha (desktop) --}}
                    <div class="hidden md:flex items-center gap-4">
                        @guest
                            <a href="{{ route('login') }}" class="flex items-center gap-2 text-sm font-medium text-gray-800 hover:text-blue-500 transition-colors">
                                Iniciar sesión / <span class="font-semibold">Registrarse</span>
                            </a>
                            <a href="#contacto" class="inline-flex items-center gap-1 px-4 py-1.5 text-sm text-white border border-gray-300 rounded bg-orange-400 hover:bg-orange-600 hover:text-white transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3v1.5m0 15V21m9-9h-1.5M3 12H1.5m16.95-6.45L17.1 7.1m-10.2 10.2-1.35 1.35m12.75 0-1.35-1.35M6.9 6.9 5.55 5.55" />
                                </svg>
                                Incluye tu negocio 
                            </a>
                        @endguest

                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-block px-4 py-1.5 text-sm text-gray-800 border border-blue-500 rounded hover:bg-blue-50 font-medium transition-colors">
                                Dashboard
                            </a>
                        @endauth
                    </div>

                    {{-- Botón hamburguesa (móvil) --}}
                    <div class="md:hidden">
                        <button id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-blue-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Menú móvil --}}
                <div id="mobile-menu" class="md:hidden hidden mt-2">
                    <div class="bg-white/95 backdrop-blur-sm shadow-lg rounded-lg border border-gray-100 px-4 py-3 space-y-2">
                        <a href="#servicios" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-500 hover:bg-gray-50 rounded-md transition-colors">Funcionalidades</a>
                        <a href="#precios" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-500 hover:bg-gray-50 rounded-md transition-colors">Precios</a>
                        <a href="#contacto" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-500 hover:bg-gray-50 rounded-md transition-colors">Contacto</a>
                        
                        <div class="pt-2 border-t border-gray-200">
                            @guest
                                <a href="{{ route('login') }}" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-500 hover:bg-gray-50 rounded-md transition-colors">
                                    Iniciar sesión / Registrarse
                                </a>
                                <a href="#contacto" class="block mt-2 px-4 py-1.5 text-center text-sm text-white bg-orange-400 hover:bg-orange-500 rounded font-medium transition-colors">
                                    Incluye tu negocio
                                </a>
                            @endguest

                            @auth
                                <a href="{{ url('/dashboard') }}" class="block mt-2 px-4 py-1.5 text-center text-sm text-gray-700 border border-blue-500 rounded hover:bg-blue-50 font-medium transition-colors">
                                    Dashboard
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </nav>

        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
            <nav class="flex items-center justify-between w-full bg-white py-3 px-4 shadow-sm rounded-lg">
                {{-- Logo a la izquierda --}}
                <div class="flex items-center">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/trimbook-logo-light-removebg.png') }}" alt="TrimBook Logo" class="h-10">
                    </a>
                </div>

                {{-- Acciones a la derecha --}}
                <div class="flex items-center gap-4">
                    @guest
                        {{-- Botón inicio sesión/registro --}}
                        <a href="{{ route('login') }}" class="flex items-center gap-2 text-sm font-medium text-gray-800 hover:text-blue-500 transition-colors">
                            
                            Iniciar sesión / <span class="font-semibold">Registrarse</span>
                        </a>

                        {{-- Botón de cambio de contacto --}}
                        <a href="#contacto" class="inline-flex items-center gap-1 px-4 py-1.5 text-sm text-white border border-gray-300 rounded bg-orange-400 hover:bg-orange-600 hover:text-white transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3v1.5m0 15V21m9-9h-1.5M3 12H1.5m16.95-6.45L17.1 7.1m-10.2 10.2-1.35 1.35m12.75 0-1.35-1.35M6.9 6.9 5.55 5.55" />
                            </svg>
                            Incluye tu negocio 
                        </a>
                    @endguest

                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-block px-4 py-1.5 text-sm text-gray-800 border border-blue-500 rounded hover:bg-blue-50">
                            Dashboard
                        </a>
                    @endauth
                </div>
            </nav>


            @endif
        </header>
        <div class="bg-white text-[#1b1b18]">
            {{-- Hero Principal --}}
            <section class="flex flex-col-reverse lg:flex-row items-center justify-between px-10 py-20 max-w-7xl mx-auto animate-on-scroll">
                <div class="max-w-xl text-center lg:text-left animate-on-scroll">
                    <h1 class="text-6xl font-bold text-blue-500 mb-4 typing-animation text-shimmer">Gestión inteligente para tu barbería</h1>
                    <p class="text-lg text-gray-700 mb-4">Olvídate del papeleo, agenda y controla tu negocio desde un solo lugar.</p>
                    <p class="text-sm text-gray-600 mb-6">TrimBook es el software de gestión para barberías que te permite administrar reservas, empleados y finanzas de forma sencilla.</p>
                    <div class="flex justify-center lg:justify-start gap-4">
                        <a href="#precios" class="bg-orange-400 text-white font-semibold px-6 py-3 rounded shadow hover:bg-orange-500 transition btn-animated hover-lift">Ver precios</a>
                        <a href="#servicios" class="px-6 py-3 border border-blue-500 text-blue-500 rounded hover:bg-blue-50 transition btn-animated hover-scale">Ver funcionalidades</a>
                    </div>
                </div>
                <div class="mb-8 lg:mb-0 animate-on-scroll">
                    <img src="{{ asset('images/trimbi.png') }}" alt="Mascota TrimBook" class="w-96 mx-auto lg:mx-0 hover-scale">
                </div>
            </section>

            {{-- Hero de Funcionalidades Principales --}}
            <section id="servicios" class="py-20 px-8 bg-gradient-to-br from-gray-50 to-blue-50 animate-on-scroll">
                <div class="max-w-7xl mx-auto">
                    {{-- Header de la sección --}}
                    <div class="text-center mb-16 animate-on-scroll">
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6 text-blue-600 text-shimmer">
                            Todo lo que necesitas para tu barbería
                        </h2>
                        <p class="text-xl md:text-2xl text-gray-700 mb-4 max-w-3xl mx-auto">
                            Una plataforma completa que revoluciona la gestión de tu negocio
                        </p>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                            Desde la primera cita hasta el análisis de rentabilidad, TrimBook te acompaña en cada paso
                        </p>
                    </div>

                    {{-- Grid de funcionalidades principales --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                        @php
                            $mainFeatures = [
                                [
                                    'icon' => '📅',
                                    'title' => 'Gestión Inteligente de Citas',
                                    'description' => 'Calendario automático que evita conflictos, optimiza horarios y maximiza tu productividad diaria.',
                                    'benefits' => ['Reservas 24/7', 'Sin solapamientos', 'Recordatorios automáticos'],
                                    'color' => 'from-green-400 to-green-600'
                                ],
                                [
                                    'icon' => '👥',
                                    'title' => 'Control Total del Equipo',
                                    'description' => 'Administra horarios, ausencias, comisiones y rendimiento de cada estilista en tiempo real.',
                                    'benefits' => ['Gestión de horarios', 'Control de ausencias', 'Análisis de rendimiento'],
                                    'color' => 'from-blue-400 to-blue-600'
                                ],
                                [
                                    'icon' => '💰',
                                    'title' => 'Finanzas Cristalinas',
                                    'description' => 'Reportes detallados de ingresos, gastos y rentabilidad con gráficos intuitivos.',
                                    'benefits' => ['Reportes en tiempo real', 'Control de gastos', 'Análisis de rentabilidad'],
                                    'color' => 'from-yellow-400 to-orange-600'
                                ],
                                [
                                    'icon' => '👤',
                                    'title' => 'Base de Datos de Clientes',
                                    'description' => 'Historial completo, preferencias y programa de fidelización para mejorar la experiencia.',
                                    'benefits' => ['Historial detallado', 'Preferencias guardadas', 'Programa de puntos'],
                                    'color' => 'from-purple-400 to-purple-600'
                                ],
                                [
                                    'icon' => '🔔',
                                    'title' => 'Notificaciones Inteligentes',
                                    'description' => 'Recordatorios automáticos por  email que reducen las cancelaciones.',
                                    'benefits' => ['Emails personalizados', 'Reduce no-shows'],
                                    'color' => 'from-pink-400 to-pink-600'
                                ],
                                [
                                    'icon' => '📊',
                                    'title' => 'Dashboard de Control',
                                    'description' => 'Métricas clave, KPIs y alertas importantes en una vista centralizada y clara.',
                                    'benefits' => ['Métricas en vivo', 'Alertas inteligentes'],
                                    'color' => 'from-indigo-400 to-indigo-600'
                                ]
                            ];
                        @endphp

                        @foreach($mainFeatures as $index => $feature)
                            <div class="feature-card bg-white border border-gray-200 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-500 hover:scale-105 hover-lift animate-on-scroll card-hover" 
                                 style="animation-delay: {{ $index * 0.1 }}s">
                                
                                {{-- Icono y título --}}
                                <div class="text-center mb-6">
                                    <div class="text-6xl mb-4 icon-bounce">{{ $feature['icon'] }}</div>
                                    <h3 class="text-xl md:text-2xl font-bold mb-3 text-gray-800">{{ $feature['title'] }}</h3>
                                    <p class="text-gray-600 leading-relaxed">{{ $feature['description'] }}</p>
                                </div>

                                {{-- Lista de beneficios --}}
                                <div class="space-y-3 mb-6">
                                    @foreach($feature['benefits'] as $benefit)
                                        <div class="flex items-center text-sm">
                                            <div class="w-2 h-2 bg-gradient-to-r {{ $feature['color'] }} rounded-full mr-3 flex-shrink-0"></div>
                                            <span class="text-gray-700">{{ $benefit }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Indicador visual --}}
                                <div class="h-1 w-full bg-gradient-to-r {{ $feature['color'] }} rounded-full"></div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Sección de estadísticas --}}
                    <div class="bg-white border border-gray-200 rounded-2xl p-8 mb-16 shadow-lg animate-on-scroll">
                        <div class="text-center mb-8">
                            <h3 class="text-2xl md:text-3xl font-bold mb-4 text-gray-800">TrimBook en números</h3>
                            <p class="text-gray-600 text-lg">Resultados reales de barberías que confían en nosotros</p>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                            @php
                                $stats = [
                                    ['number' => '40%', 'label' => 'Menos cancelaciones', 'icon' => '📈'],
                                    ['number' => '60%', 'label' => 'Ahorro de tiempo', 'icon' => '⏰'],
                                    ['number' => '25%', 'label' => 'Más ingresos', 'icon' => '💵'],
                                    ['number' => '95%', 'label' => 'Satisfacción cliente', 'icon' => '⭐']
                                ];
                            @endphp

                            @foreach($stats as $stat)
                                <div class="text-center animate-on-scroll">
                                    <div class="text-3xl mb-2">{{ $stat['icon'] }}</div>
                                    <div class="text-3xl md:text-4xl font-bold text-transparent bg-gradient-to-r from-blue-500 to-orange-500 bg-clip-text mb-2">
                                        {{ $stat['number'] }}
                                    </div>
                                    <div class="text-gray-600 text-sm md:text-base">{{ $stat['label'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Call to action final --}}
                    <div class="text-center animate-on-scroll">
                        <h3 class="text-2xl md:text-3xl font-bold mb-6 text-gray-800">¿Listo para revolucionar tu barbería?</h3>
                        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                            Únete a cientos de barberías que ya transformaron su negocio con TrimBook
                        </p>
                        <div class="flex flex-col sm:flex-row justify-center gap-6">
                            <a href="#contacto" class="inline-flex items-center bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold px-8 py-4 rounded-xl hover:from-orange-600 hover:to-red-600 transition-all duration-300 transform hover:scale-105 shadow-2xl btn-animated text-lg">
                                <span class="mr-2">🚀</span>
                                Comenzar ahora - Es GRATIS
                            </a>
                            <a href="#precios" class="inline-flex items-center bg-white border-2 border-blue-500 text-blue-600 font-semibold px-8 py-4 rounded-xl hover:bg-blue-50 transition-all duration-300 transform hover:scale-105 text-lg">
                                <span class="mr-2">💰</span>
                                Ver planes y precios
                            </a>
                        </div>
                        
                        {{-- Garantía --}}
                        <div class="mt-8 inline-flex items-center bg-green-50 border border-green-200 text-green-700 px-6 py-3 rounded-full">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium">30 días de prueba gratuita • Sin compromiso • Cancela cuando quieras</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Cómo funciona --}}
            <section class="bg-gray-100 py-16 px-6">
                <h3 class="text-3xl font-semibold text-center mb-12">¿Cómo funciona?</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center max-w-4xl mx-auto">
                    {{-- Paso 1: Elige un servicio --}}
                    <div>
                        <div class="flex justify-center mb-2 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m7.848 8.25 1.536.887M7.848 8.25a3 3 0 1 1-5.196-3 3 3 0 0 1 5.196 3Zm1.536.887a2.165 2.165 0 0 1 1.083 1.839c.005.351.054.695.14 1.024M9.384 9.137l2.077 1.199M7.848 15.75l1.536-.887m-1.536.887a3 3 0 1 1-5.196 3 3 3 0 0 1 5.196-3Zm1.536-.887a2.165 2.165 0 0 0 1.083-1.838c.005-.352.054-.695.14-1.025m-1.223 2.863 2.077-1.199m0-3.328a4.323 4.323 0 0 1 2.068-1.379l5.325-1.628a4.5 4.5 0 0 1 2.48-.044l.803.215-7.794 4.5m-2.882-1.664A4.33 4.33 0 0 0 10.607 12m3.736 0 7.794 4.5-.802.215a4.5 4.5 0 0 1-2.48-.043l-5.326-1.629a4.324 4.324 0 0 1-2.068-1.379M14.343 12l-2.882 1.664" />
                            </svg>
                        </div>
                        <h4 class="font-semibold mb-1">1. Elige un servicio</h4>
                        <p class="text-sm text-gray-600">Explora los servicios disponibles y selecciona el que necesitas.</p>
                    </div>

                    {{-- Paso 2: Selecciona fecha y hora --}}
                    <div>
                        <div class="flex justify-center mb-2 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                            </svg>
                        </div>
                        <h4 class="font-semibold mb-1">2. Selecciona fecha y hora</h4>
                        <p class="text-sm text-gray-600">Consulta disponibilidad y elige el mejor momento para ti.</p>
                    </div>

                    {{-- Paso 3: Confirmar reserva --}}
                    <div>
                        <div class="flex justify-center mb-2 text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6
                                        11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623
                                        5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152
                                        c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                        </div>
                        <h4 class="font-semibold mb-1">3. Confirma tu reserva</h4>
                        <p class="text-sm text-gray-600">Recibirás confirmación inmediata y un recordatorio.</p>
                    </div>
                </div>
            </section>

            {{-- Sección de Precios --}}
            <section id="precios" class="py-20 px-6 bg-gradient-to-br from-blue-50 via-white to-orange-50 animate-on-scroll">
                <div class="max-w-7xl mx-auto">
                    <div class="text-center mb-10 animate-on-scroll">
                        <h2 class="text-4xl md:text-5xl font-bold text-blue-500 mb-4 text-shimmer">Planes que se adaptan a tu barbería</h2>
                        <p class="text-lg text-gray-700 mb-2 animate-on-scroll">Pago mensual por estilista. Sin permanencia. Cancela cuando quieras.</p>
                        <p class="text-sm text-gray-600 animate-on-scroll">💰 Ahorra más con cada estilista que añadas</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto pt-6">
                        {{-- Plan Basic --}}
                        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8 relative card-hover hover-lift animate-on-scroll mt-6">
                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-gray-800 mb-2">Basic</h3>
                                <p class="text-gray-600 mb-6">Perfecto para empezar</p>
                                <div class="mb-6">
                                    <span class="text-4xl font-bold text-blue-500">€15</span>
                                    <span class="text-gray-600">/mes por estilista</span>
                                </div>
                                <p class="text-sm text-gray-500 mb-6">Mínimo 1 estilista</p>
                            </div>
                            
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Gestión de citas básica
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Control de 1 estilista
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Reportes básicos
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Soporte por email
                                </li>
                            </ul>
                            
                            <a href="#contacto" class="w-full bg-blue-500 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-600 transition block text-center btn-animated hover-lift">
                                Empezar ahora
                            </a>
                        </div>

                        {{-- Plan Premium --}}
                        <div class="bg-white rounded-lg shadow-xl border-2 border-orange-400 p-8 relative transform scale-105 card-hover hover-lift animate-on-scroll hover-glow">
                            <div class="absolute top-3 left-1/2 transform -translate-x-1/2 z-10">
                                <span class="bg-orange-400 text-white px-4 py-1 rounded-full text-sm font-semibold animated-gradient">
                                    ⭐ Más Popular
                                </span>
                            </div>
                            
                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-gray-800 mb-2 mt-6">Premium</h3>
                                <p class="text-gray-600 mb-6">Para barberías en crecimiento</p>
                                <div class="mb-6">
                                    <span class="text-4xl font-bold text-orange-500">€12</span>
                                    <span class="text-gray-600">/mes por estilista</span>
                                </div>
                                <p class="text-sm text-gray-500 mb-6">Mínimo 3 estilistas</p>
                            </div>
                            
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Todo del plan Basic
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Hasta 10 estilistas
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Notificaciones SMS
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Reportes avanzados
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Integraciones API
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Soporte prioritario
                                </li>
                            </ul>
                            
                            <a href="#contacto" class="w-full bg-orange-400 text-white py-3 px-6 rounded-lg font-semibold hover:bg-orange-500 transition block text-center btn-animated hover-lift">
                                Elegir Premium
                            </a>
                        </div>

                        {{-- Plan Enterprise --}}
                        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8 relative card-hover hover-lift animate-on-scroll mt-6">
                            {{-- Badge de plan empresarial --}}
                            <div class="absolute top-3 left-1/2 transform -translate-x-1/2 z-10">
                                <span class="bg-gradient-to-r from-purple-500 to-indigo-600 text-white px-4 py-1 rounded-full text-sm font-semibold animated-gradient">
                                    🏢 Enterprise
                                </span>
                            </div>
                            
                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-gray-800 mb-2 mt-6">Custom</h3>
                                <p class="text-gray-600 mb-6">Solución a medida para tu empresa</p>
                                <div class="mb-6">
                                    <div class="text-center">
                                        <span class="text-3xl font-bold text-transparent bg-gradient-to-r from-purple-500 to-indigo-600 bg-clip-text">Precio personalizado</span>
                                        <p class="text-sm text-gray-500 mt-2">Cotización basada en tus necesidades específicas</p>
                                </div>
                                </div>
                                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg p-4 mb-6">
                                    <p class="text-sm text-gray-700">
                                        <span class="font-semibold">💼 Solución empresarial:</span><br>
                                        Diseñamos un plan específico para tu negocio
                                    </p>
                                </div>
                            </div>
                            
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium">Todo del plan Premium +</span>
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Sucursales y empleados ilimitados
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Integración con sistemas existentes
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Desarrollo de funcionalidades exclusivas
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Consultor dedicado y formación completa
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    SLA garantizado 24/7
                                </li>
                            </ul>
                            
                            <a href="#contacto" class="w-full bg-gradient-to-r from-purple-500 to-indigo-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-purple-600 hover:to-indigo-700 transition duration-300 block text-center btn-animated hover-lift">
                                Solicitar propuesta
                            </a>
                        </div>
                    </div>

                    <div class="text-center mt-12 animate-on-scroll">
                        <p class="text-gray-600 mb-4">💡 <strong>¿No estás seguro qué plan elegir?</strong></p>
                        <p class="text-sm text-gray-500 mb-6">Los planes Basic y Premium incluyen 30 días de prueba gratis. Sin compromiso.</p>
                        <a href="#contacto" class="inline-block bg-gradient-to-r from-blue-500 to-orange-400 text-white px-8 py-3 rounded-lg font-semibold hover:from-blue-600 hover:to-orange-500 transition btn-animated hover-lift">
                            Hablar con un experto
                        </a>
                    </div>
                </div>
            </section>

            {{-- Testimonio --}}
            <section class="py-12 text-center">
                <blockquote class="max-w-xl mx-auto italic text-gray-800">
                    "¡Increíble servicio! Reservar una cita fue muy fácil y el estilista hizo un trabajo fantástico."
                    <footer class="mt-4 font-semibold text-sm">– Andrea M.</footer>
                </blockquote>
            </section>

            {{-- Formulario de Contacto Hero --}}
            <section id="contacto" class="bg-gradient-to-br from-blue-50 to-orange-50 py-20 px-6 animate-on-scroll">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-4xl md:text-5xl font-bold text-blue-500 mb-4 animate-on-scroll text-shimmer">¿Listo para transformar tu barbería?</h2>
                    <p class="text-lg text-gray-700 mb-8 animate-on-scroll">Déjanos tus datos y te contactaremos para una demostración personalizada</p>
                    
                    {{-- Mensajes de éxito/error --}}
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg mb-6 mx-auto max-w-2xl shadow-sm animate-on-scroll">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold">¡Éxito!</h4>
                                    <p class="text-sm">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg mb-6 mx-auto max-w-2xl shadow-sm animate-on-scroll">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 mr-3 mt-0.5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="font-semibold mb-1">Error al enviar el mensaje</h4>
                                    <p class="text-sm leading-relaxed">{{ session('error') }}</p>
                                    <div class="mt-3 p-3 bg-red-100 rounded text-xs">
                                        <p class="font-medium mb-1">💡 Posibles soluciones:</p>
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Verifica tu conexión a internet</li>
                                            <li>Intenta enviar el mensaje nuevamente</li>
                                            <li>Si el problema persiste, contáctanos directamente</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 px-6 py-4 rounded-lg mb-6 mx-auto max-w-2xl shadow-sm animate-on-scroll">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 mr-3 mt-0.5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="font-semibold mb-1">Errores de validación</h4>
                                    <ul class="text-sm space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>• {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Formulario --}}
                    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md mx-auto hover-lift animate-on-scroll">
                        <form action="{{ route('contacto.store') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <div class="text-left animate-on-scroll">
                                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre completo</label>
                                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre') border-red-500 @enderror transition-all duration-300 hover-glow" 
                                       placeholder="Tu nombre completo" required>
                                @error('nombre')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="text-left animate-on-scroll">
                                <label for="correo" class="block text-sm font-medium text-gray-700 mb-2">Correo electrónico</label>
                                <input type="email" id="correo" name="correo" value="{{ old('correo') }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('correo') border-red-500 @enderror transition-all duration-300 hover-glow" 
                                       placeholder="tu@email.com" required>
                                @error('correo')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="text-left animate-on-scroll">
                                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">Cuéntanos sobre tu barbería</label>
                                <textarea id="descripcion" name="descripcion" rows="4" 
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('descripcion') border-red-500 @enderror transition-all duration-300 hover-glow" 
                                          placeholder="Describe tu barbería, número de empleados, qué funcionalidades te interesan más..." required>{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-blue-500 to-orange-400 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-600 hover:to-orange-500 transition duration-300 transform hover:scale-105 btn-animated animate-on-scroll">
                                Enviar consulta
                            </button>
                        </form>
                        
                        <div class="mt-6 text-sm text-gray-600 animate-on-scroll">
                            <p>📞 También puedes llamarnos: <strong>+34 610 89 44 30</strong></p>
                            <p>📧 O escríbenos: <strong>info@trimbook.com</strong></p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Footer --}}
            @include('layouts.footer')
        </div>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif

        <!-- JavaScript para la navegación sticky -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const stickyNav = document.getElementById('sticky-nav');
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const mobileMenu = document.getElementById('mobile-menu');
                let lastScrollTop = 0;
                let navVisible = false;

                // Función para mostrar/ocultar la navegación
                function handleNavigation() {
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    
                    // Solo mostrar después de hacer scroll hacia abajo al menos 100px
                    if (scrollTop > 100) {
                        // Si scrolleamos hacia arriba, mostrar nav
                        if (scrollTop < lastScrollTop && !navVisible) {
                            stickyNav.classList.remove('-translate-y-full');
                            stickyNav.classList.add('translate-y-0');
                            navVisible = true;
                        }
                        // Si scrolleamos hacia abajo, ocultar nav
                        else if (scrollTop > lastScrollTop && navVisible) {
                            stickyNav.classList.remove('translate-y-0');
                            stickyNav.classList.add('-translate-y-full');
                            navVisible = false;
                            // Cerrar menú móvil si está abierto
                            mobileMenu.classList.add('hidden');
                        }
                    } else {
                        // Si estamos en la parte superior, ocultar nav
                        if (navVisible) {
                            stickyNav.classList.remove('translate-y-0');
                            stickyNav.classList.add('-translate-y-full');
                            navVisible = false;
                            // Cerrar menú móvil si está abierto
                            mobileMenu.classList.add('hidden');
                        }
                    }

                    lastScrollTop = scrollTop;
                }

                // Funcionalidad del menú móvil
                if (mobileMenuButton && mobileMenu) {
                    mobileMenuButton.addEventListener('click', function() {
                        mobileMenu.classList.toggle('hidden');
                    });

                    // Cerrar menú móvil cuando se hace clic en un enlace
                    const mobileLinks = mobileMenu.querySelectorAll('a[href^="#"]');
                    mobileLinks.forEach(link => {
                        link.addEventListener('click', function() {
                            mobileMenu.classList.add('hidden');
                        });
                    });

                    // Cerrar menú móvil al hacer clic fuera de él
                    document.addEventListener('click', function(event) {
                        if (!stickyNav.contains(event.target)) {
                            mobileMenu.classList.add('hidden');
                        }
                    });
                }

                // Añadir event listener para el scroll con throttling
                let ticking = false;
                window.addEventListener('scroll', function() {
                    if (!ticking) {
                        requestAnimationFrame(function() {
                            handleNavigation();
                            ticking = false;
                        });
                        ticking = true;
                    }
                });

                // Smooth scroll para los enlaces de navegación
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            // Offset para compensar la navegación fija
                            const offsetTop = target.offsetTop - 80;
                            window.scrollTo({
                                top: offsetTop,
                                behavior: 'smooth'
                            });
                        }
                    });
                });

                // ✨ ANIMACIONES DE SCROLL
                // Función para animar elementos cuando entran en el viewport
                function animateOnScroll() {
                    const animatedElements = document.querySelectorAll('.animate-on-scroll');
                    
                    animatedElements.forEach(element => {
                        const elementTop = element.getBoundingClientRect().top;
                        const elementVisible = 150;
                        
                        if (elementTop < window.innerHeight - elementVisible) {
                            element.classList.add('animate-fade-in-up');
                        }
                    });
                }

                // Event listener para scroll con throttling
                let animationTicking = false;
                window.addEventListener('scroll', function() {
                    if (!animationTicking) {
                        requestAnimationFrame(function() {
                            animateOnScroll();
                            animationTicking = false;
                        });
                        animationTicking = true;
                    }
                });

                // Ejecutar animaciones iniciales
                animateOnScroll();

                // ✨ EFECTO PARALLAX SUAVE EN IMÁGENES
                const parallaxElements = document.querySelectorAll('.parallax-element');
                
                window.addEventListener('scroll', function() {
                    const scrolled = window.pageYOffset;
                    
                    parallaxElements.forEach(element => {
                        const rate = scrolled * -0.5;
                        element.style.transform = `translateY(${rate}px)`;
                    });
                });

                // ✨ ANIMACIÓN DE TYPING PARA EL TÍTULO PRINCIPAL
                const titleElement = document.querySelector('.typing-animation');
                if (titleElement) {
                    const originalText = titleElement.textContent;
                    titleElement.textContent = '';
                    titleElement.style.opacity = '1';
                    
                    let i = 0;
                    function typeWriter() {
                        if (i < originalText.length) {
                            titleElement.textContent += originalText.charAt(i);
                            i++;
                            setTimeout(typeWriter, 50);
                        }
                    }
                    
                    // Iniciar la animación después de un breve delay
                    setTimeout(typeWriter, 500);
                }

                // ✨ FUNCIONALIDAD DEL CARRUSEL - FUNCIONES GLOBALES
                // Variables globales del carrusel
                let currentSlide = 0;
                const totalSlides = {{ count($galleryItems ?? []) }};
                let autoplayInterval = null;

                function moveCarousel(direction) {
                    const wrapper = document.getElementById('carousel-wrapper');
                    const indicators = document.querySelectorAll('.carousel-indicator');
                    
                    if (!wrapper) return;

                    // Pausar autoplay al interactuar
                    clearInterval(autoplayInterval);
                    startAutoplay();

                    currentSlide += direction;
                    
                    // Loop infinito
                    if (currentSlide >= totalSlides) {
                        currentSlide = 0;
                    } else if (currentSlide < 0) {
                        currentSlide = totalSlides - 1;
                    }
                    
                    // Mover carrusel
                    const translateX = -currentSlide * 100;
                    wrapper.style.transform = `translateX(${translateX}%)`;
                    
                    // Actualizar indicadores
                    updateIndicators();
                }

                function goToSlide(slideIndex) {
                    const wrapper = document.getElementById('carousel-wrapper');
                    const indicators = document.querySelectorAll('.carousel-indicator');
                    
                    if (!wrapper) return;

                    // Pausar autoplay al interactuar
                    clearInterval(autoplayInterval);
                    startAutoplay();

                    currentSlide = slideIndex;
                    
                    // Mover carrusel
                    const translateX = -currentSlide * 100;
                    wrapper.style.transform = `translateX(${translateX}%)`;
                    
                    // Actualizar indicadores
                    updateIndicators();
                }

                function updateIndicators() {
                    const indicators = document.querySelectorAll('.carousel-indicator');
                    indicators.forEach((indicator, index) => {
                        if (index === currentSlide) {
                            indicator.className = 'carousel-indicator w-8 h-3 rounded-full transition-all duration-300 bg-blue-500';
                        } else {
                            indicator.className = 'carousel-indicator w-3 h-3 rounded-full transition-all duration-300 bg-gray-300 hover:bg-gray-400';
                        }
                    });
                }

                function startAutoplay() {
                    autoplayInterval = setInterval(() => {
                        moveCarousel(1);
                    }, 5000); // Cambiar cada 5 segundos
                }

                // Inicializar carrusel cuando el DOM esté listo
                document.addEventListener('DOMContentLoaded', function() {
                    // ✨ FUNCIONALIDAD TÁCTIL PARA MÓVILES
                    let touchStartX = 0;
                    let touchEndX = 0;
                    const carouselContainer = document.querySelector('.carousel-container');

                    if (carouselContainer) {
                        carouselContainer.addEventListener('touchstart', (e) => {
                            touchStartX = e.changedTouches[0].screenX;
                        });

                        carouselContainer.addEventListener('touchend', (e) => {
                            touchEndX = e.changedTouches[0].screenX;
                            handleSwipe();
                        });

                        function handleSwipe() {
                            const swipeThreshold = 50;
                            const diff = touchStartX - touchEndX;

                            if (Math.abs(diff) > swipeThreshold) {
                                if (diff > 0) {
                                    // Swipe izquierda - siguiente slide
                                    moveCarousel(1);
                                } else {
                                    // Swipe derecha - slide anterior
                                    moveCarousel(-1);
                                }
                            }
                        }

                        // Pausar autoplay cuando el usuario interactúa
                        carouselContainer.addEventListener('mouseenter', () => {
                            clearInterval(autoplayInterval);
                        });

                        carouselContainer.addEventListener('mouseleave', () => {
                            startAutoplay();
                        });
                    }

                    // Iniciar autoplay cuando carga la página
                    if (totalSlides > 1) {
                        startAutoplay();
                    }

                    // Navegación con teclado
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'ArrowLeft') {
                            moveCarousel(-1);
                        } else if (e.key === 'ArrowRight') {
                            moveCarousel(1);
                        }
                    });
                });
            });
        </script>

        <!-- ✨ ESTILOS CSS PARA ANIMACIONES -->
        <style>
            /* Animaciones de entrada */
            .animate-on-scroll {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.6s ease-out;
            }

            .animate-fade-in-up {
                opacity: 1;
                transform: translateY(0);
            }

            /* Animaciones de hover mejoradas */
            .hover-scale {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .hover-scale:hover {
                transform: scale(1.05);
            }

            .hover-lift {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .hover-lift:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            .hover-glow:hover {
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
            }

            /* Animación de botones */
            .btn-animated {
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .btn-animated::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s;
            }

            .btn-animated:hover::before {
                left: 100%;
            }

            /* Animación de typing */
            .typing-animation {
                opacity: 0;
                border-right: 3px solid #3b82f6;
                animation: blink 1s infinite;
            }

            @keyframes blink {
                0%, 50% { border-color: #3b82f6; }
                51%, 100% { border-color: transparent; }
            }

            /* Animaciones de cards */
            .card-hover {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }

            .card-hover::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(45deg, transparent 30%, rgba(59, 130, 246, 0.05) 50%, transparent 70%);
                transform: translateX(-100%);
                transition: transform 0.6s;
            }

            .card-hover:hover::before {
                transform: translateX(100%);
            }

            .card-hover:hover {
                transform: translateY(-8px);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            }

            /* Animaciones de iconos */
            .icon-bounce {
                animation: bounce 2s infinite;
            }

            @keyframes bounce {
                0%, 20%, 53%, 80%, 100% {
                    transform: translate3d(0,0,0);
                }
                40%, 43% {
                    transform: translate3d(0,-10px,0);
                }
                70% {
                    transform: translate3d(0,-5px,0);
                }
                90% {
                    transform: translate3d(0,-2px,0);
                }
            }

            /* Gradientes animados */
            .animated-gradient {
                background: linear-gradient(-45deg, #3b82f6, #1d4ed8, #f97316, #ea580c);
                background-size: 400% 400%;
                animation: gradientShift 8s ease infinite;
            }

            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            /* Efecto de flotar */
            .float-animation {
                animation: float 3s ease-in-out infinite;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }

            /* Animaciones de texto */
            .text-shimmer {
                background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 50%, #3b82f6 100%);
                background-size: 200% 100%;
                background-clip: text;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                animation: shimmer 3s linear infinite;
            }

            @keyframes shimmer {
                0% { background-position: -200% 0; }
                100% { background-position: 200% 0; }
            }

            /* ✨ ESTILOS PARA GALERÍA ACORDEÓN */
            .accordion-item {
                min-width: 80px;
                transition: all 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            }

            .accordion-item:hover {
                flex: 3 !important;
            }

            .accordion-title {
                transition: all 0.5s ease;
            }

            .accordion-content {
                transition: all 0.5s ease;
            }

            /* Sombra de texto para mejor legibilidad */
            .text-shadow {
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            }

            /* Animaciones suaves para móvil */
            @media (max-width: 1024px) {
                .accordion-item {
                    min-height: 80px;
                }
                
                .accordion-title {
                    transform: none !important;
                    position: relative !important;
                    bottom: auto !important;
                    left: auto !important;
                }
                
                .accordion-content {
                    opacity: 1 !important;
                    transform: none !important;
                }
            }

            /* Mejoras para la experiencia táctil */
            .accordion-item:active {
                transform: scale(0.98);
            }

            /* Asegurar que las imágenes se carguen correctamente */
            .accordion-item [style*="background-image"] {
                background-size: cover !important;
                background-position: center !important;
                background-repeat: no-repeat !important;
            }

            /* Responsivo para animaciones */
            @media (prefers-reduced-motion: reduce) {
                .animate-on-scroll,
                .hover-scale,
                .hover-lift,
                .card-hover,
                .btn-animated,
                .float-animation,
                .accordion-item,
                .accordion-title,
                .accordion-content {
                    animation: none;
                    transition: none;
                }
            }

            /* ✨ ESTILOS PARA GALERÍA CARRUSEL */
            .carousel-container {
                position: relative;
                touch-action: pan-y;
            }

            .carousel-wrapper {
                display: flex;
                transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            }

            .carousel-slide {
                min-width: 100%;
                flex-shrink: 0;
            }

            .carousel-btn {
                backdrop-filter: blur(10px);
                border: 2px solid rgba(255, 255, 255, 0.2);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .carousel-btn:hover {
                background: white;
                border-color: rgba(59, 130, 246, 0.3);
                transform: translateY(-50%) scale(1.1);
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            }

            .carousel-btn:active {
                transform: translateY(-50%) scale(0.95);
            }

            .carousel-indicator {
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .carousel-indicator:hover {
                transform: scale(1.2);
            }

            /* Efectos de imagen en carrusel */
            .carousel-slide img {
                transition: transform 0.7s ease;
            }

            .carousel-slide:hover img {
                transform: scale(1.05);
            }

            /* Animaciones de entrada para el contenido */
            .carousel-slide .max-w-md > * {
                opacity: 0;
                transform: translateY(20px);
                animation: slideContentIn 0.6s ease forwards;
            }

            .carousel-slide .max-w-md > *:nth-child(1) { animation-delay: 0.1s; }
            .carousel-slide .max-w-md > *:nth-child(2) { animation-delay: 0.2s; }
            .carousel-slide .max-w-md > *:nth-child(3) { animation-delay: 0.3s; }
            .carousel-slide .max-w-md > *:nth-child(4) { animation-delay: 0.4s; }

            @keyframes slideContentIn {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Responsive para carrusel */
            @media (max-width: 1024px) {
                .carousel-btn {
                    padding: 0.5rem;
                }
                
                .carousel-btn svg {
                    width: 1.25rem;
                    height: 1.25rem;
                }

                .carousel-slide .flex {
                    min-height: 400px;
                }
            }

            @media (max-width: 640px) {
                .carousel-btn {
                    left: 1rem !important;
                    right: 1rem !important;
                }
                
                .carousel-prev {
                    left: 1rem !important;
                }
                
                .carousel-next {
                    right: 1rem !important;
                }
            }

            /* Sombra de texto para mejor legibilidad */
            .text-shadow {
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            }

            /* ✨ ESTILOS ESPECÍFICOS PARA CAPTURAS DE PANTALLA */
            .screenshot-container {
                max-width: 800px;
                margin: 0 auto;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 8px;
                border-radius: 12px;
            }

            .screenshot-image {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                transition: all 0.5s ease;
            }

            .screenshot-image:hover {
                transform: scale(1.02);
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            }

            /* Ajustes responsive para capturas */
            @media (max-width: 1024px) {
                .screenshot-container {
                    max-width: 600px;
                }
            }

            @media (max-width: 768px) {
                .screenshot-container {
                    max-width: 100%;
                    margin: 0 20px;
                }
            }
        </style>
    </body>
</html>
