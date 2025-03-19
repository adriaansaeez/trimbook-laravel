# TrimBook - Web de Gestión de Reservas para Peluquerías

**TrimBook** es una aplicación web de gestión de reservas diseñada para peluquerías. Ofrece una solución completa tanto para los administradores como para los clientes. Los administradores pueden gestionar servicios, estilistas, reservas y ver informes detallados de contabilidad, mientras que los clientes pueden realizar, modificar y gestionar sus citas de manera sencilla y eficiente.

![TrimBook](https://via.placeholder.com/1200x600?text=Logo+TrimBook) 

---

## 🚀 Características

- **Para Administradores:**
  - Gestión de reservas, clientes, estilistas y servicios.
  - Dashboard con estadísticas y reportes de contabilidad.
  - Notificaciones automáticas para clientes sobre cambios en reservas.
  
- **Para Clientes:**
  - Reservas fáciles de citas con estilistas y servicios.
  - Modificación de reservas en tiempo real.
  - Recordatorios personalizados 24 horas antes de la cita.

## 🛠️ Tecnologías

- **Frontend:** React.js, TailwindCSS
- **Backend:** Express.js, Node.js
- **Base de datos:** MySQL
- **Autenticación:** JWT (JSON Web Tokens)

## 📊 Roadmap

Aquí tienes algunas de las futuras mejoras y funcionalidades que estamos planeando para **TrimBook**:

### Backend
- ✅ **Autenticación de Usuarios**: Implementar el sistema de login y registro de usuarios para clientes y administradores.
- ❌ **Gestión de Estilistas y Servicios**: Crear API para añadir, editar y eliminar estilistas y servicios.
- ❌ **Gestión de Reservas**: Implementar lógica para gestionar las reservas, incluyendo la creación, edición, eliminación y validación de horarios disponibles.
- ❌ **Notificaciones Automáticas**: Enviar notificaciones por correo electrónico 24 horas antes de la cita.
- ❌ **Dashboard de Administrador**: Desarrollar endpoints para generar informes de reservas, ingresos, clientes y otros datos importantes para los administradores.
- ✅1/2 **API RESTful**: Crear una API RESTful para que el frontend pueda comunicarse con el backend (gestión de servicios, reservas, etc.).
- ✅1/2 **Autorización y Roles de Usuario**: Asegurar que solo los administradores tengan acceso a ciertas funciones como la gestión de estilistas y el dashboard.
- ❌ **Integración de Pagos Online**: Implementar un sistema de pagos como Stripe o PayPal para que los clientes puedan pagar las reservas en línea.
- ❌ **Control de Disponibilidad de Estilistas**: Implementar una lógica para que los estilistas puedan definir su disponibilidad y evitar reservas dobles.

### Frontend
- ❌ **Interfaz de Cliente**: Desarrollar la página de inicio donde los clientes puedan ver los servicios disponibles, estilistas y hacer reservas.
- ❌ **Formulario de Reserva**: Crear formularios para que los clientes seleccionen el servicio, estilista y horario.
- ❌ **Perfil de Usuario**: Permitir que los clientes vean, editen y gestionen sus reservas desde su perfil.
- ❌ **Interfaz de Administrador**: Crear una vista de administrador donde se puedan gestionar clientes, estilistas, servicios y consultar las reservas.
- ❌ **Notificaciones Frontend**: Mostrar notificaciones a los usuarios sobre cambios en sus reservas, confirmaciones y recordatorios.
- ❌ **Panel de Estadísticas para Administradores**: Mostrar estadísticas gráficas y detalladas sobre las reservas y los ingresos generados.
- ❌ **Vista de Calendario**: Implementar una vista de calendario interactiva para que los administradores gestionen las reservas y disponibilidad de estilistas.
- ❌ **Mejoras en la Interfaz de Usuario (UI)**: Optimizar la experiencia visual con mejoras en diseño, animaciones y adaptabilidad para dispositivos móviles.
- ❌ **Registro y Autenticación de Clientes**: Crear las pantallas de registro, login y recuperación de contraseña para los clientes.

---

## 💡 Instalación

1. Clona el repositorio:

```bash
git clone https://github.com/tuusuario/trimbook-code.git
```

```bash
cd trimbook-code
```

```bash
npm install
```

```bash
npm run dev
```

el resto a proximamento....

---

## 📝 Licencia

Este proyecto está bajo la Licencia MIT - consulta el archivo [LICENSE](LICENSE) para más detalles.

---

## 🤝 Contribuye

¡Las contribuciones son bienvenidas! Si tienes ideas para mejorar el proyecto, no dudes en abrir un **issue** o hacer un **pull request**.

1. Forkea el repositorio.
2. Crea una nueva rama para tu feature (`git checkout -b feature/nueva-funcionalidad`).
3. Haz los cambios y haz un commit (`git commit -am 'Agrega nueva funcionalidad'`).
4. Haz push a la rama (`git push origin feature/nueva-funcionalidad`).
5. Crea un pull request.

---
