<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Controlador de autenticación para la API usando Laravel Sanctum.
 * Gestiona registro, inicio de sesión, obtención de usuario autenticado y cierre de sesión.
 */
class AuthController extends Controller
{
    /**
     * Registra un nuevo usuario y devuelve un token de acceso.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Crear usuario con contraseña hasheada
        $user = User::create([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Generar token personal de acceso
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

    /**
     * Autentica credenciales y devuelve un token válido.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Verificar existencia de usuario y contraseña correcta
        $user = User::where('email', $credentials['email'])->first();
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        // Generar nuevo token de acceso
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['token' => $token]);
    }

    /**
     * Retorna los datos del usuario autenticado basado en el token.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Elimina el token actual, cerrando la sesión del usuario.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Borrar token usado para esta petición
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada']);
    }
}
