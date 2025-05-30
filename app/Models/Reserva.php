<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'servicio_id', 'estilista_id', 'fecha', 'hora', 'estado', 'recordatorio_enviado', 'recordatorio_enviado_at'];

    // Establecer el estado por defecto como CONFIRMADA
    protected $attributes = [
        'estado' => 'CONFIRMADA'
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el servicio
    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    // Relación con el estilista
    public function estilista()
    {
        return $this->belongsTo(Estilista::class);
    }
    
    // Relación con el pago
    public function pago()
    {
        return $this->hasOne(Pago::class);
    }
    
    // Accesor para obtener el precio del servicio
    public function getPrecioAttribute()
    {
        return $this->servicio->precio;
    }
    
    // Accesor para verificar si la reserva está pagada
    public function getPagadaAttribute()
    {
        return $this->pago()->exists();
    }
}
