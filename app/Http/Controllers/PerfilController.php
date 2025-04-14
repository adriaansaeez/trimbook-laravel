<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perfil;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    // Mostrar perfil del usuario autenticado
    public function index()
    {
        $perfil = Auth::user()->perfil;
        return view('perfil.index', compact('perfil'));
    }

    // Formulario de edición del perfil
    public function edit()
    {
        $perfil = Auth::user()->perfil;
        return view('perfil.edit', compact('perfil'));
    }

    // Actualizar perfil
    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // 2MB
        ]);

        $perfil = Auth::user()->perfil;

        $data = $request->except('foto_perfil');

        // Si hay una nueva foto, guardarla
        if ($request->hasFile('foto_perfil')) {
            $imagen = $request->file('foto_perfil');
            $nombreArchivo = uniqid('perfil_') . '.' . $imagen->getClientOriginalExtension();
            $imagen->storeAs('public/perfiles', $nombreArchivo);

            // Opcional: eliminar imagen antigua si no es la default
            if ($perfil->foto_perfil && $perfil->foto_perfil !== 'default.jpg') {
                \Storage::delete('public/perfiles/' . $perfil->foto_perfil);
            }

            $data['foto_perfil'] = $nombreArchivo;
        }

        $perfil->update($data);

        return redirect()->route('perfil.index')->with('success', 'Perfil actualizado correctamente.');
    }

}
