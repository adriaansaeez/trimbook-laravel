<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    // Listar usuarios
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    // Formulario de creación de usuario
    public function create()
    {
        $roles = Role::pluck('name', 'name'); // Obtener roles disponibles
        return view('users.create', compact('roles'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Asignar el rol seleccionado
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }


    // Formulario de edición de usuario
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); 
        $perfil = $user->perfil()->firstOrCreate(['usuario_id' => $user->id]); 

        return view('users.edit', compact('user', 'roles', 'perfil'));
    }


    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,$id",
            'role' => 'required|exists:roles,name',
            'nombre' => 'nullable|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|url|max:255',
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
        ]);

        $user->syncRoles([$request->role]);

        // Actualiza el perfil asociado
        $user->perfil()->updateOrCreate(
            ['usuario_id' => $user->id],
            [
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'instagram_url' => $request->instagram_url,
            ]
        );

        return redirect()->route('users.index')->with('success', 'Usuario y perfil actualizados correctamente.');
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
