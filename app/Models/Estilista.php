<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Servicio;

class Estilista extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nombre'];

    // Relación 1:1 con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relación muchos a muchos con Servicio
    public function servicios()
    {
        return $this->belongsToMany(Servicio::class);
    }

    public function horarios()
    {
        return $this->belongsToMany(Horario::class, 'horarios_estilista')
                    ->withPivot('id', 'fecha_inicio', 'fecha_fin');
    }





}
