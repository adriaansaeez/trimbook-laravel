# Configuración del Formulario de Contacto

## Descripción
Se ha añadido un nuevo formulario de contacto en la landing page que permite a los visitantes enviar consultas directamente a tu correo electrónico (adriansaezbeltra@gmail.com).

## Configuración requerida

### 1. Variables de entorno (.env)
Añade estas variables a tu archivo `.env`:

```bash
# Configuración de correo
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="TrimBook"
```

### 2. Configuración de Gmail
Para usar Gmail como servidor SMTP necesitas:

1. **Habilitar la verificación en 2 pasos** en tu cuenta de Google
2. **Generar una contraseña de aplicación:**
   - Ve a tu cuenta de Google → Seguridad
   - En "Iniciar sesión en Google" → Contraseñas de aplicaciones
   - Genera una nueva contraseña para "Correo"
   - Usa esta contraseña en `MAIL_PASSWORD` (no tu contraseña normal)

### 3. Alternativas de configuración de correo

#### Opción 1: Mailtrap (para desarrollo)
```bash
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu-username-mailtrap
MAIL_PASSWORD=tu-password-mailtrap
MAIL_ENCRYPTION=tls
```

#### Opción 2: SendGrid
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=tu-api-key-sendgrid
MAIL_ENCRYPTION=tls
```

### 4. Para pruebas locales
Si solo quieres probar sin enviar correos reales, puedes usar el driver 'log':

```bash
MAIL_MAILER=log
```

Los correos se guardarán en `storage/logs/laravel.log`

## Funcionalidades implementadas

1. **Formulario de contacto** con campos:
   - Nombre completo
   - Correo electrónico
   - Descripción de la barbería/consulta

2. **Validación** de todos los campos

3. **Envío de correo** con template HTML profesional

4. **Mensajes de confirmación** al usuario

5. **Enlaces actualizados** en los botones "Probar gratis" e "Incluye tu negocio"

## Archivos creados/modificados

- `app/Http/Controllers/ContactController.php` - Controlador del formulario
- `resources/views/emails/contact.blade.php` - Template del correo
- `resources/views/welcome.blade.php` - Landing page con formulario
- `routes/web.php` - Ruta del formulario

## Uso
Los visitantes pueden:
1. Hacer clic en "Probar gratis" o "Incluye tu negocio"
2. Llenar el formulario de contacto
3. Recibir confirmación inmediata
4. Tú recibirás el correo con toda la información del contacto 