<?php

namespace App\Http\Controllers;

use App\Models\Guild;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class InviteController extends Controller
{
    public function store(Guild $guild): JsonResponse
    {
        $invite = $guild->invites()->create([
            'name' => str()->random(8),
        ]);

        return response()->json($invite, Response::HTTP_CREATED);
    }
}
