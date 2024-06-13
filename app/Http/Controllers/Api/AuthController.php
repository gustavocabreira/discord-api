<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $user = User::query()->create(
            $request->only('name', 'email', 'password')
        );

        return response()->json($user, Response::HTTP_CREATED);
    }
}
