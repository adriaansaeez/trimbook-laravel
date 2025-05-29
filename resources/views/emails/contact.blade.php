<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva consulta desde TrimBook</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3B82F6;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }
        .info-box {
            background-color: white;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #FB923C;
            border-radius: 3px;
        }
        .label {
            font-weight: bold;
            color: #3B82F6;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>📧 Nueva consulta desde TrimBook</h2>
    </div>
    
    <div class="content">
        <p>¡Has recibido una nueva consulta a través del formulario de contacto de TrimBook!</p>
        
        <div class="info-box">
            <p><span class="label">👤 Nombre:</span> {{ $nombre }}</p>
        </div>
        
        <div class="info-box">
            <p><span class="label">📧 Correo electrónico:</span> {{ $correo }}</p>
        </div>
        
        <div class="info-box">
            <p><span class="label">💬 Mensaje:</span></p>
            <p>{{ $descripcion }}</p>
        </div>
        
        <div style="margin-top: 20px; padding: 15px; background-color: #EBF4FF; border-radius: 5px;">
            <p><strong>💡 Recomendación:</strong> Responde a este cliente lo antes posible para brindarle la mejor experiencia con TrimBook.</p>
        </div>
    </div>
    
    <div class="footer">
        <p>Este email fue enviado automáticamente desde <strong>TrimBook</strong></p>
        <p>Fecha: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 