<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    // Listar usuarios
    public function index()
    {
        $users = User::with(['roles', 'perfil'])->paginate(10);
        return view('users.index', compact('users'));
    }

    // Formulario de creación de usuario
    public function create()
    {
        $roles = Role::pluck('name', 'name'); // Obtener roles disponibles
        return view('users.create', compact('roles'));
    }

    // Guardar nuevo usuario con manejo de errores
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
        ], [
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'role.exists' => 'El rol seleccionado no es válido.'
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            $user->assignRole($request->role);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuario creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear el usuario. Por favor, inténtelo de nuevo.']);
        }
    }

    // Formulario de edición de usuario
    public function edit($id)
    {
        $user = User::with('perfil')->findOrFail($id);
        $roles = Role::pluck('name', 'name')->toArray();
        return view('users.edit', compact('user', 'roles'));
    }

    // Actualizar usuario con manejo de errores
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'role' => ['required', 'exists:roles,name'],
            'nombre' => ['nullable', 'string', 'max:255'],
            'apellidos' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Password::defaults()];
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            $userData = [
                'username' => $request->username,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);
            $user->syncRoles([$request->role]);

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

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuario actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar el usuario. Por favor, inténtelo de nuevo.']);
        }
    }

    // Eliminar usuario con manejo de errores
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->id === auth()->id()) {
                return redirect()->back()
                    ->withErrors(['error' => 'No puedes eliminar tu propio usuario.']);
            }

            DB::beginTransaction();
            $user->perfil()->delete();
            $user->delete();
            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Error al eliminar el usuario. Por favor, inténtelo de nuevo.']);
        }
    }
}
