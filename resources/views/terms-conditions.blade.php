<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Términos y Condiciones - TrimBook</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-gray-50 text-gray-900">
        <!-- Navegación simple -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-4xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <img src="{{ asset('images/trimbook-logo-light-removebg.png') }}" alt="TrimBook Logo" class="h-10">
                    </a>
                    <a href="{{ url('/') }}" class="text-sm text-blue-500 hover:text-blue-600 font-medium">
                        ← Volver al inicio
                    </a>
                </div>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="max-w-4xl mx-auto px-6 py-12">
            <div class="bg-white rounded-lg shadow-sm border p-8 lg:p-12">
                <!-- Header -->
                <div class="text-center mb-10">
                    <h1 class="text-4xl font-bold text-blue-500 mb-4">Términos y Condiciones</h1>
                    <p class="text-gray-600">Última actualización: {{ date('d/m/Y') }}</p>
                </div>

                <!-- Contenido -->
                <div class="prose prose-lg max-w-none">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8">
                        <p class="text-blue-800">
                            <strong>Bienvenido a TrimBook.</strong> Estos términos y condiciones rigen el uso de nuestro software de gestión para barberías. 
                            Al usar nuestros servicios, aceptas estos términos en su totalidad.
                        </p>
                    </div>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">1. Aceptación de los Términos</h2>
                    <p class="mb-6">Al acceder y utilizar TrimBook, confirmas que has leído, entendido y aceptas estar sujeto a estos términos y condiciones, así como a nuestra Política de Privacidad.</p>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">2. Descripción del Servicio</h2>
                    <p class="mb-4">TrimBook es una plataforma SaaS (Software as a Service) que ofrece:</p>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li>Sistema de gestión de citas y reservas</li>
                        <li>Control de empleados y horarios</li>
                        <li>Gestión financiera y reportes</li>
                        <li>Notificaciones automáticas</li>
                        <li>Integración con sistemas de pago</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">3. Registro y Cuenta de Usuario</h2>
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Elegibilidad</h3>
                    <p class="mb-4">Para usar TrimBook debes:</p>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li>Ser mayor de 18 años o tener autorización parental</li>
                        <li>Proporcionar información veraz y actualizada</li>
                        <li>Ser propietario o tener autorización para gestionar una barbería</li>
                        <li>Cumplir con todas las leyes aplicables en tu jurisdicción</li>
                    </ul>

                    <h3 class="text-lg font-medium text-gray-700 mb-3">Responsabilidades de la Cuenta</h3>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li>Mantener la confidencialidad de tus credenciales</li>
                        <li>Notificar inmediatamente cualquier uso no autorizado</li>
                        <li>Ser responsable de todas las actividades bajo tu cuenta</li>
                        <li>Mantener actualizada tu información de contacto</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">4. Planes y Pagos</h2>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-700 mb-3">💳 Facturación</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Los pagos se procesan mensualmente por adelantado</li>
                            <li>Los precios pueden cambiar con 30 días de aviso previo</li>
                            <li>No hay reembolsos por cancelaciones (excepto período de prueba)</li>
                            <li>Los impuestos aplicables se añaden según la legislación local</li>
                        </ul>
                    </div>

                    <h3 class="text-lg font-medium text-gray-700 mb-3">Período de Prueba</h3>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li>30 días gratuitos para planes Basic y Premium</li>
                        <li>Plan Enterprise: período de prueba negociable</li>
                        <li>Cancelación gratuita durante el período de prueba</li>
                        <li>Conversión automática al plan seleccionado tras el período</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">5. Uso Aceptable</h2>
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Está Permitido:</h3>
                    <ul class="list-disc pl-6 mb-4 space-y-2">
                        <li>Usar el servicio para gestionar tu barbería legítimamente</li>
                        <li>Invitar a empleados autorizados a usar la plataforma</li>
                        <li>Exportar tus propios datos</li>
                        <li>Integrar con sistemas de terceros compatibles</li>
                    </ul>

                    <h3 class="text-lg font-medium text-gray-700 mb-3">Está Prohibido:</h3>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li>Realizar ingeniería inversa del software</li>
                        <li>Revender o sublicenciar el acceso</li>
                        <li>Usar el servicio para actividades ilegales</li>
                        <li>Intentar acceder a cuentas de otros usuarios</li>
                        <li>Sobrecargar o interferir con los servidores</li>
                        <li>Crear cuentas falsas o múltiples</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">6. Propiedad Intelectual</h2>
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="font-semibold text-blue-600 mb-3">🏢 TrimBook es propietario de:</h4>
                            <ul class="text-sm space-y-2">
                                <li>• Software y código fuente</li>
                                <li>• Marca registrada y logotipos</li>
                                <li>• Documentación y materiales</li>
                                <li>• Metodologías y procesos</li>
                            </ul>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="font-semibold text-green-600 mb-3">👤 Tú eres propietario de:</h4>
                            <ul class="text-sm space-y-2">
                                <li>• Datos de tu barbería</li>
                                <li>• Información de clientes</li>
                                <li>• Registros de transacciones</li>
                                <li>• Contenido que subas</li>
                            </ul>
                        </div>
                    </div>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">7. Disponibilidad del Servicio</h2>
                    <p class="mb-4">Nos esforzamos por mantener TrimBook disponible 24/7, pero no garantizamos:</p>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li>Disponibilidad ininterrumpida del servicio</li>
                        <li>Ausencia total de errores o fallos</li>
                        <li>Compatibilidad con todas las versiones de navegadores</li>
                    </ul>

                    <p class="mb-6"><strong>Mantenimiento programado:</strong> Te notificaremos con al menos 24 horas de antelación sobre cualquier mantenimiento programado que pueda afectar el servicio.</p>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">8. Limitación de Responsabilidad</h2>
                    <div class="bg-red-50 border-l-4 border-red-400 p-6 mb-6">
                        <p class="text-red-800 mb-4"><strong>IMPORTANTE:</strong> TrimBook se proporciona "tal como está". En la máxima medida permitida por la ley:</p>
                        <ul class="list-disc pl-6 space-y-2 text-red-700">
                            <li>No somos responsables de daños indirectos o consecuentes</li>
                            <li>Nuestra responsabilidad máxima se limita al importe pagado en los últimos 12 meses</li>
                            <li>No garantizamos resultados específicos de negocio</li>
                            <li>Eres responsable de mantener copias de seguridad de tus datos</li>
                        </ul>
                    </div>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">9. Cancelación y Terminación</h2>
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Cancelación por tu parte:</h3>
                    <ul class="list-disc pl-6 mb-4 space-y-2">
                        <li>Puedes cancelar en cualquier momento desde tu cuenta</li>
                        <li>El servicio continúa hasta el final del período facturado</li>
                        <li>Tienes 30 días para exportar tus datos tras la cancelación</li>
                    </ul>

                    <h3 class="text-lg font-medium text-gray-700 mb-3">Terminación por nuestra parte:</h3>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li>Por violación de estos términos</li>
                        <li>Por falta de pago después de 15 días de gracia</li>
                        <li>Por uso indebido de la plataforma</li>
                        <li>Con 30 días de aviso si descontinuamos el servicio</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">10. Modificaciones</h2>
                    <p class="mb-6">Podemos modificar estos términos ocasionalmente. Los cambios significativos se notificarán con al menos 30 días de antelación. El uso continuado del servicio constituye aceptación de los nuevos términos.</p>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">11. Ley Aplicable</h2>
                    <p class="mb-6">Estos términos se rigen por las leyes de España. Cualquier disputa se resolverá en los tribunales competentes de Madrid, España.</p>

                    <div class="bg-orange-50 border-l-4 border-orange-400 p-6 mt-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">📞 Contacto Legal</h2>
                        <p class="mb-4">Para consultas sobre estos términos o asuntos legales:</p>
                        <div class="space-y-2">
                            <p><strong>Email:</strong> legal@trimbook.com</p>
                            <p><strong>Teléfono:</strong> +34 123 456 789</p>
                            <p><strong>Dirección:</strong> TrimBook Solutions, Calle Principal 123, 28001 Madrid, España</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('layouts.footer')
    </body>
</html> 