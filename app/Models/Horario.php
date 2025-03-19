<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $fillable = ['dia', 'hora_inicio', 'hora_fin'];

    // Relación muchos a muchos con estilistas
    public function estilistas()
    {
        return $this->belongsToMany(Estilista::class, 'estilista_horario', 'horario_id', 'estilista_id');
    }
}
