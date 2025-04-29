<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detalles del Pago #{{ $pago->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            width: 200px;
        }
        .info-value {
            flex: 1;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .diferencia {
            font-weight: bold;
            color: {{ $diferencia > 0 ? 'green' : ($diferencia < 0 ? 'red' : 'black') }};
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Detalles del Pago #{{ $pago->id }}</h1>
            <p>Fecha de emisión: {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        <div class="section">
            <div class="section-title">Información del Pago</div>
            <div class="info-row">
                <div class="info-label">ID del pago:</div>
                <div class="info-value">{{ $pago->id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de pago:</div>
                <div class="info-value">{{ $pago->fecha_pago->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Método de pago:</div>
                <div class="info-value">{{ $pago->metodo_pago }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Importe:</div>
                <div class="info-value">{{ number_format($pago->importe, 2) }} €</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Información de la Reserva</div>
            <div class="info-row">
                <div class="info-label">ID de la reserva:</div>
                <div class="info-value">{{ $pago->reserva->id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de la reserva:</div>
                <div class="info-value">{{ $pago->reserva->fecha->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Hora:</div>
                <div class="info-value">{{ $pago->reserva->hora }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Estado:</div>
                <div class="info-value">{{ $pago->reserva->estado }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Información del Servicio</div>
            <div class="info-row">
                <div class="info-label">Servicio:</div>
                <div class="info-value">{{ $pago->reserva->servicio->nombre }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Precio del servicio:</div>
                <div class="info-value">{{ number_format($pago->reserva->servicio->precio, 2) }} €</div>
            </div>
            <div class="info-row">
                <div class="info-label">Duración:</div>
                <div class="info-value">{{ $pago->reserva->servicio->duracion }} minutos</div>
            </div>
            <div class="info-row">
                <div class="info-label">Diferencia (Pago - Servicio):</div>
                <div class="info-value diferencia">{{ number_format($diferencia, 2) }} €</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Información del Cliente y Estilista</div>
            <div class="info-row">
                <div class="info-label">Cliente:</div>
                <div class="info-value">{{ $pago->reserva->user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email del cliente:</div>
                <div class="info-value">{{ $pago->reserva->user->email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Estilista:</div>
                <div class="info-value">{{ $pago->estilista->nombre }}</div>
            </div>
        </div>

        <div class="footer">
            <p>Este documento ha sido generado automáticamente por el sistema TrimBook.</p>
        </div>
    </div>
</body>
</html> 