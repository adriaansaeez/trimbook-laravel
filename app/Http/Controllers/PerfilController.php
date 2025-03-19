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
            'foto_perfil' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
        ]);

        $perfil = Auth::user()->perfil;
        $perfil->update($request->all());

        return redirect()->route('perfil.index')->with('success', 'Perfil actualizado correctamente.');
    }
}
