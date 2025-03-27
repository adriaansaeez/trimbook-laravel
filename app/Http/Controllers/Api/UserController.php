<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

/**
 * Controlador API para gestionar usuarios.
 * Proporciona endpoints CRUD para listar, crear, ver, actualizar y eliminar usuarios.
 */
class UserController extends Controller
{
    /**
     * Lista todos los usuarios paginados.
     *
     * GET /api/v1/users
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return UserResource::collection(User::paginate(15));
    }

    /**
     * Crea un nuevo usuario y devuelve sus datos formateados.
     *
     * POST /api/v1/users
     *
     * @param  Request  $request
     * @return UserResource
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Encripta la contraseña antes de guardar
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return new UserResource($user);
    }

    /**
     * Muestra los detalles de un usuario específico.
     *
     * GET /api/v1/users/{user}
     *
     * @param  User  $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Actualiza datos de un usuario existente.
     *
     * PUT|PATCH /api/v1/users/{user}
     *
     * @param  Request  $request
     * @param  User     $user
     * @return UserResource
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username' => "sometimes|string|max:255|unique:users,username,{$user->id}",
            'email'    => "sometimes|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Si se proporcionó contraseña, encriptarla; si no, eliminar clave para no modificar
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return new UserResource($user);
    }

    /**
     * Elimina un usuario existente.
     *
     * DELETE /api/v1/users/{user}
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }
}
