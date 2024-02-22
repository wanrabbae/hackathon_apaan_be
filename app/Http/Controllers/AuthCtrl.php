<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthCtrl extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('authToken')->plainTextToken;
            return response()->json(['message' => 'Login success', 'token' => $token, 'user' => User::where('email', $request->email)->first()], 200);
        }
        return response()->json(['message' => 'Email atau password salah!'], 401);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Logout success'], 200);
    }

    public function user()
    {
        return response()->json(auth()->user());
    }

    public function register(Request $request)
    {
        // overwrite password into bcrypt
        $request->merge(['password' => bcrypt($request->password)]);
        $user = User::create($request->all());
        return response()->json($user, 201);
    }
}
