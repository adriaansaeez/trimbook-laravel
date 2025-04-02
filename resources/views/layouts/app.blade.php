<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div x-data="{ expanded: true }" class="min-h-screen bg-gray-100 flex flex-col">

        <!-- Navegación principal -->
        <div :class="expanded ? 'ml-64' : 'ml-16'" class="transition-all duration-300">
            @include('layouts.navigation')
        </div>

        <div class="flex flex-1">
            @role('admin|estilista')
            <!-- Sidebar -->
                @include('layouts.sidebar')

            @endrole

            <!-- Contenido principal -->
            <div :class="expanded ? 'ml-64' : 'ml-16'" class="transition-all duration-300 flex-1">
            <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="p-6">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    @yield('scripts')
    @include('layouts.footer')

</body>



</html>