<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $fillable = ['usuario_id', 'nombre', 'apellidos', 'telefono', 'direccion', 'foto_perfil', 'instagram_url'];

    protected $table = 'perfiles'; // Especificamos el nombre correcto

    // Valores por defecto en caso de que no se envíen datos
    protected $attributes = [
        'nombre' => 'Sin nombre',
        'apellidos' => 'Sin apellidos',
        'telefono' => 'No especificado',
        'direccion' => 'No especificado',
        'foto_perfil' => 'default.jpg',
        'instagram_url' => 'No especificado',
    ];

    // Relación 1:1 con User
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
