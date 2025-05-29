<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Cita</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .reservation-card {
            background: #f8fafc;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .label {
            font-weight: 600;
            color: #4a5568;
        }
        .value {
            color: #2d3748;
        }
        .highlight {
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .footer {
            background: #f7fafc;
            padding: 20px;
            text-align: center;
            color: #718096;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 15px 0;
            font-weight: 600;
        }
        .alert {
            background: #fef5e7;
            border: 1px solid #f6ad55;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .alert-icon {
            color: #ed8936;
            font-size: 20px;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">📅</div>
            <h1>¡Tu cita es mañana!</h1>
            <p>Recordatorio de tu próxima reserva</p>
        </div>

        <div class="content">
            <h2>Hola {{ $cliente->perfil->nombre ?? $cliente->name }}! 👋</h2>
            
            <p>Te escribimos para recordarte que tienes una cita programada para <strong>mañana</strong>.</p>

            <div class="alert">
                <span class="alert-icon">⏰</span>
                <strong>¡No olvides tu cita!</strong> Te esperamos mañana puntualmente.
            </div>

            <div class="reservation-card">
                <h3 style="margin-top: 0; color: #667eea;">📋 Detalles de tu reserva</h3>
                
                <div class="detail-row">
                    <span class="label">🎯 Servicio:</span>
                    <span class="value"><strong>{{ $servicio->nombre }}</strong></span>
                </div>

                <div class="detail-row">
                    <span class="label">📅 Fecha:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($reserva->fecha)->format('l, d \d\e F \d\e Y') }}</span>
                </div>

                <div class="detail-row">
                    <span class="label">🕐 Hora:</span>
                    <span class="value"><span class="highlight">{{ \Carbon\Carbon::parse($reserva->hora)->format('H:i') }}</span></span>
                </div>

                <div class="detail-row">
                    <span class="label">✂️ Estilista:</span>
                    <span class="value">{{ $estilista->user->perfil->nombre ?? $estilista->nombre }} {{ $estilista->user->perfil->apellidos ?? '' }}</span>
                </div>

                <div class="detail-row">
                    <span class="label">💰 Precio:</span>
                    <span class="value"><strong>€{{ number_format($servicio->precio, 2) }}</strong></span>
                </div>

                <div class="detail-row">
                    <span class="label">📊 Estado:</span>
                    <span class="value">
                        @if($reserva->estado === 'CONFIRMADA')
                            <span style="color: #10b981; font-weight: 600;">✅ Confirmada</span>
                        @else
                            <span style="color: #f59e0b; font-weight: 600;">⏳ {{ $reserva->estado }}</span>
                        @endif
                    </span>
                </div>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <p><strong>¿Necesitas hacer algún cambio?</strong></p>
                <a href="{{ url('/reservas') }}" class="btn">Ver mis reservas</a>
            </div>

            <div style="border-top: 1px solid #e2e8f0; padding-top: 20px; margin-top: 30px;">
                <h3 style="color: #4a5568;">💡 Recordatorios importantes:</h3>
                <ul style="color: #718096;">
                    <li>🕐 Por favor, llega <strong>5 minutos antes</strong> de tu cita</li>
                    <li>📱 Si necesitas cancelar, hazlo con al menos <strong>2 horas de antelación</strong></li>
                    <li>💳 Puedes pagar en efectivo, tarjeta o Bizum</li>
                    <li>😷 Recuerda traer mascarilla si es necesario</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p><strong>{{ config('app.name', 'TrimBook') }}</strong></p>
            <p>📧 Este es un recordatorio automático. No responda a este email.</p>
            <p>📍 Nos vemos mañana en tu cita | 💜 ¡Estamos deseando verte!</p>
        </div>
    </div>
</body>
</html> 