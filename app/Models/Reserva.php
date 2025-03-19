<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'servicio_id', 'estilista_id', 'fecha', 'hora', 'estado'];

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
}
