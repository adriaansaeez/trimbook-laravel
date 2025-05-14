<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reserva #{{ $reserva->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .header h1 {
            color: #2d3748;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #718096;
            margin: 5px 0 0;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            color: #2d3748;
            font-size: 18px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #4a5568;
        }
        .value {
            color: #2d3748;
        }
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-confirmada {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-cancelada {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .status-completada {
            background-color: #dcfce7;
            color: #166534;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            text-align: center;
            font-size: 12px;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reserva #{{ $reserva->id }}</h1>
        <p>Fecha de emisión: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <h2 class="section-title">Información de la Reserva</h2>
        <div class="info-grid">
            <div class="label">Cliente:</div>
            <div class="value">{{ $reserva->user->username }}</div>

            <div class="label">Servicio:</div>
            <div class="value">{{ $reserva->servicio->nombre }}</div>

            <div class="label">Estilista:</div>
            <div class="value">{{ $reserva->estilista->nombre }}</div>

            <div class="label">Fecha:</div>
            <div class="value">{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</div>

            <div class="label">Hora:</div>
            <div class="value">{{ \Carbon\Carbon::parse($reserva->hora)->format('H:i') }}</div>

            <div class="label">Estado:</div>
            <div class="value">
                <span class="status status-{{ strtolower($reserva->estado) }}">
                    {{ $reserva->estado }}
                </span>
            </div>

            <div class="label">Precio:</div>
            <div class="value">{{ number_format($reserva->precio, 2) }} €</div>

            <div class="label">Estado del Pago:</div>
            <div class="value">
                @if($reserva->pagada)
                    <span class="status status-completada">Pagado</span>
                @else
                    <span class="status status-pendiente">No pagado</span>
                @endif
            </div>
        </div>
    </div>

    @if($reserva->pagada)
    <div class="section">
        <h2 class="section-title">Información del Pago</h2>
        <div class="info-grid">
            <div class="label">Método de Pago:</div>
            <div class="value">{{ $reserva->pago->metodo_pago }}</div>

            <div class="label">Importe Pagado:</div>
            <div class="value">{{ number_format($reserva->pago->importe, 2) }} €</div>

            <div class="label">Fecha de Pago:</div>
            <div class="value">{{ \Carbon\Carbon::parse($reserva->pago->fecha_pago)->format('d/m/Y H:i') }}</div>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Este documento fue generado automáticamente por el sistema de reservas.</p>
        <p>Para cualquier consulta, por favor contacte con el administrador del sistema.</p>
    </div>
</body>
</html> 