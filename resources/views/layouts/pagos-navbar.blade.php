<!-- Navbar de Pagos -->
<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo/Título -->
                <div class="flex-shrink-0 flex items-center">
                    <span class="text-xl font-semibold text-gray-800">
                        <i class="fas fa-money-bill-wave mr-2"></i>
                        Gestión de Pagos
                    </span>
                </div>

                <!-- Links de Navegación -->
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('pagos.home') }}" 
                       class="{{ request()->routeIs('pagos.home') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i class="fas fa-chart-line mr-2"></i>
                        Inicio
                    </a>

                    <a href="{{ route('pagos.index') }}"
                       class="{{ request()->routeIs('pagos.index') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        <i class="fas fa-list mr-2"></i>
                        Listado de Pagos
                    </a>
                </div>
            </div>

            <!-- Botón de Exportar -->
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <a href="{{ route('pagos.export.excel') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-file-excel mr-2"></i>
                    Exportar Excel
                </a>
            </div>

            <!-- Menú móvil -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Abrir menú principal</span>
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú móvil -->
    <div class="sm:hidden" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('pagos.home') }}" 
               class="{{ request()->routeIs('pagos.home') ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Dashboard
            </a>

            <a href="{{ route('pagos.index') }}" 
               class="{{ request()->routeIs('pagos.index') ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Listado de Pagos
            </a>

            <a href="{{ route('pagos.export.excel') }}" 
               class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Exportar Excel
            </a>
        </div>
    </div>
</nav> 