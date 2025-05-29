<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Política de Privacidad - TrimBook</title>

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
                    <h1 class="text-4xl font-bold text-blue-500 mb-4">Política de Privacidad</h1>
                    <p class="text-gray-600">Última actualización: {{ date('d/m/Y') }}</p>
                </div>

                <!-- Contenido -->
                <div class="prose prose-lg max-w-none">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8">
                        <p class="text-blue-800">
                            <strong>En TrimBook</strong>, nos comprometemos a proteger tu privacidad y garantizar la seguridad de tus datos personales. 
                            Esta política explica cómo recopilamos, utilizamos y protegemos tu información.
                        </p>
                    </div>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">1. Información que Recopilamos</h2>
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Información Personal</h3>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li>Nombre completo y datos de contacto (email, teléfono)</li>
                        <li>Información de tu barbería (nombre, dirección, número de empleados)</li>
                        <li>Datos de facturación y pago</li>
                        <li>Información de acceso (usuario y contraseña)</li>
                    </ul>

                    <h3 class="text-lg font-medium text-gray-700 mb-3">Información de Uso</h3>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li>Datos de navegación y uso de la plataforma</li>
                        <li>Dirección IP y información del dispositivo</li>
                        <li>Cookies y tecnologías similares</li>
                        <li>Registros de actividad en el sistema</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">2. Cómo Utilizamos tu Información</h2>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li><strong>Prestación del servicio:</strong> Gestionar tu cuenta y proporcionar funcionalidades de TrimBook</li>
                        <li><strong>Comunicación:</strong> Enviarte notificaciones importantes, actualizaciones y soporte técnico</li>
                        <li><strong>Mejoras:</strong> Analizar el uso para mejorar nuestros servicios</li>
                        <li><strong>Seguridad:</strong> Prevenir fraudes y garantizar la seguridad de la plataforma</li>
                        <li><strong>Marketing:</strong> Enviarte información comercial (solo con tu consentimiento)</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">3. Compartir Información</h2>
                    <p class="mb-4">No vendemos, alquilamos ni compartimos tu información personal con terceros, excepto en los siguientes casos:</p>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li><strong>Proveedores de servicios:</strong> Para procesar pagos, envío de emails y alojamiento seguro</li>
                        <li><strong>Cumplimiento legal:</strong> Cuando sea requerido por ley o autoridades competentes</li>
                        <li><strong>Protección de derechos:</strong> Para proteger nuestros derechos, propiedad o seguridad</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">4. Seguridad de los Datos</h2>
                    <p class="mb-4">Implementamos medidas técnicas y organizativas apropiadas para proteger tu información:</p>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li>Encriptación SSL/TLS para todas las comunicaciones</li>
                        <li>Servidores seguros con acceso restringido</li>
                        <li>Copias de seguridad regulares y cifradas</li>
                        <li>Monitoreo continuo de seguridad</li>
                        <li>Políticas de acceso basadas en roles</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">5. Tus Derechos</h2>
                    <p class="mb-4">Tienes derecho a:</p>
                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">✓ Acceso</h4>
                            <p class="text-sm">Solicitar una copia de tus datos personales</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">✓ Rectificación</h4>
                            <p class="text-sm">Corregir información inexacta o incompleta</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">✓ Supresión</h4>
                            <p class="text-sm">Solicitar la eliminación de tus datos</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">✓ Portabilidad</h4>
                            <p class="text-sm">Recibir tus datos en formato estructurado</p>
                        </div>
                    </div>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">6. Cookies</h2>
                    <p class="mb-4">Utilizamos cookies para mejorar tu experiencia. Puedes gestionar las cookies desde tu navegador. Las cookies que utilizamos incluyen:</p>
                    <ul class="list-disc pl-6 mb-6 space-y-2">
                        <li><strong>Esenciales:</strong> Necesarias para el funcionamiento básico</li>
                        <li><strong>Funcionales:</strong> Para recordar tus preferencias</li>
                        <li><strong>Analíticas:</strong> Para entender cómo usas nuestro servicio</li>
                    </ul>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">7. Retención de Datos</h2>
                    <p class="mb-6">Conservamos tu información personal solo durante el tiempo necesario para cumplir con los propósitos descritos en esta política, o según lo requiera la ley.</p>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">8. Cambios en esta Política</h2>
                    <p class="mb-6">Podemos actualizar esta política ocasionalmente. Te notificaremos sobre cambios significativos por email o a través de nuestro servicio.</p>

                    <div class="bg-orange-50 border-l-4 border-orange-400 p-6 mt-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">📞 Contacto</h2>
                        <p class="mb-4">Si tienes preguntas sobre esta política de privacidad o quieres ejercer tus derechos, contáctanos:</p>
                        <div class="space-y-2">
                            <p><strong>Email:</strong> privacy@trimbook.com</p>
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