<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorariosEstilista extends Model
{
    use HasFactory;

    protected $table = 'horarios_estilista';

    // Relación con el modelo Horario
    public function horario()
    {
        return $this->belongsTo(Horario::class, 'horario_id');
    }
}
