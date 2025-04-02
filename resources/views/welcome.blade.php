<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-[#FDFDFC] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
            <nav class="flex items-center justify-between w-full bg-white py-3 px-4 shadow-sm">
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
                        <a href="{{ route('login') }}" class="flex items-center gap-2 text-sm font-medium text-gray-800 hover:text-blue-500">
                            
                            Iniciar sesión / <span class="font-semibold">Registrarse</span>
                        </a>

                        {{-- Botón de cambio de contacto --}}
                        <button id="themeToggle" class="inline-flex items-center gap-1 px-4 py-1.5 text-sm text-white border border-gray-300 rounded bg-orange-400 hover:bg-orange-600 hover:text-white transition">
                            <svg id="themeIcon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3v1.5m0 15V21m9-9h-1.5M3 12H1.5m16.95-6.45L17.1 7.1m-10.2 10.2-1.35 1.35m12.75 0-1.35-1.35M6.9 6.9 5.55 5.55" />
                            </svg>
                            Incluye tu negocio 
                        </button>
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
            {{-- Hero --}}
            <section class="flex flex-col-reverse lg:flex-row items-center justify-between px-6 py-20 max-w-7xl mx-auto">
                <div class="max-w-xl text-center lg:text-left">
                    <h1 class="text-6xl font-bold text-blue-500 mb-4">Gestión inteligente para tu barbería</h1>
                    <p class="text-lg text-gray-700 mb-4">Olvídate del papeleo, agenda y controla tu negocio desde un solo lugar.</p>
                    <p class="text-sm text-gray-600 mb-6">TrimBook es el software de gestión para barberías que te permite administrar reservas, empleados y finanzas de forma sencilla.</p>
                    <div class="flex justify-center lg:justify-start gap-4">
                        <a href="{{ route('login') }}" class="bg-orange-400 text-white font-semibold px-6 py-3 rounded shadow hover:bg-orange-500 transition">Probar gratis</a>
                        <a href="#servicios" class="px-6 py-3 border border-blue-500 text-blue-500 rounded hover:bg-blue-50 transition">Ver funcionalidades</a>
                    </div>
                </div>
                <div class="mb-8 lg:mb-0">
                    <img src="{{ asset('images/trimbi.png') }}" alt="Mascota TrimBook" class="w-96 mx-auto lg:mx-0">
                </div>
            </section>

            {{-- Funcionalidades destacadas --}}
            <section class="py-16 px-6 max-w-6xl mx-auto bg-white" id="servicios">
                <h3 class="text-3xl font-semibold text-center mb-10 text-blue-500">Funcionalidades destacadas</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ([
                        ['title' => 'Gestión de citas', 'desc' => 'Organiza las reservas automáticamente y evita solapes.', 'icon' => '📅'],
                        ['title' => 'Control de empleados', 'desc' => 'Administra horarios, ausencias y rendimiento de tu equipo.', 'icon' => '👥'],
                        ['title' => 'Resumen financiero', 'desc' => 'Visualiza ingresos por día, semana o mes y controla tu caja.', 'icon' => '💰'],
                        ['title' => 'Notificaciones automáticas', 'desc' => 'Envía recordatorios por email o SMS a tus clientes.', 'icon' => '🔔'],
                    ] as $feature)
                        <div class="border rounded-lg p-6 text-center shadow hover:shadow-md transition bg-[#FDFDFC]">
                            <div class="text-4xl mb-4">{{ $feature['icon'] }}</div>
                            <h4 class="text-lg font-semibold text-blue-500">{{ $feature['title'] }}</h4>
                            <p class="text-sm text-gray-600 mt-2">{{ $feature['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Hero 2 --}}
            <section class="flex flex-col-reverse lg:flex-row items-center justify-between px-6 py-20 max-w-7xl mx-auto">
                {{-- Columna izquierda: Mascota --}}
                <div class="lg:w-1/2 mb-10 lg:mb-0 flex justify-center">
                    <img src="{{ asset('images/trimbi-pelando.png') }}" alt="Mascota TrimBook" class="w-96">
                </div>

                {{-- Columna derecha: Texto y botones --}}
                <div class="lg:w-1/2 text-center lg:text-left">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-blue-500 mb-4">Ocupate de tu negocio</h1>
                    <p class="text-lg text-gray-700 mb-4">Nosotros nos ocupamos del resto.</p>
                    <p class="text-sm text-gray-600 mb-6">TrimBook es el software de gestión para barberías que te permite administrar reservas, empleados y finanzas de forma sencilla.</p>
                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                        <a href="{{ route('login') }}" class="bg-orange-400 text-white font-semibold px-6 py-3 rounded shadow hover:bg-orange-500 transition">Probar gratis</a>
                        <a href="#servicios" class="px-6 py-3 border border-blue-500 text-blue-500 rounded hover:bg-blue-50 transition">Ver funcionalidades</a>
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



            {{-- Testimonio --}}
            <section class="py-12 text-center">
                <blockquote class="max-w-xl mx-auto italic text-gray-800">
                    “¡Increíble servicio! Reservar una cita fue muy fácil y el estilista hizo un trabajo fantástico.”
                    <footer class="mt-4 font-semibold text-sm">– Andrea M.</footer>
                </blockquote>
            </section>

            {{-- Footer --}}
            <footer class="bg-gray-100 py-6 px-6 text-center text-sm text-gray-500">
                <div class="max-w-4xl mx-auto grid sm:grid-cols-2 gap-4">
                    <div>
                        <p class="font-semibold text-gray-700">Contacto</p>
                        <p>info@trimbook.com</p>
                        <p>+34 123 456 789</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Enlaces útiles</p>
                        <p><a href="#" class="hover:underline">Precios</a></p>
                        <p><a href="#" class="hover:underline">Preguntas frecuentes</a></p>
                    </div>
                </div>
            </footer>
        </div>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
