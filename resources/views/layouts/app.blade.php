<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts de Vite / Mix -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased overflow-x-hidden">
    <!-- 
      El x-data define la variable "expanded" para alternar el ancho del sidebar.
      flex-col y min-h-screen permiten que el contenido abarque la pantalla completa
      y el footer se ubique naturalmente al final.
    -->
    <div x-data="{ expanded: false }" class="flex flex-col min-h-screen bg-gray-100 overflow-x-hidden">
        
        <!-- NAV Superior -->
        <header>
            @include('layouts.navigation')
        </header>
        
        <!-- Contenedor central con flex creará 2 columnas (sidebar + contenido) -->
        <div class="flex flex-1">
            <!-- Sidebar solo para admin o estilista -->
            @role('admin')
                @include('layouts.sidebar')
            @endrole
            
            <!-- Contenido principal -->
            <main class="flex-1 overflow-x-hidden">
                <!-- Encabezado de página (opcional) -->
                @isset($header)
                    <div class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                <!-- Contenido de la página -->
                <div class="p-6">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Footer -->
        <footer class="bg-white shadow-sm">
            @include('layouts.footer')
        </footer>
    </div>

    @yield('scripts')
</body>
</html>
