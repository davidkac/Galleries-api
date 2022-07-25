<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'token' => $token,
            'user' => Auth::user(),
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        $token = Auth::login($user);

        return response()->json([
            'token' => $token,
            'user' => Auth::user(),
        ]);
    }

    public function getMyProfile()
    {
        $activeUser = Auth::user();
        return response()->json($activeUser);
    }

    public function logout()
    {
        Auth::logout();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function refreshToken()
    {
        $token = Auth::refresh();
        return response()->json([
            'token' => $token
        ]);
    }
}
