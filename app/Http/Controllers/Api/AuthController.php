<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'username'=>'required|string|max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'username'=>$data['username'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['token'=>$token], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error'=>'Credenciales inválidas'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['token'=>$token]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Sesión cerrada']);
    }
}
