<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserva_id',
        'estilista_id',
        'metodo_pago',
        'importe',
        'fecha_pago',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    public function estilista()
    {
        return $this->belongsTo(Estilista::class);
    }
}
