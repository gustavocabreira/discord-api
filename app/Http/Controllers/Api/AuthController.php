<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $user = User::query()->create($payload);
        return response()->json($user, Response::HTTP_CREATED);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        if(!auth()->attempt($request->only('email', 'password'))){
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $user = auth()->user();

        $token = $user->createToken('Fodase', ['*'], now()->addYear());

        return response()->json([
            'user' => $user,
            'access_token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at->timestamp,
        ], Response::HTTP_OK);
    }
}
