# Configuración del Cron Job para Recordatorios

## En Servidor Linux/Mac (cPanel/WHM/VPS):

1. Accede al crontab:
```bash
crontab -e
```

2. Agrega esta línea al final del archivo:
```bash
* * * * * cd /ruta/a/tu/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

**Importante:** Reemplaza `/ruta/a/tu/proyecto` con la ruta real de tu proyecto Laravel.

## En Servidor Compartido (cPanel):

1. Ve a "Cron Jobs" en cPanel
2. Agrega un nuevo cron job con:
   - **Comando:** `cd /home/usuario/public_html && php artisan schedule:run`
   - **Frecuencia:** Cada minuto (`* * * * *`)

## En Windows Server:

1. Usa el Programador de Tareas de Windows
2. Crea una tarea que ejecute:
```cmd
C:\php\php.exe C:\ruta\a\tu\proyecto\artisan schedule:run
```
3. Configura para ejecutar cada minuto

## Verificación:

Para verificar que los recordatorios se están enviando correctamente:

1. **Consulta los logs:**
```bash
tail -f storage/logs/recordatorios.log
```

2. **Ejecuta manualmente el comando:**
```bash
php artisan reservas:enviar-recordatorios
```

3. **Lista las tareas programadas:**
```bash
php artisan schedule:list
```

## Configuración de Email:

Asegúrate de tener configurado correctamente tu `.env` con los datos de SMTP:

```env
MAIL_MAILER=smtp
MAIL_HOST=tu-servidor-smtp.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@dominio.com
MAIL_PASSWORD=tu-contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@dominio.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Horario de Envío:

Los recordatorios se envían a las **10:00 AM** cada día para reservas del día siguiente.

## Troubleshooting:

- **Si no se envían emails:** Verifica la configuración SMTP
- **Si no encuentra reservas:** Verifica que existan reservas para mañana en estado CONFIRMADA o PENDIENTE
- **Si hay errores:** Consulta `storage/logs/laravel.log` y `storage/logs/recordatorios.log` 