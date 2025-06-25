<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;


use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function register(AuthService $user, RegisterRequest $request)
    {
        return $user->RegisterUser($request);
    }


    public function login(AuthService $user, LoginRequest $request)
    {
        return $user->LoginUser($request);
    }

    public function me(Request $request)
    {
        return $request->user();
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
