<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Pago') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('pagos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Volver a la lista
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Información del pago -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-medium mb-4 text-gray-900">Información del pago</h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">ID del pago</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $pago->id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Fecha de pago</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $pago->fecha_pago->format('d/m/Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Método de pago</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $pago->metodo_pago }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Importe</p>
                                    <p class="mt-1 text-lg font-bold text-gray-900">{{ number_format($pago->importe, 2) }} €</p>
                                </div>
                            </div>
                        </div>

                        <!-- Información de la reserva -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-medium mb-4 text-gray-900">Información de la reserva</h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">ID de la reserva</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $pago->reserva->id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Fecha de la reserva</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $pago->reserva->fecha->format('d/m/Y') }} a las {{ $pago->reserva->hora }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Estado</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $pago->reserva->estado }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Información del servicio -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-medium mb-4 text-gray-900">Información del servicio</h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Servicio</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $pago->reserva->servicio->nombre }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Precio del servicio</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ number_format($pago->reserva->servicio->precio, 2) }} €</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Duración</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $pago->reserva->servicio->duracion }} minutos</p>
                                </div>
                            </div>
                        </div>

                        <!-- Información del cliente y estilista -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-medium mb-4 text-gray-900">Información del cliente y estilista</h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Cliente</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $pago->reserva->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Email del cliente</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $pago->reserva->user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Estilista</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $pago->estilista->nombre }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 