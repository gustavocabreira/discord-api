<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guild;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GuildController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $guild = Guild::query()->create([
            'name' => $request->input('name'),
            'owner_id' => request()->user()->id,
        ]);

        return response()->json($guild, Response::HTTP_CREATED);
    }
}
