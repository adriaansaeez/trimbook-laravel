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
            <nav class="flex items-center justify-between w-full">
                {{-- Logo a la izquierda --}}
                <div class="flex items-center">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/trimbook-logo-light-removebg.png') }}" alt="TrimBook Logo" class="h-12">
                    </a>
                </div>

                {{-- Enlaces a la derecha --}}
                <div class="flex items-center gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 text-black border-blue hover:border-[#1915014a] border text-[#1b1b18] rounded-sm text-sm leading-normal"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 text-blue-500 border border-transparent hover:border-blue-500 rounded-sm text-sm leading-normal"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 text-white font-semibold bg-blue-500 border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] rounded-sm text-sm leading-normal"
                            >
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>

            @endif
        </header>
        <div class="bg-white text-[#1b1b18]">
            {{-- Hero --}}
            <section class="text-center py-20 bg-gradient-to-br from-purple-600 to-pink-400 text-white">
                <h2 class="text-4xl font-bold mb-4">Reserva tu estilo en segundos</h2>
                <p class="text-lg mb-6">Citas rápidas, estilistas profesionales y sin complicaciones.</p>
                <a href="{{ route('login') }}" class="bg-white text-purple-600 font-semibold px-6 py-3 rounded shadow hover:bg-gray-100 transition">
                    Reservar ahora
                </a>
            </section>

            {{-- Servicios populares --}}
            <section class="py-16 px-6 max-w-6xl mx-auto">
                <h3 class="text-3xl font-semibold text-center mb-10">Servicios populares</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ([
                        ['title' => 'Corte de cabello', 'desc' => 'Resalta tu estilo', 'price' => 'Desde 15€'],
                        ['title' => 'Afeitado', 'desc' => 'Afeitado clásico o moderno', 'price' => 'Desde 10€'],
                        ['title' => 'Recorte de barba', 'desc' => 'Define tu perfil', 'price' => 'Desde 12€'],
                        ['title' => 'Tinte para el cabello', 'desc' => 'Colores vibrantes o naturales', 'price' => 'Desde 25€'],
                    ] as $service)
                        <div class="border rounded-lg p-4 text-center shadow hover:shadow-md transition">
                            <h4 class="text-lg font-semibold">{{ $service['title'] }}</h4>
                            <p class="text-sm text-gray-600">{{ $service['desc'] }}</p>
                            <p class="mt-2 font-bold">{{ $service['price'] }}</p>
                        </div>
                    @endforeach
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
