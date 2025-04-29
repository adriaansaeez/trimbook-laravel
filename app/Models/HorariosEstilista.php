<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorariosEstilista extends Model
{
    use HasFactory;

    protected $table = 'horarios_estilista';

    protected $fillable = [
        'estilista_id',
        'horario_id',
        'fecha_inicio',
        'fecha_fin'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date'
    ];

    // Relación con el modelo Horario
    public function horario()
    {
        return $this->belongsTo(Horario::class, 'horario_id');
    }

    // Relación con el modelo Estilista
    public function estilista()
    {
        return $this->belongsTo(Estilista::class, 'estilista_id');
    }
}
