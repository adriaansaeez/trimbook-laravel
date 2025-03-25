<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Estilista;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'duracion', 'estilista_id'];

    // Relación muchos a muchos con Estilista
    public function estilistas()
    {
        return $this->belongsToMany(Estilista::class);
    }

}
