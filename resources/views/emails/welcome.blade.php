<x-mail::message>
@component('mail::message')
# 👋 Bienvenido a TrimBook, {{ $user->name }}

Gracias por registrarte en nuestra plataforma. A partir de ahora podrás gestionar tus reservas, servicios y mucho más desde un solo lugar.

---

🎯 **¿Qué puedes hacer desde ya?**

- Reservar citas fácilmente
- Gestionar tus servicios
- Consultar tu historial
- Recibir recordatorios automáticos

@component('mail::button', ['url' => url('/home')])
Entrar a mi cuenta
@endcomponent

---

Si no fuiste tú quien se registró, puedes ignorar este correo.

Gracias por confiar en nosotros,<br>
**El equipo de TrimBook**

@slot('subcopy')
¿Tienes dudas? Escríbenos a [soporte@trimbook.pro](mailto:soporte@trimbook.pro)
@endslot
@endcomponent

</x-mail::message>
